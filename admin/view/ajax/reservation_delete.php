<?php
    //Include Core files from WP
    include_once ("../../../../../../wp-blog-header.php"); //Include WP Header
    include_once ('../../include.php');
    header("HTTP/1.1 200 OK");

if ($model->removeReservation($_POST['id'])) {
        echo 'Reservation was deleted!';
} else {
        echo 'Reservation was NOT deleted!';
}
?>