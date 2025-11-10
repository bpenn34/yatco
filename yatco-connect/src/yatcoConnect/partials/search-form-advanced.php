<div class="yatco-shortcode-yachts-search-form yatco-shortcode-search-form">
	<div id="yatco-scroll-to-once-searched">
		<br><br>
	</div>

	<form action="" method="GET" id="yacht-expanded-search" class="big-form" style="display: block;" 
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
					<label value="">Builder</label>

					<?php if ($attributes['builder_input_type'] == 'text') : ?>
						<input type="text" name="Builder" placeholder="Enter Builder" class="yt-input" list="builders_list">

					<?php elseif ($attributes['builder_input_type'] == 'select'): ?>
						<select class="yt-input" name="BuiilderID" data-yatco-fill-options="ActiveBuilders" data-yatco-empty-after-fill="true" data-value="<?= apply_filters('yatco_data_value', 'BuilderID', $_params, '2563') ?>">
							<option value="">All Builders</option>
						</select>

					<?php endif; ?>
				</div>
	        </div>

	        <div class="<?= $input_col_class ?>">
	        	<div class="more-space-for-option-above">
		            <select class="yt-input yt-above-input" name="LengthUnit">
		              	<option value="1">FT</option>
			            <option value="2">M</option>
		            </select>

		            <div class="col-input">
		            	<label for="filter_length">
		                    <span>Length</span>
		                </label>
		           		
		           		<input type="number" class="yt-input" name="loa_from" placeholder="Min">
		                    
		                <input type="number" class="yt-input" name="loa_to" placeholder="Max">  
		            </div>
		        </div>
	        </div>

	        <div class="<?= $input_col_class ?>">        
	          	<div class="more-space-for-option-above">
		            <select name="CurrencyType" class="yt-input yt-above-input" data-yatco-fill-options="Currency" data-yatco-empty-after-fill="true" data-value="<?= apply_filters('yatco_data_value', 'CurrencyType', $_params, '2563') ?>">
		            	<option selected="selected" value="2563">USD</option>
					</select>
		            
		            <div class="col-input">
		                <label for="filter_price">
		                    <span>Price</span>
		                </label>
		           
		                <input type="number" class="yt-input" name="pricerange_from" placeholder="Min" >
		                   
		                <input type="number" class="yt-input" name="pricerange_to" placeholder="Max">
		            </div>
		        </div>
	        </div>

	        <div class="<?= $input_col_class ?>">         
	            <div class="col-input">
	            	<label for="filter_year">
	                    <span>Year</span>
	                </label>
	                
	                <input type="number" class="yt-input" name="year_from" placeholder="Min">
	                
	               	<input type="number" class="yt-input" name="year_to" placeholder="Max">  
	            </div>
	        </div>	     

	        <div class="<?= $input_col_class ?>">
	        	<div class="col-input">
	                <label>Vessel Name</label>
	                <input name="vesselname" type="text" class="yt-input">
	        	</div>
	        </div>

	        <div class="<?= $input_col_class ?>">
				<div class="col-input">
				    <label for="region">Region</label>
			
				    <select class="yt-input" name="RegionID" data-yatco-fill-options="Regions" data-yatco-empty-after-fill="false" data-value="<?= apply_filters('yatco_data_value', 'RegionID', $_params) ?>">
				    	<option value="">Any</option>
				    </select>
				</div>
	        </div>

	        <?php if (isset($_params['CountryList']) || isset($_params['countrylist'])) : ?>

			    <div class="<?= $input_col_class ?>">
					<div class="col-input">
						<label>City</label>
	 
					   <input type="text" class="yt-input" name="City">
					</div>
				</div>	        

		    <?php else : ?>

		        <div class="<?= $input_col_class ?>">
					<div class="col-input">
						<label>Country</label>
	 
					    <select class="yt-input" id="country" name="CountryID" data-yatco-fill-options="Countries" data-yatco-empty-after-fill="false" data-value="<?= apply_filters('yatco_data_value', 'CountryID', $_params) ?>">
					    	<option value="">Any Country</option>
					    </select>
					</div>
				</div>

		        <div class="<?= $input_col_class ?>">
					<div class="col-input">
					    <label for="state">State</label>
					
					    <select class="yt-input fill-state" id="state" name="StateID" data-value="<?= apply_filters('yatco_data_value', 'StateID', $_params) ?>">
					    	<option value="">Pick Country First...</option>
					    </select>
					</div>
		        </div>

		    <?php endif; ?>

	        <div class="<?= $input_col_class ?>">
	        	<div class="col-input">
	                <label for="filter_type">
	                    <span>Type</span>                                    
	                </label>

	                <select name="VesselType" class="yt-input" data-value="<?= apply_filters('yatco_data_value', 'VesselType', $_params) ?>">
	                	<option value="all">Both</option>
	                	<option value="2">Motor</option>
	                	<option value="1">Sail</option>
	                </select>
	            </div>
	        </div>

	        <div class="<?= $input_col_class ?>">
				<div class="col-input">
					<label>Categories</label>
				    <select class="yt-input" name="MainCategoryID" data-value="<?= apply_filters('yatco_data_value', 'MainCategoryID', $_params) ?>">
	                    <option value="">All</option>                      
	                </select>
				</div>
	        </div>
	     	
	        <div class="<?= $input_col_class ?>">
				<div class="col-input">
					<label>Sub Categories</label>
				    <select class="yt-input" name="SubCategoryID" data-value="<?= apply_filters('yatco_data_value', 'SubCategoryID', $_params) ?>">
	                    <option value="">-- Pick A Category, First --</option>                      
	                </select>
				</div>
	        </div>

	        <div class="<?= $input_col_class ?>">
				<div class="col-input">
	                <label>Model</label>
	                <input name="Model" type="text" class="yt-input" placeholder="Model">
	        	</div>
	        </div>        

	        <div class="<?= $input_col_class ?>">
	        	<div class="col-input">
	                <label>Keywords</label>
	                <input name="Keywords" type="text" class="yt-input" placeholder="Keyword">
	        	</div>
	        </div>

	        <div class="<?= $input_col_class ?>">
	        	<div class="col-input">
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

	        <div class="<?= $input_col_class ?>">
	        	<div class="col-input">
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

	        <div class="<?= $input_col_class ?>">
	            <div class="col-input">
	            	<label>Max Draft</label>

	             	<input type="number" min="0" unit="ft" class="yt-input" name="MaxDraft" placeholder="">
	            </div>
	        </div>

	        <div class="<?= $input_col_class ?>">
	            <div class="col-input">
	            	<label for="filter_grosstonnage">
	                    <span>Gross Tonnage</span>
	                </label>

	                <input type="number" class="yt-input" name="grosstonnage_from" placeholder="Min">
	       
	                <input type="number" class="yt-input" name="grosstonnage_to" placeholder="Max">
	            </div>
	        </div>

	        <div class="<?= $input_col_class ?>">
	        	<div class="col-input">
	                <label for="filter_condition">
	                    <span>Condition</span>
	                </label>

	                <select name="Condition" class="yt-input">
	                	<option value="1">New</option>
	                	<option value="2">Used</option>
	                	<option value="all" selected="">Both</option>
	                </select>
	            </div>
	        </div>        

	        <div class="<?= $input_col_class ?>">
	        	<div class="col-input">
	                <label>Brokerage</label>
	                <input name="BrokerageCompany" type="text" class="yt-input" placeholder="Brokerage">
	        	</div>
	        </div>

	        <div class="<?= $input_col_class ?>">
	        	<div class="col-input">
	                <label>Hull Material</label>

	                <select class="yt-input" name="HullMaterial_single"  data-yatco-fill-options="HullMaterial" data-yatco-empty-after-fill="false" data-value="<?= apply_filters('yatco_data_value', 'HullMaterial_single', $_params) ?>">
	                    <option value="">All</option>                      
	                </select>
	                
	        	</div>
	        </div>

	        <div class="yt-col-lg-6 yt-col-md-6 yt-col-12 include-filters">
	            <label style="display: inline-block; color: #406695; font-weight: bold;">
	           		Include:
	        	</label>

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

	            <br>

	            <div style="display: inline-block; width: 54px;"></div>

	            <label class="YatcoCheckbox IncludeCheckbox" style="display: inline-block; color: #406695;">
	                <input type="checkbox" name="AcceptsCrypto" value="true">
	                <i></i>
	                <span>Acceptes Cryto</span>
	            </label>
	        </div>
	    </div>

        <div class="SumbitButton_Section"> 
            <input type="submit" class="yt-btn" value="SEARCH YACHTS">

           <div class="btns-after-submit">
	           	<button type="button" onclick="resetSearchForm(event)">Reset Search</button>
           </div>
        </div>
	</form>


	<datalist id="builders_list"></datalist>
</div>