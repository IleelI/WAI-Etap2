<?php
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 90, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    header('Location: index.php');
?>