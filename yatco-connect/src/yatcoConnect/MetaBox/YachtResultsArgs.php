<?php
	class yatcoConnect_MetaBox_YachtResultsArgs {


		public function __construct() {
				
		}

		public function add_actions_and_filters() {

		  //  add_action("add_meta_boxes", [$this, "meta_box_adder"]);
    	 //	add_action("save_post", [$this, "save_results_args"], 10, 1);

		}
		
		public function meta_box_adder() {

        // add_meta_box("results_args_yatco_box", "Results Args", [$this, 'meta_box_html'], "");

   	}

       	public function meta_box_html($post) {

       		$val_results_args = get_post_meta($post->ID, 'results_args', true);

       		?>

       		<p>

            <label>Results Args</label>

       			<input type="text" name="results_args" value="<?= esc_attr($val_results_args) ?>" style="width: 100%;">

       		</p>

       		<?php

       	}

        public function save_results_args($post_id) {
            
            $is_autosave = wp_is_post_autosave( $post_id );
            $is_revision = wp_is_post_revision( $post_id );
            $is_valid_nonce = ( isset( $_POST[ 'example_nonce' ] ) && wp_verify_nonce( $_POST[ 'example_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';

            // Exits script depending on save status
            if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
                return;
            }

            $current_val = get_post_meta($post_id, 'results_args', true);

            if ( isset($_POST['results_args']) && $current_val != $_POST['results_args']) {

           		// update_post_meta($post_id, 'results_args', $_POST['results_args']);

            }

            
        }

	}