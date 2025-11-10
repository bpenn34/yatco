<?php
    //initializing the template manager class from src/yatcoConnect/TemplateManager.php
    $template_manager = new yatcoConnect_TemplateManager();
    $templates = $template_manager->get_template_names();
    $duplicate_unique_ids = $template_manager->check_duplicate_unique_ids();
    $duplicate_ids = [];
    if($duplicate_unique_ids){
        echo "<strong>⚠ Warning:</strong> Found duplicate <code>unique_id</code> values:<br><ul>";
        foreach ($duplicate_unique_ids as $row) {
            echo "<li><code>{$row->unique_id}</code> used in {$row->count} rows</li>";
            $duplicate_ids[] = (int) $row->unique_id;
        }
        echo "</ul>";
    }
?>

<style>

    .yt-template-holder img{
        max-width:100%;
    }

    .nav-pills .nav-link {
      color: #ddd;
      background-color: #555;
      border-radius: 0;
      transition: background-color 0.3s ease;
    }

    .nav-pills .nav-link:hover {
      background-color: #333;
      color: #fff;
    }

    .nav-pills .nav-link.active {
      background-color: #000;
      color: #fff;
      font-weight: 500;
    }

    #pageTab{
        background-color: #777;
    }
    .table-wrapper {
      overflow-x: auto;
      border-radius: 0.5rem;
      margin-top: 2rem;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      color: #fff;
    }

    thead {
      background-color: #212529;
    }

    th, td {
      padding: 1rem;
      text-align: left;
      white-space: nowrap;
    }

    tr:nth-child(even) td {
      background-color: #1e1e1e;
    }

    tr:nth-child(odd) td {
      background-color: #2a2a2a;
    }

    th {
      background-color: #343a40;
      color: #fff;
    }

    tr:hover td {
      background-color: #3c3c3c;
    }
</style>

<div class="wrap">

    <h1>YATCO Connect Template Settings</h1>

    <div class="container py-5">
        <!-- Nav Tabs -->
        <ul class="nav nav-pills" id="pageTab" role="tablist">
            <li class="nav-item" role="presentation">
            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab">
                Sale
            </button>
            </li>
            <li class="nav-item" role="presentation">
            <button class="nav-link" id="about-tab" data-bs-toggle="tab" data-bs-target="#about" type="button" role="tab">
                Charter
            </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content p-4 border rounded bg-light" id="pageTabContent">
            <i>Select the template below</i>
            <div class="tab-pane fade show active" id="home" role="tabpanel">
                <h3>Yachts for Sale</h3>
                <div class="table-wrapper">
                    <table class="table table-dark table-hover">
                        <tr>
                            <td style="min-width:100px;">Grid View</td>
                            <td>

                            <?php
                                // Fetch the saved option value from options table for grid view
                                $yt_grid_template = get_option('yt_grid_template');

                                // Generate HTML select options
                                echo "<select id='yt-grid-template' name='yt-grid-template' data-select-template='yt_grid_template'>";
                                echo "<option value=''>None</option>";
                                foreach ($templates as $template) {
                                    $selected1 = ($template['id'] == $yt_grid_template) ? "selected" : "";
                                    echo "<option value='{$template['id']}' $selected1>{$template['template_name']}</option>";
                                }
                                echo "</select>";
                            ?>

                            </td>
                        </tr>
                        <tr>
                            <td>List View</td>
                            <td>

                            <?php
                                // Fetch the saved option value from options table for list view
                                $yt_list_template = get_option('yt_list_template');

                                // Generate HTML select options
                                echo "<select id='yt-list-template' name='yt-list-template' data-select-template='yt_list_template'>";
                                echo "<option value=''>None</option>";
                                foreach ($templates as $template) {
                                    $selected2 = ($template['id'] == $yt_list_template) ? "selected" : "";
                                    echo "<option value='{$template['id']}' $selected2>{$template['template_name']}</option>";
                                }
                                echo "</select>";
                            ?>

                            </td>
                        </tr>
                        <tr>
                            <td>Single Page</td>
                            <td>

                            <?php
                                // Fetch the saved option value from options table for single page
                                $selected_single_template = get_option('yt_single_template');

                                // Generate HTML select options
                                echo "<select id='yt-single-template' name='yt-single-template' data-select-template='yt_single_template'>";
                                echo "<option value=''>None</option>";
                                foreach ($templates as $template) {
                                    $selected3 = ($template['id'] == $selected_single_template) ? "selected" : "";
                                    echo "<option value='{$template['id']}' $selected3>{$template['template_name']}</option>";
                                }
                                echo "</select>";
                            ?>

                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="about" role="tabpanel">
                <h3>Yachts for Charter</h3>
                <div class="table-wrapper">
                    <table class="table table-dark table-hover">
                        <tr>
                            <td style="min-width:100px;">Grid View</td>
                            <td>

                            <?php
                                // Fetch the saved option value from options table for grid view
                                $yt_grid_template_forCharter = get_option('yt_grid_template_forCharter');

                                // Generate HTML select options
                                echo "<select id='yt-grid-template-for-charter' name='yt-grid-template-for-charter' data-select-template='yt_grid_template_forCharter'>";
                                echo "<option value=''>None</option>";
                                foreach ($templates as $template) {
                                    $selected1 = ($template['id'] == $yt_grid_template_forCharter) ? "selected" : "";
                                    echo "<option value='{$template['id']}' $selected1>{$template['template_name']}</option>";
                                }
                                echo "</select>";
                            ?>

                            </td>
                        </tr>
                        <tr>
                            <td>List View</td>
                            <td>

                            <?php
                                // Fetch the saved option value from options table for list view
                                $yt_list_template_forCharter = get_option('yt_list_template_forCharter');

                                // Generate HTML select options
                                echo "<select id='yt-list-template-for-charter' name='yt-list-template-for-charter' data-select-template='yt_list_template_forCharter'>";
                                echo "<option value=''>None</option>";
                                foreach ($templates as $template) {
                                    $selected2 = ($template['id'] == $yt_list_template_forCharter) ? "selected" : "";
                                    echo "<option value='{$template['id']}' $selected2>{$template['template_name']}</option>";
                                }
                                echo "</select>";
                            ?>

                            </td>
                        </tr>
                        <tr>
                            <td>Single Page</td>
                            <td>

                            <?php
                                // Fetch the saved option value from options table for single page
                                $yt_single_template_forCharter = get_option('yt_single_template_forCharter');

                                // Generate HTML select options
                                echo "<select id='yt-single-template-for-charter' name='yt-single-template-for-charter' data-select-template='yt_single_template_forCharter'>";
                                echo "<option value=''>None</option>";
                                foreach ($templates as $template) {
                                    $selected3 = ($template['id'] == $yt_single_template_forCharter) ? "selected" : "";
                                    echo "<option value='{$template['id']}' $selected3>{$template['template_name']}</option>";
                                }
                                echo "</select>";
                            ?>

                            </td>
                        </tr>
                    </table>
                </div>

            </div>
        </div>
        <button id="yt_update_template_usage" class="btn btn-primary">UPDATE</button>
    </div>
</div>

<div class="container" style="margin-top:40px;background:#efefef;padding-top:30px;padding-bottom:30px;">
    <div class="row">
        <div class="col">
            <h3>Import & Export</h3>
        </div>
        <div class="col">

            <form method="post" action="" enctype="multipart/form-data">

            <input type="file" name="import_file" accept=".json">

            <button type="submit" name="import_templates" class="btn btn-success">Import Templates</button>

            </form>

        </div>
        <div class="col">

            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" enctype="multipart/form-data">

            <input type="hidden" name="action" value="yatco_export_templates">

            <?php wp_nonce_field('yatco_export_templates_action', 'yatco_export_templates_nonce'); ?>

            <!-- Export Button -->
            <button type="submit" name="export_templates" style="margin-top:30px;" class="btn btn-danger">Export All Templates</button>

            </form>

        </div>
    </div>
</div>

<?php

if (isset($_POST['import_templates']) && !empty($_FILES['import_file']['tmp_name'])) {

    global $wpdb;

    $table_name = $wpdb->prefix . 'yatco_connect_templates';

    $site_url = get_site_url(); // Get the current WordPress site URL

    // Read uploaded file
    $file = $_FILES['import_file']['tmp_name'];

    $json_data = file_get_contents($file);

    $decoded_data = json_decode($json_data, true);

    // Check if JSON is valid
    if (json_last_error() !== JSON_ERROR_NONE) {

        echo "<p>Invalid JSON file: " . json_last_error_msg() . "</p>";

        exit;

    }

    // Handle single object JSON (convert to array for uniform processing)
    if (!isset($decoded_data[0])) {

        $decoded_data = [$decoded_data];

    }

    // Insert templates into the database
    foreach ($decoded_data as $template) {

        if (isset($template['id'])) {

            unset($template['id']); // Remove old ID if exists
        
        }

        // 🚀 Step 2: Replace old domain with new domain in all template fields
        array_walk_recursive($template, function (&$value) use ($site_url) {
            if (is_string($value)) {
                $value = preg_replace('/https?:\/\/[^\/]+/', $site_url, $value);
            }
        });


        $wpdb->insert($table_name, $template);

    }

    echo "<p>Templates imported successfully!</p>";
}


?>
    
<div class="container" style="margin-top:40px;background:#efefef;padding-top:30px;padding-bottom:30px;">
    <div class="row">
        <div class="col">
            <h3>Templates</h3>
        </div>
    </div>    
    <div class="row">

        <?php

        $allTemplates = $template_manager->get_all_templates();

        $template_in_use = array(
            "sale_grid" => get_option('yt_grid_template'),
            "sale_list" => get_option('yt_list_template'),
            "sale_single" => get_option('yt_single_template'),
            "charter_grid" => get_option('yt_grid_template_forCharter'),
            "charter_list" => get_option('yt_list_template_forCharter'),
            "charter_single" => get_option('yt_single_template_forCharter'),
        );

        $template_usage_string = array(
            "sale_grid" => "In use : <b>Sale Grid</b>",
            "sale_list" => "In use : <b>Sale List</b>",
            "sale_single" => "In use : <b>Sale Single</b>",
            "charter_grid" => "In use : <b>Charter Grid</b>",
            "charter_list" => "In use : <b>Charter List</b>",
            "charter_single" => "In use : <b>Charter Single</b>",
        );

        foreach($allTemplates as $template){

            $templateID = sanitize_text_field($template['id']);

            $unique_id = (int) sanitize_text_field($template['unique_id']);

            $key = array_search($templateID, $template_in_use, true);

            $template_name = sanitize_text_field($template['template_name']);

            $editor_contents = $template['html_contents'];

            $is_default = $template['is_default'];
            
            $styles = stripslashes($template['updated_styles']);

            ?>

            <div class="col-4" id="yt-template-<?php echo $templateID; ?>">

                <div class="yt-template-holder">
                    <?php
                    if(in_array($unique_id, $duplicate_ids)){
                        //just a warning dot in the template where duplicate ids found, So they can export that template, edit the id and import back
                       echo '<span class="yt-warnng-red-dot" title="Duplicate Unique ID Found! : '.$unique_id.'"></span>';
                    }
                    ?>
                    <div class="template" data-unique-id="<?php echo $unique_id; ?>">

                    <?php

                    $editor_contents = wp_kses_post($editor_contents);

                    echo stripslashes($editor_contents);

                    ?>

                    </div>

                    <style>
                        
                        <?php

                        // Process the stored styles so that each rule is scoped to the template container
                        
                        $parent_selector = "#yt-template-" . $templateID;
                        $modified_styles = preg_replace_callback(
                            '/(^|\})(\s*)([^{]+)\{/',
                            function ($matches) use ($parent_selector) {
                                // Split comma-separated selectors
                                $selectors = array_map('trim', explode(',', $matches[3]));
                                $prefixed_selectors = array_map(function($sel) use ($parent_selector) {
                                    // If the selector already starts with the parent, don't add it again
                                    if (strpos($sel, $parent_selector) === 0) {
                                        return $sel;
                                    }
                                    return $parent_selector . ' ' . $sel;
                                }, $selectors);
                                return $matches[1] . "\n" . implode(', ', $prefixed_selectors) . " {";
                            },
                            $styles
                        );
                        
                        echo $modified_styles;

                        ?>
                        
                    </style>

                    <div class="card-body">

                        <h5 class="card-title"><?php echo $template_name; ?></h5>

                        <?php if($is_default){ ?>
                            <button type="button" class="btn btn-primary btn-sm" data-template-edit="<?php echo admin_url("admin.php?page=design-yatco-templates&edit_template=$templateID"); ?>" disabled>
                                Default
                            </button>
                        <?php } ?>

                        <?php if( $is_default && isset($_GET['edit_as']) && $_GET['edit_as'] == 'author' ){ ?>
                            <a href="<?php echo admin_url("admin.php?page=design-yatco-templates&edit_template=$templateID&edit_as=author"); ?>" class="btn btn-primary btn-sm">Edit</a>
                        <?php } ?>

                        <?php if(!$is_default ){
                            $edit_as = '';
                            if( isset($_GET['edit_as']) && $_GET['edit_as'] == 'author' ){
                                $edit_as = '&edit_as=author';
                            }
                            ?>
                            <a href="<?php echo admin_url("admin.php?page=design-yatco-templates&edit_template=$templateID".$edit_as); ?>" class="btn btn-primary btn-sm">Edit</a>
                        <?php } ?>

                        <button class="btn btn-danger yt-delete-template btn-sm" data-template-id="<?php echo $templateID; ?>">Delete</button>

                        <button class="btn btn-warning yt-export-template btn-sm" data-template-id="<?php echo $templateID; ?>">Export</button>

                        <button class="btn btn-info yt-clone-template btn-sm" data-template-id="<?php echo $templateID; ?>">Clone</button>

                        <button class="btn btn-success yt-use-as-template btn-sm" data-template-id="<?php echo $templateID; ?>">Use As</button>

                        <?php
                            if ($key !== false) {
                                echo '<div class="template-usage-text">';
                                    echo $template_usage_string[$key];
                                echo '</div>';
                            }
                            
                            if(empty($unique_id) || $unique_id === 0){
                                echo '<div class="template-id-warning-msg">Template id missing!</div>';
                            }
                        ?>

                    </div>

                </div>

            </div>

        <?php } ?>

    </div>

</div>

<div class="yt-loader">

    <div id="loader-overlay" class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-light bg-opacity-75" style="z-index:1050;"> 
        
        <div class="spinner-border text-primary" role="status">

            <span class="visually-hidden">Loading...</span>

        </div>

    </div>

</div>

<div class="modal fade" id="useAsModal" tabindex="-1" aria-labelledby="useAsModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Use Template As</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Step 1: Choose Type</label><br>
          <button class="btn btn-outline-primary me-2 use-type" data-type="sale">For Sale</button>
          <button class="btn btn-outline-secondary use-type" data-type="charter">For Charter</button>
        </div>

        <div class="mb-3 d-none" id="viewStep">
          <label for="viewSelect" class="form-label">Step 2: Choose View</label>
          <select class="form-select" id="viewSelect">
            <option value="">Select View</option>
            <option value="grid">Grid</option>
            <option value="list">List</option>
            <option value="single">Single Page</option>
          </select>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" id="confirmUseAs" class="btn btn-success d-none">Confirm</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

<script>

    jQuery(document).ready(function($) {

        $(document).on('click', '#yt_update_template_usage', function( event ) {
            $(".yt-loader").fadeIn();
            var data = {
                action: 'yt_update_template_usage',
                yt_grid_template: $("#yt-grid-template").val(),
                yt_list_template: $("#yt-list-template").val(),
                yt_single_template: $("#yt-single-template").val(),
                yt_grid_template_forCharter: $("#yt-grid-template-for-charter").val(),
                yt_list_template_forCharter: $("#yt-list-template-for-charter").val(),
                yt_single_template_forCharter: $("#yt-single-template-for-charter").val(),
                security: '<?php echo wp_create_nonce("yatco_nonce"); ?>',
            };
            $.post(ajaxurl, data, function(response) {
                console.log(response);
                alert("Successfully Updated!");
                $(".yt-loader").fadeOut();
            });
        });

        $(document).on('click', '.yt-export-template', function(event) {
            event.preventDefault();

            var id = $(this).attr('data-template-id');
            var security = '<?php echo wp_create_nonce("yatco_nonce"); ?>'; // Use the localized nonce

            // Redirect the user to trigger file download
            window.location.href = ajaxurl + "?action=yt_export_template&template_id=" + id + "&security=" + security;
        });


        $(document).on('click', '.yt-delete-template', function( event ) {

            if (confirm("Are you sure you want to delete this template?")) {

                $(".yt-loader").fadeIn();

                var id = $(this).attr('data-template-id');

                $.ajax({
                    url: ajaxurl,  // WordPress AJAX URL (provided by WP)
                    type: 'POST',
                    data: {
                        action: 'yt_delete_template', // WordPress AJAX action
                        template_id: id, // Send template ID to backend
                        security: '<?php echo wp_create_nonce("yatco_nonce"); ?>' // Security nonce
                    },
                    success: function (response) {
                        if (response.success) {                            
                            // Optionally remove the template from UI
                            $("#yt-template-" + id).remove();
                            $(".yt-loader").fadeOut();
                        } else {
                            alert("Error: " + response.data);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error:", error);
                        alert("Failed to delete template. Please try again.");
                    }
                });

            }

        });

        $(document).on('click', '.yt-clone-template', function (event) {
            event.preventDefault();
            $(".yt-loader").fadeIn();
            const id = $(this).attr('data-template-id');
            const security = '<?php echo wp_create_nonce("yatco_nonce"); ?>';

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                action: 'yt_clone_template',
                template_id: id,
                security: security
                },
                success: function (response) {
                if (response.success) {
                    const newId = response.data.id;
                    const newUniqueId = response.data.unique_id;
                    const newTemplateName = response.data.template_name;

                    const $original = jQuery(`#yt-template-${id}`);
                    const $cloned = $original.clone();

                    // Remove usage text
                    $cloned.find('.template-usage-text').remove();

                    // Update outer ID
                    $cloned.attr('id', `yt-template-${newId}`);
                    $cloned.find('.card-title').text(newTemplateName);

                    // Update data-unique-id
                    $cloned.find('[data-unique-id]').attr('data-unique-id', newUniqueId);

                    // Replace old ID references in inline style
                    $cloned.find('style').each(function () {
                    let styleContent = jQuery(this).html();
                    const regex = new RegExp(`#yt-template-${id}`, 'g');
                    jQuery(this).html(styleContent.replace(regex, `#yt-template-${newId}`));
                    });

                    // Update all buttons and links using the template ID
                    $cloned.find('[data-template-id]').attr('data-template-id', newId);

                    // Update href in existing edit links
                    $cloned.find('a.btn-primary').each(function () {
                    const href = jQuery(this).attr('href');
                    const updatedHref = href.replace(`edit_template=${id}`, `edit_template=${newId}`);
                    jQuery(this).attr('href', updatedHref);
                    });

                    // ✅ Handle Default → Edit Button transformation
                    const $defaultButton = $cloned.find('button.btn-primary[disabled]');
                    const urlParams = new URLSearchParams(window.location.search);
                    const editAs = urlParams.get('edit_as');

                    const $existingEdit = $cloned.find('a.btn-primary[href*="edit_template="]');

                    if (editAs !== 'author' && $existingEdit.length === 0 && $defaultButton.length) {
                    const editUrl = $defaultButton.attr('data-template-edit') || '#';
                    const updatedEditUrl = editUrl.replace(`edit_template=${id}`, `edit_template=${newId}`);
                    const $editButton = $(`<a href="${updatedEditUrl}" class="btn btn-primary btn-sm" style="margin-right:5px;">Edit</a>`);
                    $cloned.find('.yt-delete-template').first().before($editButton);
                    }

                    // Always remove the default button (regardless of above logic)
                    $defaultButton.remove();

                    // Insert the cloned block
                    $cloned.insertAfter($original);
                    $(".yt-loader").fadeOut();
                } else {
                    alert("Error: " + response.data);
                }
                },
                error: function (xhr, status, error) {
                console.error("AJAX Error:", error);
                alert("Failed to clone template. Please try again.");
                }
            });
            });

        let selectedTemplateId = null;
        let selectedType = null;

        jQuery(document).on('click', '.yt-use-as-template', function () {
            selectedTemplateId = jQuery(this).data('template-id');
            selectedType = null;

            // Reset modal
            jQuery('#viewStep').addClass('d-none');
            jQuery('#viewSelect').val('');
            jQuery('#confirmUseAs').addClass('d-none');

            // Show modal
            const bsModal = new bootstrap.Modal(document.getElementById('useAsModal'));
            bsModal.show();
        });

        // Step 1: For Sale / For Charter
        jQuery(document).on('click', '.use-type', function () {
            selectedType = jQuery(this).data('type');
            jQuery('#viewStep').removeClass('d-none');
            jQuery('#confirmUseAs').addClass('d-none');
        });

        // Step 2: Show confirm once view is selected
        jQuery('#viewSelect').on('change', function () {
            const view = jQuery(this).val();
            if (view && selectedType) {
                jQuery('#confirmUseAs').removeClass('d-none');
            } else {
                jQuery('#confirmUseAs').addClass('d-none');
            }
        });

        // Confirm and send AJAX
        jQuery('#confirmUseAs').on('click', function () {
            const view = jQuery('#viewSelect').val();
            var security = '<?php echo wp_create_nonce("yatco_nonce"); ?>'; // Use the localized nonce

            const optionKeyMap = {
                'sale_grid': 'yt_grid_template',
                'sale_list': 'yt_list_template',
                'sale_single': 'yt_single_template',
                'charter_grid': 'yt_grid_template_forCharter',
                'charter_list': 'yt_list_template_forCharter',
                'charter_single': 'yt_single_template_forCharter'
            };

            const optionKey = optionKeyMap[`${selectedType}_${view}`];

            if (!optionKey) {
                alert("Invalid combination selected.");
                return;
            }

            jQuery.post(ajaxurl, {
                action: 'yt_use_template_as',
                security: security,
                template_id: selectedTemplateId,
                option_key: optionKey
            }, function (response) {
                if (response.success) {
                    const usageText = response.data.usage_text;

                    // 🧹 Remove any previous usage text with the same label
                    jQuery('.template-usage-text').each(function () {
                        if (jQuery(this).html().trim() === usageText.trim()) {
                            jQuery(this).remove();
                        }
                    });

                    // ✅ Append usage text to current template card
                    const $card = jQuery(`#yt-template-${selectedTemplateId}`);
                    $card.find('.card-body').append(`<div class="template-usage-text">${usageText}</div>`);

                    // Update the corresponding <select>
                    if (response.data.selected_field) {
                        jQuery(`select[data-select-template="${response.data.selected_field}"]`).val(response.data.id);
                    }

                    // ✅ Hide modal
                    bootstrap.Modal.getInstance(document.getElementById('useAsModal')).hide();
                }
                else {
                    alert("Failed: " + response.data);
                }
            });
        });

        //Place the jQuery function before this closing
    });

</script>