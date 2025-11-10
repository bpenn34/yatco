<div class="yatco-shortcode-yachts-search-form yatco-shortcode-search-form yt-search-form-design2">
	<div id="yatco-scroll-to-once-searched">
		<br><br>
	</div>

    <?php
    // Fetch stored options and unserialize
    $saved_filters = get_option('boatdecker_search_form_visible_fields');
    $saved_filters = !empty($saved_filters) ? unserialize($saved_filters) : [];
    ?>
	<form action="" method="GET" id="yacht-quick-search" class="small-form"
		data-input-values="<?= esc_attr(json_encode($_params)) ?>"
		data-shortcode-attributes="<?= esc_attr(json_encode($atts)); ?>">

		<input type="hidden" name="scroll-to-form" value="true">
		<input type="hidden" name="fromForm" value="1">

		<div class="row">
			<div class="col-12">
                <div class="row">

                    <?php if (in_array('Builder', $saved_filters)) { ?>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="col-input boatdeck-fields">
                            <label>Builder</label>

                            <?php if ($attributes['builder_input_type'] == 'text') : ?>
                                <input type="text" name="Builder" placeholder="Enter Builder" class="form-control yt-input" list="builders_list">

                            <?php elseif ($attributes['builder_input_type'] == 'select'): ?>
                                <select class="yt-input" name="BuiilderID" data-yatco-fill-options="ActiveBuilders" data-yatco-empty-after-fill="true" data-value="<?= apply_filters('yatco_data_value', 'BuilderID', $_params, '2563') ?>">
                                    <option value="">All Builders</option>
                                </select>

                            <?php endif; ?>

                        </div>
                    </div>
                    <?php } ?>

                    <?php if (in_array('LOA', $saved_filters)) { ?>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="col-input boatdeck-fields">
                            <label>Length</label>
                            <input type="text" name="loa_from" placeholder="Min" class="yt-input">
                            <input type="text" name="loa_to" placeholder="Max" class="yt-input">
                            <select class="yt-input  yt-additional-attr" name="LengthUnit">
                                <option value="1">FT</option>
                                <option value="2">M</option>
                            </select>
                        </div>
                    </div>
                    <?php } ?>

                    <?php if (in_array('PriceRange', $saved_filters)) { ?>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="col-input boatdeck-fields">
                            <label>Price</label>
                            <input type="text" name="pricerange_from" placeholder="Min" class="yt-input extra-attrs-fields">
                            <input type="text" name="pricerange_to" placeholder="Max" class="yt-input extra-attrs-fields">
                            <select name="CurrencyType" class="yt-input yt-additional-attr" data-yatco-fill-options="Currency" data-yatco-empty-after-fill="true" data-value="<?= apply_filters('yatco_data_value', 'CurrencyType', $_params, '2563') ?>">
                                <option selected="selected" value="USD">USD</option>
                            </select>
                        </div>
                    </div>
                    <?php } ?>

                    <?php if (in_array('Year', $saved_filters)) { ?>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="col-input boatdeck-fields">
                            <label>Year</label>
                            <input type="number" name="year_from" placeholder="Min" class="yt-input">
                            <input type="number" name="year_to" placeholder="Max" class="yt-input">
                        </div>

                        <!-- <div class="col-input boatdeck-fields">
                            <label>Association Region</label>

                            <select name="RegionID" class="yt-input fill-association-region" data-association-id="4" data-value="<?= apply_filters('yatco_data_value', 'RegionID', $_params, '') ?>">
                                
                                <option value="">Loading...</option>

                            </select> 
                        </div> -->
                    </div>
                    <?php } ?>
                    <div class="col-12 col-md-6 col-lg-4">
                        <input type="submit" value="SEARCH YACHTS" class="yt-btn yt-btn-block">
                    </div>
                </div>
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

	<!-- Mobile Search -->
	<!-- <form action="" autocomplete="off" id="mobile-search" style="display: none;" data-input-values="<?= esc_attr(json_encode($_params)) ?>">

		<select class="yt-input fill-builder" name="builder" data-value="<?php echo $_params['builder']; ?>">
			<option value="">All Builders</option>
		</select>
    		
    	<input type="number" placeholder="Builder Year" name="year">
    	
    	<div>
	    	<input type="number" placeholder="Min LOA" name="loa_from">
		 	
		 	<input type="number" placeholder="Max LOA" name="loa_from">
		</div>

  		<select name="measure">
			<option value="1">FT</option>
		    <option value="2">M</option>
    	</select>
		
		<input type="submit" placeholder="Search Yachts" value="SEARCH YACHTS">
	</form> -->

	<form action="" method="GET" id="yacht-expanded-search" class="big-form" 
		data-input-values="<?= esc_attr(json_encode($_params)) ?>"
		data-shortcode-attributes="<?= esc_attr(json_encode($atts)); ?>">

		<input type="hidden" name="openSearchForm" value="yes">
		<input type="hidden" name="scroll-to-form" value="true">

		<?php 
			$input_col_class = 'col-12 col-md-6 col-lg-4 col-xl-3';
		?>

		<div class="row">
            <?php if (in_array('Builder', $saved_filters)) { ?>
			<div class="<?= $input_col_class ?>">
				<div class="col-input boatdeck-fields">
					<label value="">Builder</label>

					<?php if ($attributes['builder_input_type'] == 'text') : ?>
						<input type="text" name="Builder" placeholder="Enter Builder" class="yt-input" list="builders_list">

					<?php elseif ($attributes['builder_input_type'] == 'select'): ?>
						<select class="yt-input" name="BuiilderID" data-yatco-fill-options="ActiveBuilders" data-yatco-empty-after-fill="true" data-value="<?= apply_filters('yatco_data_value', 'BuilderID', $_params, '2563') ?>">
							<option value="">All Builders</option>
						</select>

					<?php endif; ?>

				    <!-- <select class="yt-input fill-builder" id="builder" name="builderID" data-value="<?= $_params['builderID'] ?>" >
				    	<option value="">Loading...</option>
				    </select> -->
				</div>
	        </div>
            <?php } ?>

            <?php if (in_array('LOA', $saved_filters)) { ?>
	        <div class="<?= $input_col_class ?>">
                <div class="col-input boatdeck-fields">
                    <label for="filter_length">
                        <span>Length</span>
                    </label>
                    <input type="text" class="yt-input" name="loa_from" placeholder="Min">
                    <input type="text" class="yt-input" name="loa_to" placeholder="Max">
                    <select class="yt-input yt-additional-attr" name="LengthUnit">
                        <option value="1">FT</option>
                        <option value="2">M</option>
                    </select> 
                </div>
	        </div>
            <?php } ?>

            <?php if (in_array('PriceRange', $saved_filters)) { ?>
	        <div class="<?= $input_col_class ?>">
                <div class="col-input boatdeck-fields">
                    <label for="filter_price">
                        <span>Price</span>
                    </label>
                    <input type="text" class="yt-input extra-attrs-fields" name="pricerange_from" placeholder="Min" >
                    <input type="text" class="yt-input extra-attrs-fields" name="pricerange_to" placeholder="Max">
                    <select name="CurrencyType" class="yt-input yt-additional-attr" data-yatco-fill-options="Currency" data-yatco-empty-after-fill="true" data-value="<?= apply_filters('yatco_data_value', 'CurrencyType', $_params, '2563') ?>">
                        <option selected="selected" value="2563">USD</option>
                    </select>
                </div>
	        </div>
            <?php } ?>

            <?php if (in_array('Year', $saved_filters)) { ?>
	        <div class="<?= $input_col_class ?>">         
	            <div class="col-input boatdeck-fields">
	            	<label for="filter_year">
	                    <span>Year</span>
	                </label>
	                <input type="number" class="yt-input" name="year_from" placeholder="Min">
	               	<input type="number" class="yt-input" name="year_to" placeholder="Max">  
	            </div>
	        </div>	     
            <?php } ?>

            <?php if (in_array('VesselName', $saved_filters)) { ?>
	        <div class="<?= $input_col_class ?>">
	        	<div class="col-input boatdeck-fields">
	                <label>Vessel Name</label>
	                <input name="vesselname" type="text" class="yt-input">
	        	</div>
	        </div>
            <?php } ?>

            <?php if (in_array('Condition', $saved_filters)) { ?>
	        <div class="<?= $input_col_class ?>">
	        	<div class="col-input boatdeck-fields">
	                <label for="filter_condition">
	                    <span>Condition</span>
	                </label>

	                <select name="Condition" class="yt-input">
	                	<option value="1">New</option>
	                	<option value="2">Used</option>
	                	<option value="all" selected="">Both</option>
	                </select>
	            </div>
	            
	            <!-- <div>
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

	                <label class="YatcoCheckbox" for="Condition_Both">
	                    <input type="radio" id="Condition_Both" name="condition" checked="checked" value="all">
	                    <i></i>
	                    <span>Both</span>
	                </label>
	            </div> -->
	        </div>        
            <?php } ?>

            <?php if (in_array('VesselType', $saved_filters)) { ?>
	         <div class="<?= $input_col_class ?>">
	        	<div class="col-input boatdeck-fields">
	                <label for="filter_type">
	                    <span>Type</span>                                    
	                </label>

	                <select name="VesselType" class="yt-input" data-value="<?= apply_filters('yatco_data_value', 'VesselType', $_params) ?>">
	                	<option value="all">Both</option>
	                	<option value="2">Motor</option>
	                	<option value="1">Sail</option>
	                </select>
	            </div>
	            
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
	        </div>
            <?php } ?>

            <?php if (in_array('HullMaterial', $saved_filters)) { ?>
	        <div class="<?= $input_col_class ?>">
	        	<div class="col-input boatdeck-fields">
	                <label>Hull Material</label>

	                <select class="yt-input" name="HullMaterial_single"  data-yatco-fill-options="HullMaterial" data-yatco-empty-after-fill="false" data-value="<?= apply_filters('yatco_data_value', 'HullMaterial_single', $_params) ?>">
	                    <option value="">All</option>                      
	                </select>
	                
	        	</div>
	        </div>
			<?php } ?>

	        <!-- <div class="<?= $input_col_class ?>">
				<div class="col-input boatdeck-fields">
				    <label for="region">Region</label>
			
				    <select class="yt-input" name="RegionID" data-yatco-fill-options="Regions" data-yatco-empty-after-fill="false" data-value="<?= apply_filters('yatco_data_value', 'RegionID', $_params) ?>">
				    	<option value="">Any</option>
				    </select>
				</div>
	        </div> -->

	        <?php if (isset($_params['CountryList']) || isset($_params['countrylist'])) : ?>
                
                <?php if (in_array('LocationCity', $saved_filters)) { ?>
			    <div class="<?= $input_col_class ?>">
					<div class="col-input boatdeck-fields">
						<label>City</label>
	 
					   <input type="text" class="yt-input" name="City">
					</div>
				</div>	        
                <?php } ?>

		    <?php else : ?>

                <?php if (in_array('LocationRegion', $saved_filters)) { ?>
		        <div class="<?= $input_col_class ?> yt-region-col-field">
					<div class="col-input boatdeck-fields">
						<label>Region</label>
	 
					    <select class="yt-input" name="LocationRegionID" data-yatco-fill-options="GeoListRegionsWithActiveCountries" data-yatco-empty-after-fill="false" data-value="<?= apply_filters('yatco_data_value', 'LocationRegionID', $_params) ?>">
					    	<option value="">Any Region</option>
					    </select>
					</div>
				</div>
                <?php } ?>

                <?php if (in_array('LocationCountry', $saved_filters)) { ?>
				<div class="<?= $input_col_class ?> yt-country-col-field">
					<div class="col-input boatdeck-fields">
						<label>Country</label>
	 
					    <select class="yt-input fill-country" name="LocationCountryID" data-yatco-empty-after-fill="false" data-value="<?= apply_filters('yatco_data_value', 'LocationCountryID', $_params) ?>">
					    	<option value="">Pick Region First...</option>
					    </select>
					</div>
				</div>
                <?php } ?>

                <?php if (in_array('LocationState', $saved_filters)) { ?>
		        <div class="<?= $input_col_class ?> yt-state-col-field">
					<div class="col-input boatdeck-fields">
					    <label>State</label>
					
					    <select class="yt-input fill-state" name="LocationStateID" data-value="<?= apply_filters('yatco_data_value', 'LocationStateID', $_params) ?>">
					    	<option value="">Pick Country First...</option>
					    </select>
					</div>
		        </div>
                <?php } ?>

                <?php if (in_array('LocationCity', $saved_filters)) { ?>
		        <div class="<?= $input_col_class ?> yt-city-col-field">
					<div class="col-input boatdeck-fields">
					    <label>Cities</label>
					
					     <select class="yt-input fill-city" name="LocationCityID" data-value="<?= apply_filters('yatco_data_value', 'LocationCityID', $_params) ?>">
					    	<option value="">Pick State First...</option>
					    </select>
					</div>
		        </div>
                <?php } ?>

		    <?php endif; ?>

            <?php if (in_array('MainCategory', $saved_filters)) { ?>
	        <div class="<?= $input_col_class ?>">
				<div class="col-input boatdeck-fields">
					<label>Categories</label>
				    <select class="yt-input" name="MainCategoryID" data-value="<?= apply_filters('yatco_data_value', 'MainCategoryID', $_params) ?>">
	                    <option value="">All</option>                      
	                </select>
				</div>
	        </div>
            <?php } ?>

            <?php if (in_array('SubCategory', $saved_filters)) { ?>
	        <div class="<?= $input_col_class ?>">
				<div class="col-input boatdeck-fields">
					<label>Sub Categories</label>
				    <select class="yt-input" name="SubCategoryID" data-value="<?= apply_filters('yatco_data_value', 'SubCategoryID', $_params) ?>">
	                    <option value="">-- Pick A Category, First --</option>                      
	                </select>
				</div>
	        </div>
            <?php } ?>

            <?php if (in_array('Model', $saved_filters)) { ?>
	        <div class="<?= $input_col_class ?>">
				<div class="col-input boatdeck-fields">
	                <label>Model</label>
	                <input name="Model" type="text" class="yt-input" placeholder="Model">
	        	</div>
	        </div>        
            <?php } ?>

            <?php if (in_array('Keywords', $saved_filters)) { ?>
	        <div class="<?= $input_col_class ?>">
	        	<div class="col-input boatdeck-fields">
	                <label>Keywords</label>
	                <input name="Keywords" type="text" class="yt-input" placeholder="Keyword">
	        	</div>
	        </div>
            <?php } ?>

            <?php if (in_array('MinStaterooms', $saved_filters)) { ?>
	        <div class="<?= $input_col_class ?>">
	        	<div class="col-input boatdeck-fields">
	                <label>Staterooms</label>

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
            <?php } ?>

            <?php if (in_array('MinSleeps', $saved_filters)) { ?>
	        <div class="<?= $input_col_class ?>">
	        	<div class="col-input boatdeck-fields">
	                <label>Sleeps</label>

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
            <?php } ?>

            <?php if (in_array('MaxDraft', $saved_filters)) { ?>
	        <div class="<?= $input_col_class ?>">
	            <div class="col-input boatdeck-fields">
	            	<label>Max Draft</label>

	             	<input type="number" min="0" unit="ft" class="yt-input" name="MaxDraft" placeholder="">
	            </div>
	        </div>
            <?php } ?>

            <?php if (in_array('GrossTonnage', $saved_filters)) { ?>
	        <div class="<?= $input_col_class ?>">
	            <div class="col-input boatdeck-fields">
	            	<label for="filter_grosstonnage">
	                    <span>Gross Tonnage</span>
	                </label>

	                <input type="number" class="yt-input" name="grosstonnage_from" placeholder="Min">
	       
	                <input type="number" class="yt-input" name="grosstonnage_to" placeholder="Max">
	            </div>
	        </div>
            <?php } ?>

            <?php if (in_array('Brokerage', $saved_filters)) { ?>
            <div class="<?= $input_col_class ?>">
	        	<div class="col-input boatdeck-fields">
	                <label>Brokerage</label>
	                <input name="BrokerageCompany" type="text" class="yt-input" placeholder="Brokerage">
	        	</div>
	        </div>
            <?php } ?>
			
            <?php if (in_array('CheckboxConcept', $saved_filters) || in_array('CheckboxCommercial', $saved_filters) || in_array('CheckboxAcceptsCrypto', $saved_filters)) { ?>
	        <div class="col-lg-6 col-md-6 col-12 include-filters">
	            <label class="include-text">
	           		Include:
	        	</label>

                <?php if (in_array('CheckboxConcept', $saved_filters)) { ?>
	            <label class="YatcoCheckbox IncludeCheckbox">
	                <input type="checkbox" name="Concept" value="true" class="yt-checkbox-field">
	                <i></i>
	                <span>Concept</span>
	            </label>
	            <?php } ?>

                <?php if (in_array('CheckboxCommercial', $saved_filters)) { ?>
	            <label class="YatcoCheckbox IncludeCheckbox">
	                <input type="checkbox" name="Commercial" value="true" class="yt-checkbox-field">
	                <i></i>
	                <span>Merchant Ship</span>
	            </label>
                <?php } ?>

                <?php if (in_array('CheckboxAcceptsCrypto', $saved_filters)) { ?>
	            <label class="YatcoCheckbox IncludeCheckbox">
	                <input type="checkbox" name="AcceptsCrypto" value="true" class="yt-checkbox-field">
	                <i></i> 
	                <span>Accepts Crypto</span>
	            </label>
                <?php } ?>

	        </div>
            <?php } ?>
	    </div>

        <div class="SumbitButton_Section"> 
            <input type="submit" class="yt-btn" value="SEARCH YACHTS">

           <div class="btns-after-submit">
	           	<button type="button" onclick="resetSearchForm(event)">Reset Search</button>
	           	|
	           	<button type="button" class="close-advandenced-search">Exit Advanced</button>
           </div>
        </div>
	</form>


	<datalist id="builders_list"></datalist>
</div>