<?php
	include_once (dirname(__FILE__)."/../include.php");
?>

<div class="wrap">
<h2>Point to Point - Big Promoter</h2>
<?php
    $active = (isset($_GET['tab']))?$_GET['tab']:'basic';
    echo $control->createTabs($active);
?>
<div style="border: solid 1px #ccc;border-top: transparent!important;padding: 10px; padding-bottom: 0px!important;">
<?php
    switch(strtolower($active)) {
        case 'basic':include 'setting_basic.php';break;
        case 'calendar':include 'setting_calendar.php';break;
        case 'color':include 'setting_color.php';break;
        case 'mail':include 'setting_mail.php';break;
        case 'map':include 'setting_map.php';break;
        case 'payment':include 'setting_payment.php';break;
        case 'bigpromoter':include 'setting_bigpromoter.php';break;
        default:include 'setting_basic.php';break;
    }
?>
</div>