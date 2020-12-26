<?php

$router = new \controller\Router();

$router->get('post', function ($response) {

    $post = new \controller\Post();

    $response->setSuccess(true);
    $response->setHttpStatusCode(200);
    $response->addMessage("Posts");
    $response->setData($post->all());
    $response->send();
});

$router->get('post/:id', function ($response, $id) {

    $post = new \controller\Post((int) $id);

    $response->setSuccess(true);
    $response->setHttpStatusCode(200);
    $response->addMessage("Singel post");
    $response->setData($post->get());

    $response->send();
});

$router->post('post', function ($response) {

    if (!isAuth($response)) {
        return;
    }

    $name = !empty($_POST['name']) ? $_POST['name'] : '';
    $content = !empty($_POST['content']) ? $_POST['content'] : '';
    $photo = !empty($_POST['photo']) ? $_POST['photo'] : '';

    $post = new \controller\Post(0, $name, $content, $photo);

    if (!valid($name, 'title') || !valid($content, 'content') || !valid($photo, 'url')) {

        $response->setSuccess(false);
        $response->setHttpStatusCode(405);
        $response->addMessage("Data not valid");
        $response->setData([
            'post' => $post
        ]);

        $response->send();
        return;
    }

    $response->setSuccess(true);
    $response->setHttpStatusCode(200);
    $response->addMessage("Store post");
    $response->setData([
        'id' => $post->store()
    ]);

    $response->send();
});

$router->put('post/:id', function ($response, $id) {

    if (!isAuth($response)) {
        return;
    }

    $name = !empty($_POST['name']) ? $_POST['name'] : '';
    $content = !empty($_POST['content']) ? $_POST['content'] : '';
    $photo = !empty($_POST['photo']) ? $_POST['photo'] : '';

    $post = new \controller\Post((int) $id, $name, $content, $photo);

    if (!valid($name, 'title') || !valid($content, 'content') || !valid($photo, 'url')) {

        $response->setSuccess(false);
        $response->setHttpStatusCode(405);
        $response->addMessage("Data not valid");
        $response->setData([
            'post' => $post
        ]);

        $response->send();
        return;
    }

    $response->setSuccess(true);
    $response->setHttpStatusCode(200);
    $response->addMessage("Update post");
    $response->setData([
        'count' => $post->save()
    ]);

    $response->send();
});

$router->delete('post/:id', function ($response, $id) {

    if (!isAuth($response)) {
        return;
    }

    $post = new \controller\Post((int) $id);

    $response->setSuccess(true);
    $response->setHttpStatusCode(200);
    $response->addMessage("Delete post");
    $response->setData([
        'count' => $post->remove()
    ]);

    $response->send();
});

$router->post('session', function ($response) {

    sleep(1);

    $username = !empty($_POST['username']) ? $_POST['username'] : '';
    $password = !empty($_POST['password']) ? $_POST['password'] : '';

    if (!valid($username, 'username') || !valid($password, 'password')) {

        $response->setSuccess(false);
        $response->setHttpStatusCode(400);
        $response->addMessage("Data not valid");
        $response->send();
        return;
    }

    $temp = new \controller\User(0, $username);
    $user = $temp->getByUsername($username);

    if ($user->loginattempts > 3) {
        $response->setHttpStatusCode(401);
        $response->setSuccess(false);
        $response->addMessage("User account is currently locked out");
        $response->send();
        return;
    }

    $loginattempts = $user->loginattempts + 1;


    if (!passwordVerify($password, $user->password)) {

        $response->setHttpStatusCode(401);
        $response->setSuccess(false);
        $response->addMessage("Username or password is incorrect");
        $response->send();
        return;
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

    $updateUser = new \controller\User($user->id, $user->username, $user->password, $user->email, $loginattempts);
    $updateUser->save();

    $session = new \controller\Session(0, $user->id, $accesstoken, $access_token_expiry_seconds, $refreshtoken, $refresh_token_expiry_seconds);
    $session->id = $session->store();

    $response->setSuccess(true);
    $response->setHttpStatusCode(200);
    $response->addMessage("Store session");
    $response->setData([
        'session' => $session
    ]);

    $response->send();
});

$router->put('session/:id', function ($response) {

    $accesstoken = $_SERVER['HTTP_AUTHORIZATION'];
    $refreshtoken = !empty($_POST['refresh_token']) ? $_POST['refresh_token'] : '';

    if (empty($accesstoken) || empty($refreshtoken)) {

        $response->setSuccess(false);
        $response->setHttpStatusCode(405);
        $response->addMessage("Data not valid");
        $response->setData([]);
        $response->send();
        return;
    }

    $temp = new \controller\Session((int) $id, 0, $accesstoken, 0, $refreshtoken);
    $session = $temp->get();
    $temp = new \controller\User((int) $session->userid);
    $user = $temp->get();

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

    $updateSession = new \controller\Session($session->id, $user->id, $accesstoken, $access_token_expiry_seconds, $refreshtoken, $refresh_token_expiry_seconds);
    $updateSession->save();

    $response->setSuccess(true);
    $response->setHttpStatusCode(200);
    $response->addMessage("Update session");
    $response->setData([
        'session' => $updateSession
    ]);
});

$router->delete('session/:id', function ($response, $id) {

    $accesstoken = $_SERVER['HTTP_AUTHORIZATION'];

    $session = new \controller\Session((int) $id, 0, $accesstoken);

    $response->setSuccess(true);
    $response->setHttpStatusCode(200);
    $response->addMessage("Delete session");
    $response->setData([
        'count' => $session->remove()
    ]);

    $response->send();
});

$router->end(function ($response) {

    $response->setSuccess(false);
    $response->setHttpStatusCode(404);
    $response->addMessage("not found");
    $response->setData([]);

    $response->send();
});
