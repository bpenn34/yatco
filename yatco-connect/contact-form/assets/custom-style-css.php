<?php
function cfp_generate_custom_css($post_id) {
    $plugin_dir = CFP_PLUGIN_PATH . 'assets/';
    $plugin_url = CFP_PLUGIN_URL . 'assets/';
    
    $css_file = $plugin_dir . "cfp-style-{$post_id}.css";
    $css_url = $plugin_url . "cfp-style-{$post_id}.css";
    
    $cfp_manual_customization = get_post_meta($post_id, 'cfp_manual_customization', true);
    if ($cfp_manual_customization !== '1') {
        return false;
    }

    $css = "#cfp-form-{$post_id} {\n";
    
    // Form Box Shadow
$box_shadow_h = get_post_meta($post_id, 'cfp_box_shadow_h', true);
$box_shadow_v = get_post_meta($post_id, 'cfp_box_shadow_v', true);
$box_shadow_blur = get_post_meta($post_id, 'cfp_box_shadow_blur', true);
$box_shadow_spread = get_post_meta($post_id, 'cfp_box_shadow_spread', true);
$box_shadow_color = get_post_meta($post_id, 'cfp_box_shadow_color', true);
$box_shadow_type = get_post_meta($post_id, 'cfp_box_shadow_type', true);

// Ensure at least one value is set (including 0)
if (
    is_numeric($box_shadow_h) || is_numeric($box_shadow_v) || 
    is_numeric($box_shadow_blur) || is_numeric($box_shadow_spread) || 
    !empty($box_shadow_color) || !empty($box_shadow_type)
) {
    $box_shadow_values = [];

    if (is_numeric($box_shadow_h)) {
        $box_shadow_values[] = "{$box_shadow_h}px";
    }
    if (is_numeric($box_shadow_v)) {
        $box_shadow_values[] = "{$box_shadow_v}px";
    }
    if (is_numeric($box_shadow_blur)) {
        $box_shadow_values[] = "{$box_shadow_blur}px";
    }
    if (is_numeric($box_shadow_spread)) {
        $box_shadow_values[] = "{$box_shadow_spread}px";
    }
    if (!empty($box_shadow_color)) {
        $box_shadow_values[] = $box_shadow_color;
    }
    if (!empty($box_shadow_type)) {
        $box_shadow_values[] = $box_shadow_type;
    }

    // Only add box-shadow if there are values
    if (!empty($box_shadow_values)) {
        $css .= "    box-shadow: " . implode(' ', $box_shadow_values) . ";\n";
    }
}
   

    // Form Background
    $form_bg = get_post_meta($post_id, 'cfp_form_bg', true);
    if ($form_bg) {
        $css .= "    background-color: {$form_bg};\n";
    }

    // Form Border
    $border_style = get_post_meta($post_id, 'cfp_form_border', true);
    $border_width_top = get_post_meta($post_id, 'cfp_form_border_width_top', true);
    $border_width_right = get_post_meta($post_id, 'cfp_form_border_width_right', true);
    $border_width_bottom = get_post_meta($post_id, 'cfp_form_border_width_bottom', true);
    $border_width_left = get_post_meta($post_id, 'cfp_form_border_width_left', true);    
    $border_color = get_post_meta($post_id, 'cfp_form_border_color', true);
    
    if (($border_style && ($border_width_top || $border_width_right || $border_width_bottom || $border_width_left) && $border_color)) {
        if ($border_width_top) {
            $css .= "    border-top: {$border_width_top}px {$border_style} {$border_color};\n";
        }
        if ($border_width_right) {
            $css .= "    border-right: {$border_width_right}px {$border_style} {$border_color};\n";
        }
        if ($border_width_bottom) {
            $css .= "    border-bottom: {$border_width_bottom}px {$border_style} {$border_color};\n";
        }
        if ($border_width_left) {
            $css .= "    border-left: {$border_width_left}px {$border_style} {$border_color};\n";
        }
    }
    
    

    // Form Padding
    $form_padding_top = get_post_meta($post_id, 'cfp_form_padding_top', true);
    $form_padding_right = get_post_meta($post_id, 'cfp_form_padding_right', true);
    $form_padding_bottom = get_post_meta($post_id, 'cfp_form_padding_bottom', true);
    $form_padding_left = get_post_meta($post_id, 'cfp_form_padding_left', true);
    if ($form_padding_top || $form_padding_right || $form_padding_bottom || $form_padding_left) {
        $css .= "    padding: {$form_padding_top}px {$form_padding_right}px {$form_padding_bottom}px {$form_padding_left}px;\n";
    }

    // Form Alignment
    $form_alignment = get_post_meta($post_id, 'cfp_form_alignment', true);
    if ($form_alignment) {
        $css .= "    text-align: {$form_alignment};\n";
    }

    $css .= "}\n\n";

    // Label Styles
    $label_font_family = get_post_meta($post_id, 'cfp_label_font_family', true);
    $label_font_weight = get_post_meta($post_id, 'cfp_label_font_weight', true);
    $label_font_size = get_post_meta($post_id, 'cfp_label_font_size', true);
    $label_text_decoration = get_post_meta($post_id, 'cfp_label_text_decoration', true);
    $label_text_transform = get_post_meta($post_id, 'cfp_label_text_transform', true);
    $label_width = get_post_meta($post_id, 'cfp_label_width', true);
    $label_color = get_post_meta($post_id, 'cfp_label_color', true);
    $label_alignment = get_post_meta($post_id, 'cfp_label_alignment', true);

    $css .= "#cfp-form-{$post_id} label {\n";
    if ($label_font_family) {
        $css .= "    font-family: {$label_font_family};\n";
    }
    if ($label_font_weight) {
        $css .= "    font-weight: {$label_font_weight};\n";
    }
    if ($label_font_size) {
        $css .= "    font-size: {$label_font_size}px;\n";
    }
    if ($label_text_decoration) {
        $css .= "    text-decoration: {$label_text_decoration};\n";
    }
    if ($label_text_transform) {
        $css .= "    text-transform: {$label_text_transform};\n";
    }
    if ($label_width) {
        $css .= "    width: {$label_width}%;\n";
    }
    if ($label_color) {
        $css .= "    color: {$label_color};\n";
    }
    if ($label_alignment) {
        $css .= "    text-align: {$label_alignment};\n";
    }
    $css .= "}\n\n";

    // Input Styles
    $input_font_family = get_post_meta($post_id, 'cfp_input_font_family', true);
    $input_font_weight = get_post_meta($post_id, 'cfp_input_font_weight', true);
    $input_font_size = get_post_meta($post_id, 'cfp_input_font_size', true);
    $input_text_decoration = get_post_meta($post_id, 'cfp_input_text_decoration', true);
    $input_text_transform = get_post_meta($post_id, 'cfp_input_text_transform', true);
    $input_padding_top = get_post_meta($post_id, 'cfp_input_padding_top', true);
    $input_padding_right = get_post_meta($post_id, 'cfp_input_padding_right', true);
    $input_padding_bottom = get_post_meta($post_id, 'cfp_input_padding_bottom', true);
    $input_padding_left = get_post_meta($post_id, 'cfp_input_padding_left', true);
    $input_border_style = get_post_meta($post_id, 'cfp_input_field_border', true);
    $input_border_width_top = get_post_meta($post_id, 'cfp_input_field_border_width_top', true);
    $input_border_width_right = get_post_meta($post_id, 'cfp_input_field_border_width_right', true);
    $input_border_width_bottom = get_post_meta($post_id, 'cfp_input_field_border_width_bottom', true);
    $input_border_width_left = get_post_meta($post_id, 'cfp_input_field_border_width_left', true);
    $input_border_color = get_post_meta($post_id, 'cfp_input_field_border_color', true);
    $input_width = get_post_meta($post_id, 'cfp_input_field_width', true);
    $input_color = get_post_meta($post_id, 'cfp_input_field_color', true);
    $input_background = get_post_meta($post_id, 'cfp_input_field_bg_color', true);
    $input_border_radius_top_left = get_post_meta($post_id, 'cfp_input_border_radius_top_left', true);
    $input_border_radius_top_right = get_post_meta($post_id, 'cfp_input_border_radius_top_right', true);
    $input_border_radius_bottom_right = get_post_meta($post_id, 'cfp_input_border_radius_bottom_right', true);
    $input_border_radius_bottom_left = get_post_meta($post_id, 'cfp_input_border_radius_bottom_left', true);


    $css .= "#cfp-form-{$post_id} input, #cfp-form-{$post_id} select, #cfp-form-{$post_id} textarea {\n";
    if ($input_font_family) {
        $css .= "    font-family: {$input_font_family};\n";
    }
    if ($input_font_weight) {
        $css .= "    font-weight: {$input_font_weight};\n";
    }
    if ($input_font_size) {
        $css .= "    font-size: {$input_font_size}px;\n";
    }
    if ($input_text_decoration) {
        $css .= "    text-decoration: {$input_text_decoration};\n";
    }
    if ($input_text_transform) {
        $css .= "    text-transform: {$input_text_transform};\n";
    }
    if ($input_padding_top || $input_padding_right || $input_padding_bottom || $input_padding_left) {
        $css .= "    padding: {$input_padding_top}px {$input_padding_right}px {$input_padding_bottom}px {$input_padding_left}px;\n";
    }
    if ($input_border_style || $input_border_width_top || $input_border_width_right || $input_border_width_bottom || $input_border_width_left || $input_border_color) {
        if ($input_border_width_top && $input_border_style && $input_border_color) {
            $css .= "    border-top: {$input_border_width_top}px {$input_border_style} {$input_border_color};\n";
        }
        if ($input_border_width_right && $input_border_style && $input_border_color) {
            $css .= "    border-right: {$input_border_width_right}px {$input_border_style} {$input_border_color};\n";
        }
        if ($input_border_width_bottom && $input_border_style && $input_border_color) {
            $css .= "    border-bottom: {$input_border_width_bottom}px {$input_border_style} {$input_border_color};\n";
        }
        if ($input_border_width_left && $input_border_style && $input_border_color) {
            $css .= "    border-left: {$input_border_width_left}px {$input_border_style} {$input_border_color};\n";
        }
    }
    
    if (isset($input_border_radius_top_left, $input_border_radius_top_right, $input_border_radius_bottom_right, $input_border_radius_bottom_left)) {
        $css .= "    border-radius: {$input_border_radius_top_left}px {$input_border_radius_top_right}px {$input_border_radius_bottom_right}px {$input_border_radius_bottom_left}px;\n";
    }
    if ($input_width) {
        $css .= "    width: {$input_width}%;\n";
    }
    if ($input_color) {
        $css .= "    color: {$input_color};\n";
    }
    if ($input_background) {
        $css .= "    background-color: {$input_background};\n";
    }
    $css .= "}\n\n";

    // Input Styles hover
    $input_color_hover = get_post_meta($post_id, 'cfp_input_field_color_hover', true);
    $input_background_hover = get_post_meta($post_id, 'cfp_input_field_bg_color_hover', true);
    $input_border_color_hover = get_post_meta($post_id, 'cfp_input_field_border_color_hover', true);

    $css .= "#cfp-form-{$post_id} input:hover, #cfp-form-{$post_id} select:hover, #cfp-form-{$post_id} textarea:hover {\n";
    if ($input_border_color_hover) {
        $css .= "    border-color: {$input_border_color_hover};\n";
    }
    if ($input_color_hover) {
        $css .= "    color: {$input_color_hover};\n";
    }
    if ($input_background_hover) {
        $css .= "    background-color: {$input_background_hover};\n";
    }
    $css .= "}\n\n";

    // Input Styles focus
    $input_color_focus = get_post_meta($post_id, 'cfp_input_field_color_focus', true);
    $input_background_focus = get_post_meta($post_id, 'cfp_input_field_bg_color_focus', true);
    $input_border_color_focus = get_post_meta($post_id, 'cfp_input_field_border_color_focus', true);
    
    $css .= "#cfp-form-{$post_id} input:focus, #cfp-form-{$post_id} select:focus, #cfp-form-{$post_id} textarea:focus {\n";
    if ($input_border_color_focus) {
        $css .= "    border-color: {$input_border_color_focus};\n";
    }
    if ($input_color_focus) {
        $css .= "    color: {$input_color_focus};\n";
    }
    if ($input_background_focus) {
        $css .= "    background-color: {$input_background_focus};\n";
    }
    $css .= "}\n\n";
    

    // Button Styles
    $button_font_family = get_post_meta($post_id, 'cfp_button_font_family', true);
    $button_font_weight = get_post_meta($post_id, 'cfp_button_font_weight', true);
    $button_font_size = get_post_meta($post_id, 'cfp_button_font_size', true);
    $button_text_decoration = get_post_meta($post_id, 'cfp_button_text_decoration', true);
    $button_text_transform = get_post_meta($post_id, 'cfp_button_text_transform', true);
    $button_padding_top = get_post_meta($post_id, 'cfp_button_padding_top', true);
    $button_padding_right = get_post_meta($post_id, 'cfp_button_padding_right', true);
    $button_padding_bottom = get_post_meta($post_id, 'cfp_button_padding_bottom', true);
    $button_padding_left = get_post_meta($post_id, 'cfp_button_padding_left', true);
    $button_border_style = get_post_meta($post_id, 'cfp_button_border', true);
    $button_border_width_top = get_post_meta($post_id, 'cfp_button_border_width_top', true);
    $button_border_width_right = get_post_meta($post_id, 'cfp_button_border_width_right', true);
    $button_border_width_bottom = get_post_meta($post_id, 'cfp_button_border_width_bottom', true);
    $button_border_width_left = get_post_meta($post_id, 'cfp_button_border_width_left', true);    
    $button_border_color = get_post_meta($post_id, 'cfp_button_border_color', true);
    $button_width = get_post_meta($post_id, 'cfp_button_width', true);
    $button_color = get_post_meta($post_id, 'cfp_button_color', true);
    $button_background = get_post_meta($post_id, 'cfp_button_bg_color', true);
    $button_alignment = get_post_meta($post_id, 'cfp_button_alignment', true);
    $button_border_radius_top_left = get_post_meta($post_id, 'cfp_button_border_radius_top_left', true);
    $button_border_radius_top_right = get_post_meta($post_id, 'cfp_button_border_radius_top_right', true);
    $button_border_radius_bottom_right = get_post_meta($post_id, 'cfp_button_border_radius_bottom_right', true);
    $button_border_radius_bottom_left = get_post_meta($post_id, 'cfp_button_border_radius_bottom_left', true);


    $css .= "#cfp-form-{$post_id} button {\n";
    if ($button_font_family) {
        $css .= "    font-family: {$button_font_family};\n";
    }
    if ($button_font_weight) {
        $css .= "    font-weight: {$button_font_weight};\n";
    }
    if ($button_font_size) {
        $css .= "    font-size: {$button_font_size}px;\n";
    }
    if ($button_text_decoration) {
        $css .= "    text-decoration: {$button_text_decoration};\n";
    }
    if ($button_text_transform) {
        $css .= "    text-transform: {$button_text_transform};\n";
    }
    if ($button_padding_top || $button_padding_right || $button_padding_bottom || $button_padding_left) {
        $css .= "    padding: {$button_padding_top}px {$button_padding_right}px {$button_padding_bottom}px {$button_padding_left}px;\n";
    }
    if ($button_border_style || $button_border_width_top || $button_border_width_right || $button_border_width_bottom || $button_border_width_left || $button_border_color) {
        $css .= "    border-top: {$button_border_width_top}px {$button_border_style} {$button_border_color};\n";
        $css .= "    border-right: {$button_border_width_right}px {$button_border_style} {$button_border_color};\n";
        $css .= "    border-bottom: {$button_border_width_bottom}px {$button_border_style} {$button_border_color};\n";
        $css .= "    border-left: {$button_border_width_left}px {$button_border_style} {$button_border_color};\n";
    }    
    if (isset($button_border_radius_top_left, $button_border_radius_top_right, $button_border_radius_bottom_right, $button_border_radius_bottom_left)) {
        $css .= "    border-radius: {$button_border_radius_top_left}px {$button_border_radius_top_right}px {$button_border_radius_bottom_right}px {$button_border_radius_bottom_left}px;\n";
    }
    if ($button_width) {
        $css .= "    width: {$button_width}%;\n";
    }
    if ($button_color) {
        $css .= "    color: {$button_color};\n";
    }
    if ($button_background) {
        $css .= "    background-color: {$button_background};\n";
    }
    if ($button_alignment) {
        $alignment_value = match ($button_alignment) {
            'left' => '10px auto 0 0',   // Align left
            'center' => '10px auto 0 auto',     // Align center
            'right' => '10px 0 0 auto',  // Align right
            default => '10px auto 0 auto'       // Default to center
        };
    
        $css .= "display: block; margin: {$alignment_value};";
    }

    $css .= "}\n\n";

    // Button Hover Styles
    $button_hover_border_color = get_post_meta($post_id, 'cfp_button_border_color_hover', true);
    $button_hover_text_color = get_post_meta($post_id, 'cfp_button_color_hover', true);
    $button_hover_bg = get_post_meta($post_id, 'cfp_button_bg_color_hover', true);

    $css .= "#cfp-form-{$post_id} button:hover {\n";
    if ($button_hover_border_color) {
        $css .= "    border-color: {$button_hover_border_color};\n";
    }
    if ($button_hover_text_color) {
        $css .= "    color: {$button_hover_text_color};\n";
    }
    if ($button_hover_bg) {
        $css .= "    background-color: {$button_hover_bg};\n";
    }
    $css .= "}\n\n";


    $newsletter_form  = get_post_meta($post_id, 'inline_form', true);
    $newsletter_form_gap = get_post_meta($post_id, 'newsletter_form_gap', true); // Default gap

    if ($newsletter_form) {
        $css .= "#cfp-form-{$post_id}.newsletter-form {\n";
        $css .= "   display: flex; align-items: center; gap: {$newsletter_form_gap}px;\n";
        $css .= "}\n";

        $css .= "#cfp-form-{$post_id}.newsletter-form .cfp-form-layout {\n";
        $css .= "   flex-grow: 1;\n";
        $css .= "   display: flex;\n";
        $css .= "   gap: 10px;\n";
        $css .= "}\n";

        $css .= "#cfp-form-{$post_id}.newsletter-form button {\n";
        $css .= "   margin-top: 0px;\n";
        $css .= "   white-space: nowrap;\n";
        $css .= "}\n";
    }
    $fields = get_post_meta($post_id, 'cfp_form_fields', true);

    foreach ($fields as $field) {
        // Check if the icon class exists for the current field
        if (isset($field['icon_class'])) {
            $icon_class = $field['icon_class'];
            // Now you can use $icon_class as needed
        }
    }
    if ($icon_class) {
        $input_padding_left = (int)$input_padding_left;
        $css .= "    padding-left: " . ($input_padding_left + 25) . "px;\n";
        $css .= "#cfp-form-{$post_id} input[data-icon], #cfp-form-{$post_id} select[data-icon], #cfp-form-{$post_id} textarea[data-icon] {\n";
        $css .= "}\n";

        $css .= "#cfp-form-{$post_id} input[data-icon] + i, #cfp-form-{$post_id} select[data-icon] + i, #cfp-form-{$post_id} textarea[data-icon] + i {\n";        
        $css .= "    font-size: {$input_font_size};\n";
        $css .= "    position: absolute;\n";
        $css .= "    left: 5px;\n";
        $css .= "    top: 50%;\n";
        $css .= "    transform: translateY(-50%);\n";
        $css .= "    pointer-events: none;\n";
        $css .= "}\n";

        $css .= "#cfp-form-{$post_id} label input[data-icon] + i, #cfp-form-{$post_id} label select[data-icon] + i, #cfp-form-{$post_id} label textarea[data-icon] + i {\n";        
         $css .= "    top: 63%;\n";
        $css .= "}\n";
    }    
    file_put_contents($css_file, $css);

    return $css_url;
}