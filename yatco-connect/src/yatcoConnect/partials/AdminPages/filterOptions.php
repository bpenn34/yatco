<?php
// Save options when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    update_option('yt_yacht_api_pricerange_from', sanitize_text_field($_POST['yt_yacht_api_pricerange_from']));
    update_option('yt_yacht_api_pricerange_to', sanitize_text_field($_POST['yt_yacht_api_pricerange_to']));
    update_option('yt_yacht_api_loa_from', sanitize_text_field($_POST['yt_yacht_api_loa_from']));
    update_option('yt_yacht_api_loa_to', sanitize_text_field($_POST['yt_yacht_api_loa_to']));
    update_option('yt_yacht_api_lengthunit', sanitize_text_field($_POST['yt_yacht_api_lengthunit']));
    update_option('yt_yacht_api_currencytype', sanitize_text_field($_POST['yt_yacht_api_currencytype']));
    update_option('yt_yacht_api_exclude_vessels', sanitize_text_field($_POST['yt_yacht_api_exclude_vessels']));
    
    echo '<div class="updated"><p>Settings saved successfully!</p></div>';
}

// Fetch stored options //jpEdit
$yt_yacht_api_pricerange_from = get_option('yt_yacht_api_pricerange_from');
$yt_yacht_api_pricerange_to = get_option('yt_yacht_api_pricerange_to');
$yt_yacht_api_loa_from = get_option('yt_yacht_api_loa_from');
$yt_yacht_api_loa_to = get_option('yt_yacht_api_loa_to');
$yt_yacht_api_lengthunit = get_option('yt_yacht_api_lengthunit');
$yt_yacht_api_currencytype = get_option('yt_yacht_api_currencytype');
$yt_yacht_api_exclude_vessels = get_option('yt_yacht_api_exclude_vessels');
$currencies = [
    3508 => "AED",
    2555 => "AUD",
    2556 => "CAD",
    2561 => "CHF",
    2557 => "EUR",
    2562 => "GBP",
    2558 => "HKD",
    2559 => "NZD",
    2560 => "SEK",
    2563 => "USD"
];
?>

<div class="wrap">
    <h1 style="margin-bottom:30px;">API Data Filter Options</h1>
    <form method="post">
        <table class="form-table">
            <tr><td colspan="2"><label>Yacht Listings</label></td></tr>
            <tr>
                <th><label for="start_price">Start Price:</label></th>
                <td>
                    <input type="text" id="pricerange_from_holder" name="yt_yacht_api_pricerange_from" value="<?php echo esc_attr($yt_yacht_api_pricerange_from); ?>" min="0">
                    <input type="range" id="pricerange_from" class="pricerange_from yt-field"  min="0" max="10000000000" step="1" value="<?php echo esc_attr($yt_yacht_api_pricerange_from); ?>" oninput="updatePrice(this.value, this.id, 'convert')">
                </td>
            </tr>
            <tr>
                <th><label for="end_price">End Price:</label></th>
                <td>
                    <input type="text" id="pricerange_to_holder" name="yt_yacht_api_pricerange_to" value="<?php echo esc_attr($yt_yacht_api_pricerange_to); ?>" min="0">
                    <input type="range" id="pricerange_to" class="pricerange_to yt-field"  min="0" max="10000000000" step="1" value="<?php echo esc_attr($yt_yacht_api_pricerange_to); ?>" oninput="updatePrice(this.value, this.id, 'convert')">
                </td>
            </tr>            
            <tr>
                <th><label for="end_price">LOA Start</label></th>
                <td>
                    <input type="text" id="loa_from_holder" name="yt_yacht_api_loa_from" value="<?php echo esc_attr($yt_yacht_api_loa_from); ?>" min="0">
                    <input type="range" id="loa_from" class="loa_from yt-field"  min="0" max="200" step="1" value="<?php echo esc_attr($yt_yacht_api_loa_from); ?>" oninput="updatePrice(this.value, this.id)">
                </td>
            </tr>
            <tr>
                <th><label for="end_price">LOA End</label></th>
                <td>
                    <input type="text" id="loa_to_holder" name="yt_yacht_api_loa_to" value="<?php echo esc_attr($yt_yacht_api_loa_to); ?>" min="0">
                    <input type="range" id="loa_to" class="loa_to yt-field"  min="0" max="200" step="1" value="<?php echo esc_attr($yt_yacht_api_loa_to); ?>" oninput="updatePrice(this.value, this.id)">
                </td>
            </tr>
            <tr>
                <th><label for="end_price">Length Unit</label></th>
                <td>
                    <select name="yt_yacht_api_lengthunit">
                        <option value="">Select</option>
                        <option value="1" <?php echo ($yt_yacht_api_lengthunit == 1) ? 'selected' : ''; ?>>Feet</option>
                        <option value="2" <?php echo ($yt_yacht_api_lengthunit == 2) ? 'selected' : ''; ?>>Meters</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="end_price">Currency</label></th>
                <td>
                    <select name="yt_yacht_api_currencytype">
                        <?php foreach ($currencies as $code => $label): ?>
                            <option value="<?php echo $code; ?>" <?php echo ($yt_yacht_api_currencytype == $code) ? 'selected' : ''; ?>>
                                <?php echo $label; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="end_price">Exclude Vessels</label></th>
                <td>
                    <input type="text" name="yt_yacht_api_exclude_vessels" value="<?php echo $yt_yacht_api_exclude_vessels; ?>" placeholder="1043, 1208, 2293">
                </td>
            </tr>
        </table>
        <p class="submit">
            <input type="submit" class="button button-primary" value="Save Changes">
        </p>
    </form>
</div>
<script>
function updatePrice(value, id, c='') {
    if(c){
        value = new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(value);
    }
    document.getElementById(id+"_holder").value = value;
}
</script>
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