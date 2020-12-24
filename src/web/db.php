<?php
    require 'vendor/autoload.php';
    function get_db() {
        $mongo = new MongoDB\Client(
            "mongodb://localhost:27017/wai",
            [
                'username' => 'wai_web',
                'password' => 'w@i_w3b',
            ]
        );
        return $mongo->wai;
    }
    function saveImageToCollection($image, $title, $author, $collection) {
        $file_name_without_ext = basename($image->file_name,'.'.$image->file_ext);
        $insert_result = $collection->insertOne([
            'title' => $title,
            'author' => $author,
            'size' => $image->file_size_mb,
            'filename' => $image->file_name,
            'filename_watermarked' => $file_name_without_ext.'_watermark.'.$image->file_ext,
            'filename_miniaturized' => $file_name_without_ext.'_min.'.$image->file_ext
        ]);
    }
    function findUser($db, $login) {
        $user = $db->findOne(['login' => $login]);
        if ($user === NULL) {
            return false;
        }
        return $user;
    }
    function dropCollection($collection) {
        $collection->drop();
    }
?>