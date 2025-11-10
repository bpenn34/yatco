<div class="cfp-tab-content active" id="cfp-content-tab">
    <div class="cfp-form-group">
        <label for="cfp_form_type" class="cfp-label"><strong>Form Type:</strong></label>
        <div class="cfp-select-wrapper">
            <?php
            $options = new yatcoConnect_Options();
            $formTypes = $options->get_FormTypes();
            $form_type = get_post_meta($post->ID, 'cfp_form_type', true);
            if (empty($form_type) && array_key_exists('General', $formTypes)) {
                $form_type = 'General'; // Default to "General" if not set
            }
            ?>
            <select id="cfp_form_type" name="cfp_form_type" class="cfp-select">
                <option value="">Select Form Type</option>
                <?php foreach ($formTypes as $name => $id) : ?>
                    <option value="<?php echo esc_attr($name); ?>"
                        <?php selected($form_type, $name); ?>>
                        <?php echo esc_html($name); ?> - <?php echo esc_attr($id); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="button" id="add-field-button" class="button button-primary cfp-button">Add New Field</button>
        </div>
    </div>

    <?php
    global $post;
    $post_id = isset($post->ID) ? $post->ID : 0;

    // Define default fields based on form type
    if ($form_type === 'NewsLetter') {
        $default_fields = [
            ['type' => 'text', 'name' => 'Name', 'label' => 'Name', 'options' => [], 'required' => '1', 'enable_icon' => '0', 'icon_class' => ''],
            ['type' => 'email', 'name' => 'Email', 'label' => 'Email', 'options' => [], 'required' => '1', 'enable_icon' => '0', 'icon_class' => ''],
        ];
    }else if ($form_type === 'CharterLead') {
        $default_fields = [
            ['type' => 'text', 'name' => 'FirstName', 'label' => 'First Name', 'options' => [], 'required' => '1', 'enable_icon' => '0', 'icon_class' => ''],
            ['type' => 'text', 'name' => 'LastName', 'label' => 'Last Name', 'options' => [], 'required' => '1', 'enable_icon' => '0', 'icon_class' => ''],
            ['type' => 'email', 'name' => 'Email', 'label' => 'Email', 'options' => [], 'required' => '1', 'enable_icon' => '0', 'icon_class' => ''],
            ['type' => 'tel', 'name' => 'Phone', 'label' => 'Phone', 'options' => [], 'required' => '1', 'enable_icon' => '0', 'icon_class' => ''],
            ['type' => 'textarea', 'name' => 'Message', 'label' => 'Message', 'options' => [], 'required' => '1', 'enable_icon' => '0', 'icon_class' => ''],
            ['type' => 'date', 'name' => 'IdealCheckIn', 'label' => 'Checkin', 'options' => [], 'required' => '1', 'enable_icon' => '0', 'icon_class' => ''],
            ['type' => 'text', 'name' => 'Duration', 'label' => 'Duration', 'options' => [], 'required' => '1', 'enable_icon' => '0', 'icon_class' => ''],
            ['type' => 'select', 'name' => 'RateFreq', 'label' => 'Rate Frequency', 'options' => ['Days','Weeks'], 'required' => '1', 'enable_icon' => '0', 'icon_class' => ''],
        ];
    }
    else {
        $default_fields = [
            ['type' => 'text', 'name' => 'FirstName', 'label' => 'First Name', 'options' => [], 'required' => '1', 'enable_icon' => '0', 'icon_class' => ''],
            ['type' => 'text', 'name' => 'LastName', 'label' => 'Last Name', 'options' => [], 'required' => '1', 'enable_icon' => '0', 'icon_class' => ''],
            ['type' => 'email', 'name' => 'Email', 'label' => 'Email', 'options' => [], 'required' => '1', 'enable_icon' => '0', 'icon_class' => ''],
            ['type' => 'tel', 'name' => 'Phone', 'label' => 'Phone', 'options' => [], 'required' => '1', 'enable_icon' => '0', 'icon_class' => ''],
            ['type' => 'textarea', 'name' => 'Message', 'label' => 'Message', 'options' => [], 'required' => '1', 'enable_icon' => '0', 'icon_class' => ''],
        ];
    }
    $default_field_names = array_column($default_fields, 'name');

    // All possible default field names across form types
    $all_default_field_names = ['Name', 'Email', 'FirstName', 'LastName', 'Phone', 'Message'];

   // Get saved fields
$saved_fields = get_post_meta($post_id, 'cfp_form_fields', true);
// Determine fields to display
if (!empty($saved_fields) && is_array($saved_fields)) {
    $fields = [];
    $saved_field_names = array_column($saved_fields, 'name');
    $fields = $saved_fields;

    // Ensure non-removable Email is present for NewsLetter
    if ($form_type === 'NewsLetter' && !in_array('Email', $saved_field_names)) {
        $fields[] = ['type' => 'email', 'name' => 'Email', 'label' => 'Email', 'options' => [], 'required' => '1', 'enable_icon' => '0', 'icon_class' => ''];
    }
    // Ensure all non-removable defaults are present for General
    if ($form_type !== 'NewsLetter') {
        $non_removable_fields = ['FirstName', 'LastName', 'Email', 'Phone', 'Message'];
        foreach ($non_removable_fields as $non_removable) {
            if (!in_array($non_removable, $saved_field_names)) {
                foreach ($default_fields as $default) {
                    if ($default['name'] === $non_removable) {
                        $fields[] = $default;
                        break;
                    }
                }
            }
        }
    }
} else {
    // For a new form, explicitly set NewsLetter defaults with Name and Email
    if ($form_type === 'NewsLetter') {
        $fields = [
            ['type' => 'text', 'name' => 'Name', 'label' => 'Name', 'options' => [], 'required' => '1', 'enable_icon' => '0', 'icon_class' => ''],
            ['type' => 'email', 'name' => 'Email', 'label' => 'Email', 'options' => [], 'required' => '1', 'enable_icon' => '0', 'icon_class' => '']
        ];
    } else {
        $fields = $default_fields; // Use General defaults
    }
}

// Reindex fields
//$fields = array_values($fields);

    ?>

    <div id="cfp-fields-container">
        <label class="cfp-label"><strong>Form Fields:</strong></label>
        <table class="widefat fixed cfp-fields-table">
            <thead>
                <tr>
                    <th>Field Type</th>
                    <th>Field Name</th>
                    <th>Field Label</th>
                    <th style="text-align:center;" colspan="4">Options & Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($fields as $index => $field): ?>
                    <?php 
                    $is_default_field = in_array($field['name'], $default_field_names);
                    $is_non_removable = ($form_type === 'NewsLetter' && $field['name'] === 'Email') || ($form_type !== 'NewsLetter' && $is_default_field);
                    ?>
                    <tr class="cfp-field" data-index="<?php echo esc_attr($index); ?>">
                        <td>
                            <select name="cfp_form_fields[<?php echo $index; ?>][type]" class="cfp-field-type">
                                <option value="text" <?php selected($field['type'], 'text'); ?>>Text</option>
                                <option value="email" <?php selected($field['type'], 'email'); ?>>Email</option>
                                <option value="tel" <?php selected($field['type'], 'tel'); ?>>Phone</option>
                                <option value="textarea" <?php selected($field['type'], 'textarea'); ?>>Textarea</option>
                                <option value="select" <?php selected($field['type'], 'select'); ?>>Dropdown</option>
                                <option value="radio" <?php selected($field['type'], 'radio'); ?>>Radio</option>
                                <option value="checkbox" <?php selected($field['type'], 'checkbox'); ?>>Checkbox</option>
                                <option value="date" <?php selected($field['type'], 'date'); ?>>Date</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="cfp_form_fields[<?php echo $index; ?>][name]"
                                value="<?php echo esc_attr($field['name'] ?? $field['label']); ?>" 
                                placeholder="Field Name (no spaces)"
                                <?php echo $is_default_field ? 'readonly' : ''; ?>>
                        </td>
                        <td>
                            <input type="text" name="cfp_form_fields[<?php echo $index; ?>][label]" 
                                value="<?php echo esc_attr($field['label']); ?>" 
                                placeholder="Field Label">
                        </td>
                        <td class="cfp-field-options <?php echo ($field['type'] !== 'select') ? 'hidden' : ''; ?>">
                            <span>
                                <input type="text" name="cfp_form_fields[<?php echo $index; ?>][options]"
                                    value="<?php echo isset($field['options']) ? esc_attr(implode(',', (array) $field['options'])) : ''; ?>"
                                    placeholder="Options (comma separated)">
                            </span>
                        </td>
                        <td class="cfp-field-options-radio <?php echo ($field['type'] !== 'radio') ? 'hidden' : ''; ?>">
                            <span>
                                <input type="text" name="cfp_form_fields[<?php echo $index; ?>][radio_options]"
                                    value="<?php echo isset($field['radio_options']) ? esc_attr(implode(',', (array) $field['radio_options'])) : ''; ?>"
                                    placeholder="Radio options (comma separated)">
                            </span>
                        </td>
                        <td class="cfp-field-options-checkbox <?php echo ($field['type'] !== 'checkbox') ? 'hidden' : ''; ?>">
                            <span>
                                <input type="text" name="cfp_form_fields[<?php echo $index; ?>][checkbox_options]"
                                    value="<?php echo isset($field['checkbox_options']) ? esc_attr(implode(',', (array) $field['checkbox_options'])) : ''; ?>"
                                    placeholder="Checkbox options (comma separated)">
                            </span>
                        </td>
                        <td style="text-align: center;">
                            <label>
                                <input type="checkbox" name="cfp_form_fields[<?php echo $index; ?>][required]"
                                    value="1" <?php checked(isset($field['required']) ? $field['required'] : '', '1'); ?>>
                                Required
                            </label>
                        </td>
                        <td style="text-align: center;">
                            <label for="cfp_enable_icon_<?php echo $index; ?>">
                                <input type="checkbox" id="cfp_enable_icon_<?php echo $index; ?>" 
                                    class="cfp_enable_icon" data-index="<?php echo $index; ?>"
                                    name="cfp_form_fields[<?php echo $index; ?>][enable_icon]"
                                    value="1" <?php checked(isset($field['enable_icon']) ? $field['enable_icon'] : '', '1'); ?>>
                                Enable Icon
                            </label>
                            <label class="cfp-icon-picker" id="icon-picker-<?php echo $index; ?>"
                                style="display: <?php echo !empty($field['enable_icon']) ? 'block' : 'none'; ?>;">
                                <button type="button" class="cfp-icon-button" data-index="<?php echo $index; ?>">
                                    <i class="<?php echo !empty($field['icon_class']) ? esc_attr($field['icon_class']) : 'fa-solid fa-icons'; ?>"></i>
                                </button>
                            </label>
                            <input type="hidden" class="icon_class" name="cfp_form_fields[<?php echo $index; ?>][icon_class]" 
                                value="<?php echo !empty($field['icon_class']) ? esc_attr($field['icon_class']) : ''; ?>">
                        </td>
                        <td style="text-align: center;">
                            <button type="button" class="remove-field-button button button-secondary"
                                <?php echo $is_non_removable ? 'disabled' : ''; ?>>
                                Remove
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php wp_nonce_field('cfp_save_meta_box', 'cfp_form_fields_nonce'); ?>
    <input type="hidden" name="cfp_trigger_save" id="cfp_trigger_save" value="0">
<script>
    jQuery(document).ready(function($) {
        var initialFormType = $('#cfp_form_type').val(); // Store initial value
        $('#cfp_form_type').on('change', function() {
            var newFormType = $(this).val();
            if (newFormType !== initialFormType) { // Only trigger if value changes
                $('#cfp_trigger_save').val('1');
                $('#publish').click();
            }
            initialFormType = newFormType; // Update initial value after change
        });
    });
    </script>
</div>