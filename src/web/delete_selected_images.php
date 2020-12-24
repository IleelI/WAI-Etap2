<?php session_start();

    if (empty($_POST['photos']) === true) {
        $_SESSION['save_images_error'] = 'No images were selected!<br>';
        header("Location: index.php?saved=false");
    }
    else {
        $user_saved_photos = $_SESSION['save_images'];
        $images_to_delete = $_POST['photos'];
        foreach ($user_saved_photos as $key => $user_photo) {
            foreach ($images_to_delete as $image_to_delete) {
                if ($image_to_delete === $user_photo) {
                    unset($_SESSION['save_images'][$key]);
                }
            }
        }
    header("Location: /views-php/saved-gallery-views.php?deleted=true");
    }
?>