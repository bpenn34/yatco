<?php
// Get the saved values
$title_singleYacht       = get_option('yatcoseo_seo_title_singleYacht');
$description_singleYacht = get_option('yatcoseo_seo_description_singleYacht');
$url_singleYacht         = get_option('yatcoseo_seo_url_singleYacht');

$title_singleCharter       = get_option('yatcoseo_seo_title_singleCharter');
$description_singleCharter = get_option('yatcoseo_seo_description_singleCharter');
$url_singleCharter         = get_option('yatcoseo_seo_url_singleCharter');

?>
<div class="wrap">
    <h1>YATCO SEO Custom Settings</h1>
    <form method="post" action="options.php">
        <?php
        settings_fields('yatcoseo_options_group');
        do_settings_sections('yatcoseo_options_group');
        ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><h3>SINGLE YACHT</h3></th>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="yatcoseo_seo_title_singleYacht">Placeholders:</label></th>
                <td>{VesselName}, {Model}, {YearBuilt}, {LOAFeet}, {Builder}, {City}, {State}, {Country}, {MLSID}, {desc:200}</p></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="yatcoseo_seo_title_singleYacht">SEO Title</label></th>
                <td><input type="text" name="yatcoseo_seo_title_singleYacht" id="yatcoseo_seo_title_singleYacht" class="large-text" value="<?php echo esc_attr($title_singleYacht); ?>" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="yatcoseo_seo_description_singleYacht">SEO Description</label></th>
                <td><textarea name="yatcoseo_seo_description_singleYacht" id="yatcoseo_seo_description_singleYacht" class="large-text" rows="5"><?php echo esc_textarea($description_singleYacht); ?></textarea></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="yatcoseo_seo_url_singleYacht">SEO URL</label></th>
                <td><input type="text" name="yatcoseo_seo_url_singleYacht" id="yatcoseo_seo_url_singleYacht" class="large-text" value="<?php echo esc_attr($url_singleYacht); ?>" /></td>
            </tr>

            <tr valign="top">
                <th scope="row"><h3>SINGLE CHARTER</h3></th>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="yatcoseo_seo_title_singleCharter">Placeholders:</label></th>
                <td>{VesselID}, {VesselName}, {LOAdisplay}, {ModelYear}, {RefitYear}, {BuilderName}, {VesselType}, {VesselCategoryText}, {NumberOfGuests}, {TotalStateRooms}, {TotalHeads}, {CompanyName}, {CompanyAddress1}, {CompanyAddress2}, {CompanyCity}, {CompanyState}, {CompanyCountry}, {desc:200}</p></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="yatcoseo_seo_title_singleCharter">SEO Title</label></th>
                <td><input type="text" name="yatcoseo_seo_title_singleCharter" id="yatcoseo_seo_title_singleCharter" class="large-text" value="<?php echo esc_attr($title_singleCharter); ?>" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="yatcoseo_seo_description_singleCharter">SEO Description</label></th>
                <td><textarea name="yatcoseo_seo_description_singleCharter" id="yatcoseo_seo_description_singleCharter" class="large-text" rows="5"><?php echo esc_textarea($description_singleCharter); ?></textarea></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="yatcoseo_seo_url_singleCharter">SEO URL</label></th>
                <td><input type="text" name="yatcoseo_seo_url_singleCharter" id="yatcoseo_seo_url_singleCharter" class="large-text" value="<?php echo esc_attr($url_singleCharter); ?>" /></td>
            </tr>
        </table>
        <?php submit_button('Save SEO Settings'); ?>
    </form>
</div>