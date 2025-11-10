<?php
// Save options when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['boatdecker_search_form_submit'])) {
    // Sanitize and save selected checkboxes
    $selected_filters = isset($_POST['boatdecker_search_form_visible_fields']) ? array_map('sanitize_text_field', $_POST['boatdecker_search_form_visible_fields']) : [];
    
    // Serialize and update the option in WordPress
    update_option('boatdecker_search_form_visible_fields', serialize($selected_filters));

    // Success message
    echo '<div class="updated"><p>Settings saved successfully!</p></div>';
}

// Fetch stored options and unserialize
$saved_filters = get_option('boatdecker_search_form_visible_fields');
$saved_filters = !empty($saved_filters) ? unserialize($saved_filters) : [];

// Available filters
$filters = [
    "Builder",
    "LOA",
    "PriceRange",
    "Year",
    "VesselName",
    "Condition",
    "VesselType",
    "HullMaterial",
    "LocationRegion",
    "LocationCountry",
    "LocationState",
    "LocationCity",
    "MainCategory",
    "SubCategory",
    "Model",
    "Keywords",
    "MinStaterooms",
    "MinSleeps",
    "MaxDraft",
    "GrossTonnage",
    "BrokerageCompany",
    "CheckboxConcept",
    "CheckboxCommercial",
    "CheckboxAcceptsCrypto"
];
?>

<div class="wrap">
    <h1>Search Form Options</h1>
    <p style="margin-bottom:30px;">Fields Visibility</p>
    <hr>
    <form method="post">
        <p>
            <?php
            foreach ($filters as $filter) {
                // Check if the checkbox should be pre-checked based on saved options
                $checked = in_array($filter, $saved_filters) ? 'checked' : '';
                if($filter == 'Builder'){
                    echo "<h4>Basic Fields</h4>";
                }
                echo '<label>';
                echo '<input type="checkbox" name="boatdecker_search_form_visible_fields[]" value="' . esc_attr($filter) . '" ' . $checked . '> ' . esc_html($filter);
                echo '</label><br>';
                if($filter == 'Year'){
                    echo "<hr><h4>Advance Fields</h4>";
                }
            }
            ?>
        </p>
        <p class="submit">
            <input type="submit" name="boatdecker_search_form_submit" class="button button-primary" value="Save Changes">
        </p>
    </form>
</div>

<style>
    .wrap {
        max-width: 600px;
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    h1 {
        font-size: 24px;
        margin-bottom: 15px;
    }
    .form-table {
        width: 100%;
        border-spacing: 0;
    }
    .form-table th {
        text-align: left;
        padding: 10px 0;
        width: 150px;
    }
    .form-table td {
        padding: 10px 0;
    }
    input[type="number"], input[type="range"] {
        width: 100%;
        padding: 8px;
        font-size: 14px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    .button-primary {
        background: #0073aa;
        border-color: #0073aa;
        color: #fff;
        padding: 8px 14px;
        font-size: 14px;
        border-radius: 4px;
        cursor: pointer;
        transition: 0.3s;
    }
    .button-primary:hover {
        background: #005f87;
    }
</style>