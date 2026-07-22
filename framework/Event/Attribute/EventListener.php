<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace Framework\Event\Attribute;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class EventListener
{
    /**
     * @param null|string $event    事件类名 (可选，如果不填则尝试从参数类型推断)
     * @param int         $priority 优先级
     */
    public function __construct(
        public ?string $event = null,
        public int $priority = 0
    ) {}
}
