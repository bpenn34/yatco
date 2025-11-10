<?php
// Function to render form submissions in the admin panel
// Function to render form submissions in the admin panel
function cfp_render_all_submissions_page() {
    global $wpdb;

    // Get form IDs where 'show_form_responses' is enabled
    $form_ids = $wpdb->get_col("
        SELECT post_id FROM {$wpdb->postmeta} 
        WHERE meta_key = 'show_form_responses' AND meta_value = '1'
    ");

    if (empty($form_ids)) {
        echo '<div class="wrap"><h1>All Form Submissions</h1><p>No submissions available to display.</p></div>';
        return;
    }

    // Convert array to a comma-separated list for SQL query
    $form_ids_placeholder = implode(',', array_map('intval', $form_ids));

    // Fetch submissions only for forms where 'show_form_responses' is enabled
    $submissions = $wpdb->get_results("
        SELECT * FROM {$wpdb->prefix}cfp_submissions 
        WHERE form_id IN ($form_ids_placeholder) 
        ORDER BY submission_date DESC
    ");

    echo '<div class="wrap"><h1>All Form Submissions</h1>';

    echo '<table class="widefat fixed">
            <thead>
                <tr>
                    <th>Form ID</th>
                    <th>Form Title</th>
                    <th>Form Type ID</th>
                    <th>Submission Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>';

    if ($submissions) {
        foreach ($submissions as $submission) {
            $form_data = json_decode($submission->field_data, true);
            echo "<tr>
                <td>{$submission->form_id}</td>
                <td>{$submission->form_title}</td>
                <td>{$submission->form_type_id}</td>
                <td>{$submission->submission_date}</td>
                <td><button class='view-more-forms button button-primary' data-submission-id='{$submission->id}'>View More</button></td>
            </tr>";

            // Hidden row for "View More" details
            echo "<tr class='view-more-row-{$submission->id} view-more-details' style='display:none;'>
                    <td colspan='5'>
                        <table class='widefat'>
                            <tr><th>Field</th><th>Value</th></tr>";

            foreach ($form_data as $field_name => $field_value) {
                echo "<tr><td>" . esc_html($field_name) . "</td><td>" . esc_html($field_value) . "</td></tr>";
            }

            echo "</table></td></tr>";
        }
    } else {
        echo '<tr><td colspan="5">No submissions found.</td></tr>';
    }

    echo '</tbody></table></div>';
}


// Add the "Submissions" page to the admin menu
function cfp_add_submissions_page() {
    global $wpdb;

    // Get form IDs where 'show_form_responses' is enabled
    $form_ids = $wpdb->get_col("
        SELECT post_id FROM {$wpdb->postmeta} 
        WHERE meta_key = 'show_form_responses' AND meta_value = '1'
    ");

    // If at least one form has "Show Form Responses" enabled, display the submenu
    if (!empty($form_ids)) {
        add_submenu_page(
            'edit.php?post_type=cfp_form',
            'All Submissions',
            'All Submissions',
            'manage_options',
            'cfp-submissions',
            'cfp_render_all_submissions_page'
        );
    }
}
add_action('admin_menu', 'cfp_add_submissions_page');


function cfp_render_form_submissions_page() {
    global $wpdb;
    
    // Get the selected form ID from the URL
    $form_id = isset($_GET['form_id']) ? intval($_GET['form_id']) : 0;
    
    if ($form_id == 0) {
        echo '<div class="wrap"><h1>Select a form to view submissions.</h1></div>';
        return;
    }

    // Fetch submissions for the selected form
    $submissions = $wpdb->get_results(
        $wpdb->prepare("SELECT * FROM {$wpdb->prefix}cfp_submissions WHERE form_id = %d ORDER BY submission_date DESC", $form_id)
    );

    echo '<div class="wrap">';
    echo '<h1>Submissions for Form ID: ' . $form_id . '</h1>';

    echo '<table class="widefat fixed"><thead><tr><th>ID</th><th>Date</th><th>Form Data</th></tr></thead><tbody>';

    if ($submissions) {
        foreach ($submissions as $submission) {
            $form_data = json_decode($submission->field_data, true);
            echo "<tr><td>{$submission->id}</td><td>{$submission->submission_date}</td><td>";
            foreach ($form_data as $field_name => $field_value) {
                echo "<strong>" . esc_html($field_name) . ":</strong> " . esc_html($field_value) . "<br>";
            }
            echo "</td></tr>";
        }
    } else {
        echo '<tr><td colspan="3">No submissions found for this form.</td></tr>';
    }

    echo '</tbody></table>';
    echo '</div>';
}

