<div id="shortcode-yatco-user-subscriptions">
	<?php 
		//var_dump($user_subscriptions);
	?>

	<?php if ($user_email == null || empty($user_email)) : ?>
		<p>
			<b>Please enter your email address</b>
		</p>

		<form method="GET" action="">
			<div class="yt-row">
				<div class="yt-col-12 yt-col-md-8">
					<input type="email" name="email" class="yt-input">
				</div>

				<div class="yt-col-12 yt-col-md-4">
					<input type="submit" value="Find" class="yt-btn">
				</div>
			</div>

		</form>

	<?php else : ?>

		<h3>Your Subscriptions</h3>

		<div class="success_message yt-alert yt-alert-success yt-text-center" style="display: none;"></div>

		<p>
			<b>
				Email: <?= $user_email ?>
			</b>
		</p>

		<form id="form_user_subscriptions">
			<input type="hidden" name="user_email_address" value="<?= $user_email ?>">

			<label>
				<input type="checkbox" name="unsub_from_all">

				Unsubscribe From All
			</label>

			<?php foreach ($user_subscriptions as $subscript) : ?>
			
				<label>
					<input type="checkbox" 
						name="type_<?= $subscript->EmailTypeID ?>" 
						value="1" 
						<?php checked(true, $subscript->Enabled); ?>>

					<?= $subscript->Name ?>
				</label>

			<?php endforeach; ?>

			<div class="yt-input-block">
        		<div class="yatco-g-recaptcha" data-sitekey="<?= $options->getOption('google_recap_api_key') ?>"></div>
        	</div>

			<input type="submit" value="Update Subscriptions" class="yt-btn">

		</form>

		<script type="text/javascript">
			
			jQuery('#form_user_subscriptions').on('submit', function(e) {

				e.preventDefault();

				var ele_form = jQuery(this);
				var formData = new FormData(ele_form[0]);

				var data = Array.from(formData.entries()).reduce((memo, pair) => ({
				...memo,
				[pair[0]]: pair[1],
				}), {});

				console.log(data);

				jQuery.ajax({
					method: 'POST',
					data: {'label': 'UpdateUserSubscriptions', 'form_data': data},
					url: _yatco_wp_url._yatco_wp_rest_url+'yatco/send-lead',
					success: function(r) {
						console.log(r);

						if (typeof r.error != 'undefined') {

					  		alert(r.error);
					  	
					  	}
					  	else if (r.ResponseCode == 0) {

					  		grecaptcha.reset();

							jQuery('#shortcode-yatco-user-subscriptions .success_message').show().html(r.Response);

							setTimeout(function() {
								jQuery('#shortcode-yatco-user-subscriptions .success_message').hide();
							}, 2000);

						}
					}
				});

			});

			jQuery('#form_user_subscriptions input[name=unsub_from_all]').change(function(e) {

				if (jQuery(this).prop("checked")) {
					jQuery('#form_user_subscriptions input[name^="type_"]').prop('checked', false);
				}
				else {

				}

			});


		</script>

	<?php endif; ?>

</div>