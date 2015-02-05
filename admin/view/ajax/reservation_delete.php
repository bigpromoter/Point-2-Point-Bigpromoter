<?php
include_once ("../../../../../../wp-blog-header.php"); //Include WP Header
include_once ("../../include.php"); //Include classes to Admin 

if ($model->removeReservation($_POST['id'])) {
        echo 'Reservation was deleted!';
} else {
        echo 'Reservation was NOT deleted!';
}
?>