<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace Framework\Core;

use Framework\Attributes\Action;
use Framework\Attributes\Auth;
use Framework\Attributes\MiddlewareProviderInterface;
use Framework\Attributes\Role;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

/**
 * Router (Attribute-only Version).
 *
 * 仅基于 PHP Attribute 注解路由（#[Route] / #[GetMapping] 等）进行匹配，
 * 不再支持 URL 段自动推断与多应用 / 域名绑定。
 *
 * 特性：
 * - 混合路由：Symfony 定义路由（已由 AttributeRouteLoader 扫描生成）+ 注解路由
 * - 路由命中缓存：基于 PSR-16 缓存 URL 匹配结果
 * - 元数据编译：Attribute 扫描结果供中间件 / 鉴权使用
 * - 安全策略：黑白名单机制（作用于控制器可见性）
 */
class Router
{
    private const DEFAULT_CONTROLLER_NAMESPACE = 'App\Modules';

    private const CACHE_KEY_PREFIX = 'route_match_v1_';

    private const CACHE_TTL = 3600; // 缓存 1 小时

    // 核心依赖
    private RouteCollection $routes;

    private ?CacheInterface $cache = null; // PSR-16 缓存实例

    private string $controllerNamespace;

    // 编译后的元数据缓存 (Controller::Method => Metadata Array)
    /** @var array<mixed> */
    private array $compiledMetadata = [];

    // 运行时方法存在性缓存 (防止重复反射检查)
    /** @var array<mixed> */
    private array $methodExistenceCache = [];

    // --- 安全策略配置 ---
    private bool $requireExplicitAction = false;

    /** @var array<mixed> */
    private array $whitelist = [];

    /** @var array<mixed> */
    private array $blacklist = [];

    public function __construct(
        RouteCollection $routes,
        string $controllerNamespace = self::DEFAULT_CONTROLLER_NAMESPACE
    ) {
        $this->routes              = $routes;
        $this->controllerNamespace = rtrim($controllerNamespace, '\\');
    }

    public function setCache(CacheInterface $cache): self
    {
        $this->cache = $cache;
        return $this;
    }

    /**
     * 设置安全策略.
     *
     * @param bool         $requireExplicitAction 是否开启显式 Action 模式
     * @param array<mixed> $whitelist             允许的命名空间前缀
     * @param array<mixed> $blacklist             禁止的命名空间前缀
     */
    public function setSecurityPolicy(
        bool $requireExplicitAction = false,
        array $whitelist = [],
        array $blacklist = []
    ): self {
        $this->requireExplicitAction = $requireExplicitAction;
        $this->whitelist             = $whitelist;
        $this->blacklist             = $blacklist;
        return $this;
    }

    /**
     * 加载预编译的元数据（生产环境零反射）.
     * @param array<mixed> $metadata
     */
    public function loadMetadata(array $metadata): void
    {
        $this->compiledMetadata = $metadata;
    }

    /**
     * @return array<mixed>
     */
    public function dumpMetadata(): array
    {
        return $this->compiledMetadata;
    }

    /**
     * 执行路由匹配（仅 Attribute / 定义路由）.
     *
     * @return ?array<mixed>
     */
    public function match(Request $request): ?array
    {
        $this->preprocessRequest($request);

        // 路由命中缓存
        $cacheKey = $this->getCacheKey($request);
        if ($this->cache && $cachedResult = $this->cache->get($cacheKey)) {
            return $this->restoreFromCache($request, $cachedResult);
        }

        $context      = (new RequestContext())->fromRequest($request);
        $path         = $request->getPathInfo();
        $matchedRoute = $this->matchDefinedRoutes($path, $context, $request);

        if ($matchedRoute) {
            $this->saveToCache($cacheKey, $matchedRoute);
            return $matchedRoute;
        }

        return null;
    }

    /**
     * 匹配 Symfony 定义的静态路由（由 AttributeRouteLoader 生成）.
     *
     * @return ?array<mixed>
     */
    private function matchDefinedRoutes(
        string $path,
        RequestContext $context,
        Request $request
    ): ?array {
        try {
            $matcher = new UrlMatcher($this->routes, $context);
            $params  = $matcher->match($path);

            if (! isset($params['_controller'])) {
                return null;
            }

            [$controller, $method] = str_contains($params['_controller'], '::')
                ? explode('::', $params['_controller'], 2)
                : [$params['_controller'], '__invoke'];

            if (! $this->isControllerMethodValid($controller, $method)) {
                return null;
            }

            return $this->finalizeRoute(
                $request,
                $controller,
                $method,
                $params,
                $params['_route'] ?? 'defined_route'
            );
        } catch (MethodNotAllowedException|ResourceNotFoundException $e) {
            return null;
        }
    }

    /**
     * 最终化路由：提取 Attribute，注入 Request，构建返回数组.
     * @param  array<mixed> $params
     * @return array<mixed>
     */
    private function finalizeRoute(
        Request $request,
        string $controller,
        string $method,
        array $params,
        string $routeName
    ): array {
        $meta = $this->getMetadata($controller, $method);

        $mergedMiddleware = array_unique(
            array_merge($params['_middleware'] ?? [], $meta['middleware'])
        );

        $attributes = $params + [
            '_controller' => "{$controller}::{$method}",
            '_route'      => $routeName,
            '_middleware' => array_values($mergedMiddleware),
            '_auth'       => $meta['auth'],
            '_roles'      => $meta['roles'],
            '_attributes' => $meta['attributes_instances'],
        ];

        $request->attributes->add($attributes);

        return [
            'controller' => $controller,
            'method'     => $method,
            'params'     => $params,
            'middleware' => array_values($mergedMiddleware),
            '__meta_flat' => [
                '_auth'  => $meta['auth'],
                '_roles' => $meta['roles'],
            ],
        ];
    }

    /**
     * 安全策略检查：控制器是否允许访问.
     */
    private function isControllerAllowed(string $controller): bool
    {
        foreach ($this->blacklist as $blocked) {
            if (str_starts_with($controller, $blocked)) {
                return false;
            }
        }

        if (! empty($this->whitelist)) {
            foreach ($this->whitelist as $allowed) {
                if (str_starts_with($controller, $allowed)) {
                    return true;
                }
            }
            return false;
        }

        return true;
    }

    /**
     * 获取元数据：从预编译数组读取 或 实时扫描.
     * @return array<mixed>
     */
    private function getMetadata(string $controller, string $method): array
    {
        $key = "{$controller}::{$method}";

        if (isset($this->compiledMetadata[$key])) {
            return $this->compiledMetadata[$key];
        }

        return $this->compiledMetadata[$key] = $this->scanAttributes($controller, $method);
    }

    /**
     * 扫描 Attributes (Reflection).
     * @return array<mixed>
     */
    private function scanAttributes(string $controller, string $method): array
    {
        $middleware         = [];
        $auth               = null;
        $roles              = [];
        $attributeInstances = [];

        try {
            $rc = new \ReflectionClass($controller);
            $rm = $rc->getMethod($method);

            if (! $this->isControllerAllowed($controller)) {
                return [
                    'middleware'           => [],
                    'auth'                 => null,
                    'roles'                => [],
                    'attributes_instances' => [],
                ];
            }

            $attributes = array_merge($rc->getAttributes(), $rm->getAttributes());

            foreach ($attributes as $attr) {
                try {
                    $instance = $attr->newInstance();

                    if ($instance instanceof MiddlewareProviderInterface) {
                        foreach ((array) $instance->getMiddleware() as $m) {
                            if (is_string($m) && $m !== '') {
                                $middleware[] = $m;
                            }
                        }
                    }

                    if ($instance instanceof Auth) {
                        $auth                            = $instance->required;
                        $attributeInstances[Auth::class] = $instance;
                    }

                    if ($instance instanceof Role) {
                        $roles                           = array_merge($roles, $instance->roles);
                        $attributeInstances[Role::class] = $instance;
                    }
                } catch (\Throwable $e) {
                    continue;
                }
            }
        } catch (\Throwable $e) {
            $this->logException($e, "Attribute scan failed for {$controller}::{$method}");
        }

        return [
            'middleware'           => array_values(array_unique($middleware)),
            'auth'                 => $auth,
            'roles'                => array_values(array_unique($roles)),
            'attributes_instances' => $attributeInstances,
        ];
    }

    /**
     * 从缓存恢复请求状态.
     * @param  array<mixed> $cachedRoute
     * @return array<mixed>
     */
    private function restoreFromCache(Request $request, array $cachedRoute): array
    {
        $attributes                = $cachedRoute['params'] ?? [];
        $attributes['_controller'] = $cachedRoute['controller'] . '::' . $cachedRoute['method'];
        $cachedMiddleware          = is_array($cachedRoute['middleware'] ?? null) ? $cachedRoute['middleware'] : [];

        // 即使命中缓存，也重新读取最新 Attribute 元数据，避免注解变更后缓存导致鉴权失效
        $controller = (string) ($cachedRoute['controller'] ?? '');
        $method     = (string) ($cachedRoute['method'] ?? '');
        if ($controller !== '' && $method !== '') {
            $meta                      = $this->getMetadata($controller, $method);
            $attributes['_middleware'] = array_values(array_unique(array_merge(
                $cachedMiddleware,
                (array) ($meta['middleware'] ?? [])
            )));
            $attributes['_auth']       = $meta['auth'] ?? null;
            $attributes['_roles']      = array_values(array_unique((array) ($meta['roles'] ?? [])));
            $attributes['_attributes'] = $meta['attributes_instances'] ?? [];

            $cachedRoute['middleware']  = $attributes['_middleware'];
            $cachedRoute['__meta_flat'] = [
                '_auth'  => $attributes['_auth'],
                '_roles' => $attributes['_roles'],
            ];
        } else {
            $attributes['_middleware'] = $cachedMiddleware;
            if (isset($cachedRoute['__meta_flat'])) {
                $attributes = array_merge($attributes, $cachedRoute['__meta_flat']);
            }
        }

        $request->attributes->add($attributes);
        return $cachedRoute;
    }

    private function saveToCache(string $key, array $route): void
    {
        if (! $this->cache) {
            return;
        }

        $this->cache->set($key, $route, self::CACHE_TTL);
    }

    private function getCacheKey(Request $request): string
    {
        return self::CACHE_KEY_PREFIX . md5($request->getMethod() . $request->getPathInfo());
    }

    /**
     * 校验控制器方法是否存在（含安全策略过滤）.
     */
    private function isControllerMethodValid(string $class, string $method): bool
    {
        return class_exists($class)
            && in_array($method, $this->getValidControllerMethods($class), true);
    }

    /**
     * 获取控制器中有效的 public 方法列表.
     * @return array<mixed>
     */
    private function getValidControllerMethods(string $class): array
    {
        if (isset($this->methodExistenceCache[$class])) {
            return $this->methodExistenceCache[$class];
        }

        try {
            $rc           = new \ReflectionClass($class);
            $validMethods = [];

            foreach ($rc->getMethods(\ReflectionMethod::IS_PUBLIC) as $m) {
                if ($m->isConstructor() || str_starts_with($m->getName(), '__')) {
                    continue;
                }

                if ($this->requireExplicitAction) {
                    if (empty($m->getAttributes(Action::class))) {
                        continue;
                    }
                }

                $validMethods[] = $m->getName();
            }

            return $this->methodExistenceCache[$class] = $validMethods;
        } catch (\Throwable $e) {
            return $this->methodExistenceCache[$class] = [];
        }
    }

    private function preprocessRequest(Request $request): void
    {
        if (str_ends_with($request->getPathInfo(), '.html')) {
            $clean = substr($request->getPathInfo(), 0, -5);
            if (preg_match('#^[a-zA-Z0-9/_-]+$#', $clean)) {
                $request->server->set(
                    'REQUEST_URI',
                    str_replace($request->getPathInfo(), $clean, $request->getUri())
                );
            }
        }
    }

    private function logException(\Throwable $e, string $context): void
    {
        error_log("[Router] {$context}: {$e->getMessage()}");
    }
}
