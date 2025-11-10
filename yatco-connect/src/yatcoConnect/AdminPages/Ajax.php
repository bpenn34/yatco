<?php	
	class yatcoConnect_AdminPages_Ajax{
		
		public function __construct() {

			$this->data=new yatcoConnect_ApiToBoss();
			$this->options = new yatcoConnect_Options();
			$this->templateManager = new yatcoConnect_TemplateManager();

		}

		public function add_actions_and_filters() {

			add_action('wp_ajax_yatco_check_api_connection', [$this, 'check_api_connection']);

			add_action('wp_ajax_yatco_purge_transients', [$this, 'purge_transients']);
			
			add_action('wp_ajax_save_grapesjs_content', [$this, 'save_grapesjs_content']);

			add_action('wp_ajax_yt_update_template_usage', [$this, 'yt_update_template_usage']);

			add_filter('wp_kses_allowed_html', [$this, 'allow_custom_attributes'], 10, 2);

			add_filter( 'safe_style_css', function( $allowed_styles ) {

				$custom_styles = ['fill'];
				return array_merge($allowed_styles, $custom_styles);
				
			}, 10, 2);

			add_action('admin_post_yatco_export_templates', [$this, 'yatco_export_templates']);

			add_action('wp_ajax_yt_delete_template', [$this, 'yt_delete_template']);

			add_action('wp_ajax_yt_clone_template', [$this, 'yt_clone_template']);

			add_action('wp_ajax_yt_use_template_as', [$this, 'yt_use_template_as']);

			add_action('wp_ajax_yt_export_template', [$this, 'yt_export_template']);

			add_action('wp_ajax_yatco_update_template_preview', [$this, 'yatco_update_template_preview_callback']);

			add_action('wp', [$this, 'disable_wpautop_for_yatco_preview']);

			add_action('wp_ajax_upload_custom_icon', [$this, 'upload_custom_icon']);

			add_action('wp_ajax_remove_custom_icon', [$this, 'remove_custom_icon']);
			
			add_action('wp_ajax_yatco_confirm_svg_warning', function() {
				update_option('yatco_svg_upload_confirmed', true);
				wp_send_json_success();
			});
			
		}

		public function disable_wpautop_for_yatco_preview() {
			if ( is_page('yatco-template-preview') ) {
				remove_filter('the_content', 'wpautop');
			}
		}		

		public function check_api_connection() {

			$test_search = $this->data->get_search([]);

			echo json_encode($test_search);

			wp_die();

		}

		public function purge_transients() {

			global $wpdb;

			$prefix = '_transient_yatco_';

			$options = $wpdb->options;

			$t  = esc_sql( "$prefix%" );

			$sql = $wpdb -> prepare (
				"
					SELECT option_name
					FROM $options
					WHERE option_name LIKE '%s'
				",
				$t
			);

			$transients = $wpdb -> get_col( $sql );

			// For each transient...
			foreach( $transients as $transient ) {

				//var_dump($transient);

				// Strip away the WordPress prefix in order to arrive at the transient key.
				$key = $transient;

				// Now that we have the key, use WordPress core to the delete the transient.
				delete_option( $key );

			}

			// But guess what?  Sometimes transients are not in the DB, so we have to do this too:
			wp_cache_flush();

			echo json_encode(['success' => 'Deleted yatco transients!']);
  			
			wp_die();

		}

		public function update_unique_id($id) {
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
				$wpdb->update(
					$table_name,
					[ 'unique_id' => time() ],
					[ 'id' => $id ],
					[ '%d' ],
					[ '%d' ]
				);
			}
		}		

		function save_grapesjs_content() {

			check_ajax_referer('yatco_nonce', 'security');

			// Get the data sent via AJAX
			if ( isset($_POST['yt_template_name']) && !empty($_POST['yt_template_name']) ) {

				global $wpdb;

				$table_name = $wpdb->prefix . 'yatco_connect_templates';

				$template_name = sanitize_text_field($_POST['yt_template_name']);

				$no_of_grids = sanitize_text_field($_POST['yt_cards_count']);

				$html_contents = trim($_POST['yt_html_content']);

				$html_editor_contents = trim($_POST['yt_html_editor_content']);

				$styles = $_POST['styleString'];

				$updated_styles = $_POST['updatedstyleString'];

				$editTemplate = $_POST['editTemplate'];
				
				$is_default = filter_var(sanitize_text_field($_POST['is_default']), FILTER_VALIDATE_BOOLEAN) ? 1 : 0;

				if($editTemplate == "true"){
					
					$templateID = $_POST['templateID'];

					$this->update_unique_id($templateID);

					$wpdb->update(
						$table_name,
						[
							'template_name' => $template_name,
							'no_of_grids' => $no_of_grids,
							'html_contents' => $html_contents,
							'html_editor_contents' => $html_editor_contents,
							'styles' => $styles,
							'updated_styles' => $updated_styles,
							'is_default' => $is_default,
						],
						[
							'id' => $templateID,
						]
					);
					
					if ($wpdb->last_error) {

						echo 'Error: ' . $wpdb->last_error;

					} else {

						echo 'Template updated successfully!';

					}

				}else{

					$wpdb->insert(
						$table_name,
						[
							'template_name' => $template_name,
							'no_of_grids' => $no_of_grids,
							'html_contents' => $html_contents,
							'html_editor_contents' => $html_editor_contents,
							'styles' => $styles,
							'updated_styles' => $updated_styles,
							'is_default' => $is_default,
							'unique_id' => time(),
						]
					);

					if ($wpdb->last_error) {

						echo 'Error: ' . $wpdb->last_error;

					} else {

						echo 'Template Added successfully!';

					}

				}

			} else {

				echo 'Missing Template Name.';

			}

			wp_die();

		}

		function yt_update_template_usage() {

			check_ajax_referer('yatco_nonce', 'security');

			if ( isset($_POST['yt_grid_template']) && isset($_POST['yt_list_template']) && isset($_POST['yt_single_template']) ) {

				update_option('yt_grid_template', sanitize_text_field($_POST['yt_grid_template']));

				update_option('yt_list_template', sanitize_text_field($_POST['yt_list_template']));

				update_option('yt_single_template', sanitize_text_field($_POST['yt_single_template']));

				update_option('yt_grid_template_forCharter', sanitize_text_field($_POST['yt_grid_template_forCharter']));

				update_option('yt_list_template_forCharter', sanitize_text_field($_POST['yt_list_template_forCharter']));

				update_option('yt_single_template_forCharter', sanitize_text_field($_POST['yt_single_template_forCharter']));

				die();

			}

		}

		public function allow_custom_attributes( $allowed_tags, $context ) {

			if ('post' === $context) {
				// Add allowed SVG elements and attributes
				$allowed_tags['svg'] = array(
					'xmlns'       => true,
					'xmlns:xlink' => true,
					'version'     => true,
					'viewBox'     => true, // Must be exactly "viewBox" (case sensitive)
					'xml:space'   => true,
					'fill'        => true,
					'class'       => true,
					'width'       => true,
					'height'      => true,
					'style'       => true, // Allow inline style on <svg>
					'viewbox'     => true,
				);
				$allowed_tags['g' ]   = array( 'fill' => true );
        		$allowed_tags['title'] = array( 'title' => true );
				$allowed_tags['path'] = array(
					'd'     => true,
					'style' => true, // Allow inline style on <path>
					'fill'  => true,
				);
				// You can also add other SVG elements if needed:
				$allowed_tags['circle'] = array(
					'cx'    => true,
					'cy'    => true,
					'r'     => true,
					'fill'  => true,
					'style' => true,
				);
				$allowed_tags['rect'] = array(
					'x'      => true,
					'y'      => true,
					'width'  => true,
					'height' => true,
					'fill'   => true,
					'style'  => true,
				);
			}

			// Add custom attributes to the <div> tag
			if (isset($allowed_tags['div'])) {

				$allowed_tags['div']['datatype'] = true; // Allow 'datatype'

				$allowed_tags['div']['elementid'] = true; // Allow 'elementid'

			}

			// Add custom attributes to the <span> tag
			if (isset($allowed_tags['span'])) {

				$allowed_tags['span']['datatype'] = true; // Allow 'datatype'

				$allowed_tags['span']['elementid'] = true; // Allow 'elementid'

				$allowed_tags['span']['onclick'] = true; // Allow 'onclick' for button

				if(is_admin()){
					$allowed_tags['span']['contenteditable'] = true;
				}

			}

			// Add custom attributes to the <figure> tag
			if (isset($allowed_tags['figure'])) {

				$allowed_tags['figure']['datatype'] = true; // Allow 'datatype'

				$allowed_tags['figure']['elementid'] = true; // Allow 'elementid'

			}

			// Add custom attributes to the <i> tag
			if (isset($allowed_tags['i'])) {

				$allowed_tags['i']['datatype'] = true; // Allow 'datatype'

				$allowed_tags['i']['elementid'] = true; // Allow 'elementid'

			}

			// Add custom attributes to the <button> tag
			if (isset($allowed_tags['button'])) {

				$allowed_tags['button']['onclick'] = true; // Allow 'onclick'

			}

			return $allowed_tags;
		}

		function yatco_export_templates() {

			// Verify nonce for security
			if (!isset($_POST['yatco_export_templates_nonce']) || !wp_verify_nonce($_POST['yatco_export_templates_nonce'], 'yatco_export_templates_action')) {
				
				wp_die('Security check failed');

			}
		
			global $wpdb;
			$table_name = $wpdb->prefix . 'yatco_connect_templates';
		
			// Fetch templates from database
			$templates = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
		
			if (!empty($templates)) {

				// Convert to JSON
				$json_data = json_encode($templates, JSON_PRETTY_PRINT);
		
				// Send headers for file download
				header('Content-Type: application/json');
				header('Content-Disposition: attachment; filename="templates_export.json"');

				echo $json_data;

				exit;

			} else {

				// Redirect back with an error message
				wp_redirect(admin_url('admin.php?page=yatco-template-options&export_error=1'));
				exit;

			}
		}

		function yt_export_template() {
			global $wpdb;
			// Verify nonce for security
			if (!isset($_GET['security']) || !wp_verify_nonce($_GET['security'], 'yatco_nonce')) {
				wp_die('Security check failed');
			}

			if (!isset($_GET['template_id']) || empty($_GET['template_id'])) {
				wp_die('Invalid template ID');
			}

			$table_name = $wpdb->prefix . 'yatco_connect_templates';
			$template_id = intval($_GET['template_id']);

			// Fetch the single template
			$template = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $template_id), ARRAY_A);

			if (!$template) {
				wp_die('Template not found');
			}

			// Convert to JSON
			$json_data = json_encode($template, JSON_PRETTY_PRINT);

			// Send headers for file download
			header('Content-Type: application/json');
			header('Content-Disposition: attachment; filename="template_' . $template_id . '.json"');

			echo $json_data;
			exit;
		}

		function yt_delete_template() {
		
			// Verify nonce for security
			check_ajax_referer('yatco_nonce', 'security');
		
			// Check if template_id is received
			if (isset($_POST['template_id'])) {
				
				global $wpdb;
				$table_name = $wpdb->prefix . 'yatco_connect_templates';
				
				$template_id = intval($_POST['template_id']); // Sanitize input
		
				// Delete the template from the database
				$deleted = $wpdb->delete($table_name, array('id' => $template_id), array('%d'));
		
				if ($deleted) {
					wp_send_json_success("Template deleted successfully!");
				} else {
					wp_send_json_error("Failed to delete the template.");
				}
			} else {
				wp_send_json_error("Template ID is missing.");
			}
		}

		function yt_clone_template() {
			check_ajax_referer('yatco_nonce', 'security');

			if (isset($_POST['template_id'])) {
				global $wpdb;
				$table_name = $wpdb->prefix . 'yatco_connect_templates';
				$template_id = intval($_POST['template_id']);

				// Fetch the template to clone
				$template = $wpdb->get_row(
					$wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $template_id),
					ARRAY_A
				);

				if ($template) {
					unset($template['id']);

					// ✅ Generate unique template_name copy
					$base_name = $template['template_name'];
					$copy_name = $base_name . ' ( Copy )';
					$count = 1;

					// Check if that name already exists
					while ($wpdb->get_var(
						$wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE template_name = %s", $copy_name)
					)) {
						$count++;
						$copy_name = $base_name . " ( Copy $count )";
					}

					$template['template_name'] = $copy_name;

					// Force defaults
					$template['is_default'] = 0;
					$template['unique_id'] = time();

					// Insert the cloned row
					$inserted = $wpdb->insert($table_name, $template);

					if ($inserted) {
						wp_send_json_success([
							'message' => 'Template cloned successfully!',
							'id' => $wpdb->insert_id,
							'unique_id' => $template['unique_id'],
							'template_name' => $template['template_name']
						]);
					} else {
						wp_send_json_error("Failed to clone the template.");
					}
				} else {
					wp_send_json_error("Template not found.");
				}
			} else {
				wp_send_json_error("Template ID is missing.");
			}
		}

		public function yt_use_template_as() {
			check_ajax_referer('yatco_nonce', 'security');

			if (!isset($_POST['template_id'], $_POST['option_key'])) {
				wp_send_json_error("Missing data");
			}

			$template_id = intval($_POST['template_id']);
			$option_key = sanitize_text_field($_POST['option_key']);

			$allowed_keys = [
				'yt_grid_template',
				'yt_list_template',
				'yt_single_template',
				'yt_grid_template_forCharter',
				'yt_list_template_forCharter',
				'yt_single_template_forCharter'
			];

			if (!in_array($option_key, $allowed_keys)) {
				wp_send_json_error("Invalid option key");
			}

			// ✅ Update the selected option
			update_option($option_key, $template_id);

			// ✅ Map option key to display string
			$key_map = array(
				'yt_grid_template' => 'sale_grid',
				'yt_list_template' => 'sale_list',
				'yt_single_template' => 'sale_single',
				'yt_grid_template_forCharter' => 'charter_grid',
				'yt_list_template_forCharter' => 'charter_list',
				'yt_single_template_forCharter' => 'charter_single',
			);

			$template_usage_string = array(
				"sale_grid" => "In use : <b>Sale Grid</b>",
				"sale_list" => "In use : <b>Sale List</b>",
				"sale_single" => "In use : <b>Sale Single</b>",
				"charter_grid" => "In use : <b>Charter Grid</b>",
				"charter_list" => "In use : <b>Charter List</b>",
				"charter_single" => "In use : <b>Charter Single</b>",
			);

			$usage_key = $key_map[$option_key] ?? '';
			$usage_text = $template_usage_string[$usage_key] ?? '';

			wp_send_json_success([
				'message' => "Updated option: $option_key with template ID $template_id",
				'usage_text' => $usage_text,
				'id' => $template_id,
				'selected_field' => $option_key // new field for JS to use
			]);
		}


		public function yatco_update_template_preview_callback() {
			// Retrieve the preview content from AJAX
			$content = isset($_POST['content']) ? $_POST['content'] : '';
			$updatedStyle = isset($_POST['updatedStyle']) ? $_POST['updatedStyle'] : '';
			$templateType = isset($_POST['templateType']) ? $_POST['templateType'] : '';
			$templateFor = isset($_POST['templateFor']) ? $_POST['templateFor'] : '';

			if( $templateFor == 'charter' ){
				// Call to get all charter search loop in ApiToBoss file function
				$data = $this->data->get_search_charter([]);
			}else{
				// Call to get all yacht sale search loop in ApiToBoss file function
				$data = $this->data->get_search([]);
			}
			
			$api_data = array();
			$VesselID = '';

			if( $templateFor == 'charter' ){
				if (isset($data->Results[0])) {
					$VesselID = $data->Results[0]->VesselID ?? null;
					$api_data = $data->Results[0];
				}
			}else{
				if (isset($data->Results) && is_array($data->Results)) {
					foreach ($data->Results as $result) {
						$VesselID = $result->VesselID ?? null;
				
						if (!$VesselID) {
							continue;
						}
				
						$vessel = $this->data->get_vessel_from_mlsid($VesselID);
						if (!empty($vessel)) {
							$api_data = $result; // Save the matching result
							$VesselID = $result->VesselID;
							break;
						}
					}
				}
			}

			if ( $templateType == 'single' ){

				if( $templateFor == 'charter' ){
					$api_data = $this->data->get_charter_details($VesselID);
				} else{
					$api_data = $this->data->get_vessel($VesselID);
				}

			}

			$content = $this->templateManager->replace_content_placeholders($content, $api_data, $templateType, $templateFor);
			
			$content = $this->templateManager->setupTemplate($content, $templateType);
			
			$content .=  '<style>'.$updatedStyle.'</style>';

			// Set the page title you want to use
			$preview_title = 'yatco-template-preview';
		
			// Try to retrieve the page by title
			$page = get_page_by_title($preview_title, OBJECT, 'page');
		
			if (!$page) {
				// Create a new private page if not found
				$page_data = array(
					'post_title'   => $preview_title,
					'post_content' => $content,
					'post_status'  => 'private', // Private page
					'post_type'    => 'page',
					'post_author'  => get_current_user_id(), // Optionally, set the current user as author
				);
				$page_id = wp_insert_post($page_data);
			} else {
				$page_id = $page->ID;
				// Update the existing page with the new content
				$page_data = array(
					'ID'           => $page_id,
					'post_content' => $content,
				);
				wp_update_post($page_data);
			}
		
			// Get the permalink of the preview page and output it as the response
			$preview_url = get_permalink($page_id);
			echo $preview_url;
			wp_die();
		}

		public function is_svg_safe($tmp_path) {
			$contents = file_get_contents($tmp_path);
		
			// Basic check: look for <script>, <foreignObject>, event handlers like onload, onclick, etc.
			$patterns = [
				'/<script.*?>.*?<\/script>/is',
				'/<\s*foreignObject.*?>/i',
				'/onload\s*=/i',
				'/onclick\s*=/i',
				'/onerror\s*=/i',
				'/<iframe/i',
				'/<embed/i',
				'/<object/i',
			];
		
			foreach ($patterns as $pattern) {
				if (preg_match($pattern, $contents)) {
					return false; // SVG is unsafe
				}
			}
		
			return true; // SVG looks safe
		}
		
		function patch_fileName($filename){
			$filename = ucwords(str_replace(['-', '_'], ' ', pathinfo($filename, PATHINFO_FILENAME)));
			return $filename;
		}

		function upload_custom_icon(){
			// Check user permissions
			if ( ! current_user_can('upload_files') ) {
				wp_send_json_error(['message' => 'Permission denied']);
			}
			
			// Check file is provided
			if ( empty($_FILES['file']) ) {
				wp_send_json_error(['message' => 'No file received']);
			}
			
			$file = $_FILES['file'];
			
			$trait_fileType = $_POST['trait_fileType'] ?? 'default';

			// Extract file values
			$file_type    = is_array($file['type']) ? $file['type'][0] : $file['type'];
			$file_name    = is_array($file['name']) ? $file['name'][0] : $file['name'];
			$file_tmpname = is_array($file['tmp_name']) ? $file['tmp_name'][0] : $file['tmp_name'];

			
			if ( $file_type === 'image/svg+xml' || $trait_fileType === 'svg') {
				if ( ! $this->is_svg_safe($file_tmpname) ) {
					wp_send_json_error(['message' => 'This SVG file contains potentially unsafe content.']);
				}

				// Optional: Check & store SVG warning confirmation
				$svg_confirmed = get_option('yatco_svg_upload_confirmed');
				if (!$svg_confirmed) {
					wp_send_json_error(['message' => 'svg_confirm_warning']);
				}

			}
			// Validate file type (PNG and SVG allowed)
			$allowed_types = ($trait_fileType === 'svg') ? ['image/svg+xml'] : ['image/png'];

			if ( ! in_array($file_type, $allowed_types, true) ) {
				wp_send_json_error(['message' => 'Invalid file type!']);
			}
			
			// Determine folder + JSON file
			$folder_name = ($trait_fileType === 'svg') ? 'svg' : 'png';
			$json_file = YATCO_PLUGIN_DIR . "install-assets/{$folder_name}-icons-file.json";
			$upload_dir = YATCO_PLUGIN_DIR . "install-assets/icons/{$folder_name}";

			// Make sure directory exists
			if ( ! file_exists($upload_dir) ) {
				wp_mkdir_p($upload_dir);
			}
			
			// Prepare final filename
			$filename = sanitize_file_name($file_name);
			$target_path = $upload_dir . '/' . $filename;
			if ( file_exists($target_path) ) {
				$parts = pathinfo($filename);
				$filename = $parts['filename'] . '-' . time() . '.' . $parts['extension'];
				$target_path = $upload_dir . '/' . $filename;
			}
			
			// Move the file
			if ( move_uploaded_file($file_tmpname, $target_path) ) {
				$relative_path = "icons/{$folder_name}/{$filename}";
				$icon_entry = [
					'src'  => $relative_path,
					'name' => $this->patch_fileName($filename)
				];

				// Update JSON file
				$entries = [];
				if ( file_exists($json_file) ) {
					$json = file_get_contents($json_file);
					$entries = json_decode($json, true);
					if ( ! is_array($entries) ) {
						$entries = [];
					}
				}
				// Add new entry to the top
				array_unshift($entries, $icon_entry);

				// Add new entry to the bottom
				//$entries[] = $icon_entry;

				file_put_contents($json_file, json_encode($entries, JSON_PRETTY_PRINT));
				$url = YATCO_PLUGIN_ASSETS . 'install-assets/' . $relative_path;
				wp_send_json_success(['src' => esc_url($url), 'name' => $this->patch_fileName($filename)]);
			} else {
				wp_send_json_error(['message' => 'File upload failed']);
			}
		}

		function remove_custom_icon() {
			if ( ! current_user_can('upload_files') ) {
				wp_send_json_error(['message' => 'Permission denied']);
			}
		
			$data = json_decode(file_get_contents('php://input'), true);
			
			$url = $data['url'] ?? '';
			$fileType = $data['fileType'] ?? 'png';
		
			if (empty($url)) {
				wp_send_json_error(['message' => 'Missing file URL']);
			}
		
			$filename = basename($url);
			$folder = ($fileType === 'svg') ? 'svg' : 'png';
			$json_file = YATCO_PLUGIN_DIR . "install-assets/{$folder}-icons-file.json";
			$icon_file = YATCO_PLUGIN_DIR . "install-assets/icons/{$folder}/{$filename}";
		
			// Delete the actual file
			if (file_exists($icon_file)) {
				unlink($icon_file);
			}
		
			// Remove entry from JSON
			if (file_exists($json_file)) {
				$json = file_get_contents($json_file);
				$entries = json_decode($json, true);
				if (is_array($entries)) {
					$entries = array_filter($entries, fn($item) => basename($item['src']) !== $filename);
					file_put_contents($json_file, json_encode(array_values($entries), JSON_PRETTY_PRINT));
				}
			}
		
			wp_send_json_success();
		}		
		
	}