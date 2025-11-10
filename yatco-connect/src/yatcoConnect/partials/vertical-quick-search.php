<div class="yatco-shortcode-yachts-quick-search yatco-shortcode-vertical-yachts-quick-search yatco-shortcode-search-form">
	<?php
		$input_col_class = 'yt-col-12';
	?>
	
	<form id="yacht-vertial-quick-search" action="<?= $action_url ?>" method="GET" data-input-values="<?= esc_attr(json_encode($_params)) ?>">

		<input type="hidden" name="scroll-to-form" value="true">

		<div class="yt-row">
			<div class="<?= $input_col_class ?>">
				<div class="col-input">
					<label>Builder</label>
				
					<?php if ($attributes['builder_input_type'] == 'text') : ?>
						<input type="text" name="Builder" placeholder="Enter Builder" class="yt-input" list="builders_list">

					<?php elseif ($attributes['builder_input_type'] == 'select'): ?>
						<select class="yt-input" name="BuiilderID" data-yatco-fill-options="Builders" data-yatco-empty-after-fill="true" data-value="<?= apply_filters('yatco_data_value', 'BuilderID', $_params, '2563') ?>">
							<option value="">All Builders</option>
						</select>

					<?php endif; ?>
				</div>
			</div>

			<div class="<?= $input_col_class ?>">
				<div class="more-space-for-option-above">
					<select name="LengthUnit" class="yt-input yt-above-input">
		                <option value="1" selected="">FT</option>
		                <option value="2">M</option>
		            </select>
		            
		            <div class="col-input">
						<label>Length</label>
						<input type="number" name="loa_from" placeholder="Min" class="yt-input">
						<input type="number" name="loa_to" placeholder="Max" class="yt-input">
		            </div>
		        </div>
			</div>

			<div class="<?= $input_col_class ?>">
				<div class="more-space-for-option-above">
					<select name="CurrencyType" class="yt-input yt-above-input" data-yatco-fill-options="Currency" data-yatco-empty-after-fill="true" data-value="<?= apply_filters('yatco_data_value', 'CurrencyType', $_params, '2563') ?>">
		            	<option selected="selected" value="USD">USD</option>
					</select>

					<div class="col-input">
						<label>Price</label>
						<input type="number" name="pricerange_from" placeholder="Min" class="yt-input">
						<input type="number" name="pricerange_to" placeholder="Max" class="yt-input">
					</div>
				</div>
			</div>

			<div class="<?= $input_col_class ?>">
				<div class="col-input">
					<label>Year</label>
					<input type="number" name="year_from" placeholder="Min" class="yt-input">
					<input type="number" name="year_to" placeholder="Max" class="yt-input">
				</div>
			</div>
		</div>

		<div class="yt-text-center" style="margin-bottom: 34px;">
			<a href="<?= $options->getOption('yacht_search_url'); ?>?openSearchForm=yes">Advanced Search +</a>
		</div>

		<div style="text-align: center; margin-top: 15px;">
			<input type="submit" value="SEARCH YACHTS" class="yt-btn">
		</div>


		<datalist id="builders_list"></datalist>
	</form>
</div>