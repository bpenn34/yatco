<?php
function cfp_main_setting_page() {
    // Retrieve saved settings from the database
    $enable_recaptcha = get_option('cfp_enable_recaptcha', '0');
    $recaptcha_site_key = get_option('recaptcha_site_key', '');
    $recaptcha_secret_key = get_option('recaptcha_secret_key', '');

    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Main Form Settings', 'cfp'); ?></h1>

        <!-- Settings Form -->
        <form method="post" action="options.php">
            <?php 
            // Output nonce field for security
            settings_fields('cfp_main_settings_group');
            do_settings_sections('cfp-main-setting');
            ?>

            <div class="cfp-tabs">
                <ul class="cfp-tab-menu">
                    <li class="cfp-tab active" data-tab="reCaptcha">reCAPTCHA</li>
                    <li class="cfp-tab" data-tab="layout">Other Settings</li>
                </ul>

                <!-- reCaptcha Settings Tab -->
                <div class="cfp-tab-content recaptcha active" id="cfp-reCaptcha-tab">
                    <h3><?php esc_html_e('Google reCaptcha', 'cfp'); ?></h3>
                    <p class="info">Google reCAPTCHA helps protect your forms from spam and automated submissions. By enabling YATCO reCAPTCHA setting, you can prevent spam bots from submitting malicious or unwanted entries. For getting Google reCaptcha site key and secret key please visit this <a href="https://www.google.com/recaptcha/admin/create" target="_blank" rel=nofollow>link</a>.</p>
                    <p>
                        <label for="enable_recaptcha">
                            <input type="checkbox" id="enable_recaptcha" name="cfp_enable_recaptcha" value="1" <?php checked($enable_recaptcha, '1'); ?>>
                            <?php esc_html_e('Enable reCaptcha', 'cfp'); ?>
                        </label>
                    </p>
                    
                    <p>        
                        <label for="recaptcha_site_key">
                            <?php esc_html_e('Enter reCAPTCHA Site Key', 'cfp'); ?>
                            <input type="text" id="recaptcha_site_key" name="cfp_recaptcha_site_key" value="<?php echo esc_attr($recaptcha_site_key); ?>" />
                        </label>
                    </p>

                    <p>        
                        <label for="recaptcha_secret_key">
                            <?php esc_html_e('Enter reCAPTCHA Secret Key', 'cfp'); ?>
                            <input type="text" id="recaptcha_secret_key" name="cfp_recaptcha_secret_key" value="<?php echo esc_attr($recaptcha_secret_key); ?>" />
                        </label>
                    </p>
                </div>
            </div>

            <!-- Save Settings Button -->
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Add the settings submenu
function cfp_main_setting_page_menu() {
    add_submenu_page(
        'edit.php?post_type=cfp_form', // Parent menu (YATCO Forms)
        'Settings',                    // Page title
        'Settings',                    // Menu title
        'manage_options',              // Capability
        'cfp-main-setting',            // Menu slug
        'cfp_main_setting_page'        // Callback function to display settings page
    );
}

//uncomment the below add_action to show the settings in main admin menu
//add_action('admin_menu', 'cfp_main_setting_page_menu');

// Register settings and form fields
function cfp_register_main_settings() {
    register_setting('cfp_main_settings_group', 'cfp_enable_recaptcha');
    register_setting('cfp_main_settings_group', 'cfp_recaptcha_site_key');
    register_setting('cfp_main_settings_group', 'cfp_recaptcha_secret_key');    
}
add_action('admin_init', 'cfp_register_main_settings');
?>
