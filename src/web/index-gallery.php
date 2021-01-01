<form action="save_selected_images.php" method="post">
    <div class="gallery__images">
        <?php include './db.php';
        $images_db = get_db()->images;
        $images_db_arr = $images_db->find();
        $images_db_arr = $images_db_arr->toArray();
        $watermarked_path = './images/watermarked/';
        $miniatures_path = './images/miniatures/';
        $images = glob('./images/*.*');

        $max_number_of_images = 10;
        $total_images = count($images);
        $current_page = 0;
        $pages_count = floor($total_images / $max_number_of_images);

        $show = array_slice($images, $max_number_of_images * $current_page - 1, $max_number_of_images);
        if (!isset($_GET['page'])) {
            $current_page = 0;
        }
        else {
            $current_page = $_GET['page'];
        }
        $first_index_of_page = $current_page * $max_number_of_images;
        $current_page_total_images = (($current_page + 1) * $max_number_of_images);

        for ($i = (0 + $first_index_of_page); $i < $current_page_total_images; $i++) : ?>
            <?php if (empty($images[$i]) || empty($images_db_arr)) { break; } ?>
            <div class="image">
                <div class="image-wrapper">
                    <a target="_blank" <?php echo "href='".$watermarked_path.$images_db_arr[$i]['filename_watermarked']."'" ?>>
                        <img <?php echo "src='".$miniatures_path.$images_db_arr[$i]['filename_miniaturized']."' ". "alt='".$images_db_arr[$i]['title']."'"?>/>
                    </a>
                </div>
                <div class="image__about">
                    <p class="title">Tytuł: <?php echo $images_db_arr[$i]['title'] ?> </p>
                    <p class="author">Autor: <?php echo $images_db_arr[$i]['author'] ?></p>
                    <label>Wybierz
                        <?php $string_mdid = (string)$images_db_arr[$i]['_id'] ?>
                        <input type="checkbox" name="photos[]" autocomplete="off" value="<?php echo $string_mdid ?>">
                    </label>
                </div>
            </div>
        <?php endfor;?>
    </div>
    <div class="gallery__navigation">
        <button class="btn__save-images">Zapisz Wybrane Zdjęcia</button>
        <?php
        if (isset($_GET['saved'])) {
            if ($_GET['saved'] === 'false' || empty($_SESSION['isLoggedIn'])) {
                echo '<p class"error">'.$_SESSION['save_images_error'].'</p>';
            }
            else {
                echo '<p class="success">Images saved correctly!</p>';
            }
        } ?>
        <div class="gallery__nav">
            <?php
            $prev_link = '';
            $next_link = '';
            if ($current_page >= 1) {
                $prev_link = '<a class="link" href="/?page='.($current_page-1).'">Previous </a>';
            }
            if ($current_page < $pages_count) {
                $next_link = '<a class="link" href="/?page='.($current_page+1).'"> Next</a>';
            }
            ?>
            <p>
                <span class="nav_arrow" id="go_back"><?php echo $prev_link ?></span>
                <?php echo $current_page.' / '.$pages_count; ?>
                <span class="nav_arrow" id="go_next"><?php echo $next_link ?></span>
            </p>
        </div>
    </div>
</form>