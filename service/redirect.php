<?php
session_start();
if ($username_local !== null) {
    header("Location: " . "/ShopShoe/src/home.php");
    exit;
}
