<?php 
	class yatcoConnect_Base_VirtualPost{
	
		public function virtual_post($posty = array()) {

			if (is_array($posty) && isset($posty['post_title']) && isset($posty['post_name']) && isset($posty['post_type'])) {
				$post=new stdClass();
					$post->ID = -1;             
		            $post->post_title = $posty['post_title'];
		            $post->post_content = (isset($posty['post_content']) && $posty['post_content'] != '')?$posty['post_content']:'';
		            $post->post_name = $posty['post_name'];

		            $post->post_author = 1;      
		            $post->post_date = current_time('mysql');          
		            $post->post_date_gmt = current_time('mysql', 1);
		            $post->post_excerpt = '';
		            $post->post_status = 'publish';
		            $post->comment_status = 'closed';        
		            $post->ping_status = 'closed';           
		            $post->post_password = '';  
		            $post->to_ping = '';
		            $post->pinged = '';
		            $post->modified = $post->post_date;
		            $post->modified_gmt = $post->post_date_gmt;
		            $post->post_content_filtered = '';
		            $post->post_parent = 0;
		            $post->post_type = $posty['post_type'];
		            $post->guid = (isset($posty['guid']))?$posty['guid']:get_home_url('/'. $posty['post_type'] . '/' . $posty['post_name']);
		            $post->menu_order = 0;
		            $post->post_mime_type = '';
		            $post->comment_count = 0;
			
	            $post = (array) $post;

	            $post = array_merge($post, $posty);

	            $post = (object) $post;

				return $post;
			}
			else {

			}
		}
	}	