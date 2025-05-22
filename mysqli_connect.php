<?php
$dbcon = @mysqli_connect('localhost', 'admin', '1234', 'imfinalproject')
OR die('Could not connect to MYSQL. Error in '.mysqli_connect_error());
mysqli_set_charset($dbcon, 'utf8');