<?php
    if (!isset($_SESSION)) {
        session_start();
    }

    class File {
        public $file;
        public $file_name;
        public $file_type;
        public $file_ext;
        public $file_error_code;
        public $file_size_mb;
        public $file_tmp_name = "";
        public $isValid = true;
        public $errors_count = 0;
        public $file_errors = array();
    }

    function initImageInput($image) {
        $image->file = $_FILES['image'];
        $image->file_name = $image->file['name'];
    }
    // Header redirection is to be deleted when more input options are available
    function checkImageInputErrors($image) {
        if (isset($image->file['error'])) {
            $image->file_error_code = $image->file['error'];
            if ($image->file_error_code === 0) {
                $image->isValid = true;
            }
            else if ($image->file_error_code === 2 || $image->file_error_code === 1) {
                $image->isValid = false;
                $image->file_errors[$image->errors_count] = "File Size is too large!</br>";
                $image->errors_count++;
            }
            else if ($image->file_error_code === 4) {
                $image->isValid = false;
                $image->file_errors[$image->errors_count] = "No File Found!</br>";
            }
        }
        else {
            $image->isValid = false;
            $image->file_errors[$image->errors_count] = "No File Found!</br>";
        }
    }
    function convertImageSize($image) {
        $size_to_mb = 1024 * 1000;
        if ($image->file_error_code !== 2 && $image->file_error_code !== 1 && $image->file_error_code !== 4) {
            $image->file_size_mb = $image->file['size'] / $size_to_mb;
        }
    }
    function checkImageType($image) {
        if ($image->file_error_code === 0) {
            $file_info = finfo_open(FILEINFO_MIME_TYPE);
            $file_tmp_name = $image->file['tmp_name'];
            $mime_type = finfo_file($file_info,$file_tmp_name);
            if ($mime_type !== "image/jpeg" && $mime_type !== "image/png" && $mime_type !== "image/jpg") {
                $image->file_type = $mime_type;
                $image->isValid = false;
                $image->file_errors[$image->errors_count] = "File Type is Wrong!</br>";
                $image->errors_count++;
            }
            else {
                $image->file_type = $mime_type;
            }
        }
        else {
            $extension = substr(strrchr($image->file_name, '.'), 1);
            if ($extension !== 'jpg' && $extension !== 'png') {
                $image->isValid = false;
                $image->file_errors[$image->errors_count] = "File Type is Wrong!</br>";
                $image->errors_count++;
            }
        }
    }
    function uploadFile($image) {
        $image->file_name = basename($image->file['name']);
        $image->file_tmp_name = $image->file['tmp_name'];
        $target_dir = __DIR__.'/images/';
        $target_path = $target_dir.$image->file_name;
        if (move_uploaded_file($image->file_tmp_name, $target_path)) {
            $image->isValid = true;
        }
        else {
            $image->file_errors[$image->errors_count] = "Error Occurred When Uploading The File!";
            $image->isValid = false;
        }
    }
    function checkImageInput($image) {
        initImageInput($image);
        checkImageInputErrors($image);
        convertImageSize($image);
        checkImageType($image);
    }
    function initTextInput(&$text_input, $input_name) {
        $text_input = $_POST[(string)$input_name];
    }
    function createErrorImage() {
        $new_image = imagecreatetruecolor(200, 125);
        $bgc = imagecolorallocate($new_image, 0, 0, 0);
        $text_color = imagecolorallocate($new_image, 255, 255, 255);
        imagefilledrectangle($new_image, 0, 0, 200, 125, $bgc);
        imagestring($new_image, 5, 0, 0, 'Error Loading the Image', $text_color);
    }
    function createWatermarkImage(&$image,$watermark, $folder_dir) {
        $new_image = "";
        if ($image->file_type === 'image/jpg' || $image->file_type === 'image/jpeg') {
            $new_image = imagecreatefromjpeg(__DIR__.'/images/'.$image->file_name);
            $image->file_ext = 'jpg';
            if (!$new_image) {
                createErrorImage();
                return;
            }
        }
        else if ($image->file_type === 'image/png') {
            $new_image = imagecreatefrompng(__DIR__.'/images/'.$image->file_name);
            $image->file_ext = 'png';
            if (!$new_image) {
                createErrorImage();
                return;
            }
        }
        $margin_bottom = 128;
        $margin_right = 128;
        $image_y_size = imagesy($new_image);
        $font_path = __DIR__.'/fonts/Roboto-Black.ttf';
        $text_color = imagecolorallocatealpha($new_image, 255, 255, 255, 40);
        imagettftext($new_image, 48, 0, $margin_right, $image_y_size - $margin_bottom, $text_color, $font_path, $watermark);
        if ($image->file_type === 'image/jpg' || $image->file_type === 'image/jpeg') {
            $new_image_name = basename($image->file_name, '.jpg');
            $extension = 'jpg';
            imagejpeg($new_image, __DIR__.$folder_dir.$new_image_name.'_watermark.'.$extension);
        }
        else if ($image->file_type === 'image/png') {
            $new_image_name = basename($image->file_name, '.png');
            $extension = 'png';
            imagepng($new_image, __DIR__.$folder_dir.$new_image_name.'_watermark.'.$extension);
        }
        imagedestroy($new_image);
    }
    function createThumbnailImage($image, $folder_dir) {
        $new_image = '';
        if ($image->file_type === 'image/jpg' || $image->file_type === 'image/jpeg') {
            $image_name = basename($image->file_name, '.jpg');
            $extension = '.jpg';
            $new_image = imagecreatefromjpeg(__DIR__.'/images/'.$image->file_name);
            if (!$new_image) {
                createErrorImage();
            }
            else {
                $new_image = imagescale($new_image, 200, 125, IMG_BILINEAR_FIXED);
                imagejpeg($new_image, __DIR__.$folder_dir.$image_name.'_min'.$extension, 100);
            }
        }
        else if ($image->file_type === 'image/png') {
            $image_name = basename($image->file_name, '.png');
            $extension = '.png';
            $new_image = imagecreatefrompng(__DIR__.'/images/'.$image->file_name);
            if (!$new_image) {
                createErrorImage();
            }
            else {
                $new_image = imagescale($new_image, 200, 125, IMG_BILINEAR_FIXED);
                imagepng($new_image, __DIR__.$folder_dir.$image_name . '_min' . $extension);
            }
        }
        imagedestroy($new_image);
    }
    function checkIfIsUploaded($image, $db) {
        $images = $db->find([]);
        $images = $images->toArray();
        foreach ($images as $curr_img) {
            if ($curr_img['filename'] === $image->file_name) {
                return 1;
            }
        }
        return 0;
    }

    $image = new File();
    $watermark = "";
    $author = "";
    $title = "";
    $watermark_dir = '/images/watermarked/';
    $miniature_dir = '/images/miniatures/';

    checkImageInput($image);
    $_SESSION['file_errors'] = array();
    $_SESSION['file_is_valid'] = $image->isValid;
    $_SESSION['file_errors'] = $image->file_errors;
    initTextInput($watermark, "watermark");
    initTextInput($author, 'author');
    initTextInput($title, 'title');

    if ($image->isValid === true) {
        require './db.php';
        $images = get_db()->images;
        if (checkIfIsUploaded($image, $images)) {
            $_SESSION['file_is_valid'] = false;
            $_SESSION['file_errors'][$image->errors_count] = "Same File has already been uploaded!</br>";
            header("Location: /?upload_state=0");
        }
        else {
            uploadFile($image);
            createWatermarkImage($image, $watermark, $watermark_dir);
            createThumbnailImage($image, $miniature_dir);
            saveImageToCollection($image, $title, $author, $images);
            header("Location: /?upload_state=1");
        }
    }
    else {
        header("Location: /?upload_state=0");
    }
    ?>