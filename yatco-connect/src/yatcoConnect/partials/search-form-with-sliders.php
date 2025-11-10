<div class="yatco-shortcode-charter-search-form yatco-shortcode-search-form">
	

	<form action="" method="GET" id="charter-expanded-search" class="big-form" style="display: block;"
		<?= $_GET['openSearchForm'] == 'yes' ? ' display-expanded-search="yes" ':''; ?>
		data-input-values="<?= esc_attr(json_encode($_params)) ?>"
		data-shortcode-attributes="<?= esc_attr(json_encode($atts)); ?>">

		<input type="hidden" name="openSearchForm" value="yes">
		<input type="hidden" name="scroll-to-form" value="true">

		<?php 
			$input_col_class = 'yt-col-lg-3 yt-col-md-6 yt-col-12';
		?>

		<div class="yt-row">
			<div class="yt-col-6">
				<label>Price <span id="display-price-range"></span></label>

				<div class="yt-above-input" style="top: 26px; right: 20px;">
	          		<select name="CurrencyType" class="yt-input yt-above-input" data-yatco-fill-options="Currency" data-yatco-empty-after-fill="true" data-value="<?= apply_filters('yatco_data_value', 'CurrencyType', $_params, '2563') ?>">
		            	<option selected="selected" value="USD">USD</option>
					</select>
				</div>

				<div id="price-range-sliders"></div>

				<input type="hidden" name="pricerange_from" style="display: none;">
				<input type="hidden" name="pricerange_to" style="display: none;">
			</div>

			<div class="yt-col-6">
				<label>Length <span id="display-length-range"></span></label>

				<div id="length-range-sliders"></div>

				<div class="yt-above-input"  style="top: -10px; right: 20px;">
		            <select class="yt-input LengthUnit" name="LengthUnit">
		              	<option value="1">FT</option>
			            <option value="2">M</option>
		            </select>
		        </div>

				<input type="hidden" name="loa_from" style="display: none;">
		        <input type="hidden" name="loa_to" style="display: none;">  
			</div>
	    </div>

	    <div class="yt-row bottom-fields">
	    	<div class="<?= $input_col_class ?>">
				<div class="col-input">
					<label>Builder</label>
				
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
	        	<div class="col-input">
	                <label>Vessel Name</label>
	                <input name="vesselname" type="text" class="yt-input">
	        	</div>
	        </div>

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
	            	<label for="filter_year">
	                    <span>Year</span>
	                </label>
	                
	                <input type="number" class="yt-input" name="year_from" placeholder="Min">
	                
	               	<input type="number" class="yt-input" name="year_to" placeholder="Max">  
	            </div>
	        </div>
	    </div>

        <div class="SumbitButton_Section"> 
            <input type="submit" class="yt-btn" value="SEARCH">

           <div class="btns-after-submit">
	           	<button type="button" onclick="resetSearchForm(event)">Reset Search</button>
           </div>
        </div>
	</form>
</div>

<link rel="stylesheet" type="text/css" href="<?= YATCO_PLUGIN_ASSETS ?>/scss/3rd-party/nouislider.css">
<script type="text/javascript" src="<?= YATCO_PLUGIN_ASSETS ?>js/3rd-party/nouislider.js"></script>

<script type="text/javascript">
	<?php

		// PRICE DEFAULTS

		if (isset($atts['price_range_from'])) {
			$price_range_from = $atts['price_range_from'];			
		}
		else {
			$price_range_from = 150000;			
		} 

		if (isset($atts['price_range_to'])) {
			$price_range_to = $atts['price_range_to'];			
		}
		else {
			$price_range_to = 300000000;			
		} 

		// LENGTH DEFAULTS

		if (isset($atts['length_range_from'])) {
			$length_range_from = $atts['length_range_from'];			
		}
		else {
			$length_range_from = 20;			
		} 

		if (isset($atts['length_range_to'])) {
			$length_range_to = $atts['length_range_to'];			
		}
		else {
			$length_range_to = 140;			
		} 
	?>

	function formatNumber(valNUM) {
		valNUM = Number(valNUM);

		return (valNUM).toLocaleString(
		  'en-US', 
		  { minimumFractionDigits: 2 }
		);
	}

	jQuery(document).ready(function() {

		jQuery('form[data-input-values]').each(function() {

			var values=jQuery(this).data('input-values');

			run_through_and_sync_form(jQuery(this), values);

		});

		var PriceRangeEle = document.getElementById('price-range-sliders');

		noUiSlider.create(PriceRangeEle, {
		    start: [
		    	((jQuery('input[name=pricerange_from]').val())?jQuery('input[name=pricerange_from]').val():<?= $price_range_from ?>), 
		    	((jQuery('input[name=pricerange_to]').val())?jQuery('input[name=pricerange_to]').val():<?= $price_range_to ?>)
		    ],
		    range: {
		        'min': [<?= $price_range_from ?>],
		        'max': [<?= $price_range_to ?>]
		    },
		    step: 500,
		    //tooltips: true,
		});

		PriceRangeEle.noUiSlider.on('update', function( values, handle ) {

			jQuery('input[name=pricerange_from]').val(values[0]);
			jQuery('input[name=pricerange_to]').val(values[1]);

			jQuery('#display-price-range').text(' ('+ (formatNumber( values[0] )) +' - '+ (formatNumber( values[1] )) + ')');

		});

		var LengthRangeEle = document.getElementById('length-range-sliders');

		noUiSlider.create(LengthRangeEle, {
		    start: [
		    	((jQuery('input[name=loa_from]').val())?jQuery('input[name=loa_from]').val():<?= $length_range_from ?>), 
		    	((jQuery('input[name=loa_to]').val())?jQuery('input[name=loa_to]').val():<?= $length_range_to ?>)
		    ],
		    range: {
		        'min': [<?= $length_range_from ?>],
		        'max': [<?= $length_range_to ?>]
		    },
		    step: 5,
		    //tooltips: true,
		});

		LengthRangeEle.noUiSlider.on('update', function( values, handle ) {

			jQuery('input[name=loa_from]').val(values[0]);
			jQuery('input[name=loa_to]').val(values[1]);

			jQuery('#display-length-range').text(' ('+ values[0] +' - '+ values[1] + ')');

		});

	});

</script>