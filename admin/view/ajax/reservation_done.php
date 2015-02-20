<?php
    //Include Core files from WP
    include_once ("../../../../../../wp-blog-header.php"); //Include WP Header
    include_once ('../../include.php');
    header("HTTP/1.1 200 OK");

$update = $model->doneReservation($_POST['id'], $_POST['trip'], $_POST['value']);

if ($update) {
        echo 'Trip status changed';
}
?>