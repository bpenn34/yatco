<!-- Modal: Share -->
<div id="single-yacht-modal-share" class="yt-modal single-yacht-modal">
	<h4 style="font-size: 30px !important; font-family: initial;">Share With Your Friends</h4>
	
	<ul class="share-buttons" data-source="simplesharingbuttons.com">
		<li><a data-tracking-label="Facebook" href="https://www.facebook.com/sharer/sharer.php?u=&quote=" title="Share on Facebook" target="_blank" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(document.URL) + '&quote=' + encodeURIComponent(document.URL)); return false;"><img alt="Share on Facebook" src="<?= YATCO_PLUGIN_ASSETS.'/img/share-icons/' ?>Facebook.png" /></a></li>

		<li><a data-tracking-label="Twitter" href="https://twitter.com/intent/tweet?source=&text=:%20&via=searchyatco" target="_blank" title="Tweet" onclick="window.open('https://twitter.com/intent/tweet?text=' + encodeURIComponent(document.title) + ':%20'  + encodeURIComponent(document.URL)); return false;"><img alt="Tweet" src="<?= YATCO_PLUGIN_ASSETS.'/img/share-icons/' ?>Twitter.png" /></a></li>

		<li><a data-tracking-label="LinkedIn" href="http://www.linkedin.com/shareArticle?mini=true&url=&title=&summary=&source=" target="_blank" title="Share on LinkedIn" onclick="window.open('http://www.linkedin.com/shareArticle?mini=true&url=' + encodeURIComponent(document.URL) + '&title=' +  encodeURIComponent(document.title)); return false;"><img alt="Share on LinkedIn" src="<?= YATCO_PLUGIN_ASSETS.'/img/share-icons/' ?>LinkedIn.png" /></a></li>

		<li><a data-tracking-label="Mail" href="mailto:?subject=&body=:%20" target="_blank" title="Send email" onclick="window.open('mailto:?subject=' + encodeURIComponent(document.title) + '&body=' +  encodeURIComponent(document.URL)); return false;"><img alt="Send email" src="<?= YATCO_PLUGIN_ASSETS.'/img/share-icons/' ?>Email.png" /></a></li>
	</ul>

	<style type="text/css">
		ul.share-buttons{
			list-style: none;
			padding: 0;
		}

		ul.share-buttons li{
			display: inline;
		}

		ul.share-buttons .sr-only{
			position: absolute;
			clip: rect(1px 1px 1px 1px);
			clip: rect(1px, 1px, 1px, 1px);
			padding: 0;
			border: 0;
			height: 1px;
			width: 1px;
			overflow: hidden;
		}
	</style>
</div>

<?php foreach ($data->BrokerList as $b_index => $broker) : ?>
	<!-- Modal: Call Broker -->
	<div id="single-charter-modal-call-<?= $b_index ?>" class="yt-modal single-yacht-modal single-yacht-modal-call-broker" style="max-width: 300px;">		
		<div class="headings">
			<h4>Call Charter Agent</h4>

			<div class="yt-input-block">
				<h5 class="broker-name"><?= $broker->BrokerName ?></h5>
				<h5 class="brokerage"><?= $data->Company->CompanyName ?></h5>
			</div>
		</div>

		<?php if (isset($broker->BrokerPhone) && $broker->BrokerPhone != '') : ?>
			<div class="yt-input-block">
				<a href="tel: <?= $broker->BrokerPhone ?>" onclick="GA_TrackTele('<?= $data->VesselID ?>', 'Office');">
					<button type="button" class="yt-btn yt-btn-block">
						Office: <?= $broker->BrokerPhone ?>
					</button>
				</a>
			</div>
		<?php endif; ?>

		<?php if (isset($broker->BrokerMobile) && $broker->BrokerMobile != '') : ?>
			<div class="yt-input-block">
				<a href="tel: <?= $broker->BrokerMobile ?>" onclick="GA_TrackTele('<?= $data->VesselID ?>', 'Mobile');">
					<button type="button" class="yt-btn yt-btn-block">
						Mobile: <?= $broker->BrokerMobile ?>
					</button>
				</a>
			</div>
		<?php endif; ?>

		<?php if ( (isset($broker->BrokerPhone) && $broker->BrokerPhone != '') && (isset($broker->BrokerMobile) && $broker->BrokerMobile != '')) : ?>

		<?php else : ?>

			<?php if (isset($data->Company->MainPhone) && $data->Company->MainPhone != '') : ?>
				<div class="yt-input-block">
					<a href="tel: <?= $broker->Phone ?>" onclick="GA_TrackTele('<?= $data->BasicInfo->VesselID ?>', 'Office');">
						<button type="button" class="yt-btn yt-btn-block">
							Main: <?= $data->Company->MainPhone ?>
						</button>
					</a>
				</div>
			<?php endif; ?>
		<?php endif; ?>

		<div class="yt-input-block">
			<button type="button" class="yt-btn  yt-btn-block yt-btn-alt-blue" data-modal="#single-yacht-modal-email-broker-<?= $b_index ?>" style="width: 200px; margin: auto; display: block;">
				EMAIL INSTEAD
			</button>
		</div>
	</div>

	<!-- Modal: Email Broker -->
	<div id="single-charter-modal-email-<?= $b_index ?>" class="yt-modal single-yacht-modal single-yacht-modal-email-broker">
		<div class="headings">
			<h4>Email Charter Agent</h4>
			
			<div class="yt-input-block">
				<h5 class="broker-name"><?= $broker->BrokerName ?></h5>
				<h5 class="brokerage"><?= $data->Company->CompanyName ?></h5>
			</div>
		</div>

		<form action="" method="post" class="charter-lead-form">
			<input type="hidden" name="VesselID" value="<?= $data->VesselID ?>">
	        <input type="hidden" name="CompanyID" value="<?= $data->CompanyID ?>">
	        <input type="hidden" name="ReferrerUrl" value="<?= $data->a_url ?>">
	        <input type="hidden" name="FormTypeID" value="3">

	        <input type="text" name="contact_me_by_fax_only" style="display:none !important" tabindex="-1" autocomplete="off">

	        <div class='hide-after-submit'>
				<div class="yt-row row-smaller">
	                <div class="yt-col-md-6 yt-col-12">
	                	<div class="yt-input-block">
	                    	<input  type="text" name="FirstName" placeholder="First Name*" required="required" class="yt-input">
	               		</div>
	                </div>

	                <div class="yt-col-md-6 yt-col-12">
	                	<div class="yt-input-block">
	                    	<input type="text" name="LastName" placeholder="Last Name*" required="required" class="yt-input">
	               		</div>
	                </div>
	            </div>

	           	<div class="yt-row row-smaller">
	                <div class="yt-col-md-6 yt-col-12">
	                	<div class="yt-input-block">
		                    <input class="yt-input" type="email" name="Email" placeholder="Email Address*" required="required">
		                </div>
		            </div>

	                <div class="yt-col-md-6 yt-col-12">
	                	<div class="yt-input-block">
		                    <input class="yt-input" type="tel" name="Phone" placeholder="Phone Number">
		                </div>
		            </div>
		        </div>

		        <div class="yt-row row-smaller">
	                <div class="yt-col-12">
	                	<div class="yt-input-block">
		                    <textarea rows="5" class="yt-input" placeholder="Message*" name="Message" required="required" style="resize: none;"></textarea>
		                </div>
		            </div>
		        </div>

		        <div class="yt-row row-smaller">
	                <div class="yt-col-12">
	                	<div class="yt-input-block">
							<input type="date" name="IdealCheckIn" placeholder="Ideal Check In Date" class="yt-input">
						</div>
	                </div>
	            </div>

		        <div class="yt-row row-smaller">
					<div class="yt-col-6">
						<div class="yt-input-block">
							<input type="text" name="Duration" placeholder="Duration Of Stay" class="yt-input">
						</div>
					</div>
					<div class="yt-col-6">
						<div class="yt-input-block">
							<select name="RateFreq" class="yt-input">
								<option value="Days">Days</option>
								<option value="Weeks">Weeks</option>
							</select>
						
						</div>
					</div>
				</div>

				<div class="yt-row row-smaller">
	                <div class="yt-col-12">
	                	<div class="yt-input-block">
							<label>
								<input type="radio" name="owner_or_industry" value="owner">
								I am interested in chartering the vessel.
							</label>
							<label>
								<input type="radio" name="owner_or_industry" value="industry">
								I work in the yachting industry.
							</label>
						</div>
	                </div>
				</div>

		       	<div class="yt-row row-smaller">
	                <div class="yt-col-12">
	                	<div class="yt-input-block">
	                		<div class="yatco-g-recaptcha" data-sitekey="<?= $options->getOption('google_recap_api_key') ?>"></div>
		            	</div>

	        			<div class="yt-input-block">
	                    	<input type="submit" class="yt-btn yt-btn-block" value="CONTACT LISTING BROKER" style="border-radius: 0px !important;" />
		            	</div>
		            </div>
	            </div> 
			</div>

			 <div class="form-success-message" style="display: none; font-size: 75%;">
			    <div class="yt-alert yt-alert-success yt-text-center" role="alert">
			    	Your form has been submitted. The listing broker will contact you shortly.
			    </div>
			</div>
		</form>
	</div>
<?php endforeach; ?>


<div id="single-charter-modal-email-broker-about-full-spec" class="yt-modal single-yacht-modal single-yacht-modal-email-broker">
	<div class="headings">
	
		<div class="yt-input-block">
			<h2>Full Specifications</h2>
			<p style="text-transform: uppercase; font-weight: bold; font-size: 12px; line-height: 1.2;">receive full specifications on this listing <br> from the certified yacht broker.</p>
		</div>
	</div>

	<form action="" method="post" id="modal-form-charter-full-spec-lead" class="charter-lead-form">
		<input type="hidden" name="VesselID" value="<?= $data->VesselID ?>">
        <input type="hidden" name="CompanyID" value="<?= $data->CompanyID ?>">
        <input type="hidden" name="ReferrerUrl" value="<?= $data->a_url ?>">
        <input type="hidden" name="FormTypeID" value="3">

        <input type="hidden" name="is_about_full_spec" value="1">
        <input type="text" name="contact_me_by_fax_only" style="display:none !important" tabindex="-1" autocomplete="off">

        <div class='hide-after-submit'>
        	<div class="yt-row row-smaller">
                <div class="yt-col-md-6 yt-col-12">
                	<div class="yt-input-block">
                    	<input  type="text" name="FirstName" placeholder="First Name*" required="required" class="yt-input">
               		</div>
                </div>

                <div class="yt-col-md-6 yt-col-12">
                	<div class="yt-input-block">
                    	<input type="text" name="LastName" placeholder="Last Name*" required="required" class="yt-input">
               		</div>
                </div>
            </div>

           	<div class="yt-row row-smaller">
                <div class="yt-col-md-6 yt-col-12">
                	<div class="yt-input-block">
	                    <input class="yt-input" type="email" name="Email" placeholder="Email Address*" required="required">
	                </div>
	            </div>

                <div class="yt-col-md-6 yt-col-12">
                	<div class="yt-input-block">
	                    <input class="yt-input" type="tel" name="Phone" placeholder="Phone Number">
	                </div>
	            </div>
	        </div>

	        <div class="yt-row row-smaller">
                <div class="yt-col-12">
                	<div class="yt-input-block">
	                    <textarea rows="5" class="yt-input" placeholder="Message*" name="Message" required="required" style="resize: none;"></textarea>
	                </div>
	            </div>
	        </div>

	        <div class="yt-row row-smaller">
                <div class="yt-col-12">
                	<div class="yt-input-block">
						<input type="date" name="IdealCheckIn" placeholder="Ideal Check In Date" class="yt-input">
					</div>
                </div>
            </div>

	        <div class="yt-row row-smaller">
				<div class="yt-col-6">
					<div class="yt-input-block">
						<input type="text" name="Duration" placeholder="Duration Of Stay" class="yt-input">
					</div>
				</div>
				<div class="yt-col-6">
					<div class="yt-input-block">
						<select name="RateFreq" class="yt-input">
							<option value="Days">Days</option>
							<option value="Weeks">Weeks</option>
						</select>
					
					</div>
				</div>
			</div>

			<div class="yt-row row-smaller">
	            <div class="yt-col-12">
	            	<div class="yt-input-block">
						<label>
							<input type="radio" name="owner_or_industry" value="owner">
							I am interested in chartering the vessel.
						</label>
						<label>
							<input type="radio" name="owner_or_industry" value="industry">
							I work in the yachting industry.
						</label>
					</div>
	            </div>
			</div>

	       	<div class="yt-row row-smaller">
                <div class="yt-col-12">
                	<div class="yt-input-block">
                		<div class="yatco-g-recaptcha" data-sitekey="<?= $options->getOption('google_recap_api_key') ?>"></div>
	            	</div>

        			<div class="yt-input-block">
                    	<input type="submit" class="yt-btn yt-btn-block" value="SEND FULL SPECS">  
	            	</div>
	            </div>
            </div> 

			<!-- <button type="submit" class="yt-btn">Contact Listing Broker</button> -->  
			
		</div>

		 <div class="form-success-message" style="display: none; font-size: 75%;">
		    <div class="yt-alert yt-alert-success yt-text-center" role="alert">
		    	<?php 
		    		$success_message = $options->getOption('lead_success_message');

		    		if ($success_message == '') {
		    			if ($options->is_brokerage_site) :
		    				$success_message = 'Your form has been submitted. <br> We will contact you shortly.';
	    				else :
	    					$success_message = 'Your form has been submitted. <br> The listing broker will contact you shortly.';
	    				endif;
	    			}

		    		echo $success_message;
		    	?>		    
		    </div>
		</div>
	</form>

</div>