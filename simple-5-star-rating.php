<?php
/**
 * Plugin Name: Simple 5 Star Rating
 * Plugin URI: http://www.flapshap.com/wordpress-plugins/simple-5-star-rating/
 * Description: It will help your blog readers to share their reviews in the form of stars. Users can rate your blog posts 1 to 5 and on the basis of the rating you can imorove yourself or you can reward your authors. Simple 5 Star Rating system is simple but powerful plugin it will place the stars in the bottom of your article so that just after finishing the reading users will see the rating system to rate.
 * Version: 1.0
 * Author: Mohit Bhardwaj & Rajan Bhardwaj
 * Author URI: http://www.flapshap.com/mohit-bhardwaj/
 * License: GPL2
 
 Simple 5 Star Rating is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Simple 5 Star Rating is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with {Plugin Name}. If not, see <http://www.gnu.org/licenses/>.

 */
	function tihomRun() {
		wp_register_style( 'font_awesome_stylesheet', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' );
		wp_register_style( 'star_rating_css', plugins_url('/css/mycss.css', __FILE__), false, '1.0.0', 'all');
		wp_enqueue_style( 'font_awesome_stylesheet' );
		wp_enqueue_style( 'star_rating_css' );			
		add_filter( 'the_content','tihomRatingDivAdd'  );
		wp_register_script('insertrating', plugin_dir_url( __FILE__ ) . 'js/dataInsertJ.js', array( 'jquery' ));
		wp_localize_script( 'insertrating', 'insertrating_ajax', array( 'ajax_url' => admin_url('admin-ajax.php')) );
		wp_enqueue_script("insertrating");
			
					
		add_action( 'wp_ajax_star_rating', 'tihomInsertRating' );
		add_action( 'wp_ajax_nopriv_star_rating', 'tihomInsertRating'  );		
		
		add_action( 'wp_ajax_initial_data', 'tihomRatingMainFunction' );
		add_action( 'wp_ajax_nopriv_initial_data', 'tihomRatingMainFunction'  );
		register_activation_hook(__FILE__,'createTihomRatingTable');
	}	
		
	function tihomRatingDivAdd ( $content ) {
		if(is_single()) {
			global $wpdb;
			$table_name = $wpdb->prefix . "_tihom_rating";
			$current_post_id = get_the_ID();
			$title = get_the_title();
			$post_rating = 0;
			$post_number_of_rating = 0;
			
			$ratings = $wpdb->get_results("SELECT AVG(rating) as r,count(*) as total FROM $table_name where page_id = $current_post_id");
			foreach($ratings as $rating) {
				$post_rating = esc_html($rating->r);
				$post_number_of_rating = ($rating->total);
			}
			return $content .= "<div id='tihom_rating_div'></div>";
		}
	}
	function tihomRatingMainFunction ( $content ) {
			$starfive = ""; $starfour = ""; $starthree = ""; $startwo = ""; $starone = ""; $starzero = "";
			global $wpdb;
			$table_name = $wpdb->prefix . "_tihom_rating";
			$url = wp_get_referer();
			$current_post_id = url_to_postid( $url ); 
			//$current_post_id = get_the_ID();
			$post_rating = 0;
			$post_number_of_rating = 0;
			
			$ratings = $wpdb->get_results("SELECT AVG(rating) as r,count(*) as total FROM $table_name where page_id = $current_post_id");
			foreach($ratings as $rating) {
				$post_rating = esc_html($rating->r);
				$post_number_of_rating = esc_html($rating->total);
			}
			
			if($post_rating>4.75) {$fi = 'fa fa-star checked'; $fo = 'fa fa-star checked'; $th = 'fa fa-star checked'; $tw = 'fa fa-star checked'; $on = 'fa fa-star checked'; }
			if($post_rating>4.25 && $post_rating<=4.75) {$fi = 'fa fa-star-half-full checked'; $fo = 'fa fa-star checked'; $th = 'fa fa-star checked'; $tw = 'fa fa-star checked'; $on = 'fa fa-star checked'; }
			if($post_rating>3.75 && $post_rating<=4.25) {$fi = 'fa fa-star'; $fo = 'fa fa-star checked'; $th = 'fa fa-star checked'; $tw = 'fa fa-star checked'; $on = 'fa fa-star checked'; }
			if($post_rating>3.25 && $post_rating<=3.75) {$fi = 'fa fa-star'; $fo = 'fa fa-star-half-full checked'; $th = 'fa fa-star checked'; $tw = 'fa fa-star checked'; $on = 'fa fa-star checked'; }
			if($post_rating>2.75 && $post_rating<=3.25) {$fi = 'fa fa-star'; $fo = 'fa fa-star'; $th = 'fa fa-star checked'; $tw = 'fa fa-star checked'; $on = 'fa fa-star checked'; }
			if($post_rating>2.25 && $post_rating<=2.75) {$fi = 'fa fa-star'; $fo = 'fa fa-star'; $th = 'fa fa-star-half-full checked'; $tw = 'fa fa-star checked'; $on = 'fa fa-star checked'; }
			if($post_rating>1.75 && $post_rating<=2.25) {$fi = 'fa fa-star'; $fo = 'fa fa-star'; $th = 'fa fa-star'; $tw = 'fa fa-star checked'; $on = 'fa fa-star checked'; }
			if($post_rating>1.25 && $post_rating<=1.75) {$fi = 'fa fa-star'; $fo = 'fa fa-star'; $th = 'fa fa-star'; $tw = 'fa fa-star-half-full checked'; $on = 'fa fa-star checked'; }
			if($post_rating>0.75 && $post_rating<=1.25) {$fi = 'fa fa-star'; $fo = 'fa fa-star'; $th = 'fa fa-star'; $tw = 'fa fa-star'; $on = 'fa fa-star checked'; }
			if($post_rating>0.25 && $post_rating<=0.75) {$fi = 'fa fa-star'; $fo = 'fa fa-star'; $th = 'fa fa-star'; $tw = 'fa fa-star'; $on = 'fa fa-star-half-full checked'; }
			if($post_rating>=0 && $post_rating<=0.25) {$fi = 'fa fa-star'; $fo = 'fa fa-star'; $th = 'fa fa-star'; $tw = 'fa fa-star'; $on = 'fa fa-star'; }
			
			$data = "<p><input type='hidden' id='tihom-rating-post-id' value='$current_post_id' /><input type='hidden' id='tihom-rating-plugin-url' value='".plugins_url()."' /><span id='one' class='fa fa-star $on' title='1 Sart'></span> <span id='two' class='fa fa-star $tw' title='2 Sarts'></span> <span id='three' class='fa fa-star $th' title='3 Sarts'></span> <span id='four' class='fa fa-star $fo' title='4 Sarts'></span> <span id='five' class='fa fa-star $fi' title='5 Sarts'></span></p>" . "<div id='tihom-rating-text'><em>".number_format($post_rating,2)." Ratings By $post_number_of_rating Readers</em></div>";
			
			echo $data;
			die();
		
	}
	
	function tihomInsertRating() {
		global $wpdb;
		$postid = sanitize_text_field($_POST['postid']);
		$rating = sanitize_text_field($_POST['rating']);
		$ipaddress = sanitize_text_field($_SERVER['REMOTE_ADDR']);
		$table_name = $wpdb->prefix . "_tihom_rating";
		$numbers = 0;
		$post_rating = 0;
		$result = $wpdb->insert($table_name, array('rating' => $rating, 'page_id' => $postid, 'ipaddress' => $ipaddress));
		if($result == true) {
			$ratings = $wpdb->get_results("SELECT AVG(rating) r,count(*) t FROM $table_name where page_id = $postid");
			if($ratings == true) {
				foreach($ratings as $rating) {
					$post_rating = esc_html($rating->r);
					$numbers = esc_html($rating->t);
				}
				echo $post_rating.'-'.$numbers;
			} else {
				echo "Error1";
			}
		} else {
			echo "Error2";
		}
		die();
	}
	
	function createTihomRatingTable() {
		global $wpdb;
		$table_name = $wpdb->prefix . "_tihom_rating";
		$my_products_db_version = '1.0.0';
		$charset_collate = $wpdb->get_charset_collate();

		if ( $wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'" ) != $table_name ) {

			$sql = "CREATE TABLE `$table_name` (
		  `rating_id` int(10) NOT NULL AUTO_INCREMENT,
		  `rating` int(2) NOT NULL DEFAULT '0',
		  `page_id` int(11) NOT NULL,
		  `ipaddress` varchar(50) NOT NULL,
		  `dateandtime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		  PRIMARY KEY (rating_id),
		  UNIQUE KEY (`page_id`,`ipaddress`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}
			
	}
	
	tihomRun();

?>