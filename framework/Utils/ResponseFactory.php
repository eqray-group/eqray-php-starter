<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace Framework\Utils;

use Symfony\Component\HttpFoundation\Response;

/*
$response = new Response('ok', 200);

// 重置内容
app('response')->setContent('Hello eqrayphp!');

// 设置单个头
$response->headers->set('Authorization', 'Bearer 123');

// 添加多个头
$response->headers->add([
    'X-Token-Refresh' => 'xxx',
    'Cache-Control'   => 'no-store',
]);

// 删除头
$response->headers->remove('Authorization');
*/

class ResponseFactory
{
    public static function create(): Response
    {
        return new Response('', Response::HTTP_OK, []);
    }
}
