<div id="single-yacht-content">
	<div class="ycd-top-details">
		<p style="text-align: center;">
			<img src="<?= YATCO_PLUGIN_ASSETS.'/img/Yatco-Logo-ice-Blue.png' ?>" alt="yatco anchor icon" title="yatco anchor icon" style="display: inline-block;" class="yatco-anchor-icon">
		</p>

		<h1 class="ycd-title" style="line-height: 1.25;">
			<?php 

				/*// {Charter Yacht Name} {Builder} {Yacht Type} - {Charter Type} {Length} Yacht for Charter in {Destination/Area/Region}
				$seo_heading = sprintf(
					'%s %s %s - %s %s Yacht for Charter in %s',
					$data->VesselName,
					$data->BuilderName,
					$data->VesselCategoryText,

					$data->VesselType,
					$data->LOA_Format,

					($data->RegionName && ! empty($data->RegionName))?$data->RegionName:join(', ', $data->ParentLocations)
				);*/
				if(isset($data->RatesList) && !empty($data->RatesList)){
					$seo_heading=$data->VesselName.'<br>from '. $data->RatesList[0]->LowRateFormat.' '.$data->RatesList[0]->Frequency;
				}else{
					$seo_heading=$data->VesselName.'<br>by '.$data->BuilderName;
				}
				echo apply_filters('yatco_seo_heading_charter', $seo_heading);
			?>
			
		</h1>
	</div>

	<hr>

	<div id="single-yach-read-more">
		<div id='overview'></div>

		<div id="ycd-description" class="ag-readmore">
			<?php 
				echo apply_filters('yatco_seo_sentence_charter', ''); 

				echo ' ';

				echo apply_filters('yatco_charter_description', $data->VesselDescriptionShortDescription);		

				echo ' ';
				
				echo apply_filters('yatco_seo_last_sentence_charter', ''); 
			?>
		</div>
	</div>

	<hr>

	<div id="features"></div>

	<div class="charter-specs">

		<div class="yt-row">
			<div class="yt-col-12" style="columns: 2;">

				<?php if ($options->is_euro_site) : ?>
					<?= apply_filters('yatco_single_charter_format_key_spec_table', 'LENGTH', $data->LOAMeters.'m'); ?>	
				<?php else: ?>
					<?= apply_filters('yatco_single_charter_format_key_spec_table', 'LENGTH', $data->LOA_Format); ?>	
				<?php endif; ?>

				<?php
				if(isset($data->BuilderName)){
					echo apply_filters('yatco_single_charter_format_key_spec_table', 'BUILDER', $data->BuilderName);
				}

				if(isset($data->VesselType)){
					echo apply_filters('yatco_single_charter_format_key_spec_table', 'YACHT TYPE', $data->VesselType);
				}
				
				if(isset($data->MaxSpeed)){
					echo apply_filters('yatco_single_charter_format_key_spec_table', 'MAX SPEED', $data->MaxSpeed);
				}
				
				if(isset($data->CruisingSpeed)){
					echo apply_filters('yatco_single_charter_format_key_spec_table', 'CRUISING SPEED', $data->CruisingSpeed);
				}
				
				if(isset($data->Beam_Format)){
					echo apply_filters('yatco_single_charter_format_key_spec_table', 'BEAM', $data->Beam_Format);
				}
				
				if(isset($data->Draft_Format)){
					echo apply_filters('yatco_single_charter_format_key_spec_table', 'DRAFT', $data->Draft_Format);
				}
				
				if(isset($data->GrossTons)){
					echo apply_filters('yatco_single_charter_format_key_spec_table', 'GT', $data->GrossTons);
				}
				
				$RefitYear = ( ( isset( $data->RefitYear ) && ! empty( $data->RefitYear ) ) ? $data->RefitYear : '' );
				$label_built_year = 'BUILT'.(( !empty( $RefitYear )) ?' (REFIT)':'');

				echo apply_filters(
					'yatco_single_charter_format_key_spec_table', 
					$label_built_year,
					$data->ModelYear,
					$RefitYear
				);

				if(isset($data->TotalStateRooms)){
					echo apply_filters('yatco_single_charter_format_key_spec_table', 'STATEROOMS', $data->TotalStateRooms);
				}
				if(isset($data->NumberOfGuests)){
					echo apply_filters('yatco_single_charter_format_key_spec_table', 'CRUISING GUESTS', $data->NumberOfGuests);
				}
				if(isset($data->VesselGuests->MaxSleeps)){
					echo apply_filters('yatco_single_charter_format_key_spec_table', 'GUESTS SLEEPS', $data->VesselGuests->MaxSleeps);
				}
				if(isset($data->NumberOfCrew)){
					echo apply_filters('yatco_single_charter_format_key_spec_table', 'CREW', $data->NumberOfCrew);
				}
				
				?>
			</div>
		</div>
		

	</div>

	<?php if (count($data->Staterooms) > 0 ) : ?>

		<hr>

		<h3>Staterooms</h3>

		<div class="charter-specs" id="stateroom-list">
			<div class="yt-row" style="margin-top: 30px;">
				<?php foreach ($data->Staterooms as $stateroom) : if (!empty($stateroom->AccTypeName)) : ?>
					<div class="yt-col-12 yt-col-md-6">

						<?php 
						/*	$size = '';

							if ($stateroom->StateroomDimsLengthUnit == 1) {
								$size = $stateroom->StateroomDims1.'x'.$stateroom->StateroomDims2 . ' Feet';
							}
							elseif ($stateroom->StateroomDimsLengthUnit == 2) {
								$size = $stateroom->StateroomDims1.'x'.$stateroom->StateroomDims2 . ' Meters';
							}

						*/
						?>

						<?= apply_filters('yatco_single_charter_format_key_spec_table', 'Cabin Name', $stateroom->AccTypeName); ?>		
						<?= apply_filters('yatco_single_charter_format_key_spec_table', 'Bed', $stateroom->BedTypeName); ?>		
						<?= apply_filters('yatco_single_charter_format_key_spec_table', 'Guests', $stateroom->TTLGuest); ?>

						<hr>
						
					</div>
				<?php endif; endforeach; ?>
			</div>

		</div>

	<?php endif; ?>

	<h3>Amenities</h3>

	<div class="charter-specs charter-amenities">
		<div class="yt-row">
			<div class="yt-col-12" style="columns: 2;">
				<?php 
					//var_dump($charter_amenities);
				?>

				<?php 
					$groups = ($charter_amenities->Groups);

					$allowed_items = [

						"AT-ANCHOR STABILIZERS",
						"UNDERWAY STABILIZERS",
						"HOT TUB / JACUZZI",
						"SAUNA / STEAM ROOM",
						"SPA / MASSAGE ROOM",
						"GYM",
						"SWIMMING POOL",
						"GOLF",
						"HELICOPTER",
						"ELEVATOR / LIFT"
					];

				?>


				<?php foreach ($groups as $charter_am) : 
					$print_arr = array_map(
						function($item) use ($allowed_items) {
							if ($item->Value && in_array(strtoupper($item->AmenityName), $allowed_items)) {
								return $item->AmenityName;
							}

							return '';
						}, 

						$charter_am->Items
					);

					$print_arr2 = array_filter( $print_arr, function($item) {return ($item == false || $item == '')?false:true;});

					if (count($print_arr2) > 0) {
						echo '<div class="charter-spec" style="margin-bottom: 20px">';
							
							echo '<div style="width: 100%; display: block; margin-bottom: 5px;" class="charter-spec-label">'. $charter_am->AmenityGroupName .'</div> 
							  <span class="charter-spec-val">'. join(', ', $print_arr2) .'</span>';

							
						echo '</div>';
					}
					?>

				<?php endforeach; ?>
			</div>

			<div class="yt-col-12">
				<p style="color: #004877; font-weight: 800;">*For a complete list of amenities and features, please, contact the listing agent.</p>
			</div>
		</div>
	</div>

	<h3>Toys And Tenders</h3>

	<div class="charter-specs charter-toys-and-tenders">
		<div class="yt-row">
			<div class="yt-col-12" style="columns: 2;">

				<?php 

					if (count($charter_toys) > 0) {
						echo '<div class="charter-spec" style="margin-bottom: 20px">';
						
							echo '<div style="width: 100%; display: block; margin-bottom: 5px;" class="charter-spec-label">TOYS</div> 
							  <span class="charter-spec-val">'. join(", ", $charter_toys) .'</span>';

						echo '</div>';
					}
				?>

				<?php 

					if (count($charter_tenders) > 0) {

						echo '<div class="charter-spec" style="margin-bottom: 20px">';
						
							echo '<div style="width: 100%; display: block; margin-bottom: 5px;" class="charter-spec-label">TENDERS</div> 
							  <span class="charter-spec-val">'. $charter_tenders[0]->Details .'</span>';

						echo '</div>';

					}

				?>

			</div>
		</div>
	</div>

	<h3>Rates</h3>

	<div class="charter-specs" id="rates-list">

		<?php if (count($data->RatesList) == 0) : ?>

			<p>Contact the listing agent for rates.<!-- ... <a href="">click here</a> --></p>

		<?php endif; ?>

		<?php foreach ($data->RatesList as $listed_rate) : ?>

			<?php 
				$locations = array_map(function($l) {return $l->Name;}, $listed_rate->RateLocations);
				sort($locations);
			?>
				
			<div class="yt-row" style="margin-top: 30px;">
				<div class="yt-col-12 yt-col-md-6">

					<?= apply_filters('yatco_single_charter_format_key_spec_table', 'SEASON', $listed_rate->Season); ?>		
					<?php //echo apply_filters('yatco_single_charter_format_key_spec_table', 'Price', /*$listed_rate->LowRateFormat.' - '.*/$listed_rate->HighRateFormat); ?>

					<?php if ($options->is_euro_site) : ?>
						<?= apply_filters('yatco_single_charter_format_key_spec_table', 'PRICE (low)', $listed_rate->LowRateFormatEuro.'/'.$listed_rate->Frequency); ?>

						<?= apply_filters('yatco_single_charter_format_key_spec_table', 'PRICE (high)', $listed_rate->HighRateFormatEuro.'/'.$listed_rate->Frequency); ?>

					<?php else: ?>
						<?= apply_filters('yatco_single_charter_format_key_spec_table', 'PRICE (low)', $listed_rate->LowRateFormat.'/'.$listed_rate->Frequency); ?>

						<?= apply_filters('yatco_single_charter_format_key_spec_table', 'PRICE (high)', $listed_rate->HighRateFormat.'/'.$listed_rate->Frequency); ?>

					<?php endif; ?>
				</div>

				<div class="yt-col-12 yt-col-md-6">
					
					<?php 
						echo '<div class="charter-spec" style="margin-bottom: 20px">';
						
							echo '<div style="width: 100%; display: block; margin-bottom: 5px;" class="charter-spec-label">LOCATION</div> 
							  <span class="charter-spec-val">'. join(", ", $locations) .'</span>';

						echo '</div>';
					?>	
				</div>
			</div>
		<?php endforeach; ?>

	</div>

	




</div>