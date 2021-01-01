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