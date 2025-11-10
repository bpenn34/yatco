<div class="yatco-shortcode-yachts-search yatco-shortcode-yachts-vertical-search yatco-shortcode-search-form">
	<?php
		$input_col_class = 'yt-col-12';

		$country_list = $data->get_countries();

	?>

	<div id="yatco-scroll-to-once-searched">
	</div>
	
	<form id="yacht-vertical-search" action="<?= $action_url ?>" method="GET" data-input-values="<?= esc_attr(json_encode($_params)) ?>">

		<input type="hidden" name="scroll-to-form" value="true">
		<input type="hidden" name="fromForm" value="1">

		<div class="yt-row">
			<div class="<?= $input_col_class ?>">
				<label class="yt-search-accordion active">Type</label>

				<div class="_panel" style="display: block;">
					<div class="col-input col-input-radio-or-checks _panel">
		                <label class="YatcoCheckbox" for="VesselType_Both">
		                    <input type="radio" id="VesselType_Both" name="vesseltype" checked="checked" value="all">
		                    <i></i>
		                    <span>Both</span>
		                </label>

						<label class="YatcoCheckbox" for="VesselType_Motor">
		                    <input type="radio" id="VesselType_Motor" name="vesseltype" value="2">
		                    <i></i>
		                    <span>Motor</span>
		                </label>

		                <label class="YatcoCheckbox" for="VesselType_Sail">
		                    <input type="radio" id="VesselType_Sail" name="vesseltype" value="1">
		                    <i></i>
		                    <span>Sail</span>
		                </label>
					</div>
				</div>
			</div>

			<div class="<?= $input_col_class ?>">
				<label class="yt-search-accordion active">Condition</label>

				<div class="_panel" style="display: block;">
					<div class="col-input col-input-radio-or-checks _panel">
		                <label class="YatcoCheckbox" for="Condition_Both">
		                    <input type="radio" id="Condition_Both" name="condition" checked="checked" value="all">
		                    <i></i>
		                    <span>Both</span>
		                </label>

		                <label class="YatcoCheckbox" for="Condition_New">
		                    <input type="radio" text="New" id="Condition_New" name="condition" value="1">
		                    <i></i>
		                    <span>New</span>
		                </label>
		                
		                <label class="YatcoCheckbox" for="Condition_Used">
		                    <input type="radio" text="Used" id="Condition_Used" name="condition" value="2">
		                    <i></i>
		                    <span>Used</span>
		                </label>

		            </div>
		        </div>
	        </div>

			

			<div class="<?= $input_col_class ?>">
				<label class="yt-search-accordion active">Length</label>
				
	            <div class="_panel" style="display: block;">
		            <div class="col-input _panel">
						<input type="number" name="loa_from" placeholder="Min" class="yt-input">
						<input type="number" name="loa_to" placeholder="Max" class="yt-input">

						<select name="LengthUnit" class="yt-input">
			                <option value="1" selected="">FT</option>
			                <option value="2">M</option>
			            </select>
		            </div>
		        </div>
		    </div>

			<div class="<?= $input_col_class ?>">
				<label class="yt-search-accordion active">Price</label>
				
				<div class="_panel" style="display: block;">
					<div class="col-input _panel">
						<input type="number" name="pricerange_from" placeholder="Min" class="yt-input">
						<input type="number" name="pricerange_to" placeholder="Max" class="yt-input">
					
						<select name="CurrencyType" class="yt-input" data-yatco-fill-options="Currency" data-yatco-empty-after-fill="true" data-value="<?= apply_filters('yatco_data_value', 'CurrencyType', $_params, '2563') ?>">
			            	<option selected="selected" value="USD">USD</option>
						</select>
					</div>
				</div>
			</div>

			<div class="<?= $input_col_class ?>">
				<label class="yt-search-accordion active">Year</label>
				
				<div class="_panel" style="display: block;">
					<div class="col-input _panel">
						<input type="number" name="year_from" placeholder="Min" class="yt-input">
						<input type="number" name="year_to" placeholder="Max" class="yt-input">
					</div>
				</div>
			</div>

			<div class="<?= $input_col_class ?>">
	        	<label class="yt-search-accordion">
	                <span>Vessel Name</span>
	            </label>

	            <div class="_panel" style="display: none;">
		            <div class="col-input">
		                <input type="text" class="yt-input" name="VesselName" placeholder="Enter Name">
		       		</div>
		       	</div>
	        </div>

			<div class="<?= $input_col_class ?>">
				<label class="yt-search-accordion">Builders</label>

				<div class="_panel" style="display: none;">
					<div class="col-input _panel">
					    <div class="yt-super-select" name="BuilderID" data-yatco-fill-options="ActiveBuilders" data-yatco-empty-after-fill="true" data-value="<?= apply_filters('yatco_data_value', 'BuilderList', $_params, '') ?>">
					    	
					    	<div class="yt-super-option">
					    		<label class="uncheck-all">
					    			<input type="checkbox" name="BuilderList22" value="1">	
					    			All Builders
					    		</label>
					    	</div>

					    	<div class="yt-super-options">

					    	</div>
					    </div>
					</div>
				</div>
			</div>
			
			<!-- <div class="<?= $input_col_class ?>">
            	<label for="filter_grosstonnage" class="yt-search-accordion">
                    <span>Gross Tonnage</span>
                </label>

	            <div class="_panel" style="display: none;">
		            <div class="col-input">

		                <input type="number" class="yt-input" name="grosstonnage_from" placeholder="Min">
		       
		                <input type="number" class="yt-input" name="grosstonnage_to" placeholder="Max">
		            </div>
		       	</div>
	        </div> -->

			<div class="<?= $input_col_class ?>">
				<label class="yt-search-accordion">Categories</label>
				
				<div class="_panel" style="display: none;">
					<div class="col-input _panel">					
		                <div class="yt-super-select" name="MainCategoryID" data-value="<?= apply_filters('yatco_data_value', 'MainCategoryID', $_params) ?>">
					    	
					    	<div class="yt-super-option">
					    		<label class="uncheck-all">
					    			<input type="checkbox" name="" value="1">	
					    			All
					    		</label>
					    	</div>

					    	<div class="yt-super-options">

					    	</div>
					    </div>

					</div>


				</div>
	        </div>
	     	
	        <div class="<?= $input_col_class ?> yt-sub-category-col-field">
				<label class="yt-search-accordion">Sub Categories</label>
				
				<div class="_panel" style="display: none;">
					<div class="col-input">
					  <div class="yt-super-select" name="SubCategoryID" data-value="<?= apply_filters('yatco_data_value', 'SubCategoryID', $_params) ?>">
					    	
					    	<div class="yt-super-option">
					    		<label class="uncheck-all">
					    			<input type="checkbox" name="" value="1">	
					    			All
					    		</label>
					    	</div>

					    	<div class="yt-super-options">

					    	</div>
					    </div>
					</div>
				</div>
	        </div>

			<div class="<?= $input_col_class ?> yt-region-col-field">
			    <label class="yt-search-accordion">Region</label>

		    	<div class="_panel" style="display: none;">
					<div class="col-input">
				
					   <!--  <select multiple class="yt-input" name="LocationRegionID" data-yatco-fill-options="GeoListRegionsWithActiveCountries" data-yatco-empty-after-fill="false" data-value="<?= apply_filters('yatco_data_value', 'RegionID', $_params) ?>">
					    	<option value="">Any</option>
					    </select> -->

					    <div class="yt-super-select" name="LocationRegionID"  data-yatco-fill-options="GeoListRegionsWithActiveCountries"  data-yatco-empty-after-fill="false" data-value="<?= apply_filters('yatco_data_value', 'LocationRegionID', $_params) ?>">
					    	
					    	<div class="yt-super-option">
					    		<label>
					    			<input type="checkbox" name="LocationRegionID" value="">	
					    			Any Region
					    		</label>
					    	</div>

					    	<div class="yt-super-options">

					    	</div>
					    </div>
					</div>
				</div>
	        </div>

	        <div class="<?= $input_col_class ?> yt-country-col-field">
				<label class="yt-search-accordion">Country</label>

				<div class="_panel" style="display: none;">
					<div class="col-input">
	 
					    <div class="yt-super-select" name="LocationCountryID"  data-yatco-empty-after-fill="false" data-value="<?= apply_filters('yatco_data_value', 'LocationCountryID', $_params) ?>">
					    	
					    	<div class="yt-super-option">
					    		<label>
					    			<input type="checkbox" name="LocationCountryID" value="">	
					    			Any Country
					    		</label>
					    	</div>

					    	<div class="yt-super-options">

					    	</div>
					    </div>
					</div>
				</div>
			</div> 

			<div class="<?= $input_col_class ?> yt-state-col-field">
				<label class="yt-search-accordion">States</label>

				<div class="_panel" style="display: none;">
					<div class="col-input">
	 
					    <div class="yt-super-select" name="LocationStateID"  data-yatco-empty-after-fill="false" data-value="<?= apply_filters('yatco_data_value', 'LocationStateID', $_params) ?>">
					    	
					    	<div class="yt-super-option">
					    		<label>
					    			<input type="checkbox" name="LocationStateID" value="">	
					    			Any State
					    		</label>
					    	</div>

					    	<div class="yt-super-options">

					    	</div>
					    </div>
					</div>
				</div>
			</div>

			<div class="<?= $input_col_class ?> yt-city-col-field">
				<label class="yt-search-accordion">City</label>

				<div class="_panel" style="display: none;">
					<div class="col-input">
	 
					    <div class="yt-super-select" name="LocationCityID"  data-yatco-empty-after-fill="false" data-value="<?= apply_filters('yatco_data_value', 'LocationCityID', $_params) ?>">
					    	
					    	<div class="yt-super-option">
					    		<label>
					    			<input type="checkbox" name="LocationCityID" value="">	
					    			Any City
					    		</label>
					    	</div>

					    	<div class="yt-super-options">

					    	</div>
					    </div>
					</div>
				</div>
			</div>

	        <div class="<?= $input_col_class ?>">
	            <label class="yt-search-accordion">Staterooms</label>

	        	<div class="_panel" style="display: none;">
		        	<div class="col-input _panel">

		                <select name="MinStaterooms" class="yt-input">
		                    <option value="0">Any</option>
		                    <option value="1">1+ Staterooms</option>
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
	        </div>

	      <!--   <div class="<?= $input_col_class ?>">
	            <label class="yt-search-accordion">Sleeps</label>

	        	<div class="_panel" style="display: none;">
		        	<div class="col-input">
	 
		                <select name="MinSleeps" class="yt-input">
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
	        </div>  -->
	       
	        <div class="<?= $input_col_class ?>">

		        <label class="yt-search-accordion" style="color: #406695; font-weight: bold;">
	           		Include:
	        	</label>

	        	<div class="_panel" style="display: none;">
		        	<div class="col-input col-input-radio-or-checks">
			            <label class="YatcoCheckbox IncludeCheckbox" style="display: inline-block; color: #406695;">
			                <input type="checkbox" name="Concept" value="true">
			                <i></i>
			                <span>Concept</span>
			            </label>
			            
			            <label class="YatcoCheckbox IncludeCheckbox" style="display: inline-block; color: #406695;">
			                <input type="checkbox" name="Commercial" value="true">
			                <i></i>
			                <span>Merchant Ship</span>
			            </label>

			            <label class="YatcoCheckbox IncludeCheckbox" style="display: inline-block; color: #406695;">
			                <input type="checkbox" name="AcceptsCrypto" value="true">
			                <i></i>
			                <span>Acceptes Cryto</span>
			            </label>
					</div>
				</div>
			</div>
		</div>

		<div style="text-align: center; margin-top: 15px;">
			<input type="submit" value="SEARCH YACHTS" class="yt-btn">
		</div>


		<datalist id="builders_list"></datalist>
	</form>
</div>