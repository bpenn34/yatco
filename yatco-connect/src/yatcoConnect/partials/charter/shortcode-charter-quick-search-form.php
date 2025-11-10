<div class="yatco-shortcode-search-form yatco-shortcode-charter-search-form">
	<form action="<?= $action_url ?>" method="GET" id="desktop-quick-search" 
		data-input-values="<?= esc_attr(json_encode($_params)) ?>"
		data-shortcode-attributes="<?= esc_attr(json_encode($atts)); ?>">
		<div class="yt-row">
			<div class="yt-col-12">
				<div class="yt-col-12 yt-col-md-6 yt-col-lg-3">
					<div class="col-input">
						<label value="">Destination(s)</label>

						<!-- <input type="text" name="Destinations" class="yt-input"> -->

					    <!-- <select class="yt-input" name="Location" data-yatco-fill-options="DDCharterDestinations" data-yatco-empty-after-fill="false" data-value="<?= apply_filters('yatco_data_value', 'Location', $_params) ?>">
					    	<option value="">Any</option>
					    </select> -->

					    <input type="text" name="Destinations" class="yt-input" list="destinations_list"> 

					</div>
		        </div>

		        <div class="yt-col-12 yt-col-md-6 yt-col-lg-3">
					<div class="col-input">
					    <label for="region">Season</label>
				
					    <select class="yt-input" name="SeasonID" data-yatco-fill-options="CharterSeasons" data-yatco-empty-after-fill="false" data-value="<?= apply_filters('yatco_data_value', 'SeasonID', $_params) ?>">
					    	<option value="">Any</option>
					    </select>
					</div>
		        </div>

		        <div class="yt-col-12 yt-col-md-6 yt-col-lg-3">      
		        	<div class="more-space-for-option-above">  
		        		<div class="yt-above-input">
				            <select name="CurrencyID" class="yt-input" data-yatco-fill-options="Currency" data-yatco-empty-after-fill="true" data-value="<?= apply_filters('yatco_data_value', 'CurrencyID', $_params, '2563') ?>">
				            	<option selected="selected" value="2563">USD</option>
							</select>

							<select name="RateFrequency" class="yt-input" data-yatco-fill-options="CharterFreqTypes" data-yatco-empty-after-fill="true"  data-value="<?= apply_filters('yatco_data_value', 'RateFrequency', $_params, '3416') ?>">
				            	
							</select>
						</div>
			            
			            <div class="col-input">
			                <label>Price</label>
			           
			                <input type="number" class="yt-input" name="RateRange_from" placeholder="Min">
			                   
			                <input type="number" class="yt-input" name="RateRange_to" placeholder="Max">
			            </div>
			        </div>
		        </div>
				
				<div class="yt-col-12 yt-col-md-6 yt-col-lg-3">
		            <div class="col-input">
		                <label>Guests</label>
		           
		                <input type="number" class="yt-input" name="Guests_from" placeholder="Min" >
		                
		                <input type="number" class="yt-input" name="Guests_to" placeholder="Max">
		            </div>
		        </div>
			</div>

			<div class="yt-col-12 yt-text-right">
				<div class="yt-col-12">
					<a href="<?= $advanced_search_url ?>" style="font-weight: bold; font-size: 16px; cursor: pointer; text-align: right;  color: #406695;">
						Advanced Search +
					</a>
				</div>
			</div>

			<div class="yt-col-12 yt-text-center">
				<input type="submit" value="SEARCH CHARTERS" class="yt-btn">
			</div>
		</div>
	</form>
   
    <datalist id="destinations_list"></datalist>
</div>