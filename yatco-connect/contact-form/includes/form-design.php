<div class="cfp-tab-content designAdmin" id="cfp-layout-tab">
    <h3><?php esc_html_e("Form Layout & Theme", "cfp"); ?></h3>
    <table>
        <tr>
            <td><label for="cfp_form_layout"><strong><?php esc_html_e(
                "Select Form Layout:",
                "cfp"
            ); ?></strong></label></td>
            <td>
                <select name="cfp_form_layout" id="cfp_form_layout">
                    <option value="one_column" <?php selected(
                        $form_layout,
                        "one_column"
                    ); ?>>One Column</option>
                    <option value="two_column" <?php selected(
                        $form_layout,
                        "two_column"
                    ); ?>>Two Column</option>
                    <option value="three_column" <?php selected(
                        $form_layout,
                        "three_column"
                    ); ?>>Three Column</option>
                </select>
            </td>
        </tr>
        <tr>
            <td><label for="cfp_form_theme"><strong><?php esc_html_e(
                "Select a Predefined Theme:",
                "cfp"
            ); ?></strong></label></td>
            <td>
                <select name="cfp_form_theme" id="cfp_form_theme">
                    <option value="light" <?php selected(
                        $form_theme,
                        "light"
                    ); ?>>Light Theme</option>
                    <option value="dark" <?php selected(
                        $form_theme,
                        "dark"
                    ); ?>>Dark Theme</option>
                    <option value="modern" <?php selected(
                        $form_theme,
                        "modern"
                    ); ?>>Modern Theme</option>
                    <option value="minimal" <?php selected(
                        $form_theme,
                        "minimal"
                    ); ?>>Minimal Theme</option>
                </select>
            </td>
        </tr>
    </table>

    <h3><?php esc_html_e("Manual Customization", "cfp"); ?></h3>
    <input type="checkbox" id="cfp_manual_customization" name="cfp_manual_customization" value="1" 
    <?php checked($cfp_manual_customization, "1"); ?>>
    <label for="cfp_manual_customization"> <?php esc_html_e(
        "Enable Manual Customization",
        "cfp"
    ); ?></label>
    <button type="button" class="cfp-reset-design-btn" data-post-id="<?php echo get_the_ID(); ?>">
        🔄 Reset
    </button>

    <div id="manual-customization-settings" style="display: none;">
        <h3><?php esc_html_e("Form Appearance", "cfp"); ?></h3>
        <table>
    <tr>
        <td><label for="cfp_box_shadow"><strong><?php esc_html_e("Box Shadow:", "cfp"); ?></strong></label></td>
        <td class="flex">
        <input type="text" id="cfp_box_shadow_color" class="cfp-color-field" data-alpha-enabled="true" name="cfp_box_shadow_color" value="<?php echo esc_attr($cfp_box_shadow_color); ?>" />
            <input type="number" name="cfp_box_shadow_h" value="<?php echo esc_attr($cfp_box_shadow_h); ?>" placeholder="Horizontal">
            <input type="number" name="cfp_box_shadow_v" value="<?php echo esc_attr($cfp_box_shadow_v); ?>" placeholder="Vertical">
            <input type="number" name="cfp_box_shadow_blur" value="<?php echo esc_attr($cfp_box_shadow_blur); ?>" placeholder="Blur">
            <input type="number" name="cfp_box_shadow_spread" value="<?php echo esc_attr($cfp_box_shadow_spread); ?>" placeholder="Spread">
            <select name="cfp_box_shadow_type">
                <option value="">None</option>
                <option value="inset" <?php selected($cfp_box_shadow_type, 'inset'); ?>>Inset</option>
            </select>
        </td>
    </tr>
    <tr>
        <td><label for="cfp_form_bg"><strong>Background Color:</strong></label></td>
        <td>
            <input type="text" id="cfp_form_bg" class="cfp-color-field" data-alpha-enabled="true" name="cfp_form_bg" value="<?php echo $cfp_form_bg; ?>" />

    </td>
    </tr>
    <tr>
        <td><label for="cfp_form_border"><strong>Border:</strong></label></td>
        <td class="flex">
            <select name="cfp_form_border">
                <option value="none" <?php selected($cfp_form_border, 'none'); ?>>None</option>
                <option value="solid" <?php selected($cfp_form_border, 'solid'); ?>>Solid</option>
                <option value="dashed" <?php selected($cfp_form_border, 'dashed'); ?>>Dashed</option>
                <option value="dotted" <?php selected($cfp_form_border, 'dotted'); ?>>Dotted</option>
            </select>
            <input type="number" name="cfp_form_border_width_top" value="<?php echo esc_attr($cfp_form_border_width_top); ?>" placeholder="Top">
            <input type="number" name="cfp_form_border_width_right" value="<?php echo esc_attr($cfp_form_border_width_right); ?>" placeholder="Right">
            <input type="number" name="cfp_form_border_width_bottom" value="<?php echo esc_attr($cfp_form_border_width_bottom); ?>" placeholder="Bottom">
            <input type="number" name="cfp_form_border_width_left" value="<?php echo esc_attr($cfp_form_border_width_left); ?>" placeholder="Left">
            <input type="text" id="cfp_form_border_color" class="cfp-color-field" data-alpha-enabled="true" name="cfp_form_border_color" value="<?php echo esc_attr($cfp_form_border_color); ?>" />            
        </td>
    </tr>

    <tr>
        <td><label><strong>Padding:</strong></label></td>
        <td class="flex">
            <input type="number" name="cfp_form_padding_top" value="<?php echo esc_attr($cfp_form_padding_top); ?>" placeholder="Top">
            <input type="number" name="cfp_form_padding_right" value="<?php echo esc_attr($cfp_form_padding_right); ?>" placeholder="Right">
            <input type="number" name="cfp_form_padding_bottom" value="<?php echo esc_attr($cfp_form_padding_bottom); ?>" placeholder="Bottom">
            <input type="number" name="cfp_form_padding_left" value="<?php echo esc_attr($cfp_form_padding_left); ?>" placeholder="Left">
        </td>
    </tr>


    <tr>
        <td><label for="cfp_form_alignment"><strong>Alignment:</strong></label></td>
        <td>
            <input type="radio" id="cfp_form_alignment_left" name="cfp_form_alignment" value="left" <?php checked($cfp_form_alignment, "left"); ?>>
            <label for="cfp_form_alignment_left">Left</label>
            <input type="radio" id="cfp_form_alignment_center" name="cfp_form_alignment" value="center" <?php checked($cfp_form_alignment, "center"); ?>>
            <label for="cfp_form_alignment_center">Center</label>
            <input type="radio" id="cfp_form_alignment_right" name="cfp_form_alignment" value="right" <?php checked($cfp_form_alignment, "right"); ?>>
            <label for="cfp_form_alignment_right">Right</label>
        </td>
    </tr>
</table>


        <h3><?php esc_html_e("Labels", "cfp"); ?></h3>
        <table>
    <tr>
        <td><label for="cfp_label_typography"><strong>Typography:</strong></label></td>
        <td class="flex">
          
            <select name="cfp_label_font_family">
                <option value="" disabled selected>Fonts</option>
                <?php 
                    global $all_fonts;
                    foreach ($all_fonts as $group_label => $fonts) : ?>
                        <optgroup label="<?php echo esc_attr($group_label); ?>">
                            <?php foreach ($fonts as $font_name => $font_value) : ?>
                                <option value="<?php echo esc_attr($font_value); ?>" <?php selected($cfp_label_font_family, $font_value); ?>>
                                    <?php echo esc_html($font_name); ?>
                                </option>
                            <?php endforeach; ?>
                        </optgroup>
                <?php endforeach; ?>
            </select>
            <input type="number" name="cfp_label_font_weight" value="<?php echo esc_attr($cfp_label_font_weight); ?>" placeholder="Font Weight">
            <input type="number" name="cfp_label_font_size" value="<?php echo esc_attr($cfp_label_font_size); ?>" placeholder="Font Size">
            <select name="cfp_label_text_decoration">
                <option value="">Decoration</option>
                <option value="none" <?php selected($cfp_label_text_decoration, 'none'); ?>>None</option>
                <option value="underline" <?php selected($cfp_label_text_decoration, 'underline'); ?>>Underline</option>
            </select>
            <select name="cfp_label_text_transform">
                <option value="">Transform</option>
                <option value="none" <?php selected($cfp_label_text_transform, 'none'); ?>>None</option>
                <option value="uppercase" <?php selected($cfp_label_text_transform, 'uppercase'); ?>>Uppercase</option>
            </select>
        </td>
    </tr>
    <tr>
        <td><label for="cfp_label_width"><strong>Width (%):</strong></label></td>
        <td class="flex">
            <input type="number" name="cfp_label_width" value="<?php echo esc_attr($cfp_label_width); ?>">
            <label for="cfp_label_color"><strong>Label Color:</strong></label>                    
            <input type="text" id="cfp_label_color" class="cfp-color-field" data-alpha-enabled="true" name="cfp_label_color" value="<?php echo esc_attr($cfp_label_color); ?>" />            
        </td>
    </tr>
    <tr>
        <td><label for="cfp_label_alignment"><strong>Alignment:</strong></label></td>
        <td>
            <input type="radio" id="cfp_label_alignment_left" name="cfp_label_alignment" value="left" <?php checked($cfp_label_alignment, "left"); ?>>
            <label for="cfp_label_alignment_left">Left</label>
            <input type="radio" id="cfp_label_alignment_center" name="cfp_label_alignment" value="center" <?php checked($cfp_label_alignment, "center"); ?>>
            <label for="cfp_label_alignment_center">Center</label>
            <input type="radio" id="cfp_label_alignment_right" name="cfp_label_alignment" value="right" <?php checked($cfp_label_alignment, "right"); ?>>
            <label for="cfp_label_alignment_right">Right</label>
        </td>
    </tr>
    <tr>
        <td><label><strong>Margin:</strong></label></td>
        <td class="flex">
            <input type="number" name="cfp_label_margin_top" value="<?php echo esc_attr($cfp_label_margin_top); ?>" placeholder="Top">
            <input type="number" name="cfp_label_margin_right" value="<?php echo esc_attr($cfp_label_margin_right); ?>" placeholder="Right">
            <input type="number" name="cfp_label_margin_bottom" value="<?php echo esc_attr($cfp_label_margin_bottom); ?>" placeholder="Bottom">
            <input type="number" name="cfp_label_margin_left" value="<?php echo esc_attr($cfp_label_margin_left); ?>" placeholder="Left">
        </td>
    </tr>
</table>


        <h3><?php esc_html_e("Input Fields", "cfp"); ?></h3>
        <div class="cfp-tabs">
            <li class="input-tab-button active" data-tab="input-normal">Normal</li>
            <li class="input-tab-button" data-tab="input-hover">Hover</li>
            <li class="input-tab-button" data-tab="input-focus">Focus</li>
        </div>
        <div class="cfp-input-fields-tab-content active" id="input-normal-tab">
    <table>
        <tr>
            <td><label><strong>Typography:</strong></label></td>
            <td class="flex">
                <select name="cfp_input_font_family">
                    <option value="" disabled selected>Fonts</option>
                    <?php 
                        global $all_fonts;
                        foreach ($all_fonts as $group_label => $fonts) : ?>
                            <optgroup label="<?php echo esc_attr($group_label); ?>">
                                <?php foreach ($fonts as $font_name => $font_value) : ?>
                                    <option value="<?php echo esc_attr($font_value); ?>" <?php selected($cfp_input_font_family, $font_value); ?>>
                                        <?php echo esc_html($font_name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </optgroup>
                    <?php endforeach; ?>
                </select>
                <input type="number" name="cfp_input_font_weight" value="<?php echo esc_attr($cfp_input_font_weight); ?>" placeholder="Font Weight">
                <input type="number" name="cfp_input_font_size" value="<?php echo esc_attr($cfp_input_font_size); ?>" placeholder="Font Size">
                <select name="cfp_input_text_decoration">
                    <option value="">Decoration</option>
                    <option value="none" <?php selected($cfp_input_text_decoration, 'none'); ?>>None</option>
                    <option value="underline" <?php selected($cfp_input_text_decoration, 'underline'); ?>>Underline</option>
                </select>
                <select name="cfp_input_text_transform">
                    <option value="">Transform</option>
                    <option value="none" <?php selected($cfp_input_text_transform, 'none'); ?>>None</option>
                    <option value="uppercase" <?php selected($cfp_input_text_transform, 'uppercase'); ?>>Uppercase</option>
                </select>
            </td>
        </tr>
        <tr>
            <td><label><strong>Padding:</strong></label></td>
            <td class="flex">
                <input type="number" name="cfp_input_padding_top" value="<?php echo esc_attr($cfp_input_padding_top); ?>" placeholder="Top">
                <input type="number" name="cfp_input_padding_right" value="<?php echo esc_attr($cfp_input_padding_right); ?>" placeholder="Right">
                <input type="number" name="cfp_input_padding_bottom" value="<?php echo esc_attr($cfp_input_padding_bottom); ?>" placeholder="Bottom">
                <input type="number" name="cfp_input_padding_left" value="<?php echo esc_attr($cfp_input_padding_left); ?>" placeholder="Left">
            </td>
        </tr>
        <tr>
            <td><label for="cfp_input_field_border"><strong>Border:</strong></label></td>
            <td class="flex">
                <select name="cfp_input_field_border">
                    <option value="none" <?php selected($cfp_input_field_border, 'none'); ?>>None</option>
                    <option value="solid" <?php selected($cfp_input_field_border, 'solid'); ?>>Solid</option>
                    <option value="dashed" <?php selected($cfp_input_field_border, 'dashed'); ?>>Dashed</option>
                    <option value="dotted" <?php selected($cfp_input_field_border, 'dotted'); ?>>Dotted</option>
                </select>
                <input type="number" name="cfp_input_field_border_width_top" value="<?php echo esc_attr($cfp_input_field_border_width_top); ?>" placeholder="Top">
                <input type="number" name="cfp_input_field_border_width_right" value="<?php echo esc_attr($cfp_input_field_border_width_right); ?>" placeholder="Right">
                <input type="number" name="cfp_input_field_border_width_bottom" value="<?php echo esc_attr($cfp_input_field_border_width_bottom); ?>" placeholder="Bottom">
                <input type="number" name="cfp_input_field_border_width_left" value="<?php echo esc_attr($cfp_input_field_border_width_left); ?>" placeholder="Left">
                <input type="text" id="cfp_input_field_border_color" class="cfp-color-field" data-alpha-enabled="true" name="cfp_input_field_border_color" value="<?php echo $cfp_input_field_border_color; ?>" />                
            </td>
        </tr>

        <tr>
            <td><label><strong>Border Radius:</strong></label></td>
            <td class="flex">
                <input type="number" name="cfp_input_border_radius_top_left" value="<?php echo esc_attr($cfp_input_border_radius_top_left); ?>" placeholder="Top Left">
                <input type="number" name="cfp_input_border_radius_top_right" value="<?php echo esc_attr($cfp_input_border_radius_top_right); ?>" placeholder="Top Right">
                <input type="number" name="cfp_input_border_radius_bottom_right" value="<?php echo esc_attr($cfp_input_border_radius_bottom_right); ?>" placeholder="Bottom Right">
                <input type="number" name="cfp_input_border_radius_bottom_left" value="<?php echo esc_attr($cfp_input_border_radius_bottom_left); ?>" placeholder="Bottom Left">
            </td>
        </tr>

        <tr>
            <td><label for="cfp_input_field_width"><strong>Width (%):</strong></label></td>
            <td class="flex">
                <input type="number" name="cfp_input_field_width" value="<?php echo esc_attr($cfp_input_field_width); ?>">
                <label for="cfp_input_field_color"><strong>Text Color:</strong></label>
                <input type="text" id="cfp_input_field_color" class="cfp-color-field" data-alpha-enabled="true" name="cfp_input_field_color" value="<?php echo esc_attr($cfp_input_field_color); ?>" />
                <label for="cfp_input_field_bg_color"><strong>Background Color:</strong></label>
                <input type="text" id="cfp_input_field_bg_color" class="cfp-color-field" data-alpha-enabled="true" name="cfp_input_field_bg_color" value="<?php echo esc_attr($cfp_input_field_bg_color); ?>" />
            </td>
        </tr>

        <tr>
            <td><label><strong>Margin:</strong></label></td>
            <td class="flex">
                <input type="number" name="cfp_input_margin_top" value="<?php echo esc_attr($cfp_input_margin_top); ?>" placeholder="Top">
                <input type="number" name="cfp_input_margin_right" value="<?php echo esc_attr($cfp_input_margin_right); ?>" placeholder="Right">
                <input type="number" name="cfp_input_margin_bottom" value="<?php echo esc_attr($cfp_input_margin_bottom); ?>" placeholder="Bottom">
                <input type="number" name="cfp_input_margin_left" value="<?php echo esc_attr($cfp_input_margin_left); ?>" placeholder="Left">
            </td>
        </tr>
    </table>
</div>

<div class="cfp-input-fields-tab-content" id="input-hover-tab">
    <table>
        <tr>
            <td><label for="cfp_input_field_border_color_hover"><strong>Border Color:</strong></label></td>
            <td class="flex">
                <input type="text" id="cfp_input_field_border_color_hover" class="cfp-color-field" data-alpha-enabled="true" name="cfp_input_field_border_color_hover" value="<?php echo esc_attr($cfp_input_field_border_color_hover); ?>" />
                <label for="cfp_input_field_color_hover"><strong>Text Color:</strong></label>
                <input type="text" id="cfp_input_field_color_hover" class="cfp-color-field" data-alpha-enabled="true" name="cfp_input_field_color_hover" value="<?php echo esc_attr($cfp_input_field_color_hover); ?>" />
                <label for="cfp_input_field_bg_color_hover"><strong>Background Color:</strong></label>
                <input type="text" id="cfp_input_field_bg_color_hover" class="cfp-color-field" data-alpha-enabled="true" name="cfp_input_field_bg_color_hover" value="<?php echo esc_attr($cfp_input_field_bg_color_hover); ?>" />
            </td>
        </tr>
    </table>
</div>

<div class="cfp-input-fields-tab-content" id="input-focus-tab">
    <table>
        <tr>
            <td><label for="cfp_input_field_border_color_focus"><strong>Border Color:</strong></label></td>
            <td class="flex">
                <input type="text" id="cfp_input_field_border_color_focus" class="cfp-color-field" data-alpha-enabled="true" name="cfp_input_field_border_color_focus" value="<?php echo esc_attr($cfp_input_field_border_color_focus); ?>" />
                <label for="cfp_input_field_color_focus"><strong>Text Color:</strong></label>
                <input type="text" id="cfp_input_field_color_focus" class="cfp-color-field" data-alpha-enabled="true" name="cfp_input_field_color_focus" value="<?php echo esc_attr($cfp_input_field_color_focus); ?>" />
                <label for="cfp_input_field_bg_color_focus"><strong>Background Color:</strong></label>
                <input type="text" id="cfp_input_field_bg_color_focus" class="cfp-color-field" data-alpha-enabled="true" name="cfp_input_field_bg_color_focus" value="<?php echo esc_attr($cfp_input_field_bg_color_focus); ?>" />
            </td>
        </tr>


    </table>
</div>



        <h3><?php esc_html_e("Buttons", "cfp"); ?></h3>
        <div class="cfp-tabs">
            <li class="tab-button active" data-tab="button-normal">Normal</li>
            <li class="tab-button" data-tab="button-hover">Hover</li>
        </div>
        <div class="cfp-button-tab-content active" id="button-normal-tab">
    <table>
        <tr>
            <td><label><strong>Alignment:</strong></label></td>
            <td>
                <input type="radio" id="cfp_button_alignment_left" name="cfp_button_alignment" value="left" <?php checked($cfp_button_alignment, "left"); ?>> <label for="cfp_button_alignment_left">Left</label>
                <input type="radio" id="cfp_button_alignment_center" name="cfp_button_alignment" value="center" <?php checked($cfp_button_alignment, "center"); ?>> <label for="cfp_button_alignment_center">Center</label>
                <input type="radio" id="cfp_button_alignment_right" name="cfp_button_alignment" value="right" <?php checked($cfp_button_alignment, "right"); ?>> <label for="cfp_button_alignment_right">Right</label>
            </td>
        </tr>
        <tr>
            <td><label><strong>Typography:</strong></label></td>
            <td class="flex">
                <select name="cfp_button_font_family">
                    <option value="" disabled selected>Fonts</option>
                    <?php 
                        global $all_fonts;
                        foreach ($all_fonts as $group_label => $fonts) : ?>
                            <optgroup label="<?php echo esc_attr($group_label); ?>">
                                <?php foreach ($fonts as $font_name => $font_value) : ?>
                                    <option value="<?php echo esc_attr($font_value); ?>" <?php selected($cfp_button_font_family, $font_value); ?>>
                                        <?php echo esc_html($font_name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </optgroup>
                    <?php endforeach; ?>
                </select>
                <input type="number" name="cfp_button_font_weight" value="<?php echo esc_attr($cfp_button_font_weight); ?>" placeholder="Font Weight">
                <input type="number" name="cfp_button_font_size" value="<?php echo esc_attr($cfp_button_font_size); ?>" placeholder="Font Size">
                <select name="cfp_button_text_decoration">
                    <option value="">Decoration</option>
                    <option <?php selected($cfp_button_text_decoration, "None"); ?>>None</option>
                    <option <?php selected($cfp_button_text_decoration, "Underline"); ?>>Underline</option>
                </select>
                <select name="cfp_button_text_transform">
                    <option value="">Decoration</option>
                    <option <?php selected($cfp_button_text_transform, "None"); ?>>None</option>
                    <option <?php selected($cfp_button_text_transform, "Uppercase"); ?>>Uppercase</option>
                </select>
            </td>
        </tr>
        <tr>
            <td><label><strong>Padding:</strong></label></td>
            <td class="flex">
                <input type="number" name="cfp_button_padding_top" value="<?php echo esc_attr($cfp_button_padding_top); ?>" placeholder="Top">
                <input type="number" name="cfp_button_padding_right" value="<?php echo esc_attr($cfp_button_padding_right); ?>" placeholder="Right">
                <input type="number" name="cfp_button_padding_bottom" value="<?php echo esc_attr($cfp_button_padding_bottom); ?>" placeholder="Bottom">
                <input type="number" name="cfp_button_padding_left" value="<?php echo esc_attr($cfp_button_padding_left); ?>" placeholder="Left">
            </td>
        </tr>
        <tr>
            <td><label for="cfp_button_border"><strong>Border:</strong></label></td>
            <td class="flex">
                <select name="cfp_button_border">
                    <option value="none" <?php selected($cfp_button_border, "none"); ?>>None</option>
                    <option value="solid" <?php selected($cfp_button_border, "solid"); ?>>Solid</option>
                    <option value="dashed" <?php selected($cfp_button_border, "dashed"); ?>>Dashed</option>
                    <option value="dotted" <?php selected($cfp_button_border, "dotted"); ?>>Dotted</option>
                </select>
                <input type="number" name="cfp_button_border_width_top" value="<?php echo esc_attr($cfp_button_border_width_top); ?>" placeholder="Top">
                <input type="number" name="cfp_button_border_width_right" value="<?php echo esc_attr($cfp_button_border_width_right); ?>" placeholder="Right">
                <input type="number" name="cfp_button_border_width_bottom" value="<?php echo esc_attr($cfp_button_border_width_bottom); ?>" placeholder="Bottom">
                <input type="number" name="cfp_button_border_width_left" value="<?php echo esc_attr($cfp_button_border_width_left); ?>" placeholder="Left">
                <input type="text" id="cfp_button_border_color" class="cfp-color-field" data-alpha-enabled="true" name="cfp_button_border_color" value="<?php echo esc_attr($cfp_button_border_color); ?>" />
            </td>
        </tr>

        <tr>
            <td><label><strong>Border Radius:</strong></label></td>
            <td class="flex">
                <input type="number" name="cfp_button_border_radius_top_left" value="<?php echo esc_attr($cfp_button_border_radius_top_left); ?>" placeholder="Top Left">
                <input type="number" name="cfp_button_border_radius_top_right" value="<?php echo esc_attr($cfp_button_border_radius_top_right); ?>" placeholder="Top Right">
                <input type="number" name="cfp_button_border_radius_bottom_right" value="<?php echo esc_attr($cfp_button_border_radius_bottom_right); ?>" placeholder="Bottom Right">
                <input type="number" name="cfp_button_border_radius_bottom_left" value="<?php echo esc_attr($cfp_button_border_radius_bottom_left); ?>" placeholder="Bottom Left">
            </td>
        </tr>

        <tr>
            <td><label for="cfp_button_width"><strong>Width (%):</strong></label></td>
            <td class="flex">
                <input type="number" name="cfp_button_width" value="<?php echo esc_attr($cfp_button_width); ?>">
                <label for="cfp_button_color"><strong>Text Color:</strong></label>
                <input type="text" id="cfp_button_color" class="cfp-color-field" data-alpha-enabled="true" name="cfp_button_color" value="<?php echo esc_attr($cfp_button_color); ?>" />
                <label for="cfp_button_bg_color"><strong>Background Color:</strong></label>
                <input type="text" id="cfp_button_bg_color" class="cfp-color-field" data-alpha-enabled="true" name="cfp_button_bg_color" value="<?php echo esc_attr($cfp_button_bg_color); ?>" />
            </td>
        </tr>

        <tr>
            <td><label><strong>Margin:</strong></label></td>
            <td class="flex">
                <input type="number" name="cfp_button_margin_top" value="<?php echo esc_attr($cfp_button_margin_top); ?>" placeholder="Top">
                <input type="number" name="cfp_button_margin_right" value="<?php echo esc_attr($cfp_button_margin_right); ?>" placeholder="Right">
                <input type="number" name="cfp_button_margin_bottom" value="<?php echo esc_attr($cfp_button_margin_bottom); ?>" placeholder="Bottom">
                <input type="number" name="cfp_button_margin_left" value="<?php echo esc_attr($cfp_button_margin_left); ?>" placeholder="Left">
            </td>
        </tr>

        <tr class="newsletter_form_gap">
        <td><label for="newsletter_form_gap"><strong>Newsletter Gap:</strong></label></td>
        <td><input class="newsletter_form_gap" type="number" name="newsletter_form_gap" value="<?php echo esc_attr($newsletter_form_gap); ?>"></td>            
        </tr>
    </table>
</div>

<div class="cfp-button-tab-content" id="button-hover-tab">
    <table>
        <tr>
            <td><label for="cfp_button_border_color_hover"><strong>Border Color:</strong></label></td>
            <td class="flex">
                <input type="text" id="cfp_button_border_color_hover" class="cfp-color-field" data-alpha-enabled="true" name="cfp_button_border_color_hover" value="<?php echo esc_attr($cfp_button_border_color_hover); ?>" />
                <label for="cfp_button_color_hover"><strong>Text Color:</strong></label>
                <input type="text" id="cfp_button_color_hover" class="cfp-color-field" data-alpha-enabled="true" name="cfp_button_color_hover" value="<?php echo esc_attr($cfp_button_color_hover); ?>" />
                <label for="cfp_button_bg_color_hover"><strong>Background Color:</strong></label>
                <input type="text" id="cfp_button_bg_color_hover" class="cfp-color-field" data-alpha-enabled="true" name="cfp_button_bg_color_hover" value="<?php echo esc_attr($cfp_button_bg_color_hover); ?>" />
            </td>
        </tr>
    </table>
</div>

    </div>
</div>
