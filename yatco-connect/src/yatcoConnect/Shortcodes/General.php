<?php 
	class yatcoConnect_Shortcodes_General {
		public function __construct() {

			add_shortcode('yatco-powered-by', [$this, 'powered_by_yatco']);

		}

		public function powered_by_yatco($atts = array(), $content = null) {
			// normalize attribute keys, lowercase
		    $atts = array_change_key_case((array)$atts, CASE_LOWER);
		 
		    // override default attributes with user attributes
		    $attributes = shortcode_atts([
            	'color' => 'blue',
            ], $atts);

            switch ($attributes['color']) {
    			case 'blue':
    				$img_url=YATCO_PLUGIN_ASSETS .'/img/powerbyyatcoblue.png';
    				break;

    			case 'white':
    				$img_url=YATCO_PLUGIN_ASSETS .'/img/powerbyyatco.png';
    				break;
    		}

            ob_start();
            	echo '<a href="https://www.yatco.com/">';
            	
	            	echo '<img src="'. $img_url .'" alt="Powered by YATCO, the Official MLS of Yachting" class="poweredbyyatco yt-shortcode">';
	            	
	           echo '</a>';

		    return ob_get_clean();

		}

	}