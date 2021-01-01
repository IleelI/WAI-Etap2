<?php require './views-html/header.html'; ?>
    <div class="page-wrapper">
<?php require './views-php/navigation.php'; ?>
    <div class="hero-container">
        <div class="hero">
            <div class="hero__content">
                <h3 class="hero__heading"><span class="color-accent">Twoje zapisane zdjęcia</span></h3>
                <h5 class="hero__subheading">Zapoznaj się z kolekcją zdjęć zapisanych przez Ciebie.</h5>
                <a class="hero__cta" href="#gallery">Zobacz zdjęcia</a>
            </div>
        </div>
    </div>
    <div class="gallery-wrapper">
        <main id="gallery" class="gallery">
            <form action="./delete_selected_images.php" method="post">
                <div class="gallery__images">
                    <?php require './saved-gallery.php'?>
            </form>
        </main>
    </div>
<?php require './views-html/footer.html'; ?>