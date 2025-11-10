<div class="wrap">
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<h2>Documentation PDF</h2>
	<p>Also A Link To Our PDF Documentation, <a href="<?= YATCO_PLUGIN_ASSETS.'/docs/YATCO_DOC.pdf' ?>" target="_blank">CLICK HERE</a></p>

	<form action="options.php" method="post">
		<?php
			settings_fields( self::SLUG );
			do_settings_sections( self::SLUG );
			submit_button();
		?>
	</form>
</div>
