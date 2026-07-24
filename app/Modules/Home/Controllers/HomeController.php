<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace App\Modules\Home\Controllers;

use Framework\Attributes\Route;
use Framework\Basic\BaseController;
use Framework\Basic\BaseJsonResponse;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends BaseController
{
    #[Route(path: '/api/home', methods: ['GET'], name: 'home.index')]
    public function index(Request $request): BaseJsonResponse
    {
        return BaseJsonResponse::success(['message' => 'Hello, World!']);
    }
}
