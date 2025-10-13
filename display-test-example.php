<?php
// You would make a file like this that would echo the encrypted content dynamically 
include "cy-page-encryptor.php";
$webpage = encryptWebPage("test.php", "test", "php");
if($webpage){
    echo $webpage;
}
?>