<?php 
	class yatcoConnect_YatcoSeo {

		public function __construct() {
			
		}

        public static function get_yatcoseo_charterData( $source, $fromDB ){
            //echo "<pre>"; print_r($source);exit;
            $output = preg_replace_callback('/{(\w+)(?::(\d+))?}/', function($matches) use ($source) {
                $key = $matches[1];
                $charLimit = isset($matches[2]) ? intval($matches[2]) : null;
                // Map 'desc' to 'VesselDescriptionShortDescription'
				if ($key === 'desc') {
					$key = 'VesselDescriptionShortDescription';
				}

                if (isset($source->$key)) {
                    $value = $source->$key;
                    if (!is_scalar($value)) return ''; // prevent dumping arrays/objects
					// Format description
					if ($key === 'VesselDescriptionShortDescription') {
						$TemplateManager = new yatcoConnect_TemplateManager();
						$value = $TemplateManager->cleanAndFormatDescription($value);
						$value = self::removeTags($value);
					}
                    return $charLimit ? mb_substr($value, 0, $charLimit) : $value;
                }
            
                return ''; // if property doesn't exist
            }, $fromDB);
            
            return $output;
        }

        public static function get_yatcoseo_yatchData( $data, $fromDB ){

			$output = preg_replace_callback('/{(\w+)(?::(\d+))?}/', function($matches) use ($data) {
				$key = $matches[1];           // e.g., 'desc' or 'SomeOtherKey'
				$charLimit = $matches[2] ?? 160;  // e.g., '100' if {desc:100}
			
				// Map 'desc' to 'VesselDescriptionShortDescription'
				if ($key === 'desc') {
					$key = 'VesselDescriptionShortDescription';
				}
			
				// Ordered fallback sources
				$sources = [
					$data->Result ?? null,
					$data->BasicInfo ?? null,
					$data->MiscInfo ?? null,
					$data->Dimensions ?? null,
				];
			
				foreach ($sources as $source) {
					if (is_object($source) && isset($source->$key)) {
						$value = $source->$key;
			
						// Format description
						if ($key === 'VesselDescriptionShortDescription') {
							$TemplateManager = new yatcoConnect_TemplateManager();
							$value = $TemplateManager->cleanAndFormatDescription($value);
                            $value = self::removeTags($value);
						}
			
						// Apply character limit if provided
						if ($charLimit) {
							$value = mb_substr($value, 0, (int)$charLimit);
						}
			
						return $value;
					}
				}
			
				return ''; // fallback if not found
			}, $fromDB);						

			return $output;

		}

        public static function removeTags($html) {
            $html = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $html = strip_tags($html);
            $html = str_replace('&nbsp;', ' ', $html);
            return preg_replace('/<\/?p[^>]*>/', '', $html);
        }        
		
	}