<?php 

	function yactoConnect_init($file) {

		$yatcoConnectPlugin = new yatcoConnect_Plugin();
	    $options = new yatcoConnect_Options();

	    $yatcoConnectPlugin->version = YATCO_CONNECT_VERSION;

	    // Install the plugin
	    // NOTE: this file gets run each time you *activate* the plugin.
	    // So in WP when you "install" the plugin, all that does is dump its files in the plugin-templates directory
	    // but it does not call any of its code.
	    // So here, the plugin tracks whether or not it has run its install operation, and we ensure it is run only once
	    // on the first activation
	    if ( ! $yatcoConnectPlugin->isInstalled() ) {
	        $yatcoConnectPlugin->install();
	    } else {
	        // Perform any version-upgrade activities prior to activation (e.g. database changes)
	        $yatcoConnectPlugin->upgrade();
	    }

	    // Add callbacks to hooks
	    $yatcoConnectPlugin->addActionsAndFilters();
	    
	    if (!$file) $file = __FILE__;

	    // Register the Plugin Activation Hook
		register_activation_hook($file, array($yatcoConnectPlugin, 'activate'));

	    // // Register the Plugin Deactivation Hook
	    register_deactivation_hook($file, array($yatcoConnectPlugin, 'deactivate'));

	}