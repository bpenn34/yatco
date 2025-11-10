<?php

$yt_grid_template = get_option('yt_grid_template');

$gridCount = $template_manager->get_gridCount_by_id($yt_grid_template);

$colClass = 4;

if($gridCount){

    $colClass = 12/$gridCount;

}

?>

<div class="col-12 col-md-6 col-lg-<?php echo $colClass; ?> col-yacht">

    <?php
    
    $template_manager->display_template_content($yt_grid_template, $vessel);

    ?>

</div>