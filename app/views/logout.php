<?php 
@session_start();
@session_destroy();

echo "<script>window.location='?route=site/login'</script>";
 ?>
