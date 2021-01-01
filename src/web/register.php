<?php
    session_start();
    require_once 'db.php';
    function checkIfEmpty($input_data_arr, &$errors_arr) {
        $empty_input = false;
        foreach ($input_data_arr as $key => $input) {
            if (empty($input) || isset($input_data_arr[$key]) === false) {
                $errors_arr[$key] = $key.' is empty! ';
                $empty_input = true;
            }
        }
        return ($empty_input !== true);
    }
    function checkValidEmail($email, &$errors) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $errors['email'] .= 'Email Is Not Valid! ';
            return false;
        }
        return true;
    }
    function checkLogin($login, $user_db, &$errors) {
        $users = $user_db->find();
        $users = $users->toArray();
        foreach ($users as $user) {
            if ($login === $user['login']) {
                $errors['login'] .= 'Login is already taken! ';
                return false;
            }
        }
        return true;
    }
    function checkEmail($email, $user_db, &$errors) {
        $users = $user_db->find();
        $users = $users->toArray();
        foreach ($users as $user) {
            if ($user['email'] === $email) {
                $errors['email'] .= 'Email is already in use! ';
                return false;
            }
        }
        return true;
    }
    function checkPassword($password, $repeat_password, &$errors) {
        if ($password !== $repeat_password) {
            $errors['password'] .= "Passwords don't match! ";
            return false;
        }
        return true;
    }
    function checkRegistration(&$input_data, &$errors, &$isValid, $user_db) {
        $validationSteps = 0;
        $isValid[$validationSteps++] = checkIfEmpty($input_data,$errors);
        $isValid[$validationSteps++] = checkValidEmail($input_data['email'], $errors);
        $isValid[$validationSteps++] = checkLogin($input_data['login'],$user_db,$errors);
        $isValid[$validationSteps++] = checkEmail($input_data['email'],$user_db,$errors);
        $isValid[$validationSteps] = checkPassword($input_data['password'], $input_data['repeat_password'], $errors);
        foreach ($isValid as $checkIfValid) {
            if ($checkIfValid === false) {
                return false;
            }
        }
        return true;
    }
    function addUserToDatabase($input_data, $user_db) {
        $result = $user_db->insertOne([
            'login' => $input_data['login'],
            'password' => hash('sha256', $input_data['password']),
            'email' => $input_data['email']
        ]);
    }

    $input_data = [
        'email' => $_POST['email'],
        'login' => $_POST['login'],
        'password' => $_POST['password'],
        'repeat_password' => $_POST['re_password']
    ];
    $errors = [
        'email' => '',
        'login' => '',
        'password' => '',
        'repeat_password' => '',
    ];
    $isValid = array();
    $user_db = get_db()->users;
    $_SESSION['register_errors'] = array();
    $_SESSION['registration_success'] = false;

    if (checkRegistration($input_data,$errors,$isValid,$user_db)) {
        addUserToDatabase($input_data,$user_db);
        $_SESSION['registration_success'] = true;
        header("Location: /register?registration=success");
    }
    else {
        $_SESSION['registration_success'] = false;
        $_SESSION['register_errors'] = $errors;
        header("Location: /register?registration=failed");
    }

?>