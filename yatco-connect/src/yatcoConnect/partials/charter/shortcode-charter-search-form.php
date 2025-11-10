<div class="yatco-shortcode-charter-search-form yatco-shortcode-search-form">
	<form action="" method="GET" id="charter-quick-search" class="small-form"  
		data-input-values="<?= esc_attr(json_encode($_params)) ?>"
		data-shortcode-attributes="<?= esc_attr(json_encode($atts)); ?>">
		<div class="yt-row">
			<div class="yt-col-12 yt-col-lg-9">

				<div class="yt-col-12 yt-col-md-6 yt-col-lg-3">
					<div class="col-input">
						<label>Destination</label>

						<!-- <input type="text" name="Destinations" class="yt-input"> -->

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
		                <label>Cr. Guests</label>
		           
		                <input type="number" class="yt-input" name="Guests_from" placeholder="Min" >
		                
		                <input type="number" class="yt-input" name="Guests_to" placeholder="Max">
		            </div>
		        </div>

		        
			</div>

			<div class="yt-col-12 yt-col-lg-3">
				<input type="submit" value="SEARCH CHARTERS" class="yt-btn yt-btn-block">
			</div>
		</div>

		<div class="yt-row">
			<div class="yt-col-12">
				<div style="" class="expand-search">
					Advanced Search +
				</div>
			</div>
		</div>
	</form>

	<form action="" method="GET" id="charter-expanded-search" class="big-form" 
		<?= $_GET['openSearchForm'] == 'yes' ? ' display-expanded-search="yes" ':''; ?>
		data-input-values="<?= esc_attr(json_encode($_params)) ?>"
		data-shortcode-attributes="<?= esc_attr(json_encode($atts)); ?>">

		<input type="hidden" name="openSearchForm" value="yes">
		<input type="hidden" name="scroll-to-form" value="true">

		<?php 
			$input_col_class = 'yt-col-lg-3 yt-col-md-6 yt-col-12';
		?>

		<div class="yt-row">
			<div class="<?= $input_col_class ?>">
				<div class="col-input">
					<label value="">Destination</label>

					<!-- <input type="text" name="Destinations" class="yt-input"> -->

					<!-- <select class="yt-input" name="Location" data-yatco-fill-options="DDCharterDestinations" data-yatco-empty-after-fill="false" data-value="<?= apply_filters('yatco_data_value', 'Location', $_params) ?>">
					    	<option value="">Any</option>
					    </select> -->

					<input type="text" name="Destinations" class="yt-input" list="destinations_list"> 
				</div>
	        </div>

	         <div class="<?= $input_col_class ?>">
				<div class="col-input">
				    <label for="region">Season</label>
			
				    <select class="yt-input" name="SeasonID" data-yatco-fill-options="CharterSeasons" data-yatco-empty-after-fill="false" data-value="<?= apply_filters('yatco_data_value', 'SeasonID', $_params) ?>">
				    	<option value="">Any</option>
				    </select>
				</div>
	        </div>


	        <!-- <div class="<?= $input_col_class ?>">
	            <select class="yt-input" id="LengthUnit" name="LengthUnit">
	              	<option value="1">FT</option>
		            <option value="2">M</option>
	            </select>

	            <div class="col-input">
	            	<label for="filter_length">
	                   Length
	                </label>
	           		
	           		<input type="number" class="yt-input" name="LoaRange_from" placeholder="Min">
	                    
	                <input type="number" class="yt-input" name="LoaRange_to" placeholder="Max">  
	            </div>
	        </div> -->

	        <div class="<?= $input_col_class ?>">      
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
			
			<div class="<?= $input_col_class ?>">
	            <div class="col-input">
	                <label>Cr. Guests</label>
	           
	                <input type="number" class="yt-input" name="Guests_from" placeholder="Min" >
	                   
	                <input type="number" class="yt-input" name="Guests_to" placeholder="Max">
	            </div>
	        </div>

	        <div class="<?= $input_col_class ?>">         
	            <div class="col-input">
	            	<label>Builder(s)</label>
	                
	                <input type="text" name="BuilderName" class="yt-input">
	            </div>
	        </div>	     

	        <div class="<?= $input_col_class ?>">
	        	<div class="col-input">
	                <label>Vessel Name</label>
	                <input type="text" name="VesselName" class="yt-input">
	        	</div>
	        </div>

	        <div class="<?= $input_col_class ?>">
				<div class="col-input">
					<label>Type</label>

	                <select name="VesselTypeID" class="yt-input">
	                	<option value="2">Motor</option>
	                	<option value="1">Sail</option>
	                	<option value="all" selected="">Both</option>
	                </select>
				</div>
			</div>

	        <div class="<?= $input_col_class ?>">
				<div class="col-input">
	            	<label>Year</label>
	                
	                <input type="number" class="yt-input" name="YearRange_from" placeholder="Min">
	                
	               	<input type="number" class="yt-input" name="YearRange_to" placeholder="Max">  
	            </div>
	        </div>	        

	        <div class="<?= $input_col_class ?>">
	        	<div class="more-space-for-option-above">
	        		<div class="yt-above-input">
			            <select class="yt-input LengthUnit" name="LOAMetric">
			              	<option value="1">FT</option>
				            <option value="2">M</option>
			            </select>
			        </div>

		            <div class="col-input">
		            	<label for="filter_length">
		                   Length
		                </label>
		           		
		           		<input type="number" class="yt-input" name="LoaRange_from" placeholder="Min">
		                    
		                <input type="number" class="yt-input" name="LoaRange_to" placeholder="Max">  
		            </div>
		        </div>
	        </div>

	        <div class="<?= $input_col_class ?>">
				<div class="col-input">
				    <label>Staterooms</label>
			
				    <select name="Cabins_from" class="yt-input">=
				    	<option value="0">Any</option>
	                    <option value="1">1+ Stateroom</option>
	                    <option value="2">2+ Staterooms</option>
	                    <option value="3">3+ Staterooms</option>
	                    <option value="4">4+ Staterooms</option>
	                    <option value="5">5+ Staterooms</option>
	                    <option value="6">6+ Staterooms</option>
	                    <option value="7">7+ Staterooms</option>
	                    <option value="8">8+ Staterooms</option>
				    </select>
				</div>
	        </div>

	        <!-- <div class="<?= $input_col_class ?>"> -->
	        	<!-- <div class="col-input">
	                <label>Beds</label>
			
				    <select  class="yt-input">
				    	<option value="0">Any</option>
	                    <option value="1">1+ Bed</option>
	                    <option value="2">2+ Beds</option>
	                    <option value="3">3+ Beds</option>
	                    <option value="4">4+ Beds</option>
	                    <option value="5">5+ Beds</option>
	                    <option value="6">6+ Beds</option>
	                    <option value="7">7+ Beds</option>
	                    <option value="8">8+ Beds</option>
				    </select>
	            </div>
 -->
	            <!-- <div>
	                <label class="YatcoCheckbox" for="VesselType_Motor">
	                    <input type="radio" id="VesselType_Motor" name="type" value="2">
	                    <i></i>
	                    <span>Motor</span>
	                </label>

	                <label class="YatcoCheckbox" for="VesselType_Sail">
	                    <input type="radio" id="VesselType_Sail" name="type" value="1">
	                    <i></i>
	                    <span>Sail</span>
	                </label>

	                <label class="YatcoCheckbox" for="VesselType_Both">
	                    <input type="radio" id="VesselType_Both" name="type" checked="checked" value="all">
	                    <i></i>
	                    <span>Both</span>
	                </label>
	            </div> -->
	        <!-- </div> -->

	        <div class="<?= $input_col_class ?>">
				<div class="col-input">
	                <label>Sleeps</label>
			
				    <select name="Sleeps_from" class="yt-input">
				    	<option value="0">Any</option>
	                    <option value="1">1+ Sleeps</option>
	                    <option value="2">2+ Sleeps</option>
	                    <option value="3">3+ Sleeps</option>
	                    <option value="4">4+ Sleeps</option>
	                    <option value="5">5+ Sleeps</option>
	                    <option value="6">6+ Sleeps</option>
	                    <option value="7">7+ Sleeps</option>
	                    <option value="8">8+ Sleeps</option>
				    </select>
				</div>
	        </div>

	        <!-- <div class="<?= $input_col_class ?>">
				<div class="col-input">
	                <label>Crew</label>
			
				    <input type="number" name="NumberOfCrew" class="yt-input">
				</div>
	        </div> -->
	     	
	        <!-- <div class="<?= $input_col_class ?>">
				<div class="col-input">
	                <label>Check In</label>
	                <input type="date" name="Availability_from" class="yt-input" placeholder="">
	        	</div>
	        </div>  -->

	     <!--   	<div class="<?= $input_col_class ?>">
				<div class="col-input">
	                <label>Destinations</label>
	                <input type="text" name="Destinations" class="yt-input" placeholder="">
	        	</div>
	        </div>         -->

	        <!-- <div class="<?= $input_col_class ?>">
	        	<div class="col-input">
	                <label>Region</label>
	                <input type="text" name="RegionID" class="yt-input" placeholder="">
	        	</div>
	        </div> -->

	        <!-- <div class="<?= $input_col_class ?>">
	        	<div class="col-input">
	                <label>Base</label>
	                <input type="text" name="BasePort" class="yt-input" placeholder="">
	        	</div>
	        </div>
		    <div class="<?= $input_col_class ?>">
	        	<div class="col-input">
	                <label>Embarkation Port</label>
	                <input type="text" name="EmbarkationPort" class="yt-input" placeholder="">
	        	</div>
	        </div>

	   		<div class="<?= $input_col_class ?>">
	        	<div class="col-input">
	                <label>Disembarkation Port</label>
	                <input type="text" name="DisembarkationPort" class="yt-input" placeholder="">
	        	</div>
	        </div> -->

	        <!-- <div class="<?= $input_col_class ?>">
	        	<div class="col-input">
	                <label>Keywords</label>
	                <input type="text" name="Keywords" class="yt-input" placeholder="Keyword">
	        	</div>
	        </div> -->

	        <!-- <div class="<?= $input_col_class ?>">
	            <div class="col-input">
	                <label>Special Access</label>

	                <select name="Amenities_SpecialAccess" data-yatco-fill-options="CharterAmenities_SpecialAccess" data-value="<?= apply_filters('yatco_data_value', 'Amenities_SpecialAccess', $_params, '') ?>">
	                	<option value="">Any</option>
	                </select>
	            </div>
	        </div> -->

	        <!-- <div class="<?= $input_col_class ?>">
	            <div class="col-input">
	                <label>Air Access</label>

	                <select name="Amenities_Air" data-yatco-fill-options="CharterAmenities_Air" data-value="<?= apply_filters('yatco_data_value', 'Amenities_Air', $_params, '') ?>">
	                	<option value="">Any</option>
	                </select>
	            </div>
	        </div> -->

	        <div class="<?= $input_col_class ?>">
	            <!-- <div class="col-input">
	                <label>Special Access</label> -->

	                <label style="display: inline;">
	                	<input type="checkbox" name="Amenities_SpecialAccess_E" value="26">
	                	Elevator / Lift
	                </label>

	                &nbsp;

	                <label style="display: inline;">
	                	<input type="checkbox" name="Amenities_SpecialAccess_W" value="27">
	                	Wheelchair Friendly
	                </label>

	                <label style="display: inline;">
	                	<input type="checkbox" name="Bareboat" value="true">
	                	Bareboats
	                </label>
	            <!-- </div> -->
	        </div>
	    </div>

        <div class="SumbitButton_Section"> 
            <input type="submit" class="yt-btn" value="SEARCH CHARTERS">

           <div class="btns-after-submit">
	           	<button type="button" onclick="resetSearchForm(event)">Reset Search</button>
	           	|
	           	<button type="button" class="close-advandenced-search">Exit Advanced</button>
           </div>
        </div>
	</form>

	<datalist id="destinations_list"></datalist>
</div>