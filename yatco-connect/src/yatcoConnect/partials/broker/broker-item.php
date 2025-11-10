<div class="yt-col-lg-3 yt-col-md-6 yt-col-12">
	<div class="card broker">
		<a href='<?= $broker->a_url; ?>'>
	        <div class="pic pic-std">
	        	<img src="<?= esc_attr($broker->IMG) ?>" alt="<?= $broker->FullName ?>" loading="lazy" />
				<div class="overlay"></div>
	        </div>
	    </a>
			
		<div class="below-picture">
	    	<a href='<?= $broker->a_url; ?>'>
	            <div class="card-title">
	                <?= $broker->FullName ?>
	            </div>
	        </a>

	        <div class="card-title" style="margin-bottom: 30px;">
	            <?php 

					if (isset($broker->Office) && isset($broker->Office->Country)) {
						echo apply_filters('yatco_full_address', $broker->Office, false, true);
					}
					elseif (isset($broker->Company) && isset($broker->Company->Country)) {
						echo apply_filters('yatco_full_address', $broker->Company, false, true);
					}

				?>
			</div>
	        
	        <?php if ($broker->Stats != null) : ?>
	            <div class="card-title card-name greyfont" style="font-size: 90%;">
					<p><b>LISTINGS:</b> <?= $broker->Stats->VesselCount ?></p>

					<p><b>LOA RANGE:</b> <?= apply_filters('yatco_range', $broker->Stats->StartLOAFormatted, $broker->Stats->EndLOAFormatted, false) ?></p>

					<p><b>PRICE RANGE:</b> <?= apply_filters('yatco_range', $broker->Stats->PriceRangeUSD->Start, $broker->Stats->PriceRangeUSD->End, true) ?></p>
				</div>
			<?php endif; ?>
	     			                         										            
	        <div class="btnRow">
	            <a href="<?= $broker->a_url ?>" class="greyfont yt-pull-left">DETAILS</a>

	            <div href="#" style="position: absolute; right: 10px; bottom: 10px; width: auto;" class="yt-btn yt-btn-alt-dark-blue yt-btn-radius " onclick="<?= $contact_onclick ?>"> 
	            	CONTACT
	            </div>
	       		
	       		<div class="yt-clearfix"></div>
	   		</div>
	   	</div>
 	</div>
 </div>