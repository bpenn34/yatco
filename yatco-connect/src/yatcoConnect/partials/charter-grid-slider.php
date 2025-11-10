<?php
$yt_grid_template = get_option('yt_grid_template_forCharter');
if(isset($template_id) && !empty($template_id)){
    $yt_grid_template = $template_id;
}
$template_manager = new yatcoConnect_TemplateManager();
$template_manager->display_template_styles($yt_grid_template);

if(isset($yt_grid_template) && !empty($yt_grid_template)){
?>
<div class="swiper myAutoGridSlider" data-card-count="<?php echo $card_count; ?>">
    <div class="swiper-wrapper" data-total-result="<?php echo count($results->Results); ?>">
        <?php foreach ($results->Results as $vessel) : ?>
            <div class="swiper-slide col-yacht">
            <?php
    
                $template_manager->display_template_content($yt_grid_template, $vessel, 'listings', 'charter');

            ?>    
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php include 'charter/shortcode-charter-results-modal.php'; ?>
<?php } ?>