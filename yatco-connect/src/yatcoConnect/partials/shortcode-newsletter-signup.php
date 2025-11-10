<div class="yatco-theme-shortcode-newsletter yatco-shortcode-search-form">

	<form id="yatco-theme-shortcode-newsletter-form" class="yatco-client-newsletter-signup">

		<?php 
	    	$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	    ?>

	    <input type="hidden" name="ReferrerUrl" value="<?= $current_url ?>">
	    <input type="text" name="contact_me_by_fax_only" style="display:none !important" tabindex="-1" autocomplete="off">

		<div class='hide-after-submit'>

			<div class="yt-row">

				<div class="yt-col-12 yt-col-lg-6">
					<div class="col-input">
						<label>First Name</label>
						<input type="text" name="FirstName" placeholder="" required="" class="yt-input">
					</div>
				</div>

				<div class="yt-col-12 yt-col-lg-6 ">
					<div class="col-input">
						<label>Last Name</label>
						<input type="text" name="LastName" placeholder="" required="" class="yt-input">
					</div>
				</div>

				<div class="yt-col-12 yt-col-lg-6">	
					<div class="col-input">
						<label>Email</label>
						<input type="email" name="Email" placeholder="" required="" class="yt-input">
					</div>
				</div>

				<div class="yt-col-12 yt-col-lg-6">
					<div class="col-input">
						<label>Country</label>
						<input type="text" name="Country" placeholder="" required="" class="yt-input">
					</div>
				</div>

				<div class="yt-col-12">
					<button type="submit" class="yt-btn yt-btn-block">
						SIGN UP
					</button>
				</div>
			</div>
		</div>

		<div class="form-success-message yt-text-center" style="display: none; font-size: 75%;">
		    <div class="yt-alert yt-alert-success yt-text-center" role="alert" style="display: inline-block;">
		    	Thank you for signing up. Your newsletter will be delivered shortly to the email address provided.
		    </div>
		</div>
	</form>
</div>
