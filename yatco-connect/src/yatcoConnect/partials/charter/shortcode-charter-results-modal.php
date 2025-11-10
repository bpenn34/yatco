<div id="yatco-charter-lead-modal" class="yt-modal single-yacht-modal single-yacht-modal-email-broker">
	<form action="#" class="charter-lead-form">

	    <input type="hidden" name="VesselID" value="">
	    <input type="hidden" name="CompanyID" value="">
	    <input type="hidden" name="FormTypeID" value="3">

   		<input type="hidden" name="ReferrerUrl" value="<?= get_permalink(get_the_ID()) ?>">

   		<input type="text" name="contact_me_by_fax_only" style="display:none !important" tabindex="-1" autocomplete="off">

	    <div class='hide-after-submit'>
	    	<div class="headings">
		    	<h4>
		    		Contact Brokerage
		    	</h4>

		    	<div class="yt-input-block">
					<h5 class="brokerage"></h5>
				</div>
			</div>

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
					                    	
					    <input type="submit" value="Contact Charter Brokerage" class="yt-btn yt-btn-block">
	            	</div>
	            </div>
            </div>

	    </div>
	    
	    <div class="form-success-message" style="display: none; font-size: 75%;">
		    <div class="yt-alert yt-alert-success yt-text-center" role="alert">
		    	Your form has been submitted. <br> 
		    	The listing broker will contact you shortly.
		    </div>
		</div>
	 </form>


</div>
