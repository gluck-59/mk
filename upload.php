<?php

$uploaddir = 'upload_users/';
$uploadfile = $uploaddir.basename($_FILES['myfile']['name']);
move_uploaded_file($_FILES['myfile']['tmp_name'], $uploadfile)

?>