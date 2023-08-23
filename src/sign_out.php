<?php
session_start();
session_destroy();
echo '<script>window.location.href="/assignment/src/home.php"</script>';
?>