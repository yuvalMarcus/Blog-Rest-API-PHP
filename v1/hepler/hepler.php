<?php

function valid($content, $type) {

    switch ($type) {
        case 'title':
            if (strlen($content) === 0)
                return false;
            if (!preg_match('/^[a-zA-Z\u0590-\u05fe ]*$/', $content))
                return false;
            return true;
            break;
        case 'content':
            if (strlen($content) === 0)
                return false;
            return true;
            break;
        case 'url':
            if (strlen($content) === 0)
                return false;
            return true;
            break;
        case 'username':
            if (strlen($content) === 0)
                return false;
            if (!preg_match('/^[a-zA-Z0-9]*$/', $content))
                return false;
            return true;
            break;
        case 'password':
            if (strlen($content) === 0)
                return false;
            return true;
            break;
        case 'email':
            if (strlen($content) === 0)
                return false;
            if (!preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i', $content))
                return false;
            return true;
            break;
        default :
            return true;
    }
}

function passwordHash($string) {

    return sha1(sha1($string . '3dvdn3ds7v33kf_2d-vddn83' . $string));
}

function passwordVerify($password, $currentPassword) {

    if (passwordHash($password) === $currentPassword) {
        return true;
    }
    return false;
}

function isAuth($response) {

    $accesstoken = $_SERVER['HTTP_AUTHORIZATION'];

    if (empty($accesstoken)) {
        $response->setHttpStatusCode(401);
        $response->setSuccess(false);
        $response->addMessage("Invalid token");
        $response->send();
        return false;
    }

    $session_id = !empty($_POST['session_id']) ? $_POST['session_id'] : '';

    $temp = new \controller\Session((int) $session_id, 0, $accesstoken);
    $session = $temp->getByAccessToken();
    $temp = new \controller\User((int) $session->userid);
    $user = $temp->get();

    if (!$session) {
        $response->setHttpStatusCode(401);
        $response->setSuccess(false);
        $response->addMessage("Invalid access token");
        $response->send();
        return false;
    }

    if ($user->returned_loginattempts >= 3) {
        $response->setHttpStatusCode(401);
        $response->setSuccess(false);
        $response->addMessage("User account is currently locked out");
        $response->send();
        return false;
    }

    // check if access token has expired
    if (strtotime($session->returned_accesstokenexpiry) < time()) {
        $response->setHttpStatusCode(401);
        $response->setSuccess(false);
        $response->addMessage("Access token has expired");
        $response->send();
        return false;
    }

    return true;
}
