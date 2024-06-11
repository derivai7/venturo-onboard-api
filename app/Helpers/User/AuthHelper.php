<?php

namespace App\Helpers\User;

use App\Helpers\Venturo;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Resources\User\UserResource;
use Tymon\JWTAuth\Exceptions\JWTException;

/**
 * Helper khusus untuk authentifikasi pengguna
 */
class AuthHelper extends Venturo
{
    /**
     * Proses validasi email dan password
     * jika terdaftar pada database dilanjutkan generate token JWT
     *
     * @param string $email
     * @param string $password
     *
     * @return array
     */
    public static function login(string $email, string $password): array
    {
        try {
            $credentials = ['email' => $email, 'password' => $password];
            if (!$token = JWTAuth::attempt($credentials)) {
                return [
                    'status' => false,
                    'error' => ['Kombinasi email dan password yang kamu masukkan salah']
                ];
            }
        } catch (JWTException $e) {
            return [
                'status' => false,
                'error' => ['Could not create token.']
            ];
        }

        return [
            'status' => true,
            'data' => self::createNewToken($token)
        ];
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return array
     */
    protected static function createNewToken(string $token): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => new UserResource(auth()->user())
        ];
    }
}

