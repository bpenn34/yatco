<div class="yatco-shortcode-yachts-quick-search yatco-shortcode-search-form yt-search-form-design2">
	<?php
		$input_col_class = 'col-lg-4 col-md-6 col-12';
	?>
    
	<form id="yacht-quick-search" action="<?= $action_url ?>" method="GET" data-input-values="<?= esc_attr(json_encode($_params)) ?>">

		<input type="hidden" name="scroll-to-form" value="true">

		<div class="row">
			<div class="<?= $input_col_class ?>">
				<div class="col-input boatdeck-fields">
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

			<div class="<?= $input_col_class ?>">
                <div class="col-input boatdeck-fields">
                    <label>Price</label>
                    <input type="text" name="pricerange_from" placeholder="Min" class="yt-input extra-attrs-fields">
                    <input type="text" name="pricerange_to" placeholder="Max" class="yt-input extra-attrs-fields">
                    <select name="CurrencyType" class="yt-input yt-additional-attr" data-yatco-fill-options="Currency" data-yatco-empty-after-fill="true" data-value="<?= apply_filters('yatco_data_value', 'CurrencyType', $_params, '2563') ?>">
                        <option selected="selected" value="USD">USD</option>
                    </select>
                </div>
			</div>

			<div class="<?= $input_col_class ?>">
				<div class="col-input boatdeck-fields">
					<label>Year</label>
					<input type="number" name="year_from" placeholder="Min" class="yt-input">
					<input type="number" name="year_to" placeholder="Max" class="yt-input">
				</div>
			</div>

            <div class="<?= $input_col_class ?>">
                <input type="submit" value="SEARCH YACHTS" class="yt-btn">
            </div>
		</div>

		<div class="advanced-search-link">
			<a href="<?= $options->getOption('yacht_search_url'); ?>?openSearchForm=yes">Advanced Search +</a>
		</div>

		<datalist id="builders_list"></datalist>
	</form>
</div>