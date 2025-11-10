<?php

function cfp_add_shortcode_column($columns)
{
    $columns['cfp_shortcode'] = 'Shortcode';
    return $columns;
}
add_filter('manage_cfp_form_posts_columns', 'cfp_add_shortcode_column');

function cfp_display_shortcode_column($column, $post_id)
{
    if ($column == 'cfp_shortcode') {
        echo '<code>[contact_form id="' . esc_attr($post_id) . '"]</code>';
    }
}
add_action('manage_cfp_form_posts_custom_column', 'cfp_display_shortcode_column', 10, 2);

function cfp_add_shortcode_meta_box()
{
    add_meta_box(
        'cfp_shortcode_meta',    // Meta box ID
        'Form Shortcode',        // Title
        'cfp_render_shortcode_meta_box', // Callback function
        'cfp_form',              // Custom Post Type
        'side',                  // Context (sidebar)
        'high'                   // Priority
    );
}
add_action('add_meta_boxes', 'cfp_add_shortcode_meta_box');
function cfp_render_shortcode_meta_box($post)
{
    echo '<p>Use this shortcode to display this form:</p>';
    echo '<code>[contact_form id="' . esc_attr($post->ID) . '"]</code>';
}

function cfp_render_form_shortcode($atts)
{
    // Stop shortcode execution in the WordPress admin panel, REST API, and AJAX calls
    if (is_admin() || wp_doing_ajax() || defined('REST_REQUEST') && REST_REQUEST) {
        return '';  // Don't output anything in these cases
    }
    
    // Get the form ID from the shortcode attribute
    $atts = shortcode_atts(array(
        'id' => ''
    ), $atts, 'contact_form');
    
    $form_id = intval($atts['id']); // Sanitize the ID

    if (!$form_id) {
        return '<p><strong>Error:</strong> Invalid form ID.</p>';
    }

    // Enqueue styles dynamically when the shortcode runs
    $manual_customization = get_post_meta($form_id, 'cfp_manual_customization', true);
    if ($manual_customization) {
        $css_file = plugin_dir_url(__FILE__) . "../assets/cfp-style-{$form_id}.css";
        $css_path = plugin_dir_path(__FILE__) . "../assets/cfp-style-{$form_id}.css";

        if (file_exists($css_path)) {
            wp_enqueue_style("cfp-custom-style-{$form_id}", $css_file, array(), filemtime($css_path));
        }
    }

    // Fetch form fields from post meta
    $fields = get_post_meta($form_id, 'cfp_form_fields', true);
    $formType = get_post_meta($form_id, 'newsletter_form', true);
    
    $form_layout = get_post_meta($form_id, 'cfp_form_layout', true); // Get selected layout
    $form_theme = get_post_meta ($form_id, 'cfp_form_theme', true);
    if (!is_array($fields) || empty($fields)) {
        return '<p><strong>Error:</strong> No fields found for this form.</p>';
    }

    // Fetch the Form Type
    $form_type = get_post_meta($form_id, 'cfp_form_type', true);

    //get the form id from the array
    $options = new yatcoConnect_Options();
    $form_type_id = $options->get_FormTypeID($form_type);

    if (!$form_layout) {
        $form_layout = '1'; // Single column
    }

    $inline_form = get_post_meta($form_id, 'inline_form', true);
    $inline_form = $inline_form ? 'newsletter-form' : '';

    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    // Get the full URL
    $currentURL = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    
    global $post;
    $yacht = $post->boss_data;
    
    if(isset($yacht->Result)){

        $yacht = $yacht->Result;
        
    }
    if(isset($yacht->VesselID)){

        $VesselID = $yacht->VesselID;

    }

    ob_start(); // Start output buffering
?>
    <div class="cfp-form-header">
            <?php
            // Fetch the form title dynamically
            $hide_form_title = get_post_meta($form_id, 'hide_form_title', true);
            if($hide_form_title !== '1')
            {
                echo '<h2>' . esc_html(get_the_title($form_id)). '</h2>';
            }
            ?>
            <!-- Displaying Form Title -->
    </div>
    <?php
    //get variable not redirecting hence we use session
    if(isset($_GET['cfp_submission']) && $_GET['cfp_submission'] == 'success'){
        echo "<p>Form submitted succesfully!</p>";
    }else{
        $className = $formType ? 'cpf-subscription-form' : 'cfp-form';

    ?>
    <form id="cfp-form-<?php echo esc_attr($form_id); ?>" class="<?php echo $className; ?> <?php echo esc_attr($form_theme); ?>-theme<?php echo $inline_form ? ' ' . esc_attr($inline_form) : ''; ?>" method="post">    
        <div class='hide-after-submit'>
            <div class="cfp-form-layout cfp-form-layout-<?php echo esc_attr($form_layout); ?>">
                <?php 
                // Fetch settings for labels
                $hide_labels = get_post_meta($form_id, 'hide_labels', true);
                $labels_as_placeholders = get_post_meta($form_id, 'labels_as_placeholders', true);

                foreach ($fields as $field):
                    $field_key = $field['name'];
                    $is_required = (!empty($field['required']) && $field['required'] == '1') ? 'required' : '';
                    $placeholder = ($labels_as_placeholders === '1') ? esc_attr($field['label']) : ''; // Placeholder if setting enabled
                    $icon_class = isset($fields['icon_class']) ? $fields['icon_class'] : '';
                ?>
                    <div class="cfp-field">
                        <?php if ($hide_labels !== '1' && !empty($field['label'])): // Hide labels if setting is enabled ?>
                            <label for="cfp-field-<?php echo esc_attr($field_key); ?>">
                                <?php echo esc_html($field['label']); ?>:
                            
                        <?php endif; ?>

                        <?php if ($field['type'] === 'textarea'): ?>
                            <textarea 
                                id="cfp-field-<?php echo esc_attr($field_key); ?>" 
                                name="cfp_fields[<?php echo esc_attr($field_key); ?>]" 
                                <?php echo esc_attr($is_required); ?>
                                <?php echo ($placeholder) ? 'placeholder="' . $placeholder . '"' : ''; ?>></textarea>

                        <?php elseif ($field['type'] === 'select'): ?>
                            <select 
                                id="cfp-field-<?php echo esc_attr($field_key); ?>" 
                                name="cfp_fields[<?php echo esc_attr($field_key); ?>]">
                                <option default>--Choose Options--</option>
                                <?php
                                $options = is_array($field['options']) ? $field['options'] : explode(',', $field['options']);
                                foreach ($options as $option):
                                ?>
                                    <option value="<?php echo esc_attr(trim($option)); ?>">
                                        <?php echo esc_html(trim($option)); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                        <?php elseif ($field['type'] === 'radio'): ?>
                            <div id="cfp-field-<?php echo esc_attr($field_key); ?>" class="cfp-radio-group">
                                <?php
                                $radio_options = is_array($field['radio_options']) ? $field['radio_options'] : explode(',', $field['radio_options']);
                                foreach ($radio_options as $index => $option):
                                ?>
                                    <label>
                                        <input 
                                            type="radio" 
                                            name="cfp_fields[<?php echo esc_attr($field_key); ?>]"
                                            value="<?php echo esc_attr(trim($option)); ?>"
                                            <?php echo $index === 0 ? 'checked' : ''; ?>>
                                        <?php echo esc_html(trim($option)); ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        <?php elseif ($field['type'] === 'checkbox'): ?>
                            <div id="cfp-field-<?php echo esc_attr($field_key); ?>" class="cfp-checkbox-group">
                                <?php
                                $checkbox_options = is_array($field['checkbox_options']) ? $field['checkbox_options'] : explode(',', $field['checkbox_options']);
                                foreach ($checkbox_options as $option):
                                ?>
                                    <label>
                                        <input 
                                            type="checkbox" 
                                            name="cfp_fields[<?php echo esc_attr($field_key); ?>][]"
                                            value="<?php echo esc_attr(trim($option)); ?>">
                                        <?php echo esc_html(trim($option)); ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                            <?php elseif ($field['type'] === 'date'): ?>
                            <div id="cfp-field-<?php echo esc_attr($field_key); ?>" class="cfp-date-group">
                                <input 
                                    type="date" 
                                    name="cfp_fields[<?php echo esc_attr($field_key); ?>]"
                                    id="cfp-field-<?php echo esc_attr($field_key); ?>-input"
                                    value="<?php echo esc_attr($field['date_format']); ?>">
                            </div>
                        
                        <?php else: ?>
                            <input 
                                type="<?php echo esc_attr($field['type']); ?>" 
                                id="cfp-field-<?php echo esc_attr($field_key); ?>" 
                                name="cfp_fields[<?php echo esc_attr($field_key); ?>]" 
                                <?php echo esc_attr($is_required); ?> 
                                <?php echo ($placeholder) ? 'placeholder="' . $placeholder . '"' : ''; ?>
                                <?php echo (!empty($field['icon_class'])) ? 'data-icon="' . esc_attr($field['icon_class']) . '"' : ''; ?>>
                                <i class="<?php echo !empty($field['icon_class']) ? esc_attr($field['icon_class']) : ''; ?>"></i>
                                </label>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php 
        $enable_recaptcha = get_option('cfp_enable_recaptcha', '0'); // Retrieve it before using

        if ($enable_recaptcha == '1' && !empty($site_key)) {
            echo '<div class="g-recaptcha" data-sitekey="' . esc_attr($site_key) . '"></div>';
        }
        ?>
        <?php if(isset($VesselID)){ ?>
            <input type="hidden" name="VesselID" value="<?php echo esc_attr($VesselID); ?>">
        <?php } ?>
            <input type="hidden" name="FormTypeID" value="<?php echo esc_attr($form_type_id); ?>">
            <input type="hidden" name="cfp_form_id" value="<?php echo esc_attr($form_id); ?>">
            <input type="hidden" name="cfp_form_title" value="<?php echo esc_html(get_the_title($form_id)); ?>"> <!-- Storing Form Title -->
            <input type="hidden" name="ReferrerUrl" value="<?php echo esc_html($currentURL); ?>">
            <input type="hidden" id="recaptcha_response" name="g-recaptcha-response">
            <button type="submit">Submit</button>
        <?php
            if (isset($_GET['cfp_submission']) && $_GET['cfp_submission'] == 'recaptcha_failed') {
            echo '<p class="cfp-error">reCAPTCHA verification failed. Please try again.</p>';
        }
        ?>
        <script src="https://www.google.com/recaptcha/api.js?render=<?php echo esc_attr(get_option('yatco_connect_google_recap_api_key', '')); ?>"></script>
        <script>
            grecaptcha.ready(function() {
                grecaptcha.execute('<?php echo esc_attr(get_option('yatco_connect_google_recap_api_key', '')); ?>', {action: 'submit'}).then(function(token) {
                    document.getElementById('recaptcha_response').value = token;
                });
            });
        </script>
    </div>
    <div class="form-success-message" style="display: none; font-size: 75%;">
        <div class="yt-alert yt-alert-success yt-text-center" role="alert">
            
            <?php
            if($form_type_id == 1){
                echo 'Your form has been submitted. <br>We will contact you shortly.';
            }else if($form_type_id == 13){
                echo "Subscribed Sucessfully!";
            }
            else{
                echo 'Your form has been submitted. <br>The listing broker will contact you shortly.';
            }
            ?>
        </div>
    </div>
</form>

<?php } ?>

<?php
    return ob_get_clean(); // Return the generated form
}

// Register the Shortcode
add_shortcode('contact_form', 'cfp_render_form_shortcode');

function add_bootstrap_toast_footer() {
    ?>
    
    <!-- Bootstrap 5 Toast for Success Message -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050; display: none;" id="toastContainer">
        <div id="successToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    Form submitted successfully!
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <?php
}
add_action('wp_footer', 'add_bootstrap_toast_footer');

?>