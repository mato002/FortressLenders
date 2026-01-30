<?php
$password = '@Kenya1234';
$hash = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12));
echo "Hashed Password: " . $hash . "\n";
?>
