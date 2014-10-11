<?php 
$mysqli = new mysqli("linuxproj.ecs.soton.ac.uk","mak1g11","incorrect","db_mak1g11"); 
 
if (mysqli_connect_errno()) { 
    printf("Connect failed: %s\n", mysqli_connect_error()); 
    exit(); 
} 
$mysqli->set_charset("utf-8"); 
?> 