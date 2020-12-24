<?php
    session_start();
    require_once '../views-html/header.html';
    require_once './navigation.php';
?>
<div class="page-wrapper">
    <div class="hero-container">
        <div class="hero">
            <div class="hero__content">
                <h3 class="hero__heading"><span class="color-accent">Zaloguj się</span></h3>
                <form action="../login.php" method="post">
                    <?php if (isset($_GET['login']) && !empty($_GET['login'])) {
                        if ($_GET['login'] === 'success') {
                            echo '<h3 class="success">Login Successful!</h3>';
                        }
                        else {
                            echo '<h3 class="error">Login Failed!</h3>';
                            foreach ($_SESSION['login_errors'] as $error) {
                                echo '<p class="error">'.$error.'</p>';
                            }
                        }
                    } ?>

                    <?php if (!isset($_SESSION['user_id'])) : ?>
                        <label id="login_login">Login
                        <input type="text" name="login" placeholder="Login" required="required">
                    </label><br>
                    <label id="login_password">Hasło
                        <input type="password" name="password" placeholder="Password" required="required">
                    </label><br>
                    <button type="submit">Zaloguj się</button>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
    <?php require_once '../views-html/footer.html';?>