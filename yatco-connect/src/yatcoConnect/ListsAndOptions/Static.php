<?php

	class yatcoConnect_ListsAndOptions_Static {
		public function __construct() {

			$this->data=new yatcoConnect_ApiToBoss();
			$this->options=new yatcoConnect_Options();

		}

		public function add_actions_and_filters() {

			add_filter('yatco_fill_options_and_lists', [$this, 'version_one_options'], 10, 2);

		}

		public function version_one_options($results, $label) {

			switch ( $label ) {
				case 'DropDownYear':
					$results = [];

					for ($i=1880; $i <= (date("Y")+4); $i++) {
						$results[]=[
							'id' => $i,
							'text' => (string) $i
						];
					}

					break;

				case 'DropDownPrice':
					$results = [
						[
							'id' => 25*1000,
							'text' => '25K'
						],
						[
							'id' => 50*1000,
							'text' => '50k'
						],
						[
							'id' => 100*1000,
							'text' => '100k'
						],
						[
							'id' => 300*1000,
							'text' => '300k'
						],
						[
							'id' => 500*1000,
							'text' => '500k'
						],
						[
							'id' => 700*1000,
							'text' => '700k'
						],
						[
							'id' => 900*1000,
							'text' => '900k'
						],
						[
							'id' => 1000*1000,
							'text' => '1M'
						],
						[
							'id' => 5000*1000,
							'text' => '5M'
						],
						[
							'id' => 10*1000*1000,
							'text' => '10M'
						],
						[
							'id' => 20*1000*1000,
							'text' => '20M'
						],
						[
							'id' => 30*1000*1000,
							'text' => '30M'
						],
						[
							'id' => 40*1000*1000,
							'text' => '40M'
						],
						[
							'id' => 50*1000*1000,
							'text' => '50M'
						]
					];

					break;

				case 'DropDownPriceUS':
					$results = [
						[
							'id' => 25*1000,
							'text' => '$25K'
						],
						[
							'id' => 50*1000,
							'text' => '$50k'
						],
						[
							'id' => 100*1000,
							'text' => '$100k'
						],
						[
							'id' => 300*1000,
							'text' => '$300k'
						],
						[
							'id' => 500*1000,
							'text' => '$500k'
						],
						[
							'id' => 700*1000,
							'text' => '$700k'
						],
						[
							'id' => 900*1000,
							'text' => '$900k'
						],
						[
							'id' => 1000*1000,
							'text' => '$1M'
						],
						[
							'id' => 5000*1000,
							'text' => '$5M'
						],
						[
							'id' => 10*1000*1000,
							'text' => '$10M'
						],
						[
							'id' => 20*1000*1000,
							'text' => '$20M'
						],
						[
							'id' => 30*1000*1000,
							'text' => '$30M'
						],
						[
							'id' => 40*1000*1000,
							'text' => '$40M'
						],
						[
							'id' => 50*1000*1000,
							'text' => '$50M'
						]
					];

					break;

				case 'DropDownPriceEuro':
					$results = [
						[
							'id' => 25*1000,
							'text' => '€25K'
						],
						[
							'id' => 50*1000,
							'text' => '€50k'
						],
						[
							'id' => 100*1000,
							'text' => '€100k'
						],
						[
							'id' => 300*1000,
							'text' => '€300k'
						],
						[
							'id' => 500*1000,
							'text' => '€500k'
						],
						[
							'id' => 700*1000,
							'text' => '€700k'
						],
						[
							'id' => 900*1000,
							'text' => '€900k'
						],
						[
							'id' => 1000*1000,
							'text' => '€1M'
						],
						[
							'id' => 5000*1000,
							'text' => '€5M'
						],
						[
							'id' => 10*1000*1000,
							'text' => '€10M'
						],
						[
							'id' => 20*1000*1000,
							'text' => '€20M'
						],
						[
							'id' => 30*1000*1000,
							'text' => '€30M'
						],
						[
							'id' => 40*1000*1000,
							'text' => '€40M'
						],
						[
							'id' => 50*1000*1000,
							'text' => '€50M'
						]
					];

					break;

				case 'DropDownCharterPrice':
					$results = [
						[
							'id' => 1,
							'text' => '1K'
						],
						[
							'id' => 2*1000,
							'text' => '2k'
						],
						[
							'id' => 5*1000,
							'text' => '5k'
						],
						[
							'id' => 7*1000,
							'text' => '7k'
						],
						[
							'id' => 10*1000,
							'text' => '10k'
						],
						[
							'id' => 20*1000,
							'text' => '20k'
						],
						[
							'id' => 30*1000,
							'text' => '30k'
						],

						[
							'id' => 40*1000,
							'text' => '40k'
						],
						
						[
							'id' => 50*1000,
							'text' => '50k'
						],

						[
							'id' => 70*1000,
							'text' => '70k'
						],
						
						[
							'id' => 100*1000,
							'text' => '100k'
						],
						[
							'id' => 300*1000,
							'text' => '300k'
						],
						[
							'id' => 500*1000,
							'text' => '500k'
						],
						[
							'id' => 600*1000,
							'text' => '600k'
						]
					];

					break;

				case 'DropDownLenght':
					$results = [
						[
							'id' => 5,
							'text' => '5'
						],
						[
							'id' => 10,
							'text' => '10'
						],
						[
							'id' => 15,
							'text' => '15'
						],
						[
							'id' => 20,
							'text' => '20'
						],
						[
							'id' => 25,
							'text' => '25'
						],
						[
							'id' => 30,
							'text' => '30'
						],
						[
							'id' => 35,
							'text' => '35'
						],
						[
							'id' => 40,
							'text' => '40'
						],
						[
							'id' => 45,
							'text' => '45'
						],
						[
							'id' => 50,
							'text' => '50'
						],
						[
							'id' => 55,
							'text' => '55'
						],
						[
							'id' => 60,
							'text' => '60'
						],
						[
							'id' => 65,
							'text' => '65'
						],
						[
							'id' => 70,
							'text' => "70"
						],
						[
							'id' => 75,
							'text' => "75"
						],
						[
							'id' => 80,
							'text' => "80"
						],
						[
							'id' => 90,
							'text' => "90"
						],
						[
							'id' => 95,
							'text' => "95"
						],
						[
							'id' => 100,
							'text' => '100'
						],
						[
							'id' => 200,
							'text' => '200'
						],
						[
							'id' => 300,
							'text' => '300'
						],
						[
							'id' => 400,
							'text' => '400'
						],
						[
							'id' => 500,
							'text' => '500'
						]
					];

					break;

				case 'DropDownLenghtUS':
					$results = [
						[
							'id' => 5,
							'text' => "5'"
						],
						[
							'id' => 10,
							'text' => "10'"
						],
						[
							'id' => 15,
							'text' => "15'"
						],
						[
							'id' => 30,
							'text' => "30'"
						],
						[
							'id' => 60,
							'text' => "60'"
						],
						[
							'id' => 80,
							'text' => "80'"
						],
						[
							'id' => 100,
							'text' => "100'"
						],
						[
							'id' => 115,
							'text' => "115'"
						],
						[
							'id' => 130,
							'text' => "130'"
						],
						[
							'id' => 165,
							'text' => "165'"
						],
						[
							'id' => 200,
							'text' => "200'"
						],
						[
							'id' => 230,
							'text' => "230'"
						],
						[
							'id' => 260,
							'text' => "260'"
						],
						[
							'id' => 320,
							'text' => "320'"
						]
					];

					break;

				case 'DropDownLenghtMeters':
					$results = [
						[
							'id' => 5,
							'text' => '5m'
						],
						[
							'id' => 10,
							'text' => '10m'
						],
						[
							'id' => 20,
							'text' => '20m'
						],
						[
							'id' => 25,
							'text' => "25m"
						],
						[
							'id' => 30,
							'text' => '30m'
						],
						[
							'id' => 30,
							'text' => '30m'
						],
						[
							'id' => 30,
							'text' => '30m'
						],
						[
							'id' => 35,
							'text' => '35m'
						],
						[
							'id' => 40,
							'text' => '40m'
						],
						[
							'id' => 50,
							'text' => '50m'
						],
						[
							'id' => 60,
							'text' => '60m'
						],
						[
							'id' => 70,
							'text' => '70m'
						],
						[
							'id' => 80,
							'text' => '80m'
						],
						[
							'id' => 100,
							'text' => '100m'
						],
						[
							'id' => 120,
							'text' => '120m'
						],
						[
							'id' => 150,
							'text' => '150m'
						]
					];
					
					break;

					case 'ForSaleLengthMeterRangeDropdown':
						$results=[
							[
								'id' => '0-5',
								'text' => '0m-5m',
							],
							[
								'id' => '5-10',
								'text' => '5m-10m',
							],
							[
								'id' => '10-20',
								'text' => '10m-20m',
							],
							[
								'id' => '25-30',
								'text' => '0m-5m',
							],
							[
								'id' => '35-40',
								'text' => '30m-40m',
							],
							[
								'id' => '40-50',
								'text' => '40m-50m',
							],
							[
								'id' => '60-70',
								'text' => '60m-70m',
							],
							[
								'id' => '70-80',
								'text' => '70m-80m',
							],
							[
								'id' => '80-100',
								'text' => '80m-100m',
							],
							[
								'id' => '100+',
								'text' => '100m+',
							],
							
						];


						break;
					
					case 'ForSaleLengthFeetRangeDropdown':
						$results=[
							[
								'id' => '0-15',
								'text' => "0'-15'",
							],
							[
								'id' => '15-30',
								'text' => "15'-30'",
							],
							[
								'id' => '30-60',
								'text' => "30'-60'",
							],
							[
								'id' => '60-80',
								'text' => "60'-80'",
							],
							[
								'id' => '80-100',
								'text' => "80'-100'",
							],
							[
								'id' => '100-115',
								'text' => "100'-115'",
							],
							[
								'id' => '115-130',
								'text' => "115'-130'",
							],
							[
								'id' => '130-165',
								'text' => "130'-165'",
							],
							[
								'id' => '165-200',
								'text' => "165'-200'",
							],
							[
								'id' => '200-230',
								'text' => "200'-230'",
							],
							[
								'id' => '230-260',
								'text' => "230'-260'",
							],
							[
								'id' => '260-320',
								'text' => "260'-320'",
							],
							[
								'id' => '320+',
								'text' => "320'+",
							]
							
						];

						break;

				}

				return $results;
				
			}

		}
