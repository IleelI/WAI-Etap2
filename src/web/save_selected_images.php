<?php session_start();

    if (isset($_SESSION['isLoggedIn']) === false || empty($_SESSION['isLoggedIn']) === true) {
        $_SESSION['save_images_error'] = 'Cannot save images to anonymous user!<br>';
        header("Location: index.php?saved=false");
    }
    else if (empty($_POST['photos']) === true) {
        $_SESSION['save_images_error'] = 'No images were selected!<br>';
        header("Location: index.php?saved=false");
    }
    else {
        $photos_array = $_POST['photos'];
        if (isset($_SESSION['save_images'])) {
            foreach ($_SESSION['save_images'] as $saved_image) {
                foreach($photos_array as $new_photo) {
                    if ($saved_image === $new_photo) {
                        $new_photo = '0';
                    }
                }
            }
        }
        $images_count = 0;
        foreach ($photos_array as $photo) {
            if ($photo === '0') {
                continue;
            }
            $_SESSION['save_images'][$images_count++] = $photo;
        }
        header("Location: index.php?saved=true");
    }
?>