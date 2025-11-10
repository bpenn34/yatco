<?php 
// Save Form Fields
function cfp_save_form_fields($post_id) {
    if (!isset($_POST['cfp_form_fields_nonce']) || !wp_verify_nonce($_POST['cfp_form_fields_nonce'], 'cfp_save_meta_box')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['cfp_form_type'])) {
        update_post_meta($post_id, 'cfp_form_type', sanitize_text_field($_POST['cfp_form_type']));
    }

    if (!isset($_POST['cfp_form_fields']) || !is_array($_POST['cfp_form_fields'])) {
        delete_post_meta($post_id, 'cfp_form_fields');
        return;
    }

    foreach ($_POST['cfp_form_fields'] as $index => $field) {
        $enable_icon = isset($field['enable_icon']) ? '1' : '0';
        $icon_class = isset($field['enable_icon']) && !empty($field['icon_class']) 
            ? sanitize_text_field($field['icon_class'])
            : (isset($saved_fields[$index]['icon_class']) ? $saved_fields[$index]['icon_class'] : '');

        $fields[$index] = [
            'type'        => sanitize_text_field($field['type']),
            'name'        => sanitize_text_field($field['name']),
            'label'       => sanitize_text_field($field['label']),
            'options'     => isset($field['options']) ? array_map('sanitize_text_field', explode(',', $field['options'])) : [],
            'radio_options'   => isset($field['radio_options']) ? array_map('sanitize_text_field', explode(',', $field['radio_options'])) : [],
            'checkbox_options' => isset($field['checkbox_options']) ? array_map('sanitize_text_field', explode(',', $field['checkbox_options'])) : [],
            'date_format'     => isset($field['date_format']) ? sanitize_text_field($field['date_format']) : 'yyyy-mm-dd',
            'required'    => isset($field['required']) ? '1' : '0',
            'enable_icon' => $enable_icon,
            'icon_class'  => $icon_class,
        ];
    }

    update_post_meta($post_id, 'cfp_form_fields', $fields);


    if (isset($_POST['cfp_form_layout'])) {
        $form_layout = sanitize_text_field($_POST['cfp_form_layout']);
        update_post_meta($post_id, 'cfp_form_layout', $form_layout);
    }

    if (isset($_POST['cfp_form_theme'])) {
        $form_theme = sanitize_text_field($_POST['cfp_form_theme']);
        update_post_meta($post_id, 'cfp_form_theme', $form_theme);
    }

    // Save the form type ID
    if (isset($_POST['cfp_form_type'])) {
        $form_type = sanitize_text_field($_POST['cfp_form_type']);
        update_post_meta($post_id, 'cfp_form_type', $form_type);
    }

    // Save "Show Form Responses" Setting
    $show_form_responses = isset($_POST['show_form_responses']) ? '1' : '0';
    update_post_meta($post_id, 'show_form_responses', $show_form_responses);

     // Save "Hide Labels" Setting
     $hide_labels = isset($_POST['hide_labels']) ? '1' : '0';
     update_post_meta($post_id, 'hide_labels', $hide_labels);
 
     // Save "Make Labels as Placeholders" Setting
     $labels_as_placeholders = isset($_POST['labels_as_placeholders']) ? '1' : '0';
     update_post_meta($post_id, 'labels_as_placeholders', $labels_as_placeholders);

     // Save "Hide Form Title" Setting
     $hide_form_title = isset($_POST['hide_form_title']) ? '1' : '0';
     update_post_meta($post_id, 'hide_form_title', $hide_form_title);

     $cfp_manual_customization = isset($_POST['cfp_manual_customization']) ? '1' : '0';
    update_post_meta($post_id, 'cfp_manual_customization', $cfp_manual_customization);

    // Save "Hide Form Title" Setting
    $newsletter_form = isset($_POST['newsletter_form']) ? '1' : '0';
    update_post_meta($post_id, 'newsletter_form', $newsletter_form);
    
    // Save "Hide Form Title" Setting
    $inline_form = isset($_POST['inline_form']) ? '1' : '0';
    update_post_meta($post_id, 'inline_form', $inline_form);
    
      $form_fields = [
        'cfp_form_padding_top',
        'cfp_form_padding_right',
        'cfp_form_padding_bottom',
        'cfp_form_padding_left',
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
        'cfp_input_field_border_width_top',
        'cfp_input_field_border_width_right',
        'cfp_input_field_border_width_bottom',
        'cfp_input_field_border_width_left',
        'cfp_input_field_border_color',
        'cfp_input_border_radius_top_left',
        'cfp_input_border_radius_top_right',
        'cfp_input_border_radius_bottom_right',
        'cfp_input_border_radius_bottom_left',
        'cfp_button_border_radius_top_left',
        'cfp_button_border_radius_top_right',
        'cfp_button_border_radius_bottom_right',
        'cfp_button_border_radius_bottom_left',
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
        'cfp_button_margin_top',
        'cfp_button_margin_right',
        'cfp_button_margin_bottom',
        'cfp_button_margin_left',
        'newsletter_form_gap',
    ];
    
    foreach ($form_fields as $field_name) {
        if (isset($_POST[$field_name])) {
            // Sanitize based on field type
            $field_value = sanitize_text_field($_POST[$field_name]);
    
            // Handle specific fields with different sanitization
            switch ($field_name) {
                case 'cfp_box_shadow_color':
                case 'cfp_form_bg':
                case 'cfp_form_border_color':
                case 'cfp_label_color':
                case 'cfp_input_field_color':
                case 'cfp_input_field_bg_color':
                case 'cfp_input_field_border_color_hover':
                case 'cfp_input_field_color_hover':
                case 'cfp_input_field_bg_color_hover':
                case 'cfp_input_field_border_color_focus':
                case 'cfp_input_field_color_focus':
                case 'cfp_input_field_bg_color_focus':
                case 'cfp_button_color':
                case 'cfp_button_bg_color':
                case 'cfp_button_border_color_hover':
                case 'cfp_button_color_hover':
                case 'cfp_button_bg_color_hover':
                    //$field_value = sanitize_hex_color($field_value); // Handle color fields // skipping this test as alpha values will not filter
                    if (empty($field_value)) {
                        delete_post_meta($post_id, $field_name); // ❌ Remove empty color meta instead of saving #000000
                        continue 2;
                    }
                    break;
                case 'cfp_box_shadow_h':
                case 'cfp_box_shadow_v':
                case 'cfp_box_shadow_blur':
                case 'cfp_box_shadow_spread':
                case 'cfp_form_border_width_top':
                case 'cfp_form_border_width_right':
                case 'cfp_form_border_width_bottom':
                case 'cfp_form_border_width_left':
                case 'cfp_label_margin_top';
                case 'cfp_label_margin_right';
                case 'cfp_label_margin_bottom';
                case 'cfp_label_margin_left';                  
                case 'cfp_input_field_border_width_top':
                case 'cfp_input_field_border_width_right':
                case 'cfp_input_field_border_width_bottom':
                case 'cfp_input_field_border_width_left':
                case 'cfp_input_field_width':
                case 'cfp_input_padding_top':
                case 'cfp_input_padding_right':
                case 'cfp_input_padding_bottom':
                case 'cfp_input_padding_left':
                case 'cfp_input_margin_top':
                case 'cfp_input_margin_right':
                case 'cfp_input_margin_bottom':
                case 'cfp_input_margin_left':    
                case 'cfp_input_border_radius_top_left':
                case 'cfp_input_border_radius_top_right':
                case 'cfp_input_border_radius_bottom_right':
                case 'cfp_input_border_radius_bottom_left':
                case 'cfp_button_border_radius_top_left':
                case 'cfp_button_border_radius_top_right':
                case 'cfp_button_border_radius_bottom_right':
                case 'cfp_button_border_radius_bottom_left':
                case 'cfp_button_padding_top':
                case 'cfp_button_padding_right':
                case 'cfp_button_padding_bottom':
                case 'cfp_button_padding_left':
                case 'cfp_button_border_width_top':
                case 'cfp_button_border_width_right':
                case 'cfp_button_border_width_bottom':
                case 'cfp_button_border_width_left':
                case 'cfp_button_margin_top';
                case 'cfp_button_margin_right';
                case 'cfp_button_margin_bottom';
                case 'cfp_button_margin_left';  
                case 'cfp_button_width':
                case 'cfp_form_padding_top':
                case 'cfp_form_padding_right':
                case 'cfp_form_padding_bottom':
                case 'cfp_form_padding_left':
                case 'newsletter_form_gap';
                    $field_value = is_numeric($field_value) ? intval($field_value) : ''; // Handle numeric fields
                    break;
                case 'cfp_form_alignment':
                case 'cfp_label_alignment';
                case 'cfp_button_alignment';
                    $field_value = in_array($field_value, ['left', 'center', 'right']) ? $field_value : ''; // Handle alignment
                    break;
                default:
                    break;
            }
    
            if ($field_value === '' || $field_value === null) {
                delete_post_meta($post_id, $field_name);
                continue;
            }
    
            // Save the sanitized value to the post meta
            update_post_meta($post_id, $field_name, $field_value);
        }
    }
}
add_action('save_post', 'cfp_save_form_fields');
?>