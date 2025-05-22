<?php

$code = file_get_contents('shell.php');
$base64 = base64_encode($code);
echo $base64;

?>