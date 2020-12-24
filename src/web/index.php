<?php session_start(); ?>
<?php require './views-html/header.html' ?>
    <div class="page-wrapper">
    <?php require './views-php/navigation.php' ?>
    <div class="hero-container">
        <div class="hero">
            <div class="hero__content">
                <h3 class="hero__heading">Spójrz na Świat na <span class="color-accent">Nowo.</span></h3>
                <h5 class="hero__subheading">Zapoznaj się z kolekcją zdjęć pochodzącą od różnych użytkowników z całego świata.</h5>
                <a class="hero__cta" href="#gallery">Zobacz zdjęcia</a>
            </div>
        </div>
    </div>
    <div class="gallery-wrapper">
        <main id="gallery" class="gallery">
            <?php require 'index-gallery.php'?>
            <div class="gallery__form">
                <h3 class="form__heading">Prześlij swoje zdjęcie</h3>
                <p>Wymagany format zdjęcia to JPG/PNG, a max. rozmiar to 1Mb</p>
                <form id="form" method="post" enctype="multipart/form-data" action="post.php">
                    <?php include_once './form-errors.php'; ?>
                    <div class="inputs">
                        <label id="label_author">
                            <input type="text" name="author" placeholder="Autor" required="required"/>
                        </label>
                        <label id="label_title">
                            <input type="text" name="title" placeholder="Tytuł" required="required">
                        </label>
                        <label id="label_watermark">
                            <input type="text" name="watermark" placeholder="Wpisz swój znak wodny" required="required"/>
                        </label>
                        <label id="label_file">
                            <input type="hidden" name="MAX_FILE_SIZE" value="1000000"/>
                            <input type="file" name="image" required="required"/>
                    </div>
                    <input type="submit" value="send">
                </form>
            </div>
        </main>
    </div>
<?php require './views-html/footer.html' ?>