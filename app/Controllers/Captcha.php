<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace App\Controllers;

use Framework\Utils\Captcha as CCaptcha;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Captcha
{
    public function captchaImage(Request $request): Response
    {
        $CaptchaImage =CCaptcha::base64();

        $imgsrc = $CaptchaImage['base64'];

        return new Response("<img src='{$imgsrc}'>");
    }
}
