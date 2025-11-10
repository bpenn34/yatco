<div class="yt-col-12 col-yacht">
  <?php 
    $yt_list_template = get_option('yt_list_template');
    $template_manager->display_template_content($yt_list_template, $vessel);
  ?>
</div>