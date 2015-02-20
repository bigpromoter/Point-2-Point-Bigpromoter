<?php
    //Include Core files from WP
    include_once ("../../../../../../wp-blog-header.php"); //Include WP Header
    include_once ('../../include.php');
    header("HTTP/1.1 200 OK");
?>
<div id="testApi"></div>
<script>
    page = "http://www.bigpromoter.com/API/bigpromoter.php";
    jQuery.ajax({
        type: "POST",
        url: page,
        data: {
            check: "test",
            api: "<?php echo get_option('p2p_bigpromoter_api'); ?>",
            site: "<?php echo get_option('p2p_bigpromoter_site'); ?>"
        }
    }).done(function( msg ) {
        jQuery("#testApi").html(msg);
    });	
</script>

