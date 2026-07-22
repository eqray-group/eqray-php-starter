<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace Framework\Basic;

/**
 * 通用代理服务类.
 *
 * 这是一个空壳服务类，专门用于实现通用代理模式。
 * 继承自 BaseService，作为动态服务代理的基础类使用。
 *
 * 主要用途：
 * - 作为动态服务调用的占位类
 * - 支持运行时动态绑定和代理其他服务
 * - 提供统一的服务调用接口
 *
 * @extends \Framework\Basic\BaseService<\Framework\Basic\BaseDao>
 */
class GenericService extends BaseService
{
    // 这是一个空壳，专门用来做通用代理
}
