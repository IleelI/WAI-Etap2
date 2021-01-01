<?php
    require_once './views-html/header.html';
    require_once './views-php/navigation.php';
?>
    <div class="page-wrapper">
        <div class="hero-container">
            <div class="hero">
                <div class="hero__content">
                    <h3 class="hero__heading"><span class="color-accent">Zarejestruj się</span></h3>
                    <form action="../register.php" method="post">
                        <?php
                            if (isset($_GET['registration']) && !empty($_GET['registration'])) {
                                if ($_GET['registration'] === 'success') {
                                    echo '<h4 class="success">Registration Successful!</h4>';
                                }
                                if ($_GET['registration'] === 'failed') {
                                    echo '<h4 class="error">Registration Failed!</h4>';
                                    foreach ($_SESSION['register_errors'] as $error) {
                                        if (empty($error)) { continue; }
                                        echo '<p class="error">'.$error.'</p>';
                                    }
                                }
                            }
                        ?>
                        <label class="success" id="register_email">Email
                            <input type="email" name="email" required="required" placeholder="E-mail">
                        </label><br>
                        <label id="register_login">Login
                            <input type="text" name="login" required="required" placeholder="Login">
                        </label><br>
                        <label id="register_password">Password
                            <input type="password" name="password" required="required" placeholder="Password">
                        </label><br>
                        <label id="repeat_password">Repeat Password
                            <input type="password" name="re_password" required="required" placeholder="Repeat Password">
                        </label><br>
                        <button type="submit">Rejestracja</button>
                        <button type="reset">Resetuj Formularz</button>
                    </form>
                </div>
            </div>
        </div>
<?php require_once './views-html/footer.html'; ?>

























?>