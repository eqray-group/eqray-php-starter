# eqrayphp 框架架构指南

## 目录

1. [系统概述](#1-系统概述)
2. [请求生命周期](#2-请求生命周期)
3. [核心模块详解](#3-核心模块详解)
   - 3.1 [DI 容器](#31-di-容器)
   - 3.2 [路由系统](#32-路由系统)
   - 3.3 [中间件系统](#33-中间件系统)
   - 3.4 [多租户 (SaaS)](#34-多租户-saas)
   - 3.5 [认证与授权](#35-认证与授权)
   - 3.6 [ORM 双适配层](#36-orm-双适配层)
   - 3.7 [事件系统](#37-事件系统)
   - 3.8 [连接池](#38-连接池)
   - 3.9 [消息队列](#39-消息队列)
   - 3.10 [WebSocket](#310-websocket)
   - 3.11 [多应用架构](#311-多应用架构)
   - 3.12 [文件存储](#312-文件存储)
4. [配置参考](#4-配置参考)
5. [开发指南](#5-开发指南)
6. [测试](#6-测试)
7. [部署](#7-部署)

---

## 1. 系统概述

### 1.1 项目定位

eqrayphp 是一个基于 Workerman 常驻内存引擎驱动的现代化全栈 PHP 框架，采用 **多租户 SaaS 架构**，支持 **双 ORM**（Laravel Eloquent / ThinkPHP ORM），内置完整的 RBAC 权限体系和代码生成器。

### 1.2 技术架构图

```
┌──────────────────────────────────────────────────────────────┐
│                    外部请求层                                  │
│    HTTP (Nginx/Apache)    WebSocket    CLI (Workerman)       │
└───────────────────┬──────────────────────────────────────────┘
                    │
┌───────────────────▼──────────────────────────────────────────┐
│                   入口层                                      │
│  server.php (Workerman)    public/index.php (FPM)             │
└───────────────────┬──────────────────────────────────────────┘
                    │
┌───────────────────▼──────────────────────────────────────────┐
│               Framework::getInstance() 单例                   │
│   ┌─────────────────────────────────────────────────────┐    │
│   │  Kernel::boot()                                     │    │
│   │  ├─ 设置容器别名 (App::setContainer)                │    │
│   │  ├─ 设置时区                                       │    │
│   │  └─ 设置异常处理                                    │    │
│   └─────────────────────────────────────────────────────┘    │
└───────────────────┬──────────────────────────────────────────┘
                    │
┌───────────────────▼──────────────────────────────────────────┐
│               Framework::dispatch()                          │
│   ┌─────────────────────────────────────────────────────┐    │
│   │  1. resolveDomainApp() — 域名绑定解析               │    │
│   │  2. Router::match() — 路由匹配                      │    │
│   │  3. MiddlewareDispatcher::dispatch() — 中间件管道   │    │
│   │  4. callController() — 控制器调用 + DI 注入         │    │
│   └─────────────────────────────────────────────────────┘    │
└──────────────────────────────────────────────────────────────┘
```

### 1.3 两种运行模式

| 特性 | Workerman 模式（主推） | FPM 模式 |
|------|----------------------|----------|
| **入口** | `php server.php start` | `public/index.php` |
| **运行时** | 常驻内存，多进程 | 每次请求全新生命周期 |
| **性能** | 高（无引导开销） | 一般 |
| **连接池** | 支持 Redis/MySQL 连接池 | 不支持 |
| **WebSocket** | 内置支持（端口 1234） | 不支持 |
| **队列消费** | 独立 Worker 进程 | 不支持 |
| **部署要求** | CLI 环境 + Supervisor | Nginx/Apache |
| **进程管理** | Worker::runAll() | PHP-FPM 管理 |

### 1.4 核心依赖

| 类别 | 组件 | 说明 |
|------|------|------|
| 运行时引擎 | workerman/workerman ^5.2 | 常驻内存 HTTP + WebSocket 服务器 |
| DI 容器 | symfony/dependency-injection ^7.4 | ContainerBuilder + PhpDumper |
| HTTP 组件 | symfony/http-foundation ^7.4 | Request / Response |
| 路由 | symfony/routing ^7.4 + 自研自动路由 | 注解 + 自动推断 |
| ORM（默认） | illuminate/database ^12.58 | Laravel Eloquent |
| ORM（备选） | topthink/think-orm ^4.0 | ThinkPHP ORM |
| 权限 | casbin/casbin ^4.2 | RBAC 模型 |
| JWT | lcobucci/jwt ^5.6 | HS256 令牌认证 |
| 模板 | twig/twig ^3.14 | 视图渲染 |
| 缓存 | topthink/think-cache ^3.0 | 多驱动缓存 |
| 日志 | monolog/monolog ^3.9 | 结构化日志 |
| 国际化 | symfony/translation ^7.4 | 多语言 |
| 文件存储 | league/flysystem + 云 SDK | 本地/OSS/COS/S3/Qiniu |

---

## 2. 请求生命周期

### 2.1 Workerman 模式完整流程

```
1. 启动阶段 (php server.php start)
   ├── Worker 进程 fork（默认 4 个 HTTP Worker）
   └── onWorkerStart 回调
       ├── Framework::getInstance() — 框架初始化
       │   ├── initializeBasePath() — 定义 BASE_PATH
       │   ├── registerAppAutoloadNamespaces() — 动态注册 PSR-4
       │   └── initializeConfigAndContainer()
       │       ├── Container::init()
       │       │   ├── 加载 .env
       │       │   ├── 创建 ContainerBuilder
       │       │   ├── 加载 config/services.php
       │       │   ├── 添加 AttributeInjectionPass
       │       │   ├── 执行 compile()
       │       │   ├── 生产环境: dump → PhpDumper → require
       │       │   └── 开发环境: 使用 ContainerBuilder 直连
       │       └── Kernel::boot()
       │           ├── App::setContainer() — 设置全局容器入口
       │           ├── setupTimezone()
       │           └── setupExceptionHandling()
        ├── initializeDependencies()
        │   ├── loadAllRoutes() — 加载定义路由 + 注解路由
       │   ├── new MiddlewareDispatcher()
       │   ├── new Router()
       │   └── 注入缓存、安全策略、自动路由命名空间
       ├── SchemaWarmup — 模型 Schema 预热
       ├── 连接池初始化 (RedisPool / MysqlPool)
       └── Timer 注册 (健康检查、日志轮转、内存监控)

2. 请求阶段 (onMessage 回调)
   ├── 静态文件处理 (public/uploads, assets, etc.)
   ├── 健康检查端点 /_health
   ├── WorkermanRequest → SymfonyRequest 转换
   ├── Framework::handleRequest()
   │   └── dispatch()
   │       ├── resolveDomainApp() — 域名绑定
   │       ├── Router::match()
   │       │   ├── 预处理 (去除 .html 后缀)
   │       │   ├── 缓存命中检查
   │       │   ├── Easter Egg 检查
   │       │   ├── try 定义路由 (Symfony UrlMatcher)
    │       │   └── try 自动路由
    │       │       ├── 应用自动路由 (/admin/user/list)
    │       │       ├── 域名绑定自动路由
    │       │       └── 默认兜底 (/Controller/Action/Param)
   │       ├── MiddlewareDispatcher::dispatch()
   │       │   ├── 框架级中间件 (CORS, CSRF, RateLimit, ...)
   │       │   ├── 应用级中间件 (Auth, Permission, Tenant, ...)
   │       │   └── 控制器闭包
   │       └── callController()
   │           ├── App::make() — DI 获取或创建控制器
   │           ├── 参数类型转换 (processRequestParameters)
   │           ├── ArgumentResolver — Symfony 参数解析
   │           ├── 调用控制器方法
   │           └── normalizeResponse() — 统一响应格式
   └── SymfonyResponse → WorkermanResponse 转换
       ├── Session 保存与清理
       ├── Cookie 发送
       └── 资源释放 (gc_collect_cycles)
```

### 2.2 FPM 模式

```
public/index.php
  ├── 定义 BASE_PATH, APP_DEBUG
  ├── require vendor/autoload.php
  ├── require framework/helpers.php
  ├── require app/function.php
  └── Framework::getInstance()->run()
      ├── 初始化 (同上 Workerman 初始化流程)
      ├── Request::createFromGlobals()
      ├── dispatch(request)
      └── response->send()
```

---

## 3. 核心模块详解

### 3.1 DI 容器

**文件**: `framework/Container/Container.php`, `config/services.php`

#### 架构

基于 **Symfony DependencyInjection** 组件，在整个框架之上封装了 `Container` 类。

```
Container (Framework 封装)
  └── 包装 Symfony ContainerBuilder / PhpDumper 编译后的容器
      ├── 服务定义: config/services.php 中使用 Symfony PHP 配置器
      ├── 自动注入: autowire() + autoconfigure()
      ├── 编译优化: 生产环境 dump 到 storage/cache/container.php
      └── Attribute 注入: AttributeInjectionPass (自定义 CompilerPass)
```

#### 核心方法

| 方法 | 说明 |
|------|------|
| `Container::init()` | 初始化容器，加载 .env，编译服务定义 |
| `make(abstract, params)` | 反射解析类依赖，支持构造函数注入 + Attribute 注入 |
| `get(id)` | 获取已注册服务 |
| `has(id)` | 检查服务是否存在 |
| `singleton(id, factory)` | 注册单例 |
| `bind(abstract, concrete, shared)` | 绑定接口到实现 |
| `instance(id, object)` | 注入实例 |

#### 全局访问

```
app()              → 返回容器实例
app(SomeClass::class) → 解析并返回实例
App::make(Cls)     → 反射创建（带 DI）
App::get(id)       → 获取已注册服务
```

#### 服务注册方式

1. **config/services.php** — 主要方式，使用 Symfony PHP 配置器
2. **Provider** — 通过服务提供者注册
3. **运行时** — `app()->singleton()`, `app()->bind()`, `app()->instance()`

#### 优化策略

- 开发环境: 使用 `ContainerBuilder`，服务变更即时生效
- 生产环境: `ContainerBuilder` → `compile()` → `PhpDumper::dump()` → `require` 缓存文件，零反射开销

---

### 3.2 路由系统

**文件**: `framework/Core/Router.php`, `framework/Core/AttributeRouteLoader.php`

#### 三层路由架构

```
路由匹配顺序:
1. 定义路由 (Symfony RouteCollection) → config/routes.php
2. 注解路由 (PHP 8 Attributes) → 控制器上的 #[Route] 等
3. 自动推断路由 → URL 路径到控制器/方法的自动映射
   ├── 应用自动路由: /admin/user/list → App\Admin\Controllers\UserController::list
   ├── 域名绑定自动路由: admin.example.com/user/list → App\Admin\Controllers
   └── 默认兜底: /Controller/Action/Param1/Param2
```

#### 自动路由匹配逻辑

```
matchAutoRoute(path)
  ├── 根路径 → tryHomeController()
  ├── 应用自动路由
  ├── 域名绑定应用自动路由
  └── 倒序匹配 → 从长到短尝试控制器类名
      for i = count(segments) downto 1:
          controller = buildControllerClass(segments[0..i])
          action + params = matchActionAndParams(segments[i..])
```

#### RESTful 默认 Action

| HTTP 方法 | 默认 Action |
|-----------|-------------|
| GET | `index` |
| POST | `store` |
| PUT/PATCH | `update` |
| DELETE | `destroy` |

#### 路由缓存

- 生产环境: 路由序列化到 `storage/cache/routes.php`
- URL 匹配缓存: PSR-16 缓存（1 小时 TTL），key 为 Method+Path 的 MD5
- 注解元数据缓存: `compiledMetadata` 内存缓存，可 dump/load

#### PHP 8 Attribute 路由

```php
use Framework\Attributes\Route;
use Framework\Attributes\Routes\GetMapping;
use Framework\Attributes\Auth;

#[Route('/api/user')]
class UserController
{
    #[GetMapping('/list')]
    #[Auth]
    public function list(): array { ... }
}
```

支持的 Attribute:
- `#[Route]` — 类/方法级路由
- `#[Prefix]` — URL 前缀
- `#[(Get|Post|Put|Delete|Patch|Mapping)]` — HTTP 方法约束
- `#[Auth]` — 需要认证
- `#[Role('admin')]` — 需要角色
- `#[Permission('user:list')]` — 需要权限
- `#[Middleware]` — 指定中间件
- `#[Cache]` — 响应缓存
- `#[Validate]` — 请求验证
- `#[Log]` — 操作日志
- `#[Menu]` — 菜单配置
- `#[Action]` — 显式声明为可路由的方法

---

### 3.3 中间件系统

**文件**: `framework/Middleware/MiddlewareDispatcher.php`, `config/middleware.php`

#### 中间件层级

```
请求 → 框架级中间件 (framework/Middleware/)
  ├── ContextInitMiddleware       — 初始化请求上下文
  ├── CorsMiddleware              — 跨域处理
  ├── DebugMiddleware             — 调试模式
  ├── MethodOverrideMiddleware    — HTTP 方法覆盖
  ├── CookieConsentMiddleware     — Cookie 同意
  ├── CsrfTokenGenerateMiddleware — CSRF Token 生成
  ├── CsrfProtectionMiddleware    — CSRF 校验（跳过 Bearer 请求）
  ├── RefererCheckMiddleware      — Referer 来源检查
  ├── SecurityHeadersMiddleware   — 安全响应头 (CSP/HSTS/XFO)
  ├── XssFilterMiddleware         — XSS 过滤
  ├── IpBlockMiddleware           — IP 黑/白名单
  ├── RateLimitMiddleware         — 频率限制
  ├── LoginRateLimitMiddleware    — 登录接口限流
  ├── CircuitBreakerMiddleware    — 熔断器
  └── 应用级中间件 (app/Middlewares/)
      ├── AuthMiddleware           — JWT/Session 认证
      ├── TenantMiddleware         — 租户上下文
      ├── PermissionMiddleware     — 权限检查
      ├── CasbinRbacMiddleware     — Casbin RBAC 执行
      ├── RoleMiddleware           — 角色检查
      ├── CacheMiddleware          — 响应缓存
      ├── ValidateMiddleware        — 请求验证
      ├── OperationLogMiddleware   — 操作日志
      ├── LoginLogMiddleware       — 登录日志
      ├── AccessLogDbMiddleware    — 数据库访问日志
      ├── UserActionMiddleware     — 用户行为
      ├── TestEnvWriteGuardMiddleware — 测试环境写保护
      └── 控制器执行
```

#### 中间件配置

中间件在 `config/middleware.php` 中配置启用/停用及参数，在 `config/middlewares.php` 中注册到调度器。

控制器可通过注解指定中间件：

```php
#[Middleware(['auth', 'permission'])]
class UserController { ... }
```

---

### 3.4 多租户 (SaaS)

**文件**: `framework/Tenant/`

#### 租户隔离方案

| 维度 | 隔离方式 |
|------|----------|
| 数据行级 | 每个表通过 `tenant_id` 字段隔离 |
| 菜单权限 | 不同租户看到不同的菜单 |
| 用户 | 用户可关联多个租户并切换 |
| 配置 | 租户级系统配置 |

#### 租户上下文解析

```
请求 → TenantMiddleware
  ├── 从 JWT Claims 中提取 tenant_id（JWT 模式）
  └── 从 Session 中提取 tenant_id（Session 模式）
      └── 注入到 TenantContext
```

#### 租户切换流程

1. 用户登录 → 返回该用户关联的租户列表
2. 用户选择租户 → `POST /api/core/switch-tenant`
3. 服务端生成新的 JWT/更新 Session，绑定新 tenant_id
4. 后续请求携带新的令牌

#### 数据模型

```
sa_system_tenant           — 租户表
sa_system_user_tenant      — 用户-租户关联表（多对多）
sa_system_user             — 用户表（含 tenant_id）
sa_system_menu             — 菜单表（含 tenant_id）
```

---

### 3.5 认证与授权

#### 双认证模式

| 特性 | JWT 模式 | Session 模式 |
|------|----------|-------------|
| 令牌 | JWT (HS256) | Session ID (Redis) |
| 无状态 | 是 | 否 |
| 刷新 | Refresh Token | Session 自动续期 |
| 单设备登录 | 支持（JWT 黑名单） | 隐式支持 |
| Token 存储 | 客户端 (localStorage) | 服务端 (Redis) |

#### 认证配置 (`config/auth.php`)

```php
'mode' => 'auto',  // jwt, session, auto
'single_device' => false,
```

#### JWT 结构

```
Header:  { "alg": "HS256", "typ": "JWT" }
Payload: {
  "sub": user_id,
  "tenant_id": xxx,
  "roles": ["admin"],
  "permissions": ["user:list", "user:create"],
  "iat": timestamp,
  "exp": timestamp
}
```

#### RBAC 权限 (Casbin)

**模型**: `config/casbin_rbac_model.conf`

使用 Casbin 的 RBAC 模型，支持:
- 角色继承（子角色自动继承父角色权限）
- RESTful 路径匹配（keyMatch2: `/api/user/:id`）
- action 通配符（`*` 表示所有操作）
- 超级管理员角色: `super_admin`

```
权限检查流程:
1. UserLogin → 生成 JWT（含 roles + permissions）
2. 请求到达 → CasbinRbacMiddleware
3. Casbin::enforce(sub, obj, act)
4. 匹配 casbin_rule 表中的策略
5. 允许/拒绝
```

---

### 3.6 ORM 双适配层

**文件**: `framework/ORM/Factories/`

#### 双 ORM 设计

.env 配置 `ORM_DRIVER=laravelORM` 或 `ORM_DRIVER=thinkORM` 切换。

```
ORM_DRIVER ↓
  ├── laravelORM → 使用 illuminate/database
  │   ├── BaseModel = Framework\Basic\BaseLaORMModel
  │   ├── 特性: Eloquent ORM 全部功能
  │   └── 配置: config/database.php connections.mysql
  └── thinkORM → 使用 topthink/think-orm
      ├── BaseModel = Framework\Basic\BaseTpORMModel
      ├── 特性: ThinkPHP ORM 全部功能
      └── 配置: config/database.php connections.mysql (同源)
```

#### 适配器工厂

```php
// ORMAdapterFactory 根据 ORM_DRIVER 返回对应工厂
$factory = ORMAdapterFactory::create();
$factory->boot();        // 初始化数据库连接
$factory->getManager();  // 获取 ORM 管理器
```

#### class_alias 机制

在 `config/services.php` 中:

```php
if ($ormDriver === 'laravelORM') {
    class_alias(BaseLaORMModel::class, BaseModel::class);
} else {
    class_alias(BaseTpORMModel::class, BaseModel::class);
}
```

---

### 3.7 事件系统

**文件**: `framework/Event/`

#### PSR-14 兼容事件分发

```php
// 定义事件
class UserLoginEvent
{
    public function __construct(public User $user) {}
}

// 定义监听器
#[Listener(UserLoginEvent::class)]
class UserLoginListener implements ListenerInterface
{
    public function handle(object $event): void
    {
        // 记录登录日志
    }
}

// 分发事件
EventDispatch(new UserLoginEvent($user));
```

#### 监听器发现

- 属性注册: `#[Listener(EventClass::class)]`
- 服务提供者: `AppServiceProvider` 中注册事件-监听器映射

---

### 3.8 连接池

**文件**: `framework/Pool/`

#### 支持的连接池

| 池类型 | 类 | 默认配置 |
|--------|-----|---------|
| Redis | `RedisPool` | min: 2, max: 10 |
| MySQL | `MysqlPool` | min: 2, max: 10 |

#### 连接池工作原理

```
每个 Worker 进程独立持有 →
PoolManager::register('redis.default', new RedisPool(config))
  ├── 启动时创建 min_connections 个连接
  ├── 请求时从池中借用 (borrow)
  ├── 使用后归还 (return)
  └── 空闲连接超过 min 时自动回收
```

#### 仅在 Workerman 模式可用

连接池利用常驻内存特性，每个 Worker 进程独立维护连接集合。FPM 模式下每个请求结束后连接释放，无需连接池。

---

### 3.9 消息队列

**文件**: `framework/Queue/RedisConsumerService.php`, `server.php`

#### 队列架构

```
生产者 (控制器/服务) → Redis List/LPush
  └── 队列 Worker (独立进程)
      ├── BLPOP 阻塞获取消息
      ├── 根据 type 路由到对应 Handler
      └── 执行处理逻辑
```

#### 配置 (`config/redis.php`)

```php
'queue' => [
    'enabled' => true,
    'worker_count' => 2,
    'queues' => [
        ['name' => 'default', 'key' => 'queue:default'],
    ],
],
```

#### 处理器注册

```php
$consumer->registerHandlers([
    'default'           => new DefaultMessageHandler(),
    'article_published' => new ArticleMessageHandler(),
    'article_view'      => new ArticleMessageHandler(),
]);
```

---

### 3.10 WebSocket

**文件**: `server.php`（WebSocketManager 类）

#### WebSocket 功能

| 特性 | 说明 |
|------|------|
| 端口 | 1234 |
| 协议 | WebSocket (支持 WSS) |
| 房间 | 支持加入/离开房间 |
| 用户绑定 | Connection ↔ User ID |
| 心跳 | 55 秒间隔，120 秒超时 |
| 消息类型 | 广播、房间消息、私聊、系统通知 |

#### 消息协议

```json
// 客户端 → 服务端
{"type": "join", "data": {"room_id": "room1"}}
{"type": "message", "data": {"room_id": "room1", "content": "hello"}}
{"type": "pong"}

// 服务端 → 客户端
{"type": "connected", "data": {"connection_id": 1}}
{"type": "ping"}
{"type": "message", "data": {"room_id": "room1", "content": "hello", "time": "..."}}
```

---

### 3.11 多应用架构

**配置**: `config/apps.php`

#### 应用定义

```php
return [
    'default' => [  // 传统 app/Controllers 目录，无前缀
        'dir'       => BASE_PATH . '/app/Controllers',
        'namespace' => 'App\Controllers',
        'prefix'    => '',
    ],
    'admin' => [    // /admin/xxx → App\Admin\Controllers
        'dir'       => BASE_PATH . '/app/admin/Controllers',
        'namespace' => 'App\Admin\Controllers',
        'prefix'    => 'admin',
        'domain'    => 'admin.example.com',  // 可选域名绑定
    ],
];
```

#### 域名绑定机制

当请求 Host 匹配 `domain` 字段时，无需 URL 前缀即可路由到对应应用。

```
admin.example.com/user/list → App\Admin\Controllers\UserController::list
                   ↑ 无 /admin/ 前缀
```

#### 动态 PSR-4 注册

核心代码 `Framework::registerAppAutoloadNamespaces()` 从 `apps.php` 读取配置，调用 Composer ClassLoader 的 `addPsr4()` 方法动态注册命名空间。新增应用只需编辑 `apps.php`，无需 `composer dump-autoload`。

---

### 3.12 文件存储

**文件**: `framework/Storage/`

#### 多驱动支持

| 驱动 | 适配类 | SDK |
|------|--------|-----|
| Local | `LocalAdapter` | 本地文件系统 |
| Aliyun OSS | `OssAdapter` | aliyuncs/oss-sdk-php |
| Tencent COS | `CosAdapter` | qcloud/cos-sdk-v5 |
| Qiniu | `QiniuAdapter` | qiniu/php-sdk |
| AWS S3 | `S3Adapter` | league/flysystem-aws-s3-v3 |

通过 `config/filesystem.php` 切换驱动。

---

## 4. 配置参考

### 4.1 配置文件清单

| 文件 | 说明 |
|------|------|
| `.env` | 环境变量（数据库、Redis、应用配置） |
| `config/app.php` | 应用名称、环境、调试、时区 |
| `config/apps.php` | 多应用配置 |
| `config/database.php` | 数据库连接 + 连接池 |
| `config/redis.php` | Redis 连接 + 连接池 + 队列 |
| `config/cache.php` | 缓存驱动（file/redis） |
| `config/middleware.php` | 中间件启用和参数 |
| `config/auth.php` | 认证模式（jwt/session/auto） |
| `config/casbin_rbac_model.conf` | Casbin RBAC 模型定义 |
| `config/services.php` | DI 服务定义 |
| `config/routes.php` | 手动定义路由 |
| `config/view.php` | Twig 视图配置 |
| `config/filesystem.php` | 文件存储驱动 |
| `config/logging.php` | 日志通道 |
| `config/session.php` | Session 处理器 |
| `config/cors.php` | CORS 配置 |
| `config/translation.php` | 国际化 |

### 4.2 核心环境变量

```env
APP_ENV=local|prod|test
APP_DEBUG=true|false
APP_NAME=eqrayadmin
APP_TIMEZONE=Asia/Shanghai

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fssoa
DB_USERNAME=root
DB_PASSWORD=root

REDIS_HOST=127.0.0.1
REDIS_PORT=6379
REDIS_PASSWORD=
REDIS_DB=0

CORS_ALLOWED_ORIGINS=
ORM_DRIVER=laravelORM|thinkORM
AUTH_MODE=jwt|session|auto
```

---

## 5. 开发指南

### 5.1 创建一个新控制器

```php
<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\Attributes\Auth;
use Framework\Attributes\Route;
use Framework\Basic\BaseController;
use Framework\Basic\BaseJsonResponse;
use Symfony\Component\HttpFoundation\Request;

#[Route('/api/example')]
class ExampleController extends BaseController
{
    #[Auth]
    public function index(Request $request): array
    {
        return BaseJsonResponse::success(['data' => 'hello']);
    }

    public function store(Request $request): array
    {
        $data = $request->request->all();
        return BaseJsonResponse::success($data);
    }
}
```

### 5.2 创建一个模型

```php
<?php

declare(strict_types=1);

namespace App\Models;

use Framework\Basic\BaseModel;  // class_alias 自动指向当前 ORM

class User extends BaseModel
{
    protected string $table = 'sa_system_user';
    protected array $fillable = ['username', 'password', 'status'];
    protected array $hidden = ['password'];
}
```

### 5.3 创建一个中间件

```php
<?php

declare(strict_types=1);

namespace App\Middlewares;

use Framework\Middleware\MiddlewareInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MyMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, \Closure $next): Response
    {
        // 前置处理
        if (!$request->headers->has('X-My-Header')) {
            return new Response('Forbidden', 403);
        }

        $response = $next($request);

        // 后置处理
        $response->headers->set('X-My-Header', 'processed');

        return $response;
    }
}
```

### 5.4 使用事件系统

```php
// 1. 定义事件
namespace App\Events;
class OrderCreatedEvent
{
    public function __construct(public array $order) {}
}

// 2. 定义监听器
namespace App\Listeners;
use Framework\Event\Attribute\EventListener;
use Framework\Event\ListenerInterface;

#[EventListener(OrderCreatedEvent::class)]
class OrderCreatedListener implements ListenerInterface
{
    public function handle(object $event): void
    {
        // 发送通知、记录日志等
    }
}

// 3. 分发事件
EventDispatch(new OrderCreatedEvent($orderData));
```

### 5.5 使用助手函数

| 函数 | 说明 |
|------|------|
| `app()` | 获取容器或解析服务 |
| `config('app.env')` | 读取配置（点语法） |
| `env('APP_DEBUG')` | 读取环境变量 |
| `view('index.html.twig')` | Twig 模板渲染 |
| `caches('key')` | PSR-16 缓存 |
| `trans('messages.hello')` | 国际化翻译 |
| `EventDispatch($event)` | 分发事件 |
| `generateUuid()` | 生成 UUID |
| `storage_path('logs/error.log')` | 路径辅助 |

---

## 6. 测试

项目包含 **232 个测试文件**，覆盖框架所有核心模块。

### 6.1 运行测试

```bash
# 运行所有测试
./vendor/bin/phpunit

# 运行特定测试文件
./vendor/bin/phpunit tests/Unit/Core/FrameworkTest.php

# 运行特定测试类
./vendor/bin/phpunit --filter="RouterTest"
```

### 6.2 测试覆盖范围

| 模块 | 说明 |
|------|------|
| Core | Framework, Kernel, Router, App 核心逻辑 |
| Container | DI 容器初始化、服务解析、编译 |
| Event | 事件分发、监听器发现与注册 |
| Middleware | 13 种中间件的独立与集成测试 |
| Security | Casbin RBAC、CSRF、XSS 过滤 |
| Tenant | 多租户上下文解析与隔离 |
| ORM | Laravel/ThinkPHP 双适配层 |
| Cache | PSR-16 缓存驱动 |
| Validation | ThinkPHP 验证器工厂 |
| View | Twig 模板渲染 |

### 6.3 代码质量工具

```bash
# 静态分析
./vendor/bin/phpstan analyse

# 代码风格检查
php vendor/bin/php-cs-fixer fix --dry-run --diff

# 代码风格自动修复
php vendor/bin/php-cs-fixer fix
```

---

## 7. 部署

### 7.1 生产环境部署（Workerman 模式）

```bash
# 1. 安装依赖
composer install --no-dev --optimize-autoloader

# 2. 编辑 .env
APP_ENV=prod
APP_DEBUG=false

# 3. 使用 Supervisor 进程管理
```

**Supervisor 配置**:

```ini
[program:eqrayphp]
command=php /path/to/server.php start -d
directory=/path/to/project
autostart=true
autorestart=true
user=www-data
numprocs=1
startretries=3
stderr_logfile=/path/to/storage/logs/supervisor-error.log
stdout_logfile=/path/to/storage/logs/supervisor-out.log
```

### 7.2 生产环境部署（FPM 模式）

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/project/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:///run/php-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### 7.3 生产优化清单

- [ ] `APP_ENV=prod`
- [ ] `APP_DEBUG=false`
- [ ] `composer install --no-dev --optimize-autoloader`
- [ ] 删除 `.env` 中的调试信息
- [ ] 配置 Supervisor 管理 Workerman 进程
- [ ] 设置 `storage/` 和 `runtime/` 目录权限
- [ ] 配置日志轮转
- [ ] 配置 Redis 密码和持久化
- [ ] 配置 MySQL 连接池
- [ ] 确保 PHP 扩展: `redis`, `pdo_mysql`, `mbstring`, `json`, `openssl`, `gd`, `fileinfo`

---

> 本指南基于 eqrayphp 框架源码分析生成，版本 0.2.2。如有更新请同步修改。
