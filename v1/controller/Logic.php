<?php

namespace controller;

class Logic {

    public function __construct() {
        
    }

    public function login($username, $password) {

        $obj = new \controller\User(0, $username);
        $user = $obj->getByUsername($username);

        if ($user->loginattempts > 3) {
            
        }

        $loginattempts = $user->loginattempts + 1;

        if (!password_verify($password, $user->password)) {
            
        }

        // generate access token
        // use 24 random bytes to generate a token then encode this as base64
        // suffix with unix time stamp to guarantee uniqueness (stale tokens)
        $accesstoken = base64_encode(bin2hex(openssl_random_pseudo_bytes(24)) . time());

        // generate refresh token
        // use 24 random bytes to generate a refresh token then encode this as base64
        // suffix with unix time stamp to guarantee uniqueness (stale tokens)
        $refreshtoken = base64_encode(bin2hex(openssl_random_pseudo_bytes(24)) . time());

        // set access token and refresh token expiry in seconds (access token 20 minute lifetime and refresh token 14 days lifetime)
        // send seconds rather than date/time as this is not affected by timezones
        $access_token_expiry_seconds = 1200;
        $refresh_token_expiry_seconds = 1209600;

        $loginattempts = 0;
        
        //$updateUser = new \controller\User($user->id, $user->username, $user->password, $user->email, $loginattempts);
        //$updateUser->save();
    }

}
