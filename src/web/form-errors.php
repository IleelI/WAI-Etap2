<?php
    if (isset($_SESSION['file_is_valid'], $_GET['upload_state'])) {
        if ($_GET['upload_state'] === '0') {
            echo "<h3 class='error'> File Upload Failed!</h3>";
            foreach ($_SESSION['file_errors'] as $error) {
                echo "<p class='error'>" . $error . "</p>";
            }
        }
        else if ($_GET['upload_state'] === '1') {
            echo "<h3 class='success'> File Upload Success!</h3>";
        }
    unset($_SESSION['file_is_valid'], $_SESSION['file_errors']);
} ?>