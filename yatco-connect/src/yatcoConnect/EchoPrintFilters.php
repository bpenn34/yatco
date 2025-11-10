<?php 
	class yatcoConnect_EchoPrintFilters {


		public function __construct() {

			$this->option = new yatcoConnect_Options();

		}

		public function add_actions_and_filters() {

			add_filter('yatco_single_yacht_format_key_spec_table', [$this, 'single_yacht_format_key_spec_table'], 3, 10);
			// add_filter('yatco_single_yacht_format_key_spec', [$this, 'yatco_single_yacht_format_key_spec']);

			add_filter('yatco_single_charter_format_key_spec_table', [$this, 'single_charter_format_key_spec_table'], 3, 10);

			add_filter('yatco_full_address', [$this, 'yatco_full_address'], 2, 10);

			add_filter('yatco_data_value', [$this, 'yatco_data_value'], 3, 10);

			add_filter('yatco_range', [$this, 'yatco_range'], 3, 10);

			add_filter( 'wp_kses_allowed_html', [$this, 'allow_svg_attributes'], 10, 2 );

		}

		public function allow_svg_attributes( $allowed_tags, $context ) {
			if ( $context === 'post' ) {
				$allowed_tags['svg'] = [
					'xmlns' => true,
					'xmlns:xlink' => true,
					'xml:space' => true,
					'width' => true,
					'height' => true,
					'viewbox' => true,
					'version' => true,
					'style' => true,
					'fill' => true
				];
				$allowed_tags['g'] = [
					'transform' => true,
					'serif:id' => true,
				];
				$allowed_tags['path'] = [
					'd' => true,
					'style' => true,
					'stroke' => true,
					'stroke-width' => true,
					'stroke-linejoin' => true,
				];
				
				$tags = array('rect', 'circle', 'ellipse', 'polygon', 'polyline');
				foreach($tags as $tag){
					$allowed_tags[$tag] = [
						'x' => true,
						'y' => true,
						'rx' => true,
						'width' => true,
						'height' => true,
						'style' => true,
						'stroke' => true,
						'stroke-width' => true
					];
				}
			}

			return $allowed_tags;
		}

		public function single_yacht_format_key_spec_table($label, $val, $mVal = '') {

			ob_start();

				echo '<div class="ycd-specs">';
					if (isset($val) && ! empty($val)) {
						echo '<span class="ycd-specs-title">'. $label .'</span> 
						  <span class="ycd-specs-loa">'. $val .'</span>';

						if (isset($mVal) && !empty($mVal)) {
							echo ' (<span class="ycd-specs-loa-mt">'. $mVal .'</span>)';
						}
					}
					else {

						echo '<span class="ycd-specs-title">'. $label .'</span> 
						  <span class="ycd-specs-loa">N/A</span>';

					}		
				echo '</div>';

			return ob_get_clean();

		}

		public function single_charter_format_key_spec_table($label, $val, $mVal = '') {

			ob_start();

				echo '<div class="charter-spec">';
					if (
						isset($val) && ! empty($val) 
						&& $val != '0' 
						&& $val != "0' (0.00m)"
						&& $val != "0' (0.00m) - 0' (0.00m)"
					) {
						echo '<span class="charter-spec-label">'. $label .'</span> 
						  <span class="charter-spec-val">'. $val .'</span>';

						if (isset($mVal) && !empty($mVal) && $mVal != '0') {
							echo ' (<span class="charter-spec-sub-val">'. $mVal .'</span>)';
						}
					}
					/*else {

						echo '<span class="charter-spec-label">'. $label .'</span> 
						  <span class="charter-spec-val">N/A</span>';

					}		*/
				echo '</div>';

			return ob_get_clean();

		}

		/*
		public function Single_yacht_format_key_spec($label, $val) {

			if (is_numeric($val) && $val < 0) {
				echo "<p>". $label .": <span class='pull-right'>". $val ."</span></p>";
			}
			elseif (!empty($val) && ! is_null($val)) {
				echo "<p>". $label .": <span class='pull-right'>". $val ."</span></p>";
			}


		}*/
		
		public function yatco_range($from, $to, $USD) {

			ob_start();

				if (
					$from && $to
					&&
					$from != '0' && $to != '0'
				) {

					if ($from == $to) {
						if ($USD == true) {
							echo '$'.number_format($from);	
						}
						else {
							echo $from;
						}
					}
					else {
						if ($USD == true) {
							echo '$'.number_format($from);
							echo ' - ';
							echo '$'.number_format($to);
						}
						else {
							echo $from;
							echo ' - ';
							echo $to;
						}
					}
				} 
				else {
					echo 'N/A';
				}

			return ob_get_clean();

		}
		
		public function yatco_full_address($data, $address1and2 = false, $inline = false) {



			ob_start();
				$print='';

				// Lowercase

				if (isset($data->LocationRegion)) {
					$data->Region = $data->LocationRegion;
				}

				if (isset($data->LocationCountry)) {
					$data->Country = $data->LocationCountry;
				}

				if (isset($data->LocationState)) {
					$data->State = $data->LocationState;
				}

				if (isset($data->LocationCity)) {
					$data->City = $data->LocationCity;
				}

				if (isset($data->city)) {$data->City = $data->city;}
				if (isset($data->state)) {$data->State = $data->state;}
				if (isset($data->country)) {$data->Country = $data->country;}

				if (isset($data->Region) && ! isset($data->Country)) {

					print $data->Region;

					return ob_get_clean();
				}

				// Adding To Print
				if ($address1and2) {
					if (isset($data->Address1) && !empty($data->Address1)) {
						$print.=$data->Address1.' ';
						
						if (! $inline) {$print.='<br>';}
					}

					if (isset($data->Address2) && !empty($data->Address1)) {
						$print.=$data->Address2.' ';
						
						if (! $inline) {$print.='<br>';}
					}
				}

				if (isset($data->City) && !empty($data->City) && $data->City != ' ') {
					$print.=$data->City.', ';
				}

				if (isset($data->State) && !empty($data->State) && $data->State != ' ') {
					$print.=$data->State.', ';
				}

				if (isset($data->Country) && $data->Country == 'United States') {
					if (isset($data->PostalCode) && !empty($data->PostalCode)) {
						$print.=$data->PostalCode.' ';
					}
					elseif (isset($data->Zip) && !empty($data->Zip)) {
						$print.=$data->Zip.' ';
					}
				}

				if (! $inline) {$print.= '<br>';}

				if (isset($data->Country) && !empty($data->Country)) {
					$print.= ($data->Country == 'United States')?'USA':$data->Country;
				}
				elseif (isset($data->country) && !empty($data->country)) {
					$print.= ($data->country == 'United States')?'USA':$data->country;
				}

				//echo str_replace(array(' ""', ' .', ', '), array(' ', '.', ''), preg_replace('/\s,?\s+/', ' ', $print));

				echo $print;

			return ob_get_clean();

		}

		public function yatco_data_value($prop, $data = [], $default = '') {

			$data = (array) $data;

			$prop_lowercase = strtolower($prop);

			if (isset($data[ $prop ]) && ! empty($data[ $prop ])) {
				return $data[ $prop ];
			}
			else if (isset($data[ $prop_lowercase ]) && ! empty($data[ $prop_lowercase ])) {
				return $data[ $prop_lowercase ];
			}
			else {
				return $default;
			}

		}
	} 