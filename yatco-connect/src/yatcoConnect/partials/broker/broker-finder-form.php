<div class="yatco-shortcode-broker-quick-search yatco-shortcode-search-form">
	<?php
		$input_col_class = 'yt-col-lg-3 yt-col-md-6 yt-col-12';
	?>
		
	<form action="" method="GET" class="advanced-search" id="broker-quick-search" data-input-values="<?= esc_attr(json_encode($_params)) ?>">

		<input type="hidden" name="scroll-to-form" value="true">

		<div class="yt-row">
			<div class="<?= $input_col_class ?>">
				<div class="col-input">
					<label>Last Name</label>
					<input type="text" name="LastName" placeholder="">
				</div>
			</div>
			
			<div class="<?= $input_col_class ?>">
				<div class="col-input">
					<label>Country</label>
					
					<select name="LocationCountryID" 
						class="yt-input" 
						data-yatco-fill-options="GeoListAllCountries" 
						data-yatco-empty-after-fill="false" 
						data-value="<?= apply_filters('yatco_data_value', 'LocationCountryID', $_params) ?>">
						<option value="">All Country</option>
					</select>				
				</div>
			</div>

			<div class="<?= $input_col_class ?>">
				<div class="col-input">
					<label>State</label>

					<select name="LocationStateID"  
						class="yt-input fill-state" 
						data-value="<?= apply_filters('yatco_data_value', 'LocationStateID', $_params) ?>">
						<option value="">Pick Country First...</option>
					</select>
				</div>
			</div>

			<div class="<?= $input_col_class ?>">
				<div class="col-input">
					<label>City</label>

					<select class="yt-input fill-city" name="LocationCityID" data-value="<?= apply_filters('yatco_data_value', 'LocationCityID', $_params) ?>">
				    	<option value="">Pick State First...</option>
				    </select>
				</div>
			</div>

		</div>

		<div style="text-align: center; margin-top: 15px;">
			<input type="submit" value="SEARCH BROKERS" class="yt-btn">
		</div>
	</form>
</div>