<?php
    session_start();
    if (isset($_SESSION['isLoggedIn']) === false || empty($_SESSION['isLoggedIn']) === true) {
        $_SESSION['save_images_error'] = 'Cannot save images to anonymous user!<br>';
        header("Location: /saved?saved=false");
    }
    else if (empty($_POST['photos']) === true) {
        $_SESSION['save_images_error'] = 'No images were selected!<br>';
        header("Location: /saved?saved=false");
    }
    else {
        $photos_array = $_POST['photos'];

        if (isset($_SESSION['save_images'])) {
            foreach ($_SESSION['save_images'] as $saved_image) {
                foreach($photos_array as $key => $new_photo) {
                    if ($saved_image === $new_photo) {
                        $photos_array[$key] = '0';
                    }
                }
            }
        }
        $images_count = (empty($_SESSION['save_images']) ? 0 : count($_SESSION['save_images']));

        foreach ($photos_array as $photo) {
            if ($photo === '0') {
                continue;
            }
            $_SESSION['save_images'][$images_count++] = $photo;
        }
        header("Location: /?saved=true");
    }
?>