<?php
    session_start();
    require_once './db.php';
    function checkIfEmpty($input_data_arr, &$errors_arr) {
        $empty_input = false;
        foreach ($input_data_arr as $key => $input) {
            if (empty($input) || isset($input_data_arr[$key]) === false) {
                $errors_arr[$key] = $key.' is empty! ';
                $empty_input = true;
            }
        }
        return ($empty_input === true);
    }
    function checkUserName($login, $user_db, &$errors) {
        $users = $user_db->find();
        $users = $users->toArray();
        foreach ($users as $user) {
            if ($login === $user['login']) {
                return true;
            }
        }
        $errors['login'] .= 'Login not found! ';
        return false;
    }
    function checkPassword($password, $login, $user_db, &$errors) {
        $found_user = findUser($user_db, $login);
        $password_sha256 = hash('sha256', $password);
        if ($password_sha256 === $found_user['password']) {
            return true;
        }
        $errors['password'] = 'Wrong Password! ';
        return false;
    }
    function checkLogin($input_data_arr, &$errors_arr, $user_db, &$isValid) {
        if (checkIfEmpty($input_data_arr,$errors_arr) === true) {
            $isValid[0] = false;
            return false;
        }

        $validation_steps = 0;
        if (checkUserName($input_data_arr['login'],$user_db,$errors_arr) === false) {
            $isValid[$validation_steps++] = false;
            return false;
        }

        $isValid[$validation_steps++] = true;
        $isValid[$validation_steps++] = checkPassword($input_data_arr['password'], $input_data_arr['login'], $user_db, $errors_arr);
        foreach ($isValid as $validation) {
            if ($validation === false) {
                return false;
            }
        }
        return true;
    }

    $users_db = get_db()->selectCollection('users');
    $input_data = [
        'login' => $_POST['login'],
        'password' => $_POST['password']
    ];
    $errors = [
        'login' => '',
        'password' => ''
    ];
    $loginIsValid = array();

    if (checkLogin($input_data,$errors, $users_db,$loginIsValid) === true) {
        $current_user = findUser($users_db, $input_data['login']);
        $_SESSION['isLoggedIn'] = true;
        $_SESSION['user_id'] = $current_user['_id'];
        $_SESSION['username'] = $current_user['login'];
        header("Location: ./views-php/login-views.php?login=success");
    }
    else {
        $_SESSION['login_errors'] = $errors;
        header("Location: login-views-html.php?login=failed");
    }
?>