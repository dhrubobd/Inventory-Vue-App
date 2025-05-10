<?php
namespace App\Helper;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTToken{
    public static function createJWTToken($userEmail, $userID) {
        $key = env('JWT_KEY');
        $payload = [
            'iss'=>'laravel-token',
            'iat'=>time(),
            'exp'=>time()+(60*24), //  (60*24) = One Day
            'userEmail' =>  $userEmail,
            'userID' => $userID
        ];
        return JWT::encode($payload, $key, 'HS256');
    }

    public static function verifyJWTToken($token){
        $key = env('JWT_KEY');
        try {
            if($token == null){
                return 'Unauthorized';
            }
            else{
                return JWT::decode($token, new Key($key,'HS256'));
            }
        } catch (\Throwable $th) {
            return 'Unauthorized';
        }
    }
}
