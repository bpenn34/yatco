<div class="wrap">
	<h2>YATCO SEO HOOK EXAMPLES</h2>

	<h2>Child Theme Example</h2>
	<p>All this below into nice php files.</p>
	<p>Child Theme Example .zip file, <a href="<?= YATCO_PLUGIN_ASSETS.'/docs/child-theme-example.zip' ?>" target="_blank">CLICK HERE</a></p>

	<h2>YATCO URLs Hooks</h2>

	<p>wp_yatco_broker_url | wp_yatco_yacht_url | wp_yatco_charter_url</p>

	<code>
add_action('init', function() {

    add_rewrite_rule('/yacht-for-sale/([^/]+?)-(\d+)/?$', 'index.php?post_type=yatco_yachts&yacht_name=$matches[1]&yacht_id=$matches[2]', 'top');


});

add_filter('wp_yatco_yacht_url', function($url, $vessel) {

    if (isset($vessel->BoatName)) {

        $vessel->VesselName = $vessel->BoatName;

    }

    $_url=get_site_url().'/yacht-for-sale/'.trim(preg_replace('/[^a-z0-9]+/', '-', strtolower($vessel->VesselName)),'-').'-'. $vessel->MLSID .'/';

    return $_url;

}, 10, 2);
	</code>

	<hr>

	<h2>YOAST HOOKS</h2>

	<h3>wpseo_title</h3>
	<code>
add_filter('wpseo_title', function($title) {
	global $wp_query;

	if (is_singular('yatco_yachts')) {
		$yacht = $wp_query->queried_object->boss_data;

		$title = $yacht->BasicInfo->BoatName." Yacht for Sale | ".  $yacht->Dimensions->LOAFeet ." ". $yacht->BasicInfo->Builder ." ". $yacht->BasicInfo->YearBuilt ." | Your Superyacht";
	}

	return $title;
}, 20, 1);
	</code>

	<h3>wpseo_metadesc</h3>
	<code>
add_filter('wpseo_metadesc', function($metadesc) {
    //Andiamo Yacht for sale is a 2009 BENETTI 194' 7" Motor Yacht in Miami, Florida, United States. Find the boat that best meets your needs today.
    global $wp_query;

    $v_id = 'yacht_id';

    if (isset($wp_query->query_vars[$v_id])) {
        $yacht = $wp_query->queried_object->boss_data;

        $metadesc = $yacht->BasicInfo->BoatName.' Yacht for sale is a '. $yacht->Dimensions->LOAFeet .' '. $yacht->BasicInfo->Builder .' '.  $yacht->BasicInfo->YearBuilt .', one of the finest yachts in the worldwide fleet to accommodate your family and friends.';
    } 

    return $metadesc;
}, 20, 1);
	</code>

	<hr>

	<h2>Template Overriding</h2>

	<h3>Shortcode Templates</h3>
	<p>Place in theme</p>

	<code>
add_filter(
	'yatco_connect_shortcode_yacht_quick_search_template', 
	function($template) {

	    $template=__DIR__.'/yatco-connect/quick-search-form.php';

	    return $template;

	}
);

add_filter(
	'yatco_connect_shortcode_yacht_results_template', 
	function($template) {

		$template=__DIR__.'/templates/results.php';

		return $template;

	}
);

add_filter(
    'yatco_connect_shortcode_yacht_search_template', 
    function($template) {

        $template=__DIR__.'/templates/search-form.php';

        return $template;

    }
);
	</code>

	<h3>Single Post Type. Detail Pages.</h3>
	<p>Place in theme</p>

	<code>
add_filter('single_template', function($single_template) {

	global $post, $wp_query;

	if (is_singular('yatco_yachts')) {

		$vessel = $post->boss_data;
		
		// ACTIVE
		if ($vessel->MiscInfo->Status == 1 || $vessel->MiscInfo->Status == 4) {				
			$single_template = __DIR__.'/../single-yatco_yachts.php';
		}
		// Sold or otherwise
		else { //if ($vessel->MiscInfo->Status == 99) {
			$wp_query->is_404 = TRUE;
			//$single_template = __DIR__.'/../single-yatco_yachts-sold.php';
		}

	}

	return $single_template;

}, 15, 1);
	</code>

	<hr>



</div>

<style type="text/css">
	code{
    	display: block;	
		white-space: pre-wrap;
	}
</style>