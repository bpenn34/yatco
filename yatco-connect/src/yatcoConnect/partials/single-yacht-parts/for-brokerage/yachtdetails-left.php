<div id="single-yacht-content">
	<div class="ycd-top-details">
		<h1 class="ycd-title" style="line-height: 1.25;">
			<?php 
				$seo_heading = $data->BasicInfo->BoatName .' - '. $data->BasicInfo->YearBuilt .' '. $data->Dimensions->LOAFeet .' '. $data->BasicInfo->Builder .' '. $data->BasicInfo->Model .' <br>'. $data->BasicInfo->City . ' ' . $data->BasicInfo->State .' '. $data->BasicInfo->Country .' <br> ';

				if ($options->is_euro_site) {
					$seo_heading.=$data->BasicInfo->AskingPriceFormattedEuro;
				}   
				else {
					$seo_heading.=$data->BasicInfo->AskingPriceFormatted;
				}

				echo apply_filters('yatco_seo_heading_yacht', $seo_heading);
			?>	
		</h1>

		<?php if( isset($data->Result->AcceptsCrypto) && $data->Result->AcceptsCrypto ) : ?>
			<div class="bitcoin-here" title="crypto currecny accepted"></div>
		<?php endif; ?>
	</div>

	<hr>

	<div id="single-yach-read-more">
		<div id='overview'></div>

		<div id="ycd-description" class="ag-readmore">
			<?php 
				$seo_address = apply_filters('yatco_full_address', $data->BasicInfo, false, true);

				$seo_sentence = sprintf('Yacht for sale is a %s %s %s "%s" %s %s in %s. ',
					$data->BasicInfo->YearBuilt,
					$data->BasicInfo->Builder,
					$data->Dimensions->LOAFeet,
					$data->BasicInfo->BoatName,
					$data->BasicInfo->Model,
					$data->BasicInfo->MainCategory,
					$seo_address
				);

				$seo_second_sentence = '';

				$vessel_short_description = $data->VD->VesselDescriptionShortDescriptionNoStyles;

				$vessel_teaser = $data->VD->VesselDescriptionInternalTeaser;

				if ($vessel_teaser == $vessel_short_description || $vessel_teaser == 'N/A') {

					$vessel_teaser = '';

				}

				$seo_first_sentence = apply_filters('yatco_seo_sentence_yacht', $seo_sentence);

				$seo_second_sentence = apply_filters('yatco_seo_second_sentence_yacht', $seo_second_sentence);

				$seo_last_sentence = apply_filters('yatco_seo_last_sentence_yacht', '');

				if (!empty($seo_first_sentence)) {
					echo $seo_first_sentence;
				}

				if (!empty($seo_second_sentence)) {
					echo $seo_second_sentence;
				}

				echo '<br><br>';

				if (!empty($vessel_teaser)) {
					echo $vessel_teaser.'<br><br>';
				}
				
				if (!empty($vessel_short_description)) {
					echo $vessel_short_description.'<br><br>';
				}

				echo $seo_last_sentence;
			?>
		</div>
	</div>

	<hr>

	<div class="mobile-broker yt-hidden-lg">
		
		<?php include __DIR__.'/form.php'; ?>

		<!-- 

			<hr>

			<div class="">
				<p style="margin: 0px;padding-bottom: 10px; color: #003470; font-size: 14px; font-weight: bold; text-transform: uppercase;">
				Want A PDF Version?</p>

				<a href="<?= $data->Result->PDFUrl ?>" target="_blank" style="font-weight: bold; text-decoration: none;">
					<input type="button" class="yt-btn yt-btn-block" value="Download Brochure" style="border-radius: 0px" />
				</a>
			</div>

			<hr>
		-->
		
	</div>

	<div id="yc-details-specs">
		<div id="features"></div>

		<div class="yt-row">
			<?php $class_detail_spec_col="yt-col-6 yt-col-md-3"; ?>
			
			<div class="<?= $class_detail_spec_col ?>">
				<?=
					apply_filters('yatco_single_yacht_format_key_spec_table', 
						'LENGTH',
						($data->is_eur || $options->is_euro_site)?$data->Dimensions->LOAMeter:$data->Dimensions->LOAFeet,
						''
					); 
				?>
			</div>
			<div class="<?= $class_detail_spec_col ?>">
				<?= apply_filters('yatco_single_yacht_format_key_spec_table', 'BUILDER', $data->BasicInfo->Builder); ?>
			</div>
			<div class="<?= $class_detail_spec_col ?>">
				<?php if ($options->is_euro_site) : ?>
					<?= apply_filters('yatco_single_yacht_format_key_spec_table', 'ASKING PRICE', $data->BasicInfo->AskingPriceFormattedEuro); ?>
				<?php else : ?>
					<?= apply_filters('yatco_single_yacht_format_key_spec_table', 'ASKING PRICE', $data->BasicInfo->AskingPriceFormatted); ?>
				<?php endif; ?>
			</div>
			<div class="<?= $class_detail_spec_col ?>">
				<?php
					$label_built_year = 'BUILT'.((isset($data->BasicInfo->RefitYear) && ! empty($data->BasicInfo->RefitYear))?' (REFIT)':'');

					echo apply_filters('yatco_single_yacht_format_key_spec_table', $label_built_year, $data->BasicInfo->YearBuilt, $data->BasicInfo->RefitYear); 
				?>
			</div>
		</div>
		<div class="yt-row">
			<div class="<?= $class_detail_spec_col ?>">
				<?php 
					echo apply_filters(
						'yatco_single_yacht_format_key_spec_table', 
						'BEAM', 
						($data->is_eur)?$data->Dimensions->BeamMeter:$data->Dimensions->BeamFeet,
						''
					); 
				?>
			</div>
			<div class="<?= $class_detail_spec_col ?>">
				<?php 
					echo apply_filters(
						'yatco_single_yacht_format_key_spec_table', 
						'DRAFT', 
						($data->is_eur)?$data->Dimensions->MinDraftMeter:$data->Dimensions->MinDraftFeet, 
						''
					); 
				?>
			</div>
			<div class="<?= $class_detail_spec_col ?>">
				<?= apply_filters('yatco_single_yacht_format_key_spec_table', 'MAX SPEED', $data->SpeedWeight->MaxSpeed, ''); ?>
			</div>
			<div class="<?= $class_detail_spec_col ?>">
				<?= apply_filters('yatco_single_yacht_format_key_spec_table', 'CRUISING SPEED', $data->SpeedWeight->CruiseSpeed, ''); ?>
			</div>
		</div>
		<div class="yt-row">
			<div class="<?= $class_detail_spec_col ?>">
				<?= apply_filters('yatco_single_yacht_format_key_spec_table', 'CABINS', $data->Accommodations->StateRooms, ''); ?>
			</div>
			<div class="<?= $class_detail_spec_col ?>">
				<?= apply_filters('yatco_single_yacht_format_key_spec_table', 'GROSS TONNAGE', $data->SpeedWeight->GrossTonnage , ''); ?>
			</div>
			<div class="<?= $class_detail_spec_col ?>">
				<?php 
					echo apply_filters(
						'yatco_single_yacht_format_key_spec_table', 
						'DISPLACEMENT', 
						($data->is_eur)?$data->SpeedWeight->DisplacementKG:$data->SpeedWeight->DisplacementLBS,
						''
					);
				 ?>
			</div>
			<div class="<?= $class_detail_spec_col ?>">
				<?php if (strlen($data->BasicInfo->City.' '.$data->BasicInfo->State.' '.$data->BasicInfo->Country) >= 2) : ?>
					<?= apply_filters('yatco_single_yacht_format_key_spec_table', 'LOCATION', apply_filters('yatco_full_address', $data->BasicInfo, false, true), ''); ?>
				<?php else : ?>
					<?= apply_filters('yatco_single_yacht_format_key_spec_table', 'LOCATION', 'N/A', ''); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>