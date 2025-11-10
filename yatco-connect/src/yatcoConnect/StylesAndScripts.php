<?php 
	class yatcoConnect_StylesAndScripts {

		public function __construct() {
			$this->options = new yatcoConnect_Options();
		}

		public function add_actions_and_filters() {
			add_action( 'wp_enqueue_scripts' , [$this, 'boss_tracking']);
			add_action( 'wp_enqueue_scripts' , [$this, 'enqueueGlobalTemplateStylesAndScrpts']);
			add_action( 'wp_enqueue_scripts' , [$this, 'enqueueYachtDetailsStylesAndScrpts']);
			add_action( 'wp_head' , [$this, 'meta_powered_by_yatco']);

			add_action('admin_enqueue_scripts', [$this, 'yt_enqueue_scripts_admin']);
			add_action('wp_enqueue_scripts', [$this, 'yt_enqueue_scripts_frontend']);
			
			add_action('wp_enqueue_scripts', [$this, 'enqueue_swiper_assets']);

			add_action('wp_enqueue_scripts', [$this, 'intl_tel_input']);
		}

		public function intl_tel_input() {
			// Check if the option is enabled
			$intl_phone_enabled = $this->options->getOption('enable_intl_phone');
			if ($intl_phone_enabled) {
				// Load styles and scripts
				wp_enqueue_style( 'intl-tel', 'https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/css/intlTelInput.min.css', array(), YATCO_CONNECT_VERSION );
				wp_enqueue_script( 'intl-tel', 'https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/intlTelInput.min.js', array(), YATCO_CONNECT_VERSION, true );
				wp_enqueue_script( 'intl-utils-tel', 'https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/utils.js', array(), YATCO_CONNECT_VERSION, true );
				// Add our custom initializer
				add_action('wp_footer', [$this, 'enqueue_intl_tel_script'], 20);
			}
		}

		public function enqueue_intl_tel_script() {
			?>
			<script>
				document.addEventListener("DOMContentLoaded", function () {
					const phoneInputs = document.querySelectorAll('input[type="tel"]');

					phoneInputs.forEach(function(input) {
						const iti = window.intlTelInput(input, {
							initialCountry: "us",
							preferredCountries: ["us", "au"],
							separateDialCode: true,
							utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/utils.js"
						});

						// Override the input value on form submit
						input.form?.addEventListener("submit", function () {
							input.value = iti.getNumber(); // E.164 format replaces user input
						});
					});
				});
			</script>
			<?php
		}

		public function enqueue_swiper_assets() {
			wp_enqueue_style('swiper-css', YATCO_PLUGIN_ASSETS.'build/css/swiper-bundle.min.css', array(), YATCO_CONNECT_VERSION );
			wp_enqueue_script('swiper-js', YATCO_PLUGIN_ASSETS.'build/js/swiper-bundle.min.js', [], YATCO_CONNECT_VERSION, true);
		}

		public function enqueueGlobalTemplateStylesAndScrpts()
		{

			if (defined('YATCO_TO_USE_MINIFIED') && YATCO_TO_USE_MINIFIED === false) {	
				wp_register_style('yatco-client-side', YATCO_PLUGIN_ASSETS.'build/css/client-side.css', false, YATCO_CONNECT_VERSION, false);
				wp_register_script('yatco-client-side', YATCO_PLUGIN_ASSETS	.'build/js/client-side.js', ['jquery'], YATCO_CONNECT_VERSION, true);
			}
			elseif (defined('YATCO_TO_USE_MINIFIED') && YATCO_TO_USE_MINIFIED === 'nothanks') {
				
				wp_register_script('yatco-client-side', YATCO_PLUGIN_ASSETS.'build/js/client-side.noMaps.js', ['jquery'], YATCO_CONNECT_VERSION, true);

			}
			else {
				wp_register_style('yatco-client-side', YATCO_PLUGIN_ASSETS.'build/css/client-side.noMaps.css', false, YATCO_CONNECT_VERSION, false);
				wp_register_script('yatco-client-side', YATCO_PLUGIN_ASSETS.'build/js/client-side.noMaps.js', ['jquery'], YATCO_CONNECT_VERSION, true);
			}

			$js_urls=[
				'_yatco_wp_ajax_url' => admin_url( 'admin-ajax.php' ),
				'_yatco_wp_rest_url' => get_rest_url(),

				'_yatco_wp_yacht_search_url' => $this->options->getOption('yacht_search_url'),
				'_yatco_wp_charter_search_url' => $this->options->getOption('charter_search_url'),
				'_yatco_wp_broker_search_url' => $this->options->getOption('broker_search_url'),
			];

			wp_localize_script('yatco-client-side', '_yatco_wp_url', $js_urls);

			$js_options = [
				'_yatco_wp_is_brokerage_site' => ($this->options->is_brokerage_site)?'1':'0',
			];

			wp_localize_script('yatco-client-side', '_yatco_wp_option', $js_options); 

			// wp_enqueue_script('add-this-sharing', "//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5facb08b269b99c1", false, null, true);			
			
			// wp_enqueue_script('gogole-captcha', 'https://www.google.com/recaptcha/api.js?onload=renderYATCOrecaptcha&render=explicit', ['jquery', 'yatco-client-side'], null, true);

			wp_enqueue_script('yatco-client-side');
			wp_enqueue_style('yatco-client-side');

			if ($this->options->is_brokerage_site) {
				$this->colors_picked();
			}

		}

		public function enqueueYachtDetailsStylesAndScrpts() {
			$is_singular_vessel = (is_singular('yatco_yachts') || is_singular('yatco_yachts_gallery') || is_singular('yatco_charters') || is_singular('yatco_services_mls') || is_page('yatco-template-preview'));

			if ( $is_singular_vessel ) {
				if (defined('YATCO_TO_USE_MINIFIED') && YATCO_TO_USE_MINIFIED === false) {	
					wp_register_script('yatco-single-yacht-client-side', YATCO_PLUGIN_ASSETS.'build/js/yacht-details-client-side.js', ['jquery', 'yatco-client-side', 'boss-tracking-lib'], YATCO_CONNECT_VERSION, true);
				}
				else {
					wp_register_script('yatco-single-yacht-client-side', YATCO_PLUGIN_ASSETS.'build/js/yacht-details-client-side.noMaps.js', ['jquery', 'yatco-client-side', 'boss-tracking-lib'], YATCO_CONNECT_VERSION, true);
				}	
			
				wp_enqueue_script('yatco-single-yacht-client-side');
			}
		}

		public function boss_tracking() {
		
			wp_register_script('boss-tracking-lib', 'https://tracking.yatcoboss.com/yctobf.js', [], YATCO_CONNECT_VERSION, true);

			//wp_enqueue_script('boss-tracking-lib');

		}

		public function meta_powered_by_yatco() {

			echo '<meta name="yatco" content="Powered By YATCO Plugin" />';

		}
				
		public function colors_picked() {

			$button_color_one =  $this->options->getOption('button_color_one');

			$button_color_two =  $this->options->getOption('button_color_two');

			$bg_color = $this->options->getOption('bg_color_one');

			$css_data = '';

			ob_start();

			include __DIR__.'/partials/css_client_setting.php';

			$css_data .= ob_get_clean();

			$css_data = str_replace("\n", "", $css_data);
			//$css_data = str_replace(" ", "", $css_data);

			wp_add_inline_style('yatco-client-side', $css_data);

		}

		function yt_enqueue_scripts_admin() {
			wp_enqueue_style('bootstrap-css', YATCO_PLUGIN_ASSETS . 'build/bootstrap5/css/bootstrap.min.css', array(), YATCO_CONNECT_VERSION );
			wp_enqueue_style('yt-template-style', YATCO_PLUGIN_ASSETS . 'build/css/yt-template-styles.css', array(), YATCO_CONNECT_VERSION );

			// Font Awesome (Ensure it's loaded only if not already available)
			wp_enqueue_style('font-awesome', YATCO_PLUGIN_ASSETS . 'build/font-awesome/all.min.css', array(), YATCO_CONNECT_VERSION );

			// Icon Picker CSS & JS
			wp_enqueue_style('iconpicker-css', YATCO_PLUGIN_ASSETS . 'build/font-awesome/fontawesome-iconpicker.min.css', array(), YATCO_CONNECT_VERSION );
			wp_enqueue_script('fa-iconpicker', YATCO_PLUGIN_ASSETS . 'build/font-awesome/fontawesome-iconpicker.min.js', array('jquery'), YATCO_CONNECT_VERSION, true );
			
			// Enqueue local Bootstrap 5 Icons
			wp_enqueue_style('bootstrap-icons', YATCO_PLUGIN_ASSETS . 'build/bootstrap5/font/bootstrap-icons.css', array(), YATCO_CONNECT_VERSION, 'all' );
			wp_enqueue_script('jquery');
			wp_enqueue_script('bootstrap-js', YATCO_PLUGIN_ASSETS . 'build/bootstrap5/js/bootstrap.bundle.min.js', ['jquery'], YATCO_CONNECT_VERSION, true );
			
			//loading the script only for the design template page
			if (isset($_GET['page']) && $_GET['page'] === 'design-yatco-templates') {

				// GrapesJS CSS and JS
				wp_enqueue_style('grapesjs-css', YATCO_PLUGIN_ASSETS . 'build/css/grapes.min.css', array() ,YATCO_CONNECT_VERSION );
				
				wp_enqueue_script('grapesjs', YATCO_PLUGIN_ASSETS . 'build/js/grapesjs.min.js', array(), YATCO_CONNECT_VERSION, true );
				
				// Custom JS for GrapesJS initialization
				wp_enqueue_script('grapesjs-custom-js', YATCO_PLUGIN_ASSETS . 'build/js/grapesjs-init.js', array('jquery', 'grapesjs'), YATCO_CONNECT_VERSION, true );
				
				$placeholder = new yatcoConnect_TemplateLiterals();

				$listing_literals_yacht = $placeholder->display_listing_literals( 'yacht' );

				$single_literals_yacht = $placeholder->display_single_literals( 'yacht' );
				
				$listing_literals_charter = $placeholder->display_listing_literals( 'charter' );

				$single_literals_charter = $placeholder->display_single_literals( 'charter' );

				$Staterooms = $placeholder->dummy_charter_staterooms();

				$Amenities = $placeholder->dummy_charter_amenities();
				
				$charterToys = $placeholder->dummy_charter_toys();

				$charterTender = $placeholder->dummy_charter_tenders();

				$charter_toysDetails = $placeholder->dummy_charter_ToysDetails();
				
				//initializing empty strings as the values will while in 'edit_template'but to avoid warnings
				$template_id = $template_name = $no_of_grids = $html_contents = $styles = $editor_contents = '';

				// Retrieve the installed fonts from your custom option.
				$fonts = get_option( 'cfm_fonts', array() );
				$font_families = array();

				// Loop through the font entries and extract font family names.
				if( !empty( $fonts ) ) {
					foreach ( $fonts as $font ) {
						if ( ! empty( $font['font_family'] ) ) {
							// Determine the file URL based on availability.
							$file_url = '';
							if ( ! empty( $font['woff2_file'] ) ) {
								$file_url = $font['woff2_file'];
							} elseif ( ! empty( $font['woff_file'] ) ) {
								$file_url = $font['woff_file'];
							} elseif ( ! empty( $font['otf_file'] ) ) {
								$file_url = $font['otf_file'];
							} elseif ( ! empty( $font['ttf_file'] ) ) {
								$file_url = $font['ttf_file'];
							}
							$font_families[] = array(
								'font_family' => $font['font_family'],
								'file_url'    => $file_url,
								'font_weight' => ! empty( $font['font_weight'] ) ? $font['font_weight'] : '400',
								'font_style'  => ! empty( $font['font_style'] ) ? $font['font_style'] : 'normal',
							);
						}
					}
				}
				// Encode as JSON for use in JavaScript.
				$custom_fonts_js = $font_families;

				if ( isset( $_GET['edit_template'] ) ) {
					// Retrieve the template ID from the URL
					$template_id = sanitize_text_field( $_GET['edit_template'] );
					$template_manager = new yatcoConnect_TemplateManager();
   					$template = $template_manager->get_template_by_id($template_id);
					
					$template_id = $template['id'];
					$template_name = $template['template_name'];
					$no_of_grids = $template['no_of_grids'];
					$editor_contents = stripslashes($template['html_editor_contents']);
					$styles = stripslashes($template['styles']);
				}
				
				$edit_as = '';
				if ( isset( $_GET['edit_as'] ) && $_GET['edit_as'] == 'author') {
					$edit_as = $_GET['edit_as'];
				}
				// Data to pass
				$script_data = [
					'ajax_url'  => admin_url('admin-ajax.php'), // Useful for AJAX
					'upload_url' => admin_url('admin-ajax.php?action=upload_custom_icon'),
					'remove_url' => admin_url('admin-ajax.php?action=remove_custom_icon'),
					'site_url'  => get_site_url(),
					'YATCO_PLUGIN_ASSETS' => YATCO_PLUGIN_ASSETS,
					'placeholder_img' => YATCO_PLUGIN_ASSETS . 'build/bootstrap5/placeholder.jpg',
					'placeholder_slider' => YATCO_PLUGIN_ASSETS . 'img/slider.png',
					'placeholder_slider2' => YATCO_PLUGIN_ASSETS . 'img/slider2.png',
					'placeholder_gallery' => YATCO_PLUGIN_ASSETS . 'img/gallery.png',					
					'placeholder_video' => YATCO_PLUGIN_ASSETS . 'img/video.jpg',
					'placeholder_contactForm' => YATCO_PLUGIN_ASSETS . 'img/contactForm.png',
					'plugin_nonce' => wp_create_nonce('yatco_nonce'),
					'listing_literals_yacht' => $listing_literals_yacht,
					'single_literals_yacht' => $single_literals_yacht,
					'listing_literals_charter' => $listing_literals_charter,
					'single_literals_charter' => $single_literals_charter,
					'Staterooms' => $Staterooms,
					'Amenities' => $Amenities,
					'charterToys' => $charterToys,
					'charterTender' => $charterTender,
					'toysDetails' => $charter_toysDetails,
					'template_id' => $template_id,
					'template_name' => $template_name,
					'no_of_grids' => $no_of_grids,
					'editor_contents' => $editor_contents,
					'styles' => $styles,
					'custom_fonts_js' => $custom_fonts_js,
					'svgWarningShown' =>  get_option('yatco_svg_upload_confirmed', false),
					'edit_as' => $edit_as,
				];

				// Pass data to JavaScript
				wp_localize_script('grapesjs-custom-js', 'yatcoData', $script_data);
			}
	   }

	   function yt_enqueue_scripts_frontend() {
		
			// Font Awesome (Ensure it's loaded only if not already available)
			wp_enqueue_style('font-awesome', YATCO_PLUGIN_ASSETS . 'build/font-awesome/all.min.css', array(), YATCO_CONNECT_VERSION );
		
			wp_enqueue_style('yt-frontend-template-style', YATCO_PLUGIN_ASSETS . 'build/css/yt-frontend-template-styles.css', array(), YATCO_CONNECT_VERSION );
			
			// Enqueue local Bootstrap 5 Icons
			wp_enqueue_style('bootstrap-icons', YATCO_PLUGIN_ASSETS . 'build/bootstrap5/font/bootstrap-icons.css', array(), YATCO_CONNECT_VERSION, 'all' );
			
			wp_enqueue_style('bootstrap', YATCO_PLUGIN_ASSETS . 'build/bootstrap5/css/bootstrap.min.css', array(), YATCO_CONNECT_VERSION );
			
			wp_enqueue_script('jquery');
			
			wp_enqueue_script('bootstrap', YATCO_PLUGIN_ASSETS . 'build/bootstrap5/js/bootstrap.bundle.min.js', ['jquery', 'jquery-ui-sortable'], YATCO_CONNECT_VERSION, true);
			
			// Enqueue GLightbox CSS
			wp_enqueue_style('glightbox-css', YATCO_PLUGIN_ASSETS . 'build/css/glightbox.min.css', array(), YATCO_CONNECT_VERSION);

			// Enqueue GLightbox JS (Load in Footer)
			wp_enqueue_script('glightbox-js', YATCO_PLUGIN_ASSETS . 'build/js/glightbox.min.js', array('jquery'), YATCO_CONNECT_VERSION, true);
		
			// Initialize GLightbox (Custom JS)
			wp_add_inline_script('glightbox-js', 'const lightbox = GLightbox({ selector: ".glightbox" });');
			
			wp_enqueue_script('yt-frontend-template-script', YATCO_PLUGIN_ASSETS . 'build/js/yt-frontend-template-script.js', ['jquery','yatco-client-side'], YATCO_CONNECT_VERSION, true);
			
			$yt_data = [
				'ajax_url'  => admin_url('admin-ajax.php'), // Useful for AJAX
			];

			// Pass data to JavaScript
			wp_localize_script('yt-frontend-template-script', 'ytData', $yt_data);
		}
	}