<?php
// Flash message handler
function set_flash($type, $message) {
    $_SESSION['flash'][$type] = $message;
}

function display_flash() {
    if (!empty($_SESSION['flash'])) {
        foreach ($_SESSION['flash'] as $type => $msg) {
            echo "<div class='flash {$type}'>{$msg}</div>";
        }
        unset($_SESSION['flash']);
    }
}
?>
