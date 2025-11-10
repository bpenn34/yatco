<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="wrap">
    <h1 style="margin-bottom:30px;">YATCO Font Options</h1>
    <?php
    $fontOptions = new Custom_Font_Manager();
    $fontOptions->fonts_page();
    ?>
</div>