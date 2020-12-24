<?php
    require 'db.php';
    $images_db = get_db()->images;
    $images_db = $images_db->find()->toArray();
    $saved_images = array();
    $watermarked_path = '../images/watermarked/';
    $miniatures_path = '../images/miniatures/';
    $images = glob('../images/*.*');
?>
<?php
    if (isset($_SESSION['save_images']) === false || empty($_SESSION['save_images'])) {
        $current_page = 0;
        $pages_count = 0;
        echo '<h3>No Images Found! Add some images from the main gallery!</h3>';
    }
    else {
        $user_saved_images = $_SESSION['save_images'];
        $iterator = 0;
        foreach ($user_saved_images as $saved_image) {
            foreach ($images_db as $db_image) {
                $string_mongoid = (string)$db_image['_id'];
                if ($saved_image === $string_mongoid) {
                    $saved_images[$iterator++] = $db_image;
                    break;
                }
            }
        }
        $saved_images_count = count($saved_images);
        $page_images_count = 10;
        $current_page = 0;
        $pages_count = round($saved_images_count / $page_images_count);

        if (!isset($_GET['page'])) {
            $current_page = 0;
        } else {
            $current_page = $_GET['page'];
        }

        $array_offset = ($current_page === 0) ? 0 : $page_images_count * $current_page - 1;
        $current_images_array = array_slice($saved_images, $array_offset, $page_images_count);
        $current_page_first_index = $current_page * $page_images_count;
        $current_page_total_images_count = (($current_page + 1) * $page_images_count);
        for ($i = 0 + $current_page_first_index; $i < $current_page_total_images_count; $i++) : ?>
            <?php if (empty($saved_images[$i])) { break; } ?>
            <div class="image">
                <div class="image-wrapper">
                    <a target="_blank" <?php echo "href='".$watermarked_path.$saved_images[$i]['filename_watermarked']."'" ?>>
                        <img <?php echo "src='".$miniatures_path.$saved_images[$i]['filename_miniaturized']."' ". "alt='".$saved_images[$i]['title']."'"?>/>
                    </a>
                </div>
                <div class="image__about">
                    <p class="title">Tytuł: <?php echo $saved_images[$i]['title'] ?> </p>
                    <p class="author">Autor: <?php echo $saved_images[$i]['author'] ?></p>
                    <label>Wybierz
                        <?php $string_mdid = (string)$saved_images[$i]['_id'] ?>
                        <input type="checkbox" name="photos[]" autocomplete="off" value="<?php echo $string_mdid ?>">
                    </label>
                </div>
            </div>
        <?php endfor; ?>
    <?php } ?>
    </div>
    <div class="gallery__navigation">
        <button class="btn__save-images">Usuń wybrane zdjęcia</button>
        <?php if (isset($_GET['saved'])) {
            if ($_GET['saved'] === 'false' || empty($_SESSION['isLoggedIn'])) {
                echo '<p class"error">'.$_SESSION['save_images_error'].'</p>';
            }
            else {
                echo '<p class="success">Images deleted correctly!</p>';
            }
        } ?>
        <div class="gallery__nav">
            <?php
            $prev_link = '';
            $next_link = '';
            if ($current_page >= 1) {
                $prev_link = '<a class="link" href="saved-gallery-views.php?page='.($current_page-1).'">Previous</a>';
            }
            if ($current_page < $pages_count - 1) {
                $next_link = '<a class="link" href="saved-gallery-views.php?page='.($current_page+1).'">Next</a>';
            }
            ?>
            <p>
                <span class="nav_arrow" id="go_back"><?php echo $prev_link ?></span>
                <?php echo ($current_page).' / '.($pages_count); ?>
                <span class="nav_arrow" id="go_next"><?php echo $next_link; ?></span>
            </p>
        </div>
    </div>