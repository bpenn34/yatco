<?php

function verify_recaptcha($recaptcha_response) {
    $secret_key = get_option('yatco_connect_google_recap_api_secret', '');
    
    if (empty($secret_key) || empty($recaptcha_response)) {
        return false; // reCAPTCHA not provided or secret key missing
    }

    $response = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', [
        'body' => [
            'secret' => $secret_key,
            'response' => $recaptcha_response,
            'remoteip' => $_SERVER['REMOTE_ADDR']
        ]
    ]);

    if (is_wp_error($response)) {
        return false; // Error in communication with Google
    }

    $body = wp_remote_retrieve_body($response);
    $result = json_decode($body, true);

    return isset($result['success']) && $result['success']; // Return true if reCAPTCHA is valid
}


// Hook into form submission to capture and save the form data
function cfp_handle_form_submission() {
    if (isset($_POST['cfp_form_id']) && isset($_POST['cfp_fields'])) {
        global $wpdb;

        $secret_key = get_option('yatco_connect_google_recap_api_secret', '');

        if (!empty($secret_key)) {
            $recaptcha_response = $_POST['g-recaptcha-response'] ?? '';

            if (!verify_recaptcha($recaptcha_response)) {
                wp_redirect(add_query_arg('cfp_submission', 'recaptcha_failed', $_SERVER['HTTP_REFERER']));
                exit;
            }
        }

        // Get form data
        $form_id = intval($_POST['cfp_form_id']);
        $form_type_id = intval($_POST['FormTypeID']);
        $form_title = sanitize_text_field($_POST['cfp_form_title']); 
        $fields_data = $_POST['cfp_fields']; 
        
        // Prepare the data to insert into the submissions table
        $submission_data = [
            'form_id'        => $form_id,
            'form_type_id'   => $form_type_id,
            'form_title'     => $form_title,
            'submission_date'=> current_time('mysql'),
            'field_data'     => json_encode($fields_data)
        ];

        // Insert data into the database
        $table_name = $wpdb->prefix . 'cfp_submissions';
        $wpdb->insert($table_name, $submission_data);

        /*This redirect not working in shortcode hence adding #success below*/
        // Proper redirect after submission
        //wp_safe_redirect(add_query_arg('cfp_submission', 'success', $_SERVER['REQUEST_URI']));
        //exit;

        // Proper redirect after submission with a hash
        //wp_safe_redirect(esc_url_raw($_SERVER['REQUEST_URI'] . '#cfp_success'));
        echo "data saved in db";
        exit;

    }
}

//add_action('wp', 'cfp_handle_form_submission');
add_action('wp_ajax_cfp_handle_form_submission', 'cfp_handle_form_submission');
?>