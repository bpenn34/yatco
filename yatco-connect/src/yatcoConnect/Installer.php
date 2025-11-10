<?php 

/**
* Install the Yatco Connect Plugin
*/
class yatcoConnect_Installer
{

    public function __construct() 
    {
        $this->options = new yatcoConnect_Options();
        $this->postTypes = new yatcoConnect_PostTypes();
    }

    public function activate() {

        $this->install();

    }

	public function install()
	{
        $this->mark_default_formField_checked();

        $this->yatco_install_fonts();

        // Initialize DB Tables used by the plugin
        $this->installDatabaseTables();

        $this->installJsonData();

        $this->cfp_create_database_table();

        // Initialize custom post types
        $this->installCustomPostTypes();

        // Set plugin to 'installed'
        $this->options->markAsInstalled();
	}

    public function mark_default_formField_checked(){
        // Only install if there are no fonts already saved.
        if ( false === get_option( 'boatdecker_search_form_visible_fields' ) ) {
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
            update_option('boatdecker_search_form_visible_fields', serialize($filters));
        }
    }

    function yatco_connect_backup_existing_templates() {
        global $wpdb;
        $table = $wpdb->prefix . 'yatco_connect_templates';
        $templates = $wpdb->get_results("SELECT * FROM $table", ARRAY_A);
    
        if (empty($templates)) return;
    
        $backup_dir = YATCO_PLUGIN_DIR . 'templates-backup';
        if (!file_exists($backup_dir)) {
            wp_mkdir_p($backup_dir);
        }
    
        $timestamp = current_time('Ymd_His');
        $file_path = $backup_dir . "/template-{$timestamp}.json";
        file_put_contents($file_path, json_encode($templates, JSON_PRETTY_PRINT));
    }
    
    public function yatco_connect_add_extra_columns() {
        global $wpdb;
        $table = $wpdb->prefix . 'yatco_connect_templates';
    
        $is_default = $wpdb->get_results("SHOW COLUMNS FROM $table LIKE 'is_default'");
        if (empty($is_default)) {
            $wpdb->query("ALTER TABLE $table ADD COLUMN is_default TINYINT(1) DEFAULT 0 AFTER updated_styles");
        }
        $unique_id = $wpdb->get_results("SHOW COLUMNS FROM $table LIKE 'unique_id'");
        if (empty($unique_id)) {
            $wpdb->query("ALTER TABLE $table ADD COLUMN unique_id BIGINT DEFAULT 0 AFTER is_default");
        }
        $extra_options = $wpdb->get_results("SHOW COLUMNS FROM $table LIKE 'extra_options'");
        if (empty($extra_options)) {
            $wpdb->query("ALTER TABLE $table ADD COLUMN extra_options VARCHAR(1000) DEFAULT 0 AFTER unique_id");
        }
    }
    
	public function installDatabaseTables()
	{

        global $wpdb;
        $table_name = $wpdb->prefix . 'yatco_connect_templates';
        
        $charset_collate = $wpdb->get_charset_collate();

        // Prepare the SQL statement
        $sql = "CREATE TABLE $table_name (
            id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
            template_name varchar(200) NOT NULL,
            no_of_grids varchar(5) NOT NULL,
            html_contents MEDIUMTEXT NOT NULL,
            html_editor_contents MEDIUMTEXT NOT NULL,
            styles MEDIUMTEXT NOT NULL,
            updated_styles MEDIUMTEXT NOT NULL,
            is_default TINYINT(1) DEFAULT 0,
            unique_id BIGINT(1) DEFAULT 0,
            extra_options VARCHAR(1000) DEFAULT 0,
            PRIMARY KEY  (id)
        ) $charset_collate AUTO_INCREMENT=1;";

        // Check if the table already exists
        if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);  // Create the table
        }else{
            //add extra columns if table exists
            $this->yatco_connect_add_extra_columns();
            $this->yatco_connect_backup_existing_templates();
        }
    }

    public function installJsonData()
    {

        $json_file = YATCO_PLUGIN_DIR . 'install-assets/templates.json'; // Adjust path if needed

        if (!file_exists($json_file)) {
            error_log("❌ templates.json file not found!");
            return;
        }
    
        // Read JSON file
        $json_data = file_get_contents($json_file);
        $decoded_data = json_decode($json_data, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("❌ Invalid JSON: " . json_last_error_msg());
            return;
        }
    
        $this->import_templates($decoded_data);

    }

    /**
     * Function to import templates into the custom table
     */
    public function import_templates($templates) {
        global $wpdb;
    
        $table_name = $wpdb->prefix . 'yatco_connect_templates';
        $site_url = get_site_url(); // Current domain
    
        foreach ($templates as $template) {
            if (!isset($template['unique_id']) || empty($template['unique_id'])) {
                // Skip template if unique_id is not set
                continue;
            }
    
            $unique_id = $template['unique_id'];
    
            // 🚫 Remove old ID if present
            unset($template['id']);
    
            // 🔁 Replace domain in all values
            array_walk_recursive($template, function (&$value) use ($site_url) {
                if (is_string($value)) {
                    $value = preg_replace('/https?:\/\/[^\/]+/', $site_url, $value);
                }
            });
    
            // 🔍 Check if unique_id already exists
            $existing_id = $wpdb->get_var($wpdb->prepare(
                "SELECT id FROM $table_name WHERE unique_id = %s LIMIT 1",
                $unique_id
            ));
    
            if ($existing_id) {
                // 🔄 Update existing record
                $wpdb->update(
                    $table_name,
                    $template,
                    ['unique_id' => $unique_id]
                );
            } else {
                // ➕ Insert new record
                $wpdb->insert($table_name, $template);
            }
        }
    }    

    // Create Database Table on Plugin Activation
    public function cfp_create_database_table()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'cfp_submissions';

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id INT(11) NOT NULL AUTO_INCREMENT,
            form_id INT(11) NOT NULL,
            form_type VARCHAR(100) NOT NULL,
            form_title VARCHAR(255) NOT NULL,
            submission_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            field_data TEXT NOT NULL,  -- JSON-encoded field data
            PRIMARY KEY (id)
        ) $charset_collate;";

        // Check if the table already exists
        if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);  // Create the table
        }
    }

    public function yatco_install_fonts() {
        // Only install if there are no fonts already saved.
        if ( false === get_option( 'cfm_fonts' ) ) {
            $fonts = array();
    
            // DINOT Bold: using DINOT-Bold.otf and DINOT-Bold.woff, weight 700.
            $fonts[] = array(
                'font_family' => 'DINOT Bold',
                'font_weight' => '700',
                'font_style'  => 'normal',
                'id'          => time(), // Unique id; you can use a fixed value if needed.
                'otf_file'    => YATCO_PLUGIN_ASSETS . 'install-assets/fonts/DINOT-Bold.otf',
                'woff_file'   => YATCO_PLUGIN_ASSETS . 'install-assets/fonts/DINOT-Bold.woff'
            );
    
            // DINOT: using DINOT.ttf, weight 400.
            $fonts[] = array(
                'font_family' => 'DINOT',
                'font_weight' => '400',
                'font_style'  => 'normal',
                'id'          => time() + 1,
                'ttf_file'    => YATCO_PLUGIN_ASSETS . 'install-assets/fonts/DINOT.ttf'
            );
    
            // Vitesse Bold: using Vitesse-Bold.eot, Vitesse-Bold.ttf, and Vitesse-Bold.woff, weight 700.
            $fonts[] = array(
                'font_family' => 'Vitesse Bold',
                'font_weight' => '700',
                'font_style'  => 'normal',
                'id'          => time() + 2,
                'ttf_file'    => YATCO_PLUGIN_ASSETS . 'install-assets/fonts/Vitesse-Bold.ttf',
                'woff_file'   => YATCO_PLUGIN_ASSETS . 'install-assets/fonts/Vitesse-Bold.woff'
                // Optionally, you can add an 'eot_file' key if needed:
                // 'eot_file' => YATCO_PLUGIN_ASSETS . 'install-assets/fonts/Vitesse-Bold.eot'
            );
    
            // Vitesse Light: using Vitesse-Light.eot, Vitesse-Light.ttf, and Vitesse-Light.woff, weight 300.
            $fonts[] = array(
                'font_family' => 'Vitesse Light',
                'font_weight' => '300',
                'font_style'  => 'normal',
                'id'          => time() + 3,
                'ttf_file'    => YATCO_PLUGIN_ASSETS . 'install-assets/fonts/Vitesse-Light.ttf',
                'woff_file'   => YATCO_PLUGIN_ASSETS . 'install-assets/fonts/Vitesse-Light.woff'
                // Optionally:
                // 'eot_file' => YATCO_PLUGIN_ASSETS . 'install-assets/fonts/Vitesse-Light.eot'
            );
    
            // Vitesse (Medium): using Vitesse-Medium.eot, Vitesse-Medium.ttf, and Vitesse-Medium.woff, weight 500.
            $fonts[] = array(
                'font_family' => 'Vitesse',
                'font_weight' => '500',
                'font_style'  => 'normal',
                'id'          => time() + 4,
                'ttf_file'    => YATCO_PLUGIN_ASSETS . 'install-assets/fonts/Vitesse-Medium.ttf',
                'woff_file'   => YATCO_PLUGIN_ASSETS . 'install-assets/fonts/Vitesse-Medium.woff'
                // Optionally:
                // 'eot_file' => YATCO_PLUGIN_ASSETS . 'install-assets/fonts/Vitesse-Medium.eot'
            );
    
            update_option( 'cfm_fonts', $fonts );
        }
    }

    public function installCustomPostTypes()
    {
        $this->postTypes->add_actions_and_filters();
    }

}