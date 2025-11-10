<div class="cfp-tab-content" id="cfp-importandexport-tab">
<div class="import-export-container">
    <form></form>
    <!-- Import Section -->
    <div class="import-section">
        <h3>Import Your Settings</h3>
        <p>Import your form settings in JSON format.</p>
        <form method="POST" action="<?php echo admin_url('admin-post.php'); ?>" enctype="multipart/form-data">
            <input type="hidden" name="action" value="cfp_import_form_data">
            <input type="hidden" name="post_id" value="<?php echo get_the_ID(); ?>">
            <input type="file" name="cfp_import_json_file" accept=".json">
            <p class="iebutton"> <input type="submit" name="import_settings" value="Import Data" class="button"></p>
        </form>
    </div>

    <!-- Export Section -->
    <div class="export-section">
        <h3>Export Your Settings</h3>
        <p>Please select the options to export:</p>
        <form method="POST" action="<?php echo admin_url('admin-post.php'); ?>">
            <input type="hidden" name="action" value="cfp_export_form_data">
            <input type="hidden" name="post_id" value="<?php echo get_the_ID(); ?>">
            
            <p><strong>Please select the options to export:</strong></p>
            <label>
                <input type="checkbox" name="export_content_data" value="1"> Form Content Data
            </label><br>
            <label>
                <input type="checkbox" name="export_design_data" value="1"> Form Design Data
            </label><br>
            <label>
                <input type="checkbox" name="export_setting_data" value="1"> Form Setting Data
            </label><br>
            
            <p class="iebutton">
                <input type="submit" name="export_settings" value="Export Data" class="button primary-button">
            </p>
        </form>

    </div>
</div>

</div>