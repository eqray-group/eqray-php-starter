<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace Framework\Utils;

use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Crypt\RSA;
use phpseclib3\Crypt\RSA\PrivateKey;
use phpseclib3\Crypt\RSA\PublicKey;

/**
 * RSA秘钥服务
 *
 * @since  1.0
 */
class RSAService
{
    /**
     * 生成秘钥对.
     *
     * @return array<mixed> */
    public static function generateKeys(int $bits = 2048): array
    {
        $privateKey = RSA::createKey($bits);

        $privatePem = $privateKey->toString('PKCS1');
        $publicPem  = $privateKey->getPublicKey()->toString('PKCS8');

        return [
            'private' => $privatePem,
            'public'  => $publicPem,
        ];
    }

    /**
     * 签名.
     */
    public static function sign(string $data, string $privatePem): string
    {
        /** @var PrivateKey $privateKey */
        $privateKey = PublicKeyLoader::loadPrivateKey($privatePem);
        $privateKey = $privateKey
            ->withHash('sha256')
            ->withPadding(RSA::SIGNATURE_PKCS1);

        return $privateKey->sign($data);
    }

    /**
     * 验证签名.
     */
    public static function verify(string $data, string $signature, string $publicPem): bool
    {
        /** @var PublicKey $publicKey */
        $publicKey = PublicKeyLoader::load($publicPem);
        $publicKey = $publicKey
            ->withHash('sha256')
            ->withPadding(RSA::SIGNATURE_PKCS1);

        return $publicKey->verify($data, $signature);
    }

    /**
     * 公钥加密.
     */
    public static function encrypt(string $data, string $publicPem): string
    {
        /** @var PublicKey $publicKey */
        $publicKey = PublicKeyLoader::load($publicPem);
        $publicKey = $publicKey->withPadding(RSA::ENCRYPTION_PKCS1);
        return base64_encode($publicKey->encrypt($data));
    }

    /**
     * 私钥解密.
     */
    public static function decrypt(string $cipherBase64, string $privatePem): string
    {
        /** @var PrivateKey $privateKey */
        $privateKey = PublicKeyLoader::loadPrivateKey($privatePem);
        $privateKey = $privateKey->withPadding(RSA::ENCRYPTION_PKCS1);

        $cipher = base64_decode($cipherBase64);
        return $privateKey->decrypt($cipher);
    }

    /**
     * 公钥掩码显示.
     */
    public static function maskKey(string $pem, int $keep = 20): string
    {
        $clean = str_replace(
            ["\r", "\n", '-----BEGIN PUBLIC KEY-----', '-----END PUBLIC KEY-----'],
            '',
            $pem
        );
        $len   = strlen($clean);
        return substr($clean, 0, $keep) . '...' . substr($clean, -$keep);
    }
}
