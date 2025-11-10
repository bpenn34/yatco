<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Define plugin path
define('CFP_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('CFP_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include necessary files
require_once CFP_PLUGIN_PATH . 'includes/form-submission.php';
require_once CFP_PLUGIN_PATH . 'includes/form-handler.php';
require_once CFP_PLUGIN_PATH . 'includes/form-main-setting.php';
// Enqueue styles and scripts
function cfp_enqueue_frontend_assets()
{
    global $wpdb;
        $enable_recaptcha = get_option('cfp_enable_recaptcha', '0');
        $site_key = get_option('cfp_recaptcha_site_key', '');
        $secret_key = get_option('cfp_recaptcha_secret_key', '');
     
        if ($enable_recaptcha == '1' && !empty($site_key) && !empty($secret_key)) {
            wp_enqueue_script('google-recaptcha', 'https://www.google.com/recaptcha/api.js', [], null, true );
        }
    
    // Load frontend styles and scripts
    wp_enqueue_style('cfp-style', CFP_PLUGIN_URL . 'assets/style.css', array(), YATCO_CONNECT_VERSION );

    wp_enqueue_script('cfp-script', CFP_PLUGIN_URL . 'assets/script.js', array('jquery'), YATCO_CONNECT_VERSION, true );

    // Localize script for AJAX
    wp_localize_script('cfp-script', 'cfp_ajax', array('ajax_url' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'cfp_enqueue_frontend_assets');

function cfp_enqueue_admin_assets($hook) {
    global $typenow;

    // Check if we are on any CFP Form page (Post Type: cfp_form OR Submenu Pages)
    if ($typenow === 'cfp_form' || isset($_GET['post_type']) && $_GET['post_type'] === 'cfp_form' || strpos($hook, 'cfp-submissions') !== false) {
        wp_enqueue_script('cfp-admin-script', CFP_PLUGIN_URL . 'assets/admin-scripts.js', array('jquery'), YATCO_CONNECT_VERSION, true );
        wp_enqueue_style('cfp-admin-style', CFP_PLUGIN_URL . 'assets/style.css', array(), YATCO_CONNECT_VERSION );
    }
}
add_action('admin_enqueue_scripts', 'cfp_enqueue_admin_assets');

require_once CFP_PLUGIN_PATH . 'includes/form-post-type.php'; 
require_once CFP_PLUGIN_PATH . 'includes/forms-fields-metabox.php'; 
require_once CFP_PLUGIN_PATH . 'includes/forms-data-saving.php'; 
require_once CFP_PLUGIN_PATH . 'includes/forms-shortcode.php'; 
// Hook for Export

add_action('admin_post_cfp_export_form_data', 'cfp_export_form_data');

function cfp_export_form_data() {
    // Ensure no unwanted output before headers
    ob_clean();

    // Verify user permissions
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have permission to export data.', 'text-domain'));
    }

    // Validate post_id
    if (!isset($_POST['post_id'])) {
        wp_die(__('Invalid request.', 'text-domain'));
    }

    $post_id = intval($_POST['post_id']);
    $export_data = [];

    // Check selected export options
    if (!empty($_POST['export_content_data'])) {
        $export_data['content_data'] = [
            'form_type_id' => get_post_meta($post_id, 'cfp_form_type_id', true),
            'form_fields' => get_post_meta($post_id, 'cfp_form_fields', true),
        ];
    }

    if (!empty($_POST['export_design_data'])) {
        $export_data['design_data'] = [
'cfp_manual_customization' => get_post_meta($post_id, 'cfp_manual_customization', true),
'cfp_form_layout' => get_post_meta($post_id, 'cfp_form_layout', true),
'cfp_form_theme' => get_post_meta($post_id, 'cfp_form_theme', true),
'cfp_box_shadow_color' => get_post_meta($post_id, 'cfp_box_shadow_color', true),
'cfp_box_shadow_h' => get_post_meta($post_id, 'cfp_box_shadow_h', true),
'cfp_box_shadow_v' => get_post_meta($post_id, 'cfp_box_shadow_v', true),
'cfp_box_shadow_blur' => get_post_meta($post_id, 'cfp_box_shadow_blur', true),
'cfp_box_shadow_spread' => get_post_meta($post_id, 'cfp_box_shadow_spread', true),
'cfp_box_shadow_type' => get_post_meta($post_id, 'cfp_box_shadow_type', true),
'cfp_form_bg' => get_post_meta($post_id, 'cfp_form_bg', true),
'cfp_form_border' => get_post_meta($post_id, 'cfp_form_border', true),
'cfp_form_border_width' => get_post_meta($post_id, 'cfp_form_border_width', true),
'cfp_form_border_color' => get_post_meta($post_id, 'cfp_form_border_color', true),
'cfp_form_padding_top' => get_post_meta($post_id, 'cfp_form_padding_top', true),
'cfp_form_padding_right' => get_post_meta($post_id, 'cfp_form_padding_right', true),
'cfp_form_padding_bottom' => get_post_meta($post_id, 'cfp_form_padding_bottom', true),
'cfp_form_padding_left' => get_post_meta($post_id, 'cfp_form_padding_left', true),
'cfp_form_alignment' => get_post_meta($post_id, 'cfp_form_alignment', true),
'cfp_label_font_family' => get_post_meta($post_id, 'cfp_label_font_family', true),
'cfp_label_font_weight' => get_post_meta($post_id, 'cfp_label_font_weight', true),
'cfp_label_font_size' => get_post_meta($post_id, 'cfp_label_font_size', true),
'cfp_label_text_decoration' => get_post_meta($post_id, 'cfp_label_text_decoration', true),
'cfp_label_text_transform' => get_post_meta($post_id, 'cfp_label_text_transform', true),
'cfp_label_width' => get_post_meta($post_id, 'cfp_label_width', true),
'cfp_label_color' => get_post_meta($post_id, 'cfp_label_color', true),
'cfp_label_alignment' => get_post_meta($post_id, 'cfp_label_alignment', true),
'cfp_label_margin_top' => get_post_meta($post_id, 'cfp_label_margin_top', true),
'cfp_label_margin_right' => get_post_meta($post_id, 'cfp_label_margin_right', true),
'cfp_label_margin_bottom' => get_post_meta($post_id, 'cfp_label_margin_bottom', true),
'cfp_label_margin_left' => get_post_meta($post_id, 'cfp_label_margin_left', true),
'cfp_input_font_family' => get_post_meta($post_id, 'cfp_input_font_family', true),
'cfp_input_font_weight' => get_post_meta($post_id, 'cfp_input_font_weight', true),
'cfp_input_font_size' => get_post_meta($post_id, 'cfp_input_font_size', true),
'cfp_input_text_decoration' => get_post_meta($post_id, 'cfp_input_text_decoration', true),
'cfp_input_text_transform' => get_post_meta($post_id, 'cfp_input_text_transform', true),
'cfp_input_padding_top' => get_post_meta($post_id, 'cfp_input_padding_top', true),
'cfp_input_padding_right' => get_post_meta($post_id, 'cfp_input_padding_right', true),
'cfp_input_padding_bottom' => get_post_meta($post_id, 'cfp_input_padding_bottom', true),
'cfp_input_padding_left' => get_post_meta($post_id, 'cfp_input_padding_left', true),
'cfp_input_margin_top' => get_post_meta($post_id, 'cfp_input_margin_top', true),
'cfp_input_margin_right' => get_post_meta($post_id, 'cfp_input_margin_right', true),
'cfp_input_margin_bottom' => get_post_meta($post_id, 'cfp_input_margin_bottom', true),
'cfp_input_margin_left' => get_post_meta($post_id, 'cfp_input_margin_left', true),
'cfp_input_field_border' => get_post_meta($post_id, 'cfp_input_field_border', true),
'cfp_input_field_border_width' => get_post_meta($post_id, 'cfp_input_field_border_width', true),
'cfp_input_field_border_color' => get_post_meta($post_id, 'cfp_input_field_border_color', true),
'cfp_input_field_width' => get_post_meta($post_id, 'cfp_input_field_width', true),
'cfp_input_field_color' => get_post_meta($post_id, 'cfp_input_field_color', true),
'cfp_input_field_bg_color' => get_post_meta($post_id, 'cfp_input_field_bg_color', true),
'cfp_input_field_border_color_hover' => get_post_meta($post_id, 'cfp_input_field_border_color_hover', true),
'cfp_input_field_color_hover' => get_post_meta($post_id, 'cfp_input_field_color_hover', true),
'cfp_input_field_bg_color_hover' => get_post_meta($post_id, 'cfp_input_field_bg_color_hover', true),
'cfp_input_field_border_color_focus' => get_post_meta($post_id, 'cfp_input_field_border_color_focus', true),
'cfp_input_field_color_focus' => get_post_meta($post_id, 'cfp_input_field_color_focus', true),
'cfp_input_field_bg_color_focus' => get_post_meta($post_id, 'cfp_input_field_bg_color_focus', true),
'cfp_button_alignment' => get_post_meta($post_id, 'cfp_button_alignment', true),
'cfp_button_font_family' => get_post_meta($post_id, 'cfp_button_font_family', true),
'cfp_button_font_weight' => get_post_meta($post_id, 'cfp_button_font_weight', true),
'cfp_button_font_size' => get_post_meta($post_id, 'cfp_button_font_size', true),
'cfp_button_text_decoration' => get_post_meta($post_id, 'cfp_button_text_decoration', true),
'cfp_button_text_transform' => get_post_meta($post_id, 'cfp_button_text_transform', true),
'cfp_button_padding_top' => get_post_meta($post_id, 'cfp_button_padding_top', true),
'cfp_button_padding_right' => get_post_meta($post_id, 'cfp_button_padding_right', true),
'cfp_button_padding_bottom' => get_post_meta($post_id, 'cfp_button_padding_bottom', true),
'cfp_button_padding_left' => get_post_meta($post_id, 'cfp_button_padding_left', true),
'cfp_button_border' => get_post_meta($post_id, 'cfp_button_border', true),
'cfp_button_border_width' => get_post_meta($post_id, 'cfp_button_border_width', true),
'cfp_button_border_color' => get_post_meta($post_id, 'cfp_button_border_color', true),
'cfp_button_width' => get_post_meta($post_id, 'cfp_button_width', true),
'cfp_button_color' => get_post_meta($post_id, 'cfp_button_color', true),
'cfp_button_bg_color' => get_post_meta($post_id, 'cfp_button_bg_color', true),
'cfp_button_border_color_hover' => get_post_meta($post_id, 'cfp_button_border_color_hover', true),
'cfp_button_color_hover' => get_post_meta($post_id, 'cfp_button_color_hover', true),
'cfp_button_bg_color_hover' => get_post_meta($post_id, 'cfp_button_bg_color_hover', true),
'cfp_button_margin_top' => get_post_meta($post_id, 'cfp_button_margin_top', true),
'cfp_button_margin_right' => get_post_meta($post_id, 'cfp_button_margin_right', true),
'cfp_button_margin_bottom' => get_post_meta($post_id, 'cfp_button_margin_bottom', true),
'cfp_button_margin_left' => get_post_meta($post_id, 'cfp_button_margin_left', true),
'newsletter_form_gap' => get_post_meta($post_id, 'newsletter_form_gap', true),
        ];
    }
    

    if (!empty($_POST['export_setting_data'])) {
        $export_data['setting_data'] = [
        'show_form_responses' => get_post_meta($post_id, 'show_form_responses', true),
        'hide_labels' => get_post_meta($post_id, 'hide_labels', true),
        'labels_as_placeholders' => get_post_meta($post_id, 'labels_as_placeholders', true),
        'hide_form_title' => get_post_meta($post_id, 'hide_form_title', true), 
        'newsletter_form' => get_post_meta($post_id, 'newsletter_form', true), 
        'inline_form' => get_post_meta($post_id, 'inline_form', true), 
        ];
    }

    // Prevent empty export
    if (empty($export_data)) {
        wp_die(__('No data selected for export.', 'text-domain'));
    }

    // Convert to JSON
    $json_data = json_encode($export_data, JSON_PRETTY_PRINT);

    // Set headers for file download
    header('Content-Type: application/json');
    header('Content-Disposition: attachment; filename="form-export-' . $post_id . '.json"');
    header('Content-Length: ' . strlen($json_data));
    header('Pragma: no-cache');
    header('Expires: 0');

    echo $json_data;
    exit;
}

add_action('admin_init', function() {
    add_action('admin_post_cfp_export_form_data', 'cfp_export_form_data');
});


function cfp_import_form_data() {
    error_log('Import function triggered.');

    if (!current_user_can('manage_options')) {
        error_log('Permission denied.');
        wp_die(__('You do not have permission to import data.', 'text-domain'));
    }

    if (!isset($_FILES['cfp_import_json_file']) || $_FILES['cfp_import_json_file']['error'] !== UPLOAD_ERR_OK) {
        error_log('File upload error: ' . $_FILES['cfp_import_json_file']['error']);
        wp_die(__('Invalid file upload.', 'text-domain'));
    }

    $file_content = file_get_contents($_FILES['cfp_import_json_file']['tmp_name']);
    if (!$file_content) {
        error_log('Failed to read file content.');
        wp_die(__('Failed to read the file.', 'text-domain'));
    }

    $import_data = json_decode($file_content, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log('JSON decode error: ' . json_last_error_msg());
        wp_die(__('Invalid JSON format.', 'text-domain'));
    }

    // Log data for debugging
    error_log(print_r($import_data, true));

    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    if (!$post_id) {
        error_log('Post ID is missing.');
        wp_die(__('Post ID missing.', 'text-domain'));
    }

   // Import content data dynamically
if (!empty($import_data['content_data'])) {
    if (!empty($import_data['content_data']['form_type_id'])) {
        update_post_meta($post_id, 'cfp_form_type_id', sanitize_text_field($import_data['content_data']['form_type_id']));
    }

    if (!empty($import_data['content_data']['form_fields'])) {
        update_post_meta($post_id, 'cfp_form_fields', $import_data['content_data']['form_fields']);
    }

    error_log('Content data imported.');
}

// Import design data dynamically
if (!empty($import_data['design_data'])) {
    // Loop through design data keys and update post meta if key exists
    $design_fields = [
        'cfp_manual_customization',
        'cfp_form_layout',
        'cfp_form_theme',
        'cfp_box_shadow_color',
        'cfp_box_shadow_h',
        'cfp_box_shadow_v',
        'cfp_box_shadow_blur',
        'cfp_box_shadow_spread',
        'cfp_box_shadow_type',
        'cfp_form_bg',
        'cfp_form_border',
        'cfp_form_border_width',
        'cfp_form_border_color',
        'cfp_form_padding_top',
        'cfp_form_padding_right',
        'cfp_form_padding_bottom',
        'cfp_form_padding_left',
        'cfp_form_alignment',
        'cfp_label_font_family',
        'cfp_label_font_weight',
        'cfp_label_font_size',
        'cfp_label_text_decoration',
        'cfp_label_text_transform',
        'cfp_label_width',
        'cfp_label_color',
        'cfp_label_alignment',
        'cfp_label_margin_top',
        'cfp_label_margin_right',
        'cfp_label_margin_bottom',
        'cfp_label_margin_left',
        'cfp_input_font_family',
        'cfp_input_font_weight',
        'cfp_input_font_size',
        'cfp_input_text_decoration',
        'cfp_input_text_transform',
        'cfp_input_padding_top',
        'cfp_input_padding_right',
        'cfp_input_padding_bottom',
        'cfp_input_padding_left',
        'cfp_input_margin_top',
        'cfp_input_margin_right',
        'cfp_input_margin_bottom',
        'cfp_input_margin_left',
        'cfp_input_field_border',
        'cfp_input_field_border_width',
        'cfp_input_field_border_color',
        'cfp_input_field_width',
        'cfp_input_field_color',
        'cfp_input_field_bg_color',
        'cfp_input_field_border_color_hover',
        'cfp_input_field_color_hover',
        'cfp_input_field_bg_color_hover',
        'cfp_input_field_border_color_focus',
        'cfp_input_field_color_focus',
        'cfp_input_field_bg_color_focus',
        'cfp_button_alignment',
        'cfp_button_font_family',
        'cfp_button_font_weight',
        'cfp_button_font_size',
        'cfp_button_text_decoration',
        'cfp_button_text_transform',
        'cfp_button_padding_top',
        'cfp_button_padding_right',
        'cfp_button_padding_bottom',
        'cfp_button_padding_left',
        'cfp_button_border',
        'cfp_button_border_width',
        'cfp_button_border_color',
        'cfp_button_width',
        'cfp_button_color',
        'cfp_button_bg_color',
        'cfp_button_border_color_hover',
        'cfp_button_color_hover',
        'cfp_button_bg_color_hover',
        'cfp_button_margin_top',
        'cfp_button_margin_right',
        'cfp_button_margin_bottom',
        'cfp_button_margin_left',
        'newsletter_form_gap'
    ];

    foreach ($design_fields as $field) {
        if (!empty($import_data['design_data'][$field])) {
            update_post_meta($post_id, $field, sanitize_text_field($import_data['design_data'][$field]));
        }
    }

    error_log('Design data imported.');
}

// Import setting data dynamically
if (!empty($import_data['setting_data'])) {
    // Loop through setting data keys and update post meta if key exists
    $setting_fields = [
        'show_form_responses',
        'hide_labels',
        'labels_as_placeholders',
        'hide_form_title',
        'newsletter_form', 
        'inline_form', 
    ];

    foreach ($setting_fields as $field) {
        if (isset($import_data['setting_data'][$field])) {
            update_post_meta($post_id, $field, sanitize_text_field($import_data['setting_data'][$field]));
        }
    }

    error_log('Settings data imported.');
}


    // Redirect back to edit screen with a success message
    error_log('Import successful. Redirecting...');
    wp_redirect(admin_url('post.php?post=' . $post_id . '&action=edit&import_success=1'));
    exit;
}

add_action('admin_post_cfp_import_form_data', 'cfp_import_form_data');
add_action('admin_init', function() {
    add_action('admin_post_cfp_import_form_data', 'cfp_import_form_data');
});

require_once CFP_PLUGIN_PATH . 'includes/custom-style-css.php'; 
add_action('save_post_cfp_form', 'cfp_generate_custom_css', 10, 3);

function cfp_generate_css_on_form_save($post_id) {
    if (get_post_type($post_id) !== 'cfp_form') {
        return;
    }
    cfp_generate_custom_css($post_id); // Call your existing function to regenerate CSS
}
add_action('save_post', 'cfp_generate_css_on_form_save');



function cfp_enqueue_dynamic_form_styles() {
    global $post;

    if (!$post) {
        return;
    }

    // Check if the content contains the [contact_form] shortcode with an ID
    if (preg_match_all('/\[contact_form id="(\d+)"\]/', $post->post_content, $matches)) {
        foreach ($matches[1] as $form_id) {
            // Check if manual customization is enabled
            $manual_customization = get_post_meta($form_id, 'cfp_manual_customization', true);

            if ($manual_customization) { // Only enqueue if enabled
                $css_file = plugin_dir_url(__FILE__) . "assets/cfp-style-{$form_id}.css";
                $css_path = plugin_dir_path(__FILE__) . "assets/cfp-style-{$form_id}.css";

                // Check if the specific CSS file exists before enqueuing
                if (file_exists($css_path)) {
                    wp_enqueue_style("cfp-custom-style-{$form_id}", $css_file, array(), filemtime($css_path));
                }
            }
        }
    }
}
/*we are enqueing it while shorcode rendering*/
//add_action('wp_enqueue_scripts', 'cfp_enqueue_dynamic_form_styles');


add_action('wp_ajax_cfp_reset_form_design', 'cfp_reset_form_design');

function cfp_reset_form_design() {
    if (!isset($_POST['post_id'])) {
        wp_send_json_error(['message' => 'Form ID is missing']);
    }

    $post_id = intval($_POST['post_id']);

    if (!current_user_can('edit_post', $post_id)) {
        wp_send_json_error(['message' => 'Unauthorized action.']);
    }

    // Define design-related meta fields
    $design_meta_keys = [
        'cfp_manual_customization',
        'cfp_box_shadow_color',
        'cfp_box_shadow_h',
        'cfp_box_shadow_v',
        'cfp_box_shadow_blur',
        'cfp_box_shadow_spread',
        'cfp_box_shadow_type',
        'cfp_form_bg',
        'cfp_form_border',
        'cfp_form_border_width_top',
        'cfp_form_border_width_right',
        'cfp_form_border_width_bottom',
        'cfp_form_border_width_left',
        'cfp_form_border_color',
        'cfp_form_padding_top',
        'cfp_form_padding_right',
        'cfp_form_padding_bottom',
        'cfp_form_padding_left',
        'cfp_form_alignment',
        'cfp_label_font_family',
        'cfp_label_font_weight',
        'cfp_label_font_size',
        'cfp_label_text_decoration',
        'cfp_label_text_transform',
        'cfp_label_width',
        'cfp_label_color',
        'cfp_label_alignment',
        'cfp_input_font_family',
        'cfp_input_font_weight',
        'cfp_input_font_size',
        'cfp_input_text_decoration',
        'cfp_input_text_transform',
        'cfp_input_padding_top',
        'cfp_input_padding_right',
        'cfp_input_padding_bottom',
        'cfp_input_padding_left',
        'cfp_input_field_border',
        'cfp_input_field_border_width_top',
        'cfp_input_field_border_width_right',
        'cfp_input_field_border_width_bottom',
        'cfp_input_field_border_width_left',
        'cfp_input_field_border_color',
        'cfp_input_field_width',
        'cfp_input_field_color',
        'cfp_input_field_bg_color',
        'cfp_input_field_border_color_hover',
        'cfp_input_field_color_hover',
        'cfp_input_field_bg_color_hover',
        'cfp_input_field_border_color_focus',
        'cfp_input_field_color_focus',
        'cfp_input_field_bg_color_focus',
        'cfp_button_alignment',
        'cfp_button_font_family',
        'cfp_button_font_weight',
        'cfp_button_font_size',
        'cfp_button_text_decoration',
        'cfp_button_text_transform',
        'cfp_button_padding_top',
        'cfp_button_padding_right',
        'cfp_button_padding_bottom',
        'cfp_button_padding_left',
        'cfp_button_border',
        'cfp_button_border_width_top',
        'cfp_button_border_width_right',
        'cfp_button_border_width_bottom',
        'cfp_button_border_width_left',
        'cfp_button_border_color',
        'cfp_button_width',
        'cfp_button_color',
        'cfp_button_bg_color',
        'cfp_button_border_color_hover',
        'cfp_button_color_hover',
        'cfp_button_bg_color_hover',

    ];

    // Loop through and delete only design-related meta fields
    foreach ($design_meta_keys as $key) {
        delete_post_meta($post_id, $key);
    }

    wp_send_json_success([
        'message' => "Design settings for Form ID {$post_id} have been reset.",
        'post_id' => $post_id
    ]);
}
// Remove Elementor's Font Awesome (ensure it's completely deregistered)
function remove_elementor_font_awesome() {
    // Deregister Elementor's Font Awesome version
    wp_deregister_style('font-awesome');
    wp_dequeue_style('font-awesome');
}
add_action('wp_enqueue_scripts', 'remove_elementor_font_awesome', 5); // Lower priority to ensure it runs early


function override_elementor_font_awesome() {
    wp_deregister_style('elementor-icons-fa');
    wp_enqueue_style('font-awesome-6', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css', array(), '6.5.1');
}
add_action('wp_enqueue_scripts', 'override_elementor_font_awesome', 20);

function cfp_enqueue_admin_scripts_icons($hook) {

    // Enqueue your custom admin JS script (make sure it loads after jQuery and icon picker)
    wp_enqueue_script('cfp-admin-script', plugin_dir_url(__FILE__) . 'assets/cfp-custom-script.js', array('jquery', 'fa-iconpicker'), null, true);
}
add_action('admin_enqueue_scripts', 'cfp_enqueue_admin_scripts_icons');

function cfp_enqueue_admin_scripts_sortable($hook) {
    global $post_type;
    if ($hook === 'post.php' || $hook === 'post-new.php' && $post_type === 'cfp_form') {
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script(
            'cfp-admin-script-sortable',
            plugins_url('assets/cfp-admin.js', __FILE__),
            ['jquery', 'jquery-ui-sortable'],
            '1.0',
            true
        );
    }
}
add_action('admin_enqueue_scripts', 'cfp_enqueue_admin_scripts_sortable');

function cfp_enqueue_admin_scripts($hook) {
    global $post_type;

    // Load scripts only on your custom form type edit page
    if ('cfp_form' !== $post_type) {
        return;
    }
    //default wordpress color picker
    wp_enqueue_style('wp-color-picker');
    $args = array(
        'strategy'  => 'defer',
        'in_footer' => true,
    );
    //registering alpha library to give opacity to color picker
    wp_register_script( 'wp-color-picker-alpha', plugin_dir_url(__FILE__) . 'assets/wp-color-picker-alpha.js', array( 'jquery', 'wp-color-picker' ), '3.0.4', $args );

    // Enqueue the wp-color-picker-alpha script
    wp_enqueue_script( 'wp-color-picker-alpha' );

    wp_enqueue_script('cfp-color-picker', plugin_dir_url(__FILE__) . 'assets/cfp-custom-script.js', array( 'wp-color-picker', 'wp-color-picker-alpha' ), false, true);
}
add_action('admin_enqueue_scripts', 'cfp_enqueue_admin_scripts');

function get_google_fonts() {
    $transient_key = 'cfp_google_fonts';
    $cached_fonts = get_transient($transient_key);

    if ($cached_fonts) {
        return $cached_fonts; // Use cached fonts if available
    }

    $api_key = 'xxxx'; // Replace with your API key
    $url = "https://www.googleapis.com/webfonts/v1/webfonts?key={$api_key}";

    $response = wp_remote_get($url);

    if (is_wp_error($response)) {
        return []; // Return an empty array if API fails
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (isset($data['items'])) {
        $google_fonts = [];
        foreach ($data['items'] as $font) {
            $google_fonts[$font['family']] = $font['family'];
        }

        set_transient($transient_key, $google_fonts, DAY_IN_SECONDS); // Cache for 1 day
        return $google_fonts;
    }

    return [];
}

function set_global_fonts() {
    global $all_fonts; // Declare as global

    $all_fonts = [
        'System Fonts' => [
            'Arial' => 'Arial',
            'Helvetica' => 'Helvetica',
            'Verdana' => 'Verdana',
            'Georgia' => 'Georgia',
            'Times New Roman' => 'Times New Roman',
            'Courier New' => 'Courier New'
        ],
        'Google Fonts' => get_google_fonts() // Fetch dynamic Google Fonts
    ];
}

add_action('init', 'set_global_fonts'); 


// Hook for Import
//add_action('admin_post_cfp_import_form_data', 'cfp_import_form_data');
