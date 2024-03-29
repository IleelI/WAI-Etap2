<nav class="nav">
    <ul class="nav__links">
        <li><a class="link link--horizontal" href="/">Home</a></li>
        <?php if (isset($_SESSION['isLoggedIn']) === false) : ?>
            <li><a class="link link--horizontal" href="">Zapisane Zdjęcia</a>
        <?php else : ?>
            <li><a class="link link--horizontal" href="/saved">Zapisane Zdjęcia</a></li>
        <?php endif; ?>
    </ul>
    <div class="nav__logo">
        <h2>Podróż</h2>
    </div>
    <ul class="nav__user">
        <!--Routing will solve the problem of this links-->
        <li>
            <a class="link link--horizontal" href="/register">Rejestracja</a>
        </li>
        <li>
            <?php if (!isset($_SESSION['isLoggedIn']) && !isset($_SESSION['user_id'])) : ?>
                <a class="link link--horizontal" href="/login">Zaloguj Się</a>
            <?php else : ?>
                <p>Zalogowany jako: <?php echo $_SESSION['username'] ?> </p>
                <a class="link link--horizontal" href="logout">Wyloguj się</a>
            <?php endif; ?>
        </li>
    </ul>
</nav>