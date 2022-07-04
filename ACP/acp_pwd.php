<?php

$options = [
    'cost' => 11,
    'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
];

echo $_GET['pwd'].'<br/>';
$hash =  password_hash($_GET['pwd'], PASSWORD_BCRYPT, $options);
echo $hash;
if(password_verify($_GET['pwd'],$hash)) {
	echo "<br/>OK";
}

?>