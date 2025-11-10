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

				<div class="yt-above-input" style="top: -10px; right: 20px;">
		            <select name="CurrencyID" class="yt-input" data-yatco-fill-options="Currency" data-yatco-empty-after-fill="true" data-value="<?= apply_filters('yatco_data_value', 'CurrencyID', $_params, '2563') ?>">
		            	<option selected="selected" value="2563">USD</option>
					</select>

					<select name="RateFrequency" class="yt-input" data-yatco-fill-options="CharterFreqTypes" data-yatco-empty-after-fill="true"  data-value="<?= apply_filters('yatco_data_value', 'RateFrequency', $_params, '3416') ?>">
		            	
					</select>
				</div>

				<div id="price-range-sliders"></div>

				<input type="hidden" name="RateRange_from">
				<input type="hidden" name="RateRange_to">
			</div>

			<div class="yt-col-6">
				<label>Length <span id="display-length-range"></span></label>

				<div id="length-range-sliders"></div>

				<div class="yt-above-input"  style="top: -10px; right: 20px;">
		            <select class="yt-input LengthUnit" name="LOAMetric">
		              	<option value="1">FT</option>
			            <option value="2">M</option>
		            </select>
		        </div>

				<input type="hidden" name="LoaRange_from">
		        <input type="hidden" name="LoaRange_to">  
			</div>
	    </div>

	    <div class="yt-row">
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

	    	<div class="<?= $input_col_class ?>">
				<div class="col-input">
					<label value="">Destination</label>

					<!-- <input type="text" name="Destinations" class="yt-input"> -->

					<select class="yt-input" name="Location" data-yatco-fill-options="DDCharterDestinations" data-yatco-empty-after-fill="false" data-value="<?= apply_filters('yatco_data_value', 'Location', $_params) ?>">
					    	<option value="">Any</option>
					    </select>
				</div>
	        </div>
	        
	        <div class="<?= $input_col_class ?>">
	        	<div class="col-input">
	                <label>Vessel Name</label>
	                <input type="text" name="VesselName" class="yt-input">
	        	</div>
	        </div>
	    </div>

        <div class="SumbitButton_Section"> 
            <input type="submit" class="yt-btn" value="SEARCH CHARTERS">

           <div class="btns-after-submit">
	           	<button type="button" onclick="resetSearchForm(event)">Reset Search</button>
           </div>
        </div>
	</form>
</div>

<link rel="stylesheet" type="text/css" href="<?= YATCO_PLUGIN_ASSETS ?>/scss/3rd-party/nouislider.css">
<script type="text/javascript" src="<?= YATCO_PLUGIN_ASSETS ?>js/3rd-party/nouislider.js"></script>

<script type="text/javascript">
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
		    	((jQuery('input[name=RateRange_from]').val())?jQuery('input[name=RateRange_from]').val():1000), 
		    	((jQuery('input[name=RateRange_to]').val())?jQuery('input[name=RateRange_to]').val():300000000)
		    ],
		    range: {
		        'min': [1000],
		        'max': [300000000]
		    },
		    step: 500,
		    //tooltips: true,
		});

		PriceRangeEle.noUiSlider.on('update', function( values, handle ) {

			jQuery('input[name=RateRange_from]').val(values[0]);
			jQuery('input[name=RateRange_to]').val(values[1]);

			jQuery('#display-price-range').text(' ('+ (formatNumber( values[0] )) +' - '+ (formatNumber( values[1] )) + ')');

		});

		var LengthRangeEle = document.getElementById('length-range-sliders');

		noUiSlider.create(LengthRangeEle, {
		    start: [
		    	((jQuery('input[name=LoaRange_from]').val())?jQuery('input[name=LoaRange_from]').val():20), 
		    	((jQuery('input[name=LoaRange_to]').val())?jQuery('input[name=LoaRange_to]').val():140)
		    ],
		    range: {
		        'min': [20],
		        'max': [140]
		    },
		    step: 5,
		    //tooltips: true,
		});

		LengthRangeEle.noUiSlider.on('update', function( values, handle ) {

			jQuery('input[name=LoaRange_from]').val(values[0]);
			jQuery('input[name=LoaRange_to]').val(values[1]);

			jQuery('#display-length-range').text(' ('+ values[0] +' - '+ values[1] + ')');

		});

	});

</script>