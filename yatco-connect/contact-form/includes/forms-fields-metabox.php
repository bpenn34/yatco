<?php 
// Add Meta Box for Form Fields
function cfp_add_meta_boxes()
{
    add_meta_box(
        'cfp_form_fields_meta',  // Meta box ID
        'Add/Edit Form Fields',  // Title
        'cfp_render_form_fields', // Callback function
        'cfp_form',              // Custom Post Type
        'normal',                // Context
        'high'                   // Priority
    );
}
add_action('add_meta_boxes', 'cfp_add_meta_boxes');

// Render the form fields meta box in the admin panel
function cfp_render_form_fields($post)
{
    // Security nonce field
    wp_nonce_field('cfp_save_meta_box', 'cfp_form_fields_nonce');

    // Retrieve existing fields from post meta
    $fields = get_post_meta($post->ID, 'cfp_form_fields', true);
    if (!$fields || !is_array($fields)) {
        $fields = []; // Ensure it's an array
    }

    $form_type = get_post_meta($post->ID, 'cfp_form_type', true);
    $form_layout = get_post_meta($post->ID, 'cfp_form_layout', true);
    $form_layout = $form_layout ?: 'one_column'; // Default: One Column
    $form_theme = get_post_meta($post->ID, 'cfp_form_theme', true);
    $newsletter_form = get_post_meta($post->ID, 'newsletter_form', true);
    $newsletter_form_gap = get_post_meta($post->ID, 'newsletter_form_gap', true);
    $cfp_manual_customization = get_post_meta($post->ID, 'cfp_manual_customization', true);

    $cfp_box_shadow_color = get_post_meta($post->ID, 'cfp_box_shadow_color', true);
    $cfp_box_shadow_h = get_post_meta($post->ID, 'cfp_box_shadow_h', true);
    $cfp_box_shadow_v = get_post_meta($post->ID, 'cfp_box_shadow_v', true);
    $cfp_box_shadow_blur = get_post_meta($post->ID, 'cfp_box_shadow_blur', true);
    $cfp_box_shadow_spread = get_post_meta($post->ID, 'cfp_box_shadow_spread', true);
    $cfp_box_shadow_type = get_post_meta($post->ID, 'cfp_box_shadow_type', true);
    $cfp_form_bg = get_post_meta($post->ID, 'cfp_form_bg', true);
    $cfp_form_border = get_post_meta($post->ID, 'cfp_form_border', true);

    $cfp_form_border_width_top = get_post_meta($post->ID, 'cfp_form_border_width_top', true);
    $cfp_form_border_width_right = get_post_meta($post->ID, 'cfp_form_border_width_right', true);
    $cfp_form_border_width_bottom = get_post_meta($post->ID, 'cfp_form_border_width_bottom', true);
    $cfp_form_border_width_left = get_post_meta($post->ID, 'cfp_form_border_width_left', true);

    $cfp_form_border_color = get_post_meta($post->ID, 'cfp_form_border_color', true);
    $cfp_form_padding_top = get_post_meta($post->ID, 'cfp_form_padding_top', true);
    $cfp_form_padding_right = get_post_meta($post->ID, 'cfp_form_padding_right', true);
    $cfp_form_padding_bottom = get_post_meta($post->ID, 'cfp_form_padding_bottom', true);
    $cfp_form_padding_left = get_post_meta($post->ID, 'cfp_form_padding_left', true);
    $cfp_form_alignment = get_post_meta($post->ID, 'cfp_form_alignment', true);
    $cfp_label_font_family = get_post_meta($post->ID, 'cfp_label_font_family', true);
    $cfp_label_font_weight = get_post_meta($post->ID, 'cfp_label_font_weight', true);
    $cfp_label_font_size = get_post_meta($post->ID, 'cfp_label_font_size', true);
    $cfp_label_text_decoration = get_post_meta($post->ID, 'cfp_label_text_decoration', true);
    $cfp_label_text_transform = get_post_meta($post->ID, 'cfp_label_text_transform', true);
    $cfp_label_width = get_post_meta($post->ID, 'cfp_label_width', true);
    $cfp_label_color = get_post_meta($post->ID, 'cfp_label_color', true);
    $cfp_label_alignment = get_post_meta($post->ID, 'cfp_label_alignment', true);

    $cfp_label_margin_top = get_post_meta($post->ID, 'cfp_label_margin_top', true);
    $cfp_label_margin_right = get_post_meta($post->ID, 'cfp_label_margin_right', true);
    $cfp_label_margin_bottom = get_post_meta($post->ID, 'cfp_label_margin_bottom', true);
    $cfp_label_margin_left = get_post_meta($post->ID, 'cfp_label_margin_left', true);


    $cfp_input_font_family = get_post_meta($post->ID, 'cfp_input_font_family', true);
    $cfp_input_font_weight = get_post_meta($post->ID, 'cfp_input_font_weight', true);
    $cfp_input_font_size = get_post_meta($post->ID, 'cfp_input_font_size', true);
    $cfp_input_text_decoration = get_post_meta($post->ID, 'cfp_input_text_decoration', true);
    $cfp_input_text_transform = get_post_meta($post->ID, 'cfp_input_text_transform', true);
    $cfp_input_padding_top = get_post_meta($post->ID, 'cfp_input_padding_top', true);
    $cfp_input_padding_right = get_post_meta($post->ID, 'cfp_input_padding_right', true);
    $cfp_input_padding_bottom = get_post_meta($post->ID, 'cfp_input_padding_bottom', true);
    $cfp_input_padding_left = get_post_meta($post->ID, 'cfp_input_padding_left', true);
    $cfp_input_margin_top = get_post_meta($post->ID, 'cfp_input_margin_top', true);
    $cfp_input_margin_right = get_post_meta($post->ID, 'cfp_input_margin_right', true);
    $cfp_input_margin_bottom = get_post_meta($post->ID, 'cfp_input_margin_bottom', true);
    $cfp_input_margin_left = get_post_meta($post->ID, 'cfp_input_margin_left', true);

    $cfp_input_border_radius_top_left = get_post_meta($post->ID, 'cfp_input_border_radius_top_left', true);
    $cfp_input_border_radius_top_right = get_post_meta($post->ID, 'cfp_input_border_radius_top_right', true);
    $cfp_input_border_radius_bottom_right = get_post_meta($post->ID, 'cfp_input_border_radius_bottom_right', true);
    $cfp_input_border_radius_bottom_left = get_post_meta($post->ID, 'cfp_input_border_radius_bottom_left', true);
    $cfp_button_border_radius_top_left = get_post_meta($post->ID, 'cfp_button_border_radius_top_left', true);
    $cfp_button_border_radius_top_right = get_post_meta($post->ID, 'cfp_button_border_radius_top_right', true);
    $cfp_button_border_radius_bottom_right = get_post_meta($post->ID, 'cfp_button_border_radius_bottom_right', true);
    $cfp_button_border_radius_bottom_left = get_post_meta($post->ID, 'cfp_button_border_radius_bottom_left', true);
    $cfp_input_field_border = get_post_meta($post->ID, 'cfp_input_field_border', true);
    $cfp_input_field_border_width_top = get_post_meta($post->ID, 'cfp_input_field_border_width_top', true);
    $cfp_input_field_border_width_right = get_post_meta($post->ID, 'cfp_input_field_border_width_right', true);
    $cfp_input_field_border_width_bottom = get_post_meta($post->ID, 'cfp_input_field_border_width_bottom', true);
    $cfp_input_field_border_width_left = get_post_meta($post->ID, 'cfp_input_field_border_width_left', true);
    $cfp_input_field_border_color = get_post_meta($post->ID, 'cfp_input_field_border_color', true);
    $cfp_input_field_width = get_post_meta($post->ID, 'cfp_input_field_width', true);
    $cfp_input_field_color = get_post_meta($post->ID, 'cfp_input_field_color', true);
    $cfp_input_field_bg_color = get_post_meta($post->ID, 'cfp_input_field_bg_color', true);
    $cfp_input_field_border_color_hover = get_post_meta($post->ID, 'cfp_input_field_border_color_hover', true);
    $cfp_input_field_color_hover = get_post_meta($post->ID, 'cfp_input_field_color_hover', true);
    $cfp_input_field_bg_color_hover = get_post_meta($post->ID, 'cfp_input_field_bg_color_hover', true);
    $cfp_input_field_border_color_focus = get_post_meta($post->ID, 'cfp_input_field_border_color_focus', true);
    $cfp_input_field_color_focus = get_post_meta($post->ID, 'cfp_input_field_color_focus', true);
    $cfp_input_field_bg_color_focus = get_post_meta($post->ID, 'cfp_input_field_bg_color_focus', true);
    $cfp_button_alignment = get_post_meta($post->ID, 'cfp_button_alignment', true);
    $cfp_button_font_family = get_post_meta($post->ID, 'cfp_button_font_family', true);
    $cfp_button_font_weight = get_post_meta($post->ID, 'cfp_button_font_weight', true);
    $cfp_button_font_size = get_post_meta($post->ID, 'cfp_button_font_size', true);
    $cfp_button_text_decoration = get_post_meta($post->ID, 'cfp_button_text_decoration', true);
    $cfp_button_text_transform = get_post_meta($post->ID, 'cfp_button_text_transform', true);
    $cfp_button_padding_top = get_post_meta($post->ID, 'cfp_button_padding_top', true);
    $cfp_button_padding_right = get_post_meta($post->ID, 'cfp_button_padding_right', true);
    $cfp_button_padding_bottom = get_post_meta($post->ID, 'cfp_button_padding_bottom', true);
    $cfp_button_padding_left = get_post_meta($post->ID, 'cfp_button_padding_left', true);
    $cfp_button_border = get_post_meta($post->ID, 'cfp_button_border', true);
    $cfp_button_border_width_top = get_post_meta($post->ID, 'cfp_button_border_width_top', true);
    $cfp_button_border_width_right = get_post_meta($post->ID, 'cfp_button_border_width_right', true);
    $cfp_button_border_width_bottom = get_post_meta($post->ID, 'cfp_button_border_width_bottom', true);
    $cfp_button_border_width_left = get_post_meta($post->ID, 'cfp_button_border_width_left', true);    
    $cfp_button_border_color = get_post_meta($post->ID, 'cfp_button_border_color', true);
    $cfp_button_width = get_post_meta($post->ID, 'cfp_button_width', true);
    $cfp_button_color = get_post_meta($post->ID, 'cfp_button_color', true);
    $cfp_button_bg_color = get_post_meta($post->ID, 'cfp_button_bg_color', true);
    $cfp_button_border_color_hover = get_post_meta($post->ID, 'cfp_button_border_color_hover', true);
    $cfp_button_color_hover = get_post_meta($post->ID, 'cfp_button_color_hover', true);
    $cfp_button_bg_color_hover = get_post_meta($post->ID, 'cfp_button_bg_color_hover', true);
    $cfp_button_margin_top = get_post_meta($post->ID, 'cfp_button_margin_top', true);
    $cfp_button_margin_right = get_post_meta($post->ID, 'cfp_button_margin_right', true);
    $cfp_button_margin_bottom = get_post_meta($post->ID, 'cfp_button_margin_bottom', true);
    $cfp_button_margin_left = get_post_meta($post->ID, 'cfp_button_margin_left', true);
    
    require_once CFP_PLUGIN_PATH . 'includes/forms.php'; 
}
?>