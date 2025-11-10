<?php 
	class yatcoConnect_TemplateManager {

		private $table_name;

		public function __construct() {
			global $wpdb;
			$this->table_name = $wpdb->prefix . 'yatco_connect_templates';
			$this->options = new yatcoConnect_Options();
		}

		/**
		 * Fetch all templates from the custom table.
		 *
		 * @return array|object|null Returns an array of results or null if no data.
		 */
		public function get_all_templates() {
			global $wpdb;
			$query = "SELECT * FROM {$this->table_name} ORDER BY template_name ASC";
			return $wpdb->get_results($query, ARRAY_A);
		}

		public function get_template_names() {
			global $wpdb;
			$query = "SELECT id, template_name FROM {$this->table_name} ORDER BY template_name ASC";
			return $wpdb->get_results($query, ARRAY_A);
		}

		/**
		 * Fetch a single template by its ID.
		 *
		 * @param int $id Template ID.
		 * @return array|null Template data or null if not found.
		 */

		public function get_template_by_id($id) {
			global $wpdb;
			$query = $wpdb->prepare("SELECT * FROM {$this->table_name} WHERE id = %d", $id);
			return $wpdb->get_row($query, ARRAY_A);
		}

		public function check_unique_id($id) {
			global $wpdb;
		
			$table_name = $wpdb->prefix . 'yatco_connect_templates';
		
			// Check if unique_id is already set
			$unique_id = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT unique_id FROM $table_name WHERE id = %d",
					$id
				)
			);
		
			// If empty or null, update it with current timestamp
			if (empty($unique_id)) {
				return 'empty';
			}else{
				return 'exists';
			}
		}

		public function check_duplicate_unique_ids() {
			global $wpdb;
		
			$table_name = $wpdb->prefix . 'yatco_connect_templates';
		
			$duplicates = $wpdb->get_results("
				SELECT unique_id, COUNT(*) as count
				FROM $table_name
				WHERE unique_id IS NOT NULL AND unique_id != ''
				GROUP BY unique_id
				HAVING count > 1
			");
		
			if (!empty($duplicates)) {
				return $duplicates;
			}
		
			return false; // no duplicates found
		}		

		public function is_default_template($id) {
			global $wpdb;
		
			// Prepare query properly — no quotes around column name
			$query = $wpdb->prepare(
				"SELECT is_default FROM {$this->table_name} WHERE id = %d LIMIT 1",
				$id
			);
		
			$result = $wpdb->get_var($query); // Get single value (not full row)
		
			// Return true if is_default === '1' (or 1), false otherwise
			return (int)$result === 1;
		}		

		public function template_exists($id) {
			global $wpdb;
			$query = $wpdb->prepare("SELECT COUNT(*) FROM {$this->table_name} WHERE id = %d", $id);
			$count = $wpdb->get_var($query);
			return ($count > 0);
		}

		public function get_gridCount_by_id($id) {
			global $wpdb;
			$query = $wpdb->prepare("SELECT * FROM {$this->table_name} WHERE id = %d", $id);
			$result = $wpdb->get_row($query, ARRAY_A);
			
			if (!empty($result) && isset($result['no_of_grids'])) {
				return $result['no_of_grids'];
			}
			return '';
		}

		public function display_editor_content($id, $templateType='') {

			$template = $this->get_template_by_id($id);

			if ($template && isset($template['html_editor_contents'])) {

				$editor_contents = $template['html_editor_contents'];

				$editor_contents = wp_kses_post($editor_contents);

				echo stripslashes($editor_contents);

			}

		}

		public function process_template_data( $id, $data, $templateType = '', $templateFor = '' ){
			$this->display_template_content($id, $data, $templateType, $templateFor );
		}

		/**
		 * Display the template content by ID.
		 *
		 * @param int $id Template ID.
		 */
		public function display_template_content($id, $data, $templateType = '', $templateFor = '' ) {

			$template = $this->get_template_by_id($id);

			if ($template && isset($template['html_contents'])) {

				$html_contents = $template['html_contents'];

				echo $this->replace_content_placeholders( $html_contents, $data, $templateType, $templateFor );
	
			} else {

				echo "Template not found.";

			}

		}

		public function replace_stateroom_block($blockHtml, $staterooms) {
			libxml_use_internal_errors(true);
			$dom = new DOMDocument();
			$dom->loadHTML(
				mb_convert_encoding('<div>' . $blockHtml . '</div>', 'HTML-ENTITIES', 'UTF-8'),
				LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
			);			
		
			$xpath = new DOMXPath($dom);
		
			// Find the block
			$blockDiv = $xpath->query('//div[@data-type="stateroom-block"]')->item(0);
			if (!$blockDiv) return $blockHtml;
		
			// Find the row with selected fields
			$rowDiv = $xpath->query('.//div[@data-selected-fields]', $blockDiv)->item(0);
			if (!$rowDiv) return $blockHtml;
		
			// Get selected fields
			$selectedFields = array_map('trim', explode(',', $rowDiv->getAttribute('data-selected-fields')));

			// Now get the additional attributes
			$itemCount = (int) $rowDiv->getAttribute('data-item-count');
			$buttonText = $rowDiv->getAttribute('data-button-text') ?: ''; // Fallback

		
			// Get the first .stateroom-item as template
			$template = null;
			foreach ($rowDiv->childNodes as $child) {
				if ($child instanceof DOMElement && strpos($child->getAttribute('class'), 'stateroom-item') !== false) {
					$template = $child;
					break;
				}
			}
		
			if (!$template) return $blockHtml;
		
			// Remove all existing stateroom-item nodes
			$toRemove = [];
			foreach ($rowDiv->childNodes as $child) {
				if ($child instanceof DOMElement && strpos($child->getAttribute('class'), 'stateroom-item') !== false) {
					$toRemove[] = $child;
				}
			}
			foreach ($toRemove as $node) {
				$rowDiv->removeChild($node);
			}
			
			$counter = 0; // For tracking number of appended items

			if(count($staterooms) > 0){
				// Rebuild all items from $staterooms
				foreach ($staterooms as $room) {
					$newItem = $template->cloneNode(true);
					foreach ($selectedFields as $field) {
						$class = 'yt-' . strtolower($field);
						$fieldDiv = $xpath->query('.//*[contains(@class, "' . $class . '")]', $newItem)->item(0);
						if ($fieldDiv) {
							$span = $xpath->query('.//span[contains(@class, "staterooms-field-value")]', $fieldDiv)->item(0);
							if ($span) {
								$span->nodeValue = isset($room->$field) ? htmlspecialchars($room->$field) : '';
							}
						}
					}
					// ✅ Add 'yt-hidden-item' class if above itemCount
					if ($itemCount && $counter >= $itemCount) {
						$currentClass = $newItem->getAttribute('class');
						$newItem->setAttribute('class', trim($currentClass . ' yt-hidden-item'));
					}
					$rowDiv->appendChild($newItem);
					$counter++;
				}
			}else{
				//stateroom array is empty
				$naDiv = $dom->createElement('div');
				$naDiv->setAttribute('class', 'col-12');
				$naText = $dom->createTextNode('N/A');
				$naDiv->appendChild($naText);
				$rowDiv->appendChild($naDiv);
			}
		
			// ✅ Add Load More Button if needed
			if ($buttonText && $itemCount && $itemCount < count($staterooms)) {
				$buttonWrapper = $dom->createElement('div');
				$buttonWrapper->setAttribute('class', 'col-12');

				$button = $dom->createElement('button', htmlspecialchars($buttonText));
				$button->setAttribute('class', 'yt-load-more-button btn btn-primary mt-3');
				$button->setAttribute('type', 'button');

				$buttonWrapper->appendChild($button);
				$rowDiv->appendChild($buttonWrapper);
			}

			// Extract and return the final HTML cleanly
			$fragment = '';
			foreach ($blockDiv->childNodes as $child) {
				$fragment .= $dom->saveHTML($child);
			}
		
			// Clean opening div tag
			$blockOuter = $dom->saveHTML($blockDiv);
			preg_match('/^<div[^>]*?>/', $blockOuter, $match);
			$openingTag = $match[0] ?? '<div>';
		
			$html = $openingTag . $fragment;
			// ✅ Fix closing tag imbalance by removing the last </div>
			$lastDivPos = strrpos($html, '</div>');
			if ($lastDivPos !== false) {
				$html = substr_replace($html, '', $lastDivPos, 6);
			}
			// Avoid duplicate closing tags
			return $html;
		}

		public function remove_grapesjs_dummy_blocks($html) {
			libxml_use_internal_errors(true);
		
			$dom = new DOMDocument();
			$dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);
		
			$xpath = new DOMXPath($dom);
		
			// Find all elements with class 'grapesjs-dummy-block'
			$nodes = $xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), " grapesjs-dummy-block ")]');
		
			foreach ($nodes as $node) {
				$node->parentNode->removeChild($node);
			}
		
			// Only return the <body> inner HTML without <html>, <head>, <body>
			$body = $dom->getElementsByTagName('body')->item(0);
			$innerHTML = '';
			
			if(isset($body->childNodes)){
				foreach ($body->childNodes as $child) {
					$innerHTML .= $dom->saveHTML($child);
				}
			
			}
			return $innerHTML;
		}		
		
		public function replace_sections_block($html, $apiSections) {
			if (empty($apiSections) || !is_array($apiSections)) {
				return $html;
			}

			libxml_use_internal_errors(true);
			$dom = new DOMDocument();
			$dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);
			$xpath = new DOMXPath($dom);

			// Find all accordion-blocks
			$blocks = $xpath->query('//div[contains(concat(" ", normalize-space(@class), " "), " accordion-block ")]');

			foreach ($blocks as $block) {
				$blockClass = $block->getAttribute('class');

				$sampleTitle = $xpath->query('.//div[contains(@class, "accordion-title")]', $block)->item(0);
				$sampleContent = $xpath->query('.//div[contains(@class, "accordion-content")]', $block)->item(0);
				$sampleInner = $xpath->query('.//div[contains(@class, "accordion-inner")]', $block)->item(0);

				$titleClass = $sampleTitle ? $sampleTitle->getAttribute('class') : 'accordion-title';
				$contentClass = $sampleContent ? $sampleContent->getAttribute('class') : 'accordion-content';
				$innerClass = $sampleInner ? $sampleInner->getAttribute('class') : 'accordion-inner';

				// Remove existing items
				$items = $xpath->query('.//div[contains(@class, "accordion-item")]', $block);
				foreach ($items as $item) {
					$block->removeChild($item);
				}

				foreach ($apiSections as $section) {
					// accordion-item
					$item = $dom->createElement('div');
					$item->setAttribute('class', 'accordion-item');

					// title
					$titleDiv = $dom->createElement('div', htmlspecialchars($section->SectionName));
					$titleDiv->setAttribute('class', $titleClass);
					$item->appendChild($titleDiv);

					// content
					$contentDiv = $dom->createElement('div');
					$contentDiv->setAttribute('class', $contentClass);

					// inner
					$innerDiv = $dom->createElement('div');
					$innerDiv->setAttribute('class', $innerClass);

					// ✅ Use DOMDocument to safely parse HTML
					$tempDom = new DOMDocument();
					libxml_use_internal_errors(true);
					$tempDom->loadHTML('<?xml encoding="utf-8" ?><div>' . $section->SectionText . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
					libxml_clear_errors();

					$tempBody = $tempDom->getElementsByTagName('div')->item(0);
					if ($tempBody) {
						foreach ($tempBody->childNodes as $child) {
							$imported = $dom->importNode($child, true);
							$innerDiv->appendChild($imported);
						}
					}

					$contentDiv->appendChild($innerDiv);
					$item->appendChild($contentDiv);
					$block->appendChild($item);
				}

				$block->setAttribute('class', $blockClass);
			}

			$body = $dom->getElementsByTagName('body')->item(0);
			return $dom->saveHTML($body->firstChild); // innerHTML only
		}


		public function replace_amenities_block($html, $apiAmenities) {
			libxml_use_internal_errors(true);
			$dom = new DOMDocument();
			$dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);
			$xpath = new DOMXPath($dom);
		
			// Find all amenities-block wrappers
			$blocks = $xpath->query('//div[@data-type="amenities-block"]');
			foreach ($blocks as $block) {
				$row = $xpath->query('.//div[contains(@class, "row")]', $block)->item(0);
				if (! $row instanceof DOMElement) continue;
		
				$selectedGroupsRaw = $row->getAttribute('data-selected-groups');
				$selectedGroups = array_map('trim', explode('|', $selectedGroupsRaw));
		
				// Map amenity groups by name for quick lookup
				$groupMap = [];
				foreach ($apiAmenities->Groups as $group) {
					$groupMap[$group->AmenityGroupName] = $group;
				}
		
				// Loop through each .amenity-items div
				foreach ($xpath->query('.//div[contains(@class, "amenity-items")]', $row) as $groupDiv) {
					$titleNode = $xpath->query('.//*[contains(@class, "amenity-group-title")]', $groupDiv)->item(0);
					$groupName = $titleNode ? trim($titleNode->textContent) : '';
		
					$amenityText = $xpath->query('.//*[contains(@class, "amenity-items-text") or contains(@class, "amenity-list")]', $groupDiv)->item(0);
					if (! $groupName || ! isset($groupMap[$groupName]) || ! $amenityText) continue;
		
					// Filter items with Value == 1
					$validAmenities = array_filter($groupMap[$groupName]->Items, fn($item) => $item->Value == 1);
		
					// Get names or fallback to N/A
					if (!empty($validAmenities)) {
						$names = array_map(fn($item) => $item->AmenityName, $validAmenities);
						$separator = $row->getAttribute('data-separator') ?: ', ';
						$amenityText->nodeValue = implode($separator, $names);
					} else {
						$amenityText->nodeValue = 'N/A';
					}
				}
			}
		
			// Return cleaned HTML
			return preg_replace('~<(?:!DOCTYPE|/?(?:html|body))[^>]*>\s*~i', '', $dom->saveHTML());
		}

		public function replace_toys_tender_block($html, $data, $type) {
			if ($type === 'tender' || $type === 'toysDetails') {
				$data = explode("\n", $data);
				$data = array_values(array_filter(array_map(function ($item) {
					$clean = trim($item);
					if ($clean !== '') {
						return preg_replace('/^[-\p{Pd}]\s*/u', '', $clean); // remove dash types
					}
				}, $data)));
			}
		
			libxml_use_internal_errors(true);
			$dom = new DOMDocument();
			$dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);
			$xpath = new DOMXPath($dom);
		
			$block = $xpath->query('//div[@data-type="toys-tender-block" and @data-tt-block="' . $type . '"]')->item(0);
			if (!$block instanceof DOMElement) return $html;
		
			$listDiv = $xpath->query('.//div[contains(@class, "tt-list")]', $block)->item(0);
			if (!$listDiv instanceof DOMElement) return $html;
		
			$separator = $block->getAttribute('data-separator') ?: '';
			$total = count($data);
		
			// Get first <i> or SVG inside the first template <span>
			$templateSpan = $xpath->query('.//span[contains(@class, "tt-item-' . $type . '")]', $listDiv)->item(0);
			$iconElement = null;
		
			if ($templateSpan instanceof DOMElement) {
				foreach ($templateSpan->childNodes as $child) {
					if ($child->nodeType === XML_ELEMENT_NODE) {
						$iconElement = $child->cloneNode(true);
						break;
					}
				}
			}
		
			// Clear all children from .tt-list
			while ($listDiv->firstChild) {
				$listDiv->removeChild($listDiv->firstChild);
			}
		
			if ($total > 0) {
				foreach ($data as $i => $item) {
					$cleanItem = htmlspecialchars(trim($item));
					$isSubheading = in_array($item, ['Toys:', 'Other:']);
					$class = 'tt-item-' . $type . ($isSubheading ? ' tt-subheading' : '');
		
					$span = $dom->createElement('span');
					$span->setAttribute('class', $class);
		
					// Append cloned icon if exists
					if ($iconElement && !$isSubheading) {
						$span->appendChild($iconElement->cloneNode(true));
					}
		
					// Append text after icon
					$span->appendChild($dom->createTextNode($cleanItem));
					$listDiv->appendChild($span);
		
					if (!$isSubheading && $i < $total - 1 && $separator !== '') {
						$listDiv->appendChild($dom->createTextNode($separator));
					}
				}
			} else {
				$span = $dom->createElement('span', 'N/A');
				$span->setAttribute('class', 'tt-item-' . $type);
				$listDiv->appendChild($span);
			}
		
			return preg_replace('~<(?:!DOCTYPE|/?(?:html|body))[^>]*>\s*~i', '', $dom->saveHTML());
		}
		
		public function replace_charter_RatesList_block($html, $data) {

			libxml_use_internal_errors(true);
			$dom = new DOMDocument();
			$dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);
			$xpath = new DOMXPath($dom);
		
			$block = $xpath->query('//div[@data-type="charter-rates"]')->item(0);
			if (! $block instanceof DOMElement) return $html;
		
			// Find and clone the first existing row as a template
			$templateRow = $xpath->query('.//div[contains(@class, "yt-charter-rates-row")]', $block)->item(0);
			if (! $templateRow instanceof DOMElement) return $html;
		
			// Remove all rows except the first one
			foreach ($xpath->query('.//div[contains(@class, "yt-charter-rates-row")]', $block) as $i => $oldRow) {
				if ($i > 0) {
					$block->removeChild($oldRow);
				}
			}
		
			// Remove the first row so we can re-append from scratch
			$block->removeChild($templateRow);
		
			if (count($data) > 0) {

				foreach ($data as $rateObj) {
					$newRow = $templateRow->cloneNode(true);
			
					// Set SEASON
					$seasonNode = $xpath->query('.//*[contains(@class, "yt-charter-season")]//span[contains(@class, "charter-spec-val")]', $newRow)->item(0);
					if ($seasonNode) {
						$seasonNode->nodeValue = $rateObj->Season ?? 'N/A';
					}
			
					// Set LOW RATE
					$lowRateNode = $xpath->query('.//*[contains(@class, "yt-charter-low-rate")]//span[contains(@class, "charter-spec-val")]', $newRow)->item(0);
					if ($lowRateNode) {
						$lowRateNode->nodeValue = ($rateObj->LowRateFormat ?? $rateObj->LowRateFormat ?? 'N/A') . '/ ' . ($rateObj->Frequency ?? '');
					}
			
					// Set HIGH RATE
					$highRateNode = $xpath->query('.//*[contains(@class, "yt-charter-high-rate")]//span[contains(@class, "charter-spec-val")]', $newRow)->item(0);
					if ($highRateNode) {
						$highRateNode->nodeValue = ($rateObj->HighRateFormat ?? $rateObj->HighRateFormat ?? 'N/A') . '/ ' . ($rateObj->Frequency ?? '');
					}
			
					// Set LOCATION
					$locationNode = $xpath->query('.//*[contains(@class, "yt-charter-location")]//span[contains(@class, "charter-spec-val")]', $newRow)->item(0);
					if ($locationNode) {
						$locationNames = array_map(function ($loc) {
							return $loc->Name ?? '';
						}, $rateObj->RateLocations ?? []);
						$locationNode->nodeValue = implode(', ', array_filter($locationNames));
					}
			
					$block->appendChild($newRow);
				}

			}else{

				$span = $dom->createElement('span', 'N/A');
				$block->appendChild($span);

			}
		
			// Return the final cleaned-up HTML
			return preg_replace('~<(?:!DOCTYPE|/?(?:html|body))[^>]*>\s*~i', '', $dom->saveHTML());
		}		

		public function replace_content_placeholders( $updated_content, $data, $templateType='', $templateFor = ''  ){

			//adding temporary data for testing
			//$data->VirtualTourURL = "https://my.matterport.com/show/?m=AFpBgfRvrBm"; For Charter Sale
			//$data->BasicInfo->VirtualTourURL = "https://my.matterport.com/show/?m=AFpBgfRvrBm";// For Yacht Sale
			//$video = new stdClass();
			//$video->VideoUrl = "https://www.youtube.com/embed/Ttw1jXPKSZc";
			//$video->VideoUrl = "https://player.vimeo.com/video/750432905?h=ab55dbe681";
			//$data->Videos = array($video);
			//echo "<pre>"; print_r($data); echo "</pre>";exit;
			
			$updated_content = wp_kses_post($updated_content);
			if($templateType == 'single'){
				$updated_content = $this->remove_grapesjs_dummy_blocks( $updated_content );
			}
			// Check for MainPhotoUrl in either $data or $data->Result
			$mainPhotoUrl = null;
			if (isset($data->MainPhotoUrl)) { //small image
				$mainPhotoUrl = $data->MainPhotoUrl;
			} elseif (isset($data->Result->MainPhotoUrl)) {
				$mainPhotoUrl = $data->Result->MainPhotoUrl;
			}

			if (isset($data->MainPhotoMedURL)) { //medium image
				$mainPhotoUrl = $data->MainPhotoMedURL;//medium image
			} elseif (isset($data->Result->MainPhotoMedURL)) {
				$mainPhotoUrl = $data->Result->MainPhotoMedURL;
			}

			if($templateType == 'single'){
				//We only need large image in single page due to page load issues on listings
				if(isset($data->BasicInfo->MainPhotoURL)){
					$mainPhotoUrl = $data->BasicInfo->MainPhotoURL;
				}
			}

			if($templateFor == 'charter' && $templateType == 'listings' ){
				if(isset($data->MediumImageUrl)){
					$mainPhotoUrl = $data->MediumImageUrl;
				}
			}

			if($templateFor == 'charter' && $templateType == 'single' ){
				if(isset($data->MainPhoto)){
					$mainPhotoUrl = $data->MainPhoto;
				}
			}

			if ($mainPhotoUrl) {
				// Dynamically extract placeholder image path from plugin assets
				$placeholder_url = YATCO_PLUGIN_ASSETS . 'build/bootstrap5/placeholder.jpg';
				// Convert full URL to relative path
				$parsed_placeholder = parse_url($placeholder_url, PHP_URL_PATH);
				// Use regex to match any domain in the placeholder URL and replace it with the actual URL
				$updated_content = preg_replace(
					'#https?://[^/]+'. preg_quote($parsed_placeholder, '#') . '#i',
					$mainPhotoUrl,
					$updated_content
				);
			}

			//replace video if exits
			if (isset($data->Videos[0]->VideoUrl)) {
				$video_url = $data->Videos[0]->VideoUrl;

				// Match <video> tags with class="video-block" (and any other attributes)
				$updated_content = preg_replace_callback(
					'#<video\b[^>]*class="[^"]*\bvideo-block\b[^"]*"[^>]*>.*?</video>#is',
					function ($matches) use ($video_url) {
						// Extract a fallback poster if needed (optional)
						preg_match('/poster="([^"]+)"/i', $matches[0], $posterMatch);
						$poster = $posterMatch[1] ?? '';
			
						// Return an iframe embed (can be styled externally)
						return sprintf(
							'<div class="yt-video-embed-wrapper"><iframe src="%s" frameborder="0" allowfullscreen loading="lazy"></iframe></div>',
							esc_url($video_url)
						);
					},
					$updated_content
				);

			}

			if (isset($data->Sections) && !empty($data->Sections)) {
				$apiSections = $data->Sections;
				$updated_content = $this->replace_sections_block($updated_content, $apiSections);
			}

			if (isset($data->Amenities) && !empty($data->Amenities)) {
				$apiAmenities = $data->Amenities;
				$updated_content = $this->replace_amenities_block($updated_content, $apiAmenities);
			}
			
			if (isset($data->Staterooms)) {
				$updated_content = preg_replace_callback(
					'/<div[^>]+data-type=["\']stateroom-block["\'][^>]*>\s*<div[^>]+data-selected-fields=["\']([^"\']+)["\'][^>]*>.*?<\/div>\s*<\/div>/is',
					function ($matches) use ($data) {
						return $this->replace_stateroom_block($matches[0], $data->Staterooms);
					},
					$updated_content
				);
			}

			if (isset($data->ToysList)) {
				$toys = $data->ToysList;
				$updated_content = $this->replace_toys_tender_block($updated_content, $toys, 'toys');
			}

			if (isset($data->ToysTenders)) {
				$tenders = $data->ToysTenders[0]->Details;
				$toysDetails = $data->ToysTenders[0]->ToysDetails;
				$updated_content = $this->replace_toys_tender_block($updated_content, $tenders, 'tender');
				$updated_content = $this->replace_toys_tender_block($updated_content, $toysDetails, 'toysDetails');
			}
			
			if(isset($data->RatesList)){
				$RatesList = $data->RatesList;
				$updated_content = $this->replace_charter_RatesList_block($updated_content, $RatesList);
			}
			$updated_content = preg_replace_callback('/\{([a-zA-Z0-9_]+)(?::([a-zA-Z0-9_]+))?\}/', function ($matches) use ($data, $templateType, $templateFor) {
				$init_section = $section = $matches[1]; // First part (e.g., "BrokerList", "VD")
				$key = $matches[2] ?? null; // Second part (e.g., "CompanyName", "VesselDescriptionShowingInstructions"), or null for {Variable}
				if($section == 'RatesList'){
					if(!empty($data->$section)){
						return $data->$section[0]->$key;
					}else{
						return '';
					}
				}
				if($section == 'lowCost'){
					$section = 'RatesList';
					if(!empty($data->$section)){
						return $data->$section[0]->LowRateFormat;
					}else{
						return 'N/A';
					}
				}
				if($section == 'highCost'){
					$section = 'RatesList';
					if(!empty($data->$section)){
						return $data->$section[0]->HighRateFormat;
					}else{
						return 'N/A';
					}
				}
				if($section == 'frequency'){
					$section = 'RatesList';
					if(!empty($data->$section)){
						return $data->$section[0]->Frequency;
					}else{
						return '';
					}
				}
				//special case for tag as it is an array
				if($section == 'tags'){
					$tags = $data->$section;
					if(isset($tags[0]) && $tags[0] == '4'){
						return 'Featured';
					}else{
						return '<span class="yt-empty-tag"></span>';
					}
				}
				if($section == 'new'){
					if($data->DaysOnMarket < 31){
						return 'New';
					}else{
						return '<span class="yt-empty-tag"></span>';
					}
				}
				if($templateType == 'single'){
					// We need this placeholders on Single Yacht page
					if ($section == 'EngineNos') {
						if(isset($data->Engines) && is_array($data->Engines)){
							return count($data->Engines);
						}else{
							echo 'N/A';
						}
					}
					if ($section == 'AllEnginesHours') {
						$engines = $data->Engines;
						if(isset($engines)){
							$AppoxHours = [];
							foreach($engines as $engine){
								$AppoxHours[] = $engine->AppoxHours;
							}
							return implode(" / ", $AppoxHours);;
						}else{
							return '';
						}
					}
					if ($section == 'contactForm') {
						//display contact form
						if($templateFor == 'charter'){
							return $this->displayContact_form_forCharters($data);
						}
						return $this->displayContact_form($data);
					}
					if ($section == 'shareButtons') {
						//display contact form
						return $this->displayShare_buttons();
					}
					if ($section == 'Description') {
						return $data->VD->VesselDescriptionShortDescription;
					}
					if ($section == 'shortDescription') {
						if($templateFor == 'charter'){
							if(isset($data->VesselDescriptionShortDescription)){
								return $this->cleanAndFormatDescription($data->VesselDescriptionShortDescription);
							}else{
								return '';
							}
						}
						$section = 'MiscInfo';
						$key = 'VesselDescriptionShortDescription';
					}
					// Handle 'slider, slider2 or gallery' case and map to 'PhotoGallery' with 'largeImageURL'
					if (in_array($section, ['slider', 'slider2', 'gallery'])) {
						//echo "<pre>"; print_r($data);exit;
						if($templateFor == 'charter'){
							//different object key for charters
							$section = 'Photos';

						}else{

							$section = 'PhotoGallery';

						}
						$key = 'largeImageURL';
						if ( wp_is_mobile() ) {
							$key = 'mediumImageURL';
						}
					}
					// Manupulating VesselType as VesselTypeText
					if ($section == 'VesselType') {
						$section = 'VesselTypeText';
					}
					//expection for engine as multiple engines are available for a vessel. But giving only first engines parameters
					if ($section == 'Engines') {
						$engines = $data->$section;
						if(isset($engines[0]->$key)){
							return $engines[0]->$key;
						}else{
							return '';
						}
					}
				}
			
				// Handle {Array:Property} format
				if ($key !== null) {
					if (isset($data->$section)) {
						if (is_array($data->$section)) {
							// If section is an array (like BrokerList), loop through and collect values
							$values = [];
							foreach ($data->$section as $item) {
								if (isset($item->$key)) {
									$values[] = $item->$key;
								}
							}
							// If it's a slider, return the collected image URLs to create the slider
							if (( $section == 'PhotoGallery' || $section == 'Photos' ) && ($init_section =='slider' || $init_section =='slider2')) {
								if($init_section == 'slider2'){
									//slider type 2
									return $this->makeSlider2($values);
								}
								return $this->makeSlider($values);
							}
							else if (( $section == 'PhotoGallery' || $section == 'Photos' ) && $init_section =='gallery') {
								// If it's a gallery, return the collected image URLs to create the gallery
								return $this->displayGallery($data, $templateFor);
							} else {
								return implode(', : ', $values); // Return a comma-separated list
							}
						} elseif (isset($data->$section->$key)) {
							if($init_section == 'shortDescription'){
								return $this->cleanAndFormatDescription($data->$section->$key);
							}
							return $data->$section->$key; // Return the key from the nested object
						}
					}
				}
			
				// Handle {Variable} format where the key is not provided
				elseif ( isset( $data->$section ) ) {
					if( empty( $data->$section ) ){
						return 'N/A';
					}
					return $data->$section; // Return the entire section if the key is not specified
				}
				elseif ( isset($data->Result->$section ) ) {
					if( empty( $data->Result->$section ) ){
						return 'N/A';
					}
					//If not found, check in Results object
					return $data->Result->$section;
				}
				elseif (isset($data->Accommodations->$section)) {
					if( empty( $data->Accommodations->$section ) ){
						return 'N/A';
					}
					//If not found, check in Accommodations object
					return $data->Accommodations->$section;
				}
				elseif (isset($data->Dimensions->$section)) {
					if( empty( $data->Dimensions->$section ) ){
						return 'N/A';
					}
					//If not found, check in Dimensions object
					return $data->Dimensions->$section;
				}
				elseif (isset($data->SpeedWeight->$section)) {
					if( empty( $data->SpeedWeight->$section ) ){
						return 'N/A';
					}
					//If not found, check in SpeedWeight object
					return $data->SpeedWeight->$section;
				}
				elseif (isset($data->HullDeck->$section)) {
					if( empty( $data->HullDeck->$section ) ){
						return 'N/A';
					}
					//If not found, check in HullDeck object
					return $data->HullDeck->$section;
				}					
				elseif (isset($data->BasicInfo->$section)) {
					//If not found, check in BasicInfo object
					if( empty( $data->BasicInfo->$section ) ){
						return 'N/A';
					}
					return $data->BasicInfo->$section;
				}				
				elseif (isset($data->BrokerList->$section)) {
					if( empty( $data->BrokerList->$section ) ){
						return 'N/A';
					}
					//If not found, check in BrokerList object
					return $data->BrokerList->$section;
				}
				// Handle deeper nested objects like VD.VesselDescriptionShowingInstructions
				elseif (strpos($section, '.') !== false) {
					// Split the section into parts if it's a nested object, e.g., "VD.VesselDescriptionShowingInstructions"
					$parts = explode('.', $section);
					$nestedObject = $data;
			
					foreach ($parts as $part) {
						if (isset($nestedObject->$part)) {
							$nestedObject = $nestedObject->$part;
						} else {
							return $matches[0]; // Return the original placeholder if any part of the path doesn't exist
						}
					}
			
					// If a key is provided, return the corresponding value
					if ($key && isset($nestedObject->$key)) {
						return $nestedObject->$key;
					} else {
						return $nestedObject; // Return the entire nested object if no key is provided
					}
				}
			
				// Return the original placeholder if no match is found
				return $matches[0];
			}, $updated_content);																														

			return do_shortcode(stripslashes($updated_content));

		}

		public function displayContact_form_forCharters( $data ){
			$VesselID = $CompanyID = $a_url = '';

			if(isset($data->VesselID)){
				$VesselID = $data->VesselID;
			}
			if(isset($data->CompanyID)){
				$CompanyID = $data->CompanyID;
			}
			if(isset($data->a_url)){
				$a_url = $data->a_url;
			}
			ob_start();  // Start output buffering
			?>
			<div class="YachtDetail_ContactForm">
				<div style="padding-bottom: 10px; color: #003470; font-size: 14px; font-weight: bold; text-transform: uppercase;">
					Get more information on this listing
				</div>

				<div>
					<form action="" method="post" class="charter-lead-form">            
						<input type="hidden" name="VesselID" value="<?php echo $VesselID ?>">
						<input type="hidden" name="CompanyID" value="<?php echo $CompanyID ?>">
						<input type="hidden" name="ReferrerUrl" value="<?php echo $a_url ?>">
						<input type="hidden" name="FormTypeID" value="3">
						<input type="text" name="contact_me_by_fax_only" style="display:none !important" tabindex="-1" autocomplete="off">
						<div class='hide-after-submit'>
							<div class="">
								<div class="yt-row row-smaller">
									<div class="yt-col-md-6 yt-col-12">
										<div class="yt-input-block">
											<input  type="text" name="FirstName" placeholder="First Name*" required="required" class="yt-input">
										</div>
									</div>

									<div class="yt-col-md-6 yt-col-12">
										<div class="yt-input-block">
											<input type="text" name="LastName" placeholder="Last Name*" required="required" class="yt-input">
										</div>
									</div>
								</div>

								<div class="yt-row row-smaller">
									<div class="yt-col-md-6 yt-col-12">
										<div class="yt-input-block">
											<input class="yt-input" type="email" name="Email" placeholder="Email Address*" required="required">
										</div>
									</div>

									<div class="yt-col-md-6 yt-col-12">
										<div class="yt-input-block">
											<input class="yt-input" type="tel" name="Phone" placeholder="Phone Number">
										</div>
									</div>
								</div>

								<div class="yt-row row-smaller">
									<div class="yt-col-12">
										<div class="yt-input-block">
											<textarea rows="5" class="yt-input" placeholder="Message*" name="Message" required="required" style="resize: none;"></textarea>
										</div>
									</div>
								</div>

								<div class="yt-row row-smaller">
									<div class="yt-col-12">
										<div class="yt-input-block">
											<input type="date" name="IdealCheckIn" placeholder="Ideal Check In Date" class="yt-input">
										</div>
									</div>
								</div>
			
								<div class="yt-row row-smaller">
									<div class="yt-col-6">
										<div class="yt-input-block">
											<input type="text" name="Duration" placeholder="Duration Of Stay" class="yt-input">
										</div>
									</div>
									<div class="yt-col-6">
										<div class="yt-input-block">
											<select name="RateFreq" class="yt-input">
												<option value="Days">Days</option>
												<option value="Weeks">Weeks</option>
											</select>
										
										</div>
									</div>
								</div>
								
								<div class="yt-row row-smaller test">
									<div class="yt-col-12">
										<div class="yt-input-block">
											<label>
												<input type="radio" name="owner_or_industry[]" value="owner">
												I am interested in chartering the vessel.
											</label>
											<label>
												<input type="radio" name="owner_or_industry[]" value="industry">
												I work in the yachting industry.
											</label>
										</div>
									</div>
								</div>

								<div class="yt-row row-smaller">
									<div class="yt-col-12">
										<div class="yt-input-block">
											<div class="yatco-g-recaptcha" data-sitekey="<?php echo $this->options->getOption('google_recap_api_key') ?>"></div>
										</div>

										<div class="yt-input-block">
											<?php if(isset($data->VesselID)){ //Display the button only if we are getting data from api ?>
											<input type="submit" class="yt-btn yt-btn-block" value="CONTACT LISTING BROKER" style="border-radius: 0px !important;" />
											<?php } ?>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="form-success-message" style="display: none; font-size: 75%;">
							<div class="yt-alert yt-alert-success yt-text-center" role="alert">
								Your form has been submitted. <br>
								The listing broker will contact you shortly.
							</div>
						</div>
					</form>
				</div>

			</div>
		<?php
		return ob_get_clean();  // Get buffered content and clean buffer
		}

		public function displayContact_form($data) {
			
			$options = new yatcoConnect_Options();
			$html = '
			<div class="YachtDetail_ContactForm yt-print-hide">
				<div style="padding-bottom: 10px; color: #003470; font-size: 14px; font-weight: bold; text-transform: uppercase;">
					Get more information on this listing
				</div>
		
				<div>
					<form action="" method="post" id="template-form-vessel-details" class="contact-broker-form">            
						<input type="hidden" name="VesselID" value="' . $data->BasicInfo->VesselID . '">
						<input type="hidden" name="CompanyID" value="' . $data->Company->CompanyID . '">
						<input type="hidden" name="ReferrerUrl" value="' . $data->a_url . '">
						<input type="hidden" name="FormTypeID" value="9">
		
						<input type="text" name="contact_me_by_fax_only" style="display:none !important" tabindex="-1" autocomplete="off">
		
						<div class="hide-after-submit">
							<div class="">
								<div class="yt-row row-smaller">
									<div class="yt-col-md-6 yt-col-12">
										<div class="yt-input-block">
											<input  type="text" name="FirstName" placeholder="First Name*" required="required" class="yt-input">
										</div>
									</div>
		
									<div class="yt-col-md-6 yt-col-12">
										<div class="yt-input-block">
											<input type="text" name="LastName" placeholder="Last Name*" required="required" class="yt-input">
										</div>
									</div>
								</div>
		
								<div class="yt-row row-smaller">
									<div class="yt-col-md-6 yt-col-12">
										<div class="yt-input-block">
											<input class="yt-input" type="email" name="Email" placeholder="Email Address*" required="required">
										</div>
									</div>
		
									<div class="yt-col-md-6 yt-col-12">
										<div class="yt-input-block">
											<input class="yt-input" type="tel" name="Phone" placeholder="Phone Number">
										</div>
									</div>
								</div>
		
								<div class="yt-row row-smaller">
									<div class="yt-col-12">
										<div class="yt-input-block">
											<textarea rows="5" class="yt-input" placeholder="Message*" name="Message" required="required" style="resize: none;"></textarea>
										</div>
		
										<div class="yt-input-block">
											<div class="yatco-g-recaptcha" data-sitekey="' . $options->getOption('yatco_connect_google_recap_api_key') . '"></div>
										</div>
		
										<div class="yt-input-block">
											<input type="submit" class="yt-btn yt-btn-block" value="MESSAGE BROKER" style="border-radius: 0px !important;" />
										</div>
									</div>
								</div>
							</div>
						</div>';

						$html .= '<div class="form-success-message" style="display: none; font-size: 75%;">';
							$html .= '<div class="yt-alert yt-alert-success yt-text-center" role="alert">';
							$success_message = $options->getOption('lead_success_message');

							if ($success_message == '') {
								if ($options->is_brokerage_site) :
									$success_message = 'Your form has been submitted. <br> We will contact you shortly.';
								else :
									$success_message = 'Your form has been submitted. <br> The listing broker will contact you shortly.';
								endif;
							}

							$html .= $success_message;
										
							$html .= '</div>';
						$html .= '</div>';
						$html .= '
					</form>
				</div>
			</div>';

			return $html; // Return the HTML string
		}		

		function makeSlider($images){
			$slider = '<div id="yt-light-slider-gallery" class="">';
			$count = intval(8);
			$gallery = '';

			if (is_array($images)) {

				foreach($images as $img) {
					$image_url=htmlentities($img);

					$slider .= '<div class="carousel-cell">';
					$slider .= '<img data-flickity-lazyload-srcset="'.$image_url.' 650w" sizes="(min-width: 650px) 650px, 320px" class="pic" />';
					$slider .= '</div>';
					
					$count--;
					
					if ($count === 0) {
						break;
					}
				}
			}
			$slider .= '</div>';

			return $slider;
		}

		function makeSlider2($images) {
			$slider = '';
		
			/* Main Image Slider */
			$slider .= '<div id="yachtCarousel" class="carousel slide yt-dynamic-slider" data-bs-ride="carousel">
				<div class="carousel-inner">';
			
			if (is_array($images) && count($images) > 0) {
				$i = 0;
				foreach ($images as $img) {
					$image_url = htmlentities($img);
					$active = ($i === 0) ? 'active' : ''; // Set 'active' only for the first image
		
					$slider .= '<div class="carousel-item ' . $active . '">';
					$slider .= '<a href="' . $image_url . '" class="glightbox">';
					$slider .= '<img src="' . $image_url . '" class="d-block w-100" alt="Slide ' . ($i + 1) . '">';
					$slider .= '</a>';
					$slider .= '</div>';
					
					$i++; // Increment index
				}
			}
		
			$slider .= '</div>';
			
			// Carousel controls
			$slider .= '
				<button class="carousel-control-prev" type="button" data-bs-target="#yachtCarousel" data-bs-slide="prev">
					<span class="carousel-control-prev-icon"></span>
				</button>
				<button class="carousel-control-next" type="button" data-bs-target="#yachtCarousel" data-bs-slide="next">
					<span class="carousel-control-next-icon"></span>
				</button>';
			
			$slider .= '</div>'; // Close carousel div
		
			/* Thumbnails */
			if (is_array($images) && count($images) > 0) {
				$i = 0;
				$slider .= '<div class="thumb-container">';
				foreach ($images as $img) {
					$image_url = htmlentities($img);
					$active = ($i === 0) ? 'active' : ''; // First thumbnail should be active
					
					$slider .= '<img src="' . $image_url . '" data-bs-target="#yachtCarousel" data-bs-slide-to="' . $i . '" class="thumb ' . $active . '">';
					
					$i++; // Increment index
				}
				$slider .= '</div>';
			}
		
			return $slider;
		}
		
		function displayGallery($data, $templateFor){
			ob_start();  // Start output buffering
			if($templateFor == 'charter'){

				$basic_info = $data;
				$photos = $data->Photos;
				$firstPhoto = reset($photos); // Gets the first stdClass object
				$mediumUrl = $firstPhoto->mediumImageURL;

			}else{

				$basic_info = $data->BasicInfo;
				$photos = $data->PhotoGallery;
				$mediumUrl = $photos[0]->mediumImageURL;
			}
			if(isset($basic_info->VirtualTourURL) && !empty($basic_info->VirtualTourURL)){
				// Step 1: Get model ID
				parse_str(parse_url($basic_info->VirtualTourURL, PHP_URL_QUERY), $query);
				$modelId = $query['m'] ?? '';
				$thumbnailUrl = "https://my.matterport.com/api/v1/player/models/{$modelId}/thumb";
			}
			$YachtName = !empty($basic_info->VesselName) ? $basic_info->VesselName : $basic_info->BoatName;
			
			?>
			<style>.gallery-panel figure{margin-bottom:0px;}</style>
			<div class="gallery-panel" id="gallery">
			<div class="section-content read-more-on-photos">
				<div id="lightgallery" class="yt-row" data-vessel-id="<?= $basic_info->VesselID ?>">
					<?php if (isset($basic_info) && isset($basic_info->VirtualTourURL) && $basic_info->VirtualTourURL != '') : ?>
						<figure data-src="<?= $basic_info->VirtualTourURL ?>" data-iframe="true">
							<div data-src="<?= $basic_info->VirtualTourURL ?>" data-iframe="true" class="noLightbox">
								<div class="yt-col-lg-3 yt-col-md-4 yt-col-6 col-pic">
									<div class="pic-wrapper">
										<div class="pic-overlay" style="text-align: center;">360 TOUR</div>
										
										<div class="icon tour"></div>

										<img src="<?= $thumbnailUrl ?>" alt="MEDIA 360 TOUR" loading="lazy" class="pic" />
									</div>
								</div>
							</div>
							
						</figure>
					<?php endif; ?>

					<?php foreach ($data->Videos as $vid) :

						$vimeo_url = '';

						preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $vid->VideoUrl, $yt_matches);

						if (isset($yt_matches[1])) {
							$y_id=$yt_matches[1]; 
						}

						if(preg_match("/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/?(showcase\/)*([0-9))([a-z]*\/)*([0-9]{6,11})[?]?.*/", $vid->VideoUrl, $vimeo_output)) {
							$vimeo_id = $vimeo_output[6];
						}
						
						if (preg_match('/(?:video|playback)\/([0-9]{6,})/', $vid->VideoUrl, $vimeo_output)) {
							$vimeo_id = $vimeo_output[1];
							$vimeo_url = $vid->VideoUrl;
							if (preg_match('/playback\/(\d{6,})/', $vimeo_url, $matches)) {
								$vimeo_id = $matches[1];
								$vimeo_url = 'https://player.vimeo.com/video/' . $vimeo_id;
							}
						}

						$video_url = $vid->VideoUrl;

						if (isset($y_id) && $y_id != null) :
							
							?>
							<figure data-src="<?php echo $video_url; ?>" >
								<div data-src="<?php echo $video_url; ?>" class="noLightbox">
									<div class="yt-col-lg-3 yt-col-md-4 yt-col-6 col-pic">
										<div class="pic-wrapper">
											<div class="pic-overlay">PLAY VIDEO</div>
											
											<div class="icon play"></div>
											<picture>
												<source media="(min-width: 650px)" srcset="https://img.youtube.com/vi/<?= $y_id ?>/maxresdefault.jpg">
												<img src="https://img.youtube.com/vi/<?= $y_id ?>/mqdefault.jpg" alt="VIDEO <?= $y_id ?>" loading="lazy" class="pic" />
											</picture>
										</div>
									</div>
								</div>
							</figure>
						<?php elseif (isset($vimeo_id)) : ?>
							<?php

							$vimeoThumbnail = $this->get_vimeo_thumbnail($video_url);

							//Only for Vimeop if the url structure is different, Give the simple structured url so it will work in gallery
							if(!empty($vimeo_url)){
								$video_url = $vimeo_url;
							}

							?>
							<figure data-src="<?php echo $video_url; ?>" data-video-id="<?php echo $vimeo_id; ?>">
								<div data-src="<?php echo $video_url; ?>" class="noLightbox">
									<div class="yt-col-lg-3 yt-col-md-4 yt-col-6 col-pic">
										<div class="pic-wrapper">
											<div class="pic-overlay">PLAY VIDEO</div>
											
											<div class="icon play"></div>
											<picture>
												<source media="(min-width: 650px)" srcset="<?php echo $vimeoThumbnail; ?>">
												<img src="<?= $mediumUrl ?>" alt="VIDEO <?= $vimeo_id ?>" loading="lazy" class="pic" />
											</picture>
										</div>
									</div>
								</div>
							</figure>
						<?php else : ?>
							<figure data-src="<?php echo $video_url; ?>"  data-iframe="true" >
								<div data-src="<?php echo $video_url; ?>" data-iframe="true" class="noLightbox">
									<div class="yt-col-lg-3 yt-col-md-4 yt-col-6 col-pic">
										<div class="pic-wrapper">
											<div class="pic-overlay" style="text-align: center;">360 TOUR</div>
											
											<div class="icon tour"></div>

											<img src="<?= $mediumUrl ?>" alt="MEDIA 360 TOUR" loading="lazy" class="pic" />
										</div>
									</div>
								</div>
							</figure>
						<?php endif; ?>	

					<?php endforeach; ?>

					<?php foreach ($photos as $pic_index => $pic) : 
						$gallery_href = (wp_is_mobile())?$pic->mediumImageURL:$pic->largeImageURL;
						?>
						<figure itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject" data-src="<?= $pic->largeImageURL ?>">
							<div data-src="<?= $gallery_href ?>" class="noLightbox" itemprop="contentUrl">	
								<div class="yt-col-lg-3 yt-col-md-4 yt-col-6 col-pic">
									<div class="pic-wrapper">
										<div class="pic-overlay">VIEW GALLERY</div>
										<picture>
											<source media="(min-width: 650px)" srcset="<?= $pic->mediumImageURL ?>">
											<img src="<?= $pic->smallImageURL ?>" itemprop="thumbnail" alt="<?= $YachtName.' '.$pic_index.' '.$pic->Caption ?>" loading="lazy" class="pic" />
										</picture>
									</div>
								</div>

								<figcaption itemprop="caption description" style="display: none;"><?= $YachtName.' '.$pic_index.' '.$pic->Caption ?></figcaption>
							</div>
						</figure>
					<?php endforeach; ?>
				</div>

			</div>
			
		</div>
		<?php
		
		return ob_get_clean();  // Get buffered content and clean buffer
		}

		public function get_vimeo_thumbnail($vimeo_url) {
			$oembed_endpoint = 'https://vimeo.com/api/oembed.json?url=' . urlencode($vimeo_url);
		
			$response = wp_remote_get($oembed_endpoint);
			if (is_wp_error($response)) return null;
		
			$data = json_decode(wp_remote_retrieve_body($response));
			return $data->thumbnail_url ?? null;
		}		

		function cleanAndFormatDescription($description) {
			// Step 1: Remove wrapping quotes if present
			$description = trim($description, '"');
		
			// Step 2: Decode HTML entities (e.g., &quot; to actual quotes)
			$description = htmlspecialchars_decode($description, ENT_QUOTES);
		
			// Step 4: Convert escaped newlines and tabs (`\r\n`, `\t`) into real spaces
			$description = preg_replace('/(\\\r\\\n|\\\r|\\\n|\\\t|rnrn)/', ' ', $description);
		
			// Step 5: Replace multiple spaces with a single space for clean formatting
			$description = preg_replace('/\s+/', ' ', $description);
		
			// Step 6: Ensure proper newlines for readability
			$description = preg_replace('/(\.)([A-Z])/', "$1\n$2", $description);
		
			// Step 7: Convert line breaks into <br> for HTML display
			$description = nl2br(trim($description));
		
			// Step 8: Remove empty <p> tags (including those with just spaces or &nbsp;)
			$description = preg_replace('/<p>\s*(?:&nbsp;|\s)*<\/p>/', '', $description);

			// Remove all inline style attributes
			if(!empty($description)){
				$description = $this->removeStyles($description);
			}
		
			return $description;
		}																

		function removeStyles($html){
			// Load into DOMDocument
			$dom = new DOMDocument();
			libxml_use_internal_errors(true); // Suppress warnings for malformed HTML
			$dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);

			// Remove all style attributes
			$xpath = new DOMXPath($dom);
			foreach ($xpath->query('//*[@style]') as $node) {
				$node->removeAttribute('style');
			}

			// Get the cleaned HTML
			$body = $dom->getElementsByTagName('body')->item(0);
			$clean_html = '';
			foreach ($body->childNodes as $child) {
				if(!empty($child->textContent)){
					$clean_html .= $dom->saveHTML($child);
				}
			}

			return $clean_html;
		}

		function displayShare_buttons(){

			ob_start();  // Start output buffering
			include plugin_dir_path(__FILE__) . 'partials/sharing.php';
			return ob_get_clean();  // Get buffered content and clean buffer
			
		}

		function display_template_styles($id){
			$template = $this->get_template_by_id($id);
			if ($template && isset($template['updated_styles'])) {
				echo '<style>'.stripslashes($template['updated_styles']).'.yt_row_add{display:none;}.component{padding:12px;}.card{margin-bottom:20px;}</style>';
			}
		}
		/**
		 * Add a new template to the custom table.
		 *
		 * @param string $name Template name.
		 * @param string $content Template content.
		 * @return bool|int False on failure, or number of rows affected.
		 */
		public function add_template($name, $content) {
			global $wpdb;
			return $wpdb->insert(
				$this->table_name,
				[
					'template_name' => sanitize_text_field($name),
					'html_contents' => wp_kses_post($content),
					'created_at' => current_time('mysql')
				]
			);
		}
	
		/**
		 * Update an existing template by ID.
		 *
		 * @param int $id Template ID.
		 * @param string $name Template name.
		 * @param string $content Template content.
		 * @return bool|int False on failure, or number of rows affected.
		 */
		public function update_template($id, $name, $content) {
			global $wpdb;
			return $wpdb->update(
				$this->table_name,
				[
					'template_name' => sanitize_text_field($name),
					'html_contents' => wp_kses_post($content)
				],
				[ 'id' => $id ]
			);
		}
	
		/**
		 * Delete a template by ID.
		 *
		 * @param int $id Template ID.
		 * @return bool|int False on failure, or number of rows affected.
		 */
		public function delete_template($id) {
			global $wpdb;
			return $wpdb->delete(
				$this->table_name,
				[ 'id' => $id ]
			);
		}

		public function get_json_style( $styles ) {
			// Match all CSS rules outside media queries
			preg_match_all('/\.([a-zA-Z0-9\-]+)\s*{([^}]*)}/', $styles, $matches, PREG_SET_ORDER);
		
			// Initialize the array
			$cssArray = [];
		
			// Process each CSS rule
			foreach ($matches as $match) {
				$selector = $match[1]; // Extract the selector (e.g., .container-element-8114)
				$rules = trim($match[2]);    // Extract the CSS rules
		
				// Skip empty rules
				if (empty($rules)) {
					continue;
				}
		
				// Parse the rules into key-value pairs
				$rulesArray = [];
				foreach (explode(';', $rules) as $rule) {
					$ruleParts = explode(':', $rule);
					if (count($ruleParts) === 2) {
						$property = str_replace("-", "_", trim($ruleParts[0]));
						$value = trim($ruleParts[1]);
		
						// Skip if value is empty (e.g., null or empty string)
						if (empty($value) && $value !== "0") {
							continue;
						}
		
						$rulesArray[$property] = $value;
					}
				}
		
				// Only add selector if there are rules
				if (!empty($rulesArray)) {
					$cssArray[$selector] = $rulesArray;
				}
			}
		
			// Check if the array is empty and return an empty object if so
			if (empty($cssArray)) {
				return json_encode(new stdClass()); // Return an empty object
			}
		
			// Convert PHP array to JSON and return
			return json_encode($cssArray, JSON_PRETTY_PRINT);
		}
		
		public function setupTemplate($content, $templateType){

			if($templateType == 'single'){

				return '<div class="container"><div class="vessel-details-style-container">'.$content.'</div></div>';

			}else if($templateType=='grid'){

				return '<div class="container"><div class="yt-template-custom yatco-shortcode-yacht-results">
				<div class="grid-view">
					<div class="yt-card-container">
						<div class="yt-row row" id="yacht-loop">
							<div class="col-12 col-md-6 col-lg-4 col-yacht">
								'.$content.'
							</div>
						</div>
					</div>
				</div>
				</div></div>';

			}else if($templateType=='list'){

				return '<div class="container"><div class="yt-template-custom yatco-shortcode-yacht-results">
				<div class="list-view">
					<div class="yt-card-container">
						<div class="yt-row row" id="yacht-loop">
							<div class="yt-col-12 col-yacht">
								'.$content.'
							</div>
						</div>
					</div>
				</div>
				</div></div>';

			}else{

				return $content;

			}

			return $content;

		}
		
	}