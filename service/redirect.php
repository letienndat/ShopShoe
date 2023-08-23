<?php
session_start();
if ($username_local !== null) {
    header("Location: " . "/assignment/src/home.php");
    exit;
}
