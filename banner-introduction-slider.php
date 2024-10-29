<?php
/*
Plugin Name: Banner Introduction Slider
Plugin URL: http://beautiful-module.com/demo/banner-introduction-slider/
Description: A simple Responsive Banner Introduction Slider
Version: 1.0
Author: Module Express
Author URI: http://beautiful-module.com
Contributors: Module Express
*/
/*
 * Register CPT banner.intro.slider
 *
 */
 
if (!defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if(!class_exists('Banner_Introduction_Slider')) {
	class Banner_Introduction_Slider {

		function __construct() {
		    if(!function_exists('add_shortcode')) {
		            return;
		    }
			add_action ( 'init' , array( $this , 'bigs_responsive_gallery_setup_post_types' ));

			/* Include style and script */
			add_action ( 'wp_enqueue_scripts' , array( $this , 'bigs_register_style_script' ));
			
			/* Register Taxonomy */
			add_action ( 'init' , array( $this , 'bigs_responsive_gallery_taxonomies' ));
			add_action ( 'add_meta_boxes' , array( $this , 'bigs_rsris_add_meta_box_gallery' ));
			add_action ( 'save_post' , array( $this , 'bigs_rsris_save_meta_box_data_gallery' ));
			register_activation_hook( __FILE__, 'bigs_responsive_gallery_rewrite_flush' );


			// Manage Category Shortcode Columns
			add_filter ( 'manage_responsive_bigs_slider-category_custom_column' , array( $this , 'bigs_responsive_gallery_category_columns' ), 10, 3);
			add_filter ( 'manage_edit-responsive_bigs_slider-category_columns' , array( $this , 'bigs_responsive_gallery_category_manage_columns' ));
			require_once( 'bigs_gallery_admin_settings_center.php' );
			require_once( 'multiple-post-thumbnails.php' );

			if (class_exists('MultiPostThumbnails'))
			{
				new MultiPostThumbnails(array(
					'label' => '2nd Feature Image',
					'id' => 'secondary-image',
					'post_type' => 'banner_intro_slider'
				));
			}
		    add_shortcode ( 'banner.intro.slider' , array( $this , 'bigs_responsivegallery_shortcode' ));
		}


		function bigs_responsive_gallery_setup_post_types() {

			$responsive_gallery_labels =  apply_filters( 'banner_intro_slider_labels', array(
				'name'                => 'Banner Introduction Slider',
				'singular_name'       => 'Banner Introduction Slider',
				'add_new'             => __('Add New', 'banner_intro_slider'),
				'add_new_item'        => __('Add New Image', 'banner_intro_slider'),
				'edit_item'           => __('Edit Image', 'banner_intro_slider'),
				'new_item'            => __('New Image', 'banner_intro_slider'),
				'all_items'           => __('All Images', 'banner_intro_slider'),
				'view_item'           => __('View Image', 'banner_intro_slider'),
				'search_items'        => __('Search Image', 'banner_intro_slider'),
				'not_found'           => __('No Image found', 'banner_intro_slider'),
				'not_found_in_trash'  => __('No Image found in Trash', 'banner_intro_slider'),
				'parent_item_colon'   => '',
				'menu_name'           => __('Banner Introduction Slider', 'banner_intro_slider'),
				'exclude_from_search' => true
			) );


			$responsiveslider_args = array(
				'labels' 			=> $responsive_gallery_labels,
				'public' 			=> true,
				'publicly_queryable'		=> true,
				'show_ui' 			=> true,
				'show_in_menu' 		=> true,
				'query_var' 		=> true,
				'capability_type' 	=> 'post',
				'has_archive' 		=> true,
				'hierarchical' 		=> false,
				'menu_icon'   => 'dashicons-format-gallery',
				'supports' => array('title','editor','thumbnail')
				
			);
			register_post_type( 'banner_intro_slider', apply_filters( 'sp_faq_post_type_args', $responsiveslider_args ) );

		}
		
		function bigs_register_style_script() {
		    wp_enqueue_style( 'bigs_responsiveimgslider',  plugin_dir_url( __FILE__ ). 'css/responsiveimgslider.css' );
			/*   REGISTER ALL CSS FOR SITE */
			wp_enqueue_style( 'bigs_featurelist',  plugin_dir_url( __FILE__ ). 'css/introductionslider.css' );

			/*   REGISTER ALL JS FOR SITE */			
			wp_enqueue_script( 'bigs_jssor.core', plugin_dir_url( __FILE__ ) . 'js/jssor.core.js', array( 'jquery' ));
			wp_enqueue_script( 'bigs_jssor.utils', plugin_dir_url( __FILE__ ) . 'js/jssor.utils.js', array( 'jquery' ));
			wp_enqueue_script( 'bigs_jssor.slider', plugin_dir_url( __FILE__ ) . 'js/jssor.slider.js', array( 'jquery' ));
			
		}
		
		
		function bigs_responsive_gallery_taxonomies() {
		    $labels = array(
		        'name'              => _x( 'Category', 'taxonomy general name' ),
		        'singular_name'     => _x( 'Category', 'taxonomy singular name' ),
		        'search_items'      => __( 'Search Category' ),
		        'all_items'         => __( 'All Category' ),
		        'parent_item'       => __( 'Parent Category' ),
		        'parent_item_colon' => __( 'Parent Category:' ),
		        'edit_item'         => __( 'Edit Category' ),
		        'update_item'       => __( 'Update Category' ),
		        'add_new_item'      => __( 'Add New Category' ),
		        'new_item_name'     => __( 'New Category Name' ),
		        'menu_name'         => __( 'Gallery Category' ),
		    );

		    $args = array(
		        'hierarchical'      => true,
		        'labels'            => $labels,
		        'show_ui'           => true,
		        'show_admin_column' => true,
		        'query_var'         => true,
		        'rewrite'           => array( 'slug' => 'responsive_bigs_slider-category' ),
		    );

		    register_taxonomy( 'responsive_bigs_slider-category', array( 'banner_intro_slider' ), $args );
		}

		function bigs_responsive_gallery_rewrite_flush() {  
				bigs_responsive_gallery_setup_post_types();
		    flush_rewrite_rules();
		}


		function bigs_responsive_gallery_category_manage_columns($theme_columns) {
		    $new_columns = array(
		            'cb' => '<input type="checkbox" />',
		            'name' => __('Name'),
		            'gallery_bigs_shortcode' => __( 'Gallery Category Shortcode', 'bigs_slick_slider' ),
		            'slug' => __('Slug'),
		            'posts' => __('Posts')
					);

		    return $new_columns;
		}

		function bigs_responsive_gallery_category_columns($out, $column_name, $theme_id) {
		    $theme = get_term($theme_id, 'responsive_bigs_slider-category');

		    switch ($column_name) {      
		        case 'title':
		            echo get_the_title();
		        break;
		        case 'gallery_bigs_shortcode':
					echo '[banner.intro.slider cat_id="' . $theme_id. '"]';			  	  

		        break;
		        default:
		            break;
		    }
		    return $out;   

		}

		/* Custom meta box for slider link */
		function bigs_rsris_add_meta_box_gallery() {
			add_meta_box('custom-metabox',__( 'LINK URL', 'link_textdomain' ),array( $this , 'bigs_rsris_gallery_box_callback' ),'banner_intro_slider');			
		}
		
		function bigs_rsris_gallery_box_callback( $post ) {
			wp_nonce_field( 'bigs_rsris_save_meta_box_data_gallery', 'rsris_meta_box_nonce' );
			$value = get_post_meta( $post->ID, 'rsris_bigs_link', true );
			echo '<input type="url" id="rsris_bigs_link" name="rsris_bigs_link" value="' . esc_attr( $value ) . '" size="35" /><br />';
			echo 'ie http://www.google.com';
		}
		
		function bigs_rsris_save_meta_box_data_gallery( $post_id ) {
			if ( ! isset( $_POST['rsris_meta_box_nonce'] ) ) {
				return;
			}
			if ( ! wp_verify_nonce( $_POST['rsris_meta_box_nonce'], 'bigs_rsris_save_meta_box_data_gallery' ) ) {
				return;
			}
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}
			if ( isset( $_POST['post_type'] ) && 'banner_intro_slider' == $_POST['post_type'] ) {

				if ( ! current_user_can( 'edit_page', $post_id ) ) {
					return;
				}
			} else {

				if ( ! current_user_can( 'edit_post', $post_id ) ) {
					return;
				}
			}
			if ( ! isset( $_POST['rsris_bigs_link'] ) ) {
				return;
			}
			$link_data = sanitize_text_field( $_POST['rsris_bigs_link'] );
			update_post_meta( $post_id, 'rsris_bigs_link', $link_data );
		}
		
		/*
		 * Add [banner.intro.slider] shortcode
		 *
		 */
		function bigs_responsivegallery_shortcode( $atts, $content = null ) {
			
			extract(shortcode_atts(array(
				"limit"  => '',
				"cat_id" => '',
				"autoplay" => '',
				"autoplay_interval" => ''
			), $atts));
			
			if( $limit ) { 
				$posts_per_page = $limit; 
			} else {
				$posts_per_page = '-1';
			}
			if( $cat_id ) { 
				$cat = $cat_id; 
			} else {
				$cat = '';
			}
			
			if( $autoplay ) { 
				$autoplay_slider = $autoplay; 
			} else {
				$autoplay_slider = 'true';
			}	 	
			
			if( $autoplay_interval ) { 
				$autoplay_intervalslider = $autoplay_interval; 
			} else {
				$autoplay_intervalslider = '2000';
			}
						

			ob_start();
			// Create the Query
			$post_type 		= 'banner_intro_slider';
			$orderby 		= 'post_date';
			$order 			= 'DESC';
						
			 $args = array ( 
		            'post_type'      => $post_type, 
		            'orderby'        => $orderby, 
		            'order'          => $order,
		            'posts_per_page' => $posts_per_page,  
		           
		            );
			if($cat != ""){
		            	$args['tax_query'] = array( array( 'taxonomy' => 'responsive_bigs_slider-category', 'field' => 'id', 'terms' => $cat) );
		            }        
		      $query = new WP_Query($args);

			$post_count = $query->post_count;
			$i = 1;

			if( $post_count > 0) :
			?>
				<div id="bigs_slider1_container" style="position: relative; width: 980px;height: 380px; overflow: hidden;">
				<div u="loading" style="position: absolute; top: 0px; left: 0px;">
					<div style="filter: alpha(opacity=70); opacity:0.7; position: absolute; display: block;
						background-color: #000; top: 0px; left: 0px;width: 100%; height:100%;"> 
					</div> 
					<div class="bigs_loading_screen">
					</div> 
				</div> 
				<div u="slides" style="cursor: move; position: absolute; left: 0px; top: 0px; width: 980px; height: 380px;
					overflow: hidden;">
					<?php								
							while ($query->have_posts()) : $query->the_post();
								include('designs/template.php');
								
							$i++;
							endwhile;									
					?>
				</div> 
		 
				<div u="navigator" class="jssorn03" style="position: absolute; bottom: 16px; left: 6px;">
					<div u="prototype" style="POSITION: absolute; WIDTH: 21px; HEIGHT: 21px; text-align:center; line-height:21px; color:White; font-size:12px;"><NumberTemplate></NumberTemplate></div>
				</div>
				<span u="arrowleft" class="jssord20l" style="width: 55px; height: 55px; top: 123px; left: 8px;">
				</span>
				<span u="arrowright" class="jssord20r" style="width: 55px; height: 55px; top: 123px; right: 8px">
				</span>
			</div>
	
			<?php
				endif;
				// Reset query to prevent conflicts
				wp_reset_query();
			?>							
			<script type="text/javascript">			
				jQuery(document).ready(function ($) {
				var _SlideshowTransitions = [
				//Collapse Random
				{$Duration: 1000, $Delay: 80, $Cols: 10, $Rows: 4, $Clip: 15, $SlideOut: true, $Easing: $JssorEasing$.$EaseOutQuad }
				//Fade in LR Chess
				, { $Duration: 1200, $Cols: 2, $During: { $Top: [0.3, 0.7] }, $FlyDirection: 4, $ChessMode: { $Column: 12 }, $Easing: { $Top: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear }, $ScaleVertical: 0.3, $Opacity: 2 }
				//Rotate VDouble+ out
				, { $Duration: 1000, $Rows: 2, $Zoom: 11, $Rotate: true, $SlideOut: true, $FlyDirection: 6, $Assembly: 2049, $ChessMode: { $Row: 15 }, $Easing: { $Left: $JssorEasing$.$EaseInExpo, $Top: $JssorEasing$.$EaseInExpo, $Zoom: $JssorEasing$.$EaseInExpo, $Opacity: $JssorEasing$.$EaseLinear, $Rotate: $JssorEasing$.$EaseInExpo }, $ScaleHorizontal: 1, $ScaleVertical: 2, $Opacity: 2, $Round: { $Rotate: 0.85} }
				//Swing Inside in Stairs
				, { $Duration: 1200, $Delay: 20, $Cols: 10, $Rows: 4, $Clip: 15, $During: { $Left: [0.3, 0.7], $Top: [0.3, 0.7] }, $FlyDirection: 9, $Formation: $JssorSlideshowFormations$.$FormationStraightStairs, $Assembly: 260, $Easing: { $Left: $JssorEasing$.$EaseInWave, $Top: $JssorEasing$.$EaseInWave, $Clip: $JssorEasing$.$EaseOutQuad }, $ScaleHorizontal: 0.2, $ScaleVertical: 0.1, $Round: { $Left: 1.3, $Top: 2.5} }
				//Zoom HDouble+ out
				, { $Duration: 1200, $Cols: 2, $Zoom: 11, $SlideOut: true, $FlyDirection: 1, $Assembly: 2049, $ChessMode: { $Column: 15 }, $Easing: { $Left: $JssorEasing$.$EaseInExpo, $Zoom: $JssorEasing$.$EaseInExpo, $Opacity: $JssorEasing$.$EaseLinear }, $ScaleHorizontal: 4, $Opacity: 2 }
				//Dodge Pet Inside in Stairs
				, { $Duration: 1500, $Delay: 20, $Cols: 10, $Rows: 4, $Clip: 15, $During: { $Left: [0.3, 0.7], $Top: [0.3, 0.7] }, $FlyDirection: 9, $Formation: $JssorSlideshowFormations$.$FormationStraightStairs, $Assembly: 260, $Easing: { $Left: $JssorEasing$.$EaseInWave, $Top: $JssorEasing$.$EaseInWave, $Clip: $JssorEasing$.$EaseOutQuad }, $ScaleHorizontal: 0.2, $ScaleVertical: 0.1, $Round: { $Left: 0.8, $Top: 2.5} }
				//Rotate Zoom+ out BL
				, { $Duration: 1200, $Zoom: 11, $Rotate: true, $SlideOut: true, $FlyDirection: 9, $Easing: { $Left: $JssorEasing$.$EaseInExpo, $Top: $JssorEasing$.$EaseInExpo, $Zoom: $JssorEasing$.$EaseInExpo, $Opacity: $JssorEasing$.$EaseLinear, $Rotate: $JssorEasing$.$EaseInExpo }, $ScaleHorizontal: 4, $ScaleVertical: 4, $Opacity: 2, $Round: { $Rotate: 0.8} }
				//Dodge Dance Inside in Random
				, { $Duration: 1500, $Delay: 80, $Cols: 10, $Rows: 4, $Clip: 15, $During: { $Left: [0.3, 0.7], $Top: [0.3, 0.7] }, $FlyDirection: 9, $Easing: { $Left: $JssorEasing$.$EaseInJump, $Top: $JssorEasing$.$EaseInJump, $Clip: $JssorEasing$.$EaseOutQuad }, $ScaleHorizontal: 0.3, $ScaleVertical: 0.3, $Round: { $Left: 0.8, $Top: 2.5} }
				//Rotate VFork+ out
				, { $Duration: 1200, $Rows: 2, $Zoom: 11, $Rotate: true, $SlideOut: true, $FlyDirection: 6, $Assembly: 2049, $ChessMode: { $Row: 28 }, $Easing: { $Left: $JssorEasing$.$EaseInExpo, $Top: $JssorEasing$.$EaseInExpo, $Zoom: $JssorEasing$.$EaseInExpo, $Opacity: $JssorEasing$.$EaseLinear, $Rotate: $JssorEasing$.$EaseInExpo }, $ScaleHorizontal: 3, $ScaleVertical: 1, $Opacity: 2, $Round: { $Rotate: 0.7} }
				//Clip and Chess in
				, { $Duration: 1200, $Cols: 10, $Rows: 4, $Clip: 15, $During: { $Top: [0.5, 0.5], $Clip: [0, 0.5] }, $FlyDirection: 8, $Formation: $JssorSlideshowFormations$.$FormationStraight, $ChessMode: { $Column: 12 }, $ScaleClip: 0.5 }
				//Swing Inside in Swirl
				, { $Duration: 1200, $Delay: 20, $Cols: 10, $Rows: 4, $Clip: 15, $During: { $Left: [0.3, 0.7], $Top: [0.3, 0.7] }, $FlyDirection: 9, $Formation: $JssorSlideshowFormations$.$FormationSwirl, $Assembly: 260, $Easing: { $Left: $JssorEasing$.$EaseInWave, $Top: $JssorEasing$.$EaseInWave, $Clip: $JssorEasing$.$EaseOutQuad }, $ScaleHorizontal: 0.2, $ScaleVertical: 0.1, $Round: { $Left: 1.3, $Top: 2.5} }
				//Rotate Zoom+ out
				, { $Duration: 1200, $Zoom: 11, $Rotate: true, $SlideOut: true, $Easing: { $Zoom: $JssorEasing$.$EaseInCubic, $Rotate: $JssorEasing$.$EaseInCubic }, $Opacity: 2, $Round: { $Rotate: 0.7} }
				//Dodge Pet Inside in ZigZag
				, { $Duration: 1500, $Delay: 20, $Cols: 10, $Rows: 4, $Clip: 15, $During: { $Left: [0.3, 0.7], $Top: [0.3, 0.7] }, $FlyDirection: 9, $Formation: $JssorSlideshowFormations$.$FormationZigZag, $Assembly: 260, $Easing: { $Left: $JssorEasing$.$EaseInWave, $Top: $JssorEasing$.$EaseInWave, $Clip: $JssorEasing$.$EaseOutQuad }, $ScaleHorizontal: 0.2, $ScaleVertical: 0.1, $Round: { $Left: 0.8, $Top: 2.5} }
				//Rotate Zoom- out TL
				, { $Duration: 1200, $Zoom: 1, $Rotate: true, $SlideOut: true, $FlyDirection: 5, $Easing: $JssorEasing$.$EaseLinear, $ScaleHorizontal: 0.8, $ScaleVertical: 0.8, $Opacity: 2, $Round: { $Rotate: 0.2} }
				//Rotate Zoom- in BR
				, { $Duration: 1200, $Zoom: 1, $Rotate: true, $During: { $Left: [0.2, 0.8], $Top: [0.2, 0.8], $Zoom: [0.2, 0.8], $Rotate: [0.2, 0.8] }, $FlyDirection: 10, $Easing: { $Zoom: $JssorEasing$.$EaseSwing, $Opacity: $JssorEasing$.$EaseLinear, $Rotate: $JssorEasing$.$EaseSwing }, $ScaleHorizontal: 0.6, $ScaleVertical: 0.6, $Opacity: 2, $Round: { $Rotate: 0.5} }
				// Wave out Eagle
				, { $Duration: 1500, $Delay: 60, $Cols: 24, $SlideOut: true, $FlyDirection: 8, $Formation: $JssorSlideshowFormations$.$FormationCircle, $Easing: $JssorEasing$.$EaseInWave, $ScaleVertical: 0.5, $Round: { $Top: 1.5} }
				//Expand Stairs
				, { $Duration: 1000, $Delay: 30, $Cols: 10, $Rows: 4, $Clip: 15, $Formation: $JssorSlideshowFormations$.$FormationStraightStairs, $Assembly: 2050, $Easing: $JssorEasing$.$EaseInQuad }
				//Fade Clip out H
				, { $Duration: 1200, $Delay: 20, $Clip: 3, $SlideOut: true, $Assembly: 260, $Easing: { $Clip: $JssorEasing$.$EaseOutCubic, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2 }
				//Dodge Pet Inside in Random Chess
				, { $Duration: 1500, $Delay: 80, $Cols: 10, $Rows: 4, $Clip: 15, $During: { $Left: [0.2, 0.8], $Top: [0.2, 0.8] }, $FlyDirection: 9, $ChessMode: { $Column: 15, $Row: 15 }, $Easing: { $Left: $JssorEasing$.$EaseInWave, $Top: $JssorEasing$.$EaseInWave, $Clip: $JssorEasing$.$EaseLinear }, $ScaleHorizontal: 0.2, $ScaleVertical: 0.1, $Round: { $Left: 0.8, $Top: 2.5} }
				];



				var _CaptionTransitions = [];
				_CaptionTransitions["L"] = { $Duration: 900, $FlyDirection: 1, $Easing: { $Left: $JssorEasing$.$EaseInCubic }, $ScaleHorizontal: 0.6, $Opacity: 2 };
				_CaptionTransitions["R"] = { $Duration: 900, $FlyDirection: 2, $Easing: { $Left: $JssorEasing$.$EaseInCubic }, $ScaleHorizontal: 0.6, $Opacity: 2 };
				_CaptionTransitions["T"] = { $Duration: 900, $FlyDirection: 4, $Easing: { $Top: $JssorEasing$.$EaseInCubic }, $ScaleVertical: 0.6, $Opacity: 2 };
				_CaptionTransitions["B"] = { $Duration: 900, $FlyDirection: 8, $Easing: { $Top: $JssorEasing$.$EaseInCubic }, $ScaleVertical: 0.6, $Opacity: 2 };
				_CaptionTransitions["TR"] = { $Duration: 900, $FlyDirection: 6, $Easing: { $Left: $JssorEasing$.$EaseInCubic, $Top: $JssorEasing$.$EaseInCubic }, $ScaleHorizontal: 0.6, $ScaleVertical: 0.6, $Opacity: 2 };

				_CaptionTransitions["L|IB"] = { $Duration: 1200, $FlyDirection: 1, $Easing: { $Left: $JssorEasing$.$EaseInOutBack }, $ScaleHorizontal: 0.6, $Opacity: 2 };
				_CaptionTransitions["R|IB"] = { $Duration: 1200, $FlyDirection: 2, $Easing: { $Left: $JssorEasing$.$EaseInOutBack }, $ScaleHorizontal: 0.6, $Opacity: 2 };
				_CaptionTransitions["T|IB"] = { $Duration: 1200, $FlyDirection: 4, $Easing: { $Top: $JssorEasing$.$EaseInOutBack }, $ScaleVertical: 0.6, $Opacity: 2 };

				_CaptionTransitions["CLIP|LR"] = { $Duration: 900, $Clip: 3, $Easing: { $Clip: $JssorEasing$.$EaseInOutCubic }, $Opacity: 2 };
				_CaptionTransitions["CLIP|TB"] = { $Duration: 900, $Clip: 12, $Easing: { $Clip: $JssorEasing$.$EaseInOutCubic }, $Opacity: 2 };
				_CaptionTransitions["CLIP|L"] = { $Duration: 900, $Clip: 1, $Easing: { $Clip: $JssorEasing$.$EaseInOutCubic }, $Opacity: 2 };

				_CaptionTransitions["MCLIP|R"] = { $Duration: 900, $Clip: 2, $Move: true, $Easing: { $Clip: $JssorEasing$.$EaseInOutCubic }, $Opacity: 2 };
				_CaptionTransitions["MCLIP|T"] = { $Duration: 900, $Clip: 4, $Move: true, $Easing: { $Clip: $JssorEasing$.$EaseInOutCubic }, $Opacity: 2 };

				_CaptionTransitions["WV|B"] = { $Duration: 1200, $FlyDirection: 10, $Easing: { $Left: $JssorEasing$.$EaseInWave, $Top: $JssorEasing$.$EaseLinear }, $ScaleHorizontal: 0.2, $ScaleVertical: 0.6, $Opacity: 2, $Round: { $Left: 1.5} };

				_CaptionTransitions["TORTUOUS|VB"] = { $Duration: 1800, $Zoom: 1, $FlyDirection: 8, $Easing: { $Top: $JssorEasing$.$EaseOutWave, $Zoom: $JssorEasing$.$EaseOutCubic }, $ScaleVertical: 0.2, $Opacity: 2, $During: { $Top: [0, 0.7] }, $Round: { $Top: 1.3} };

				_CaptionTransitions["LISTH|R"] = { $Duration: 1500, $Clip: 1, $FlyDirection: 2, $Easing: $JssorEasing$.$EaseInOutCubic, $ScaleHorizontal: 0.8, $ScaleClip: 0.8, $Opacity: 2, $During: { $Left: [0.4, 0.6], $Clip: [0, 0.4], $Opacity: [0.4, 0.6]} };

				_CaptionTransitions["RTT|360"] = { $Duration: 900, $Rotate: 1, $Easing: { $Opacity: $JssorEasing$.$EaseLinear, $Rotate: $JssorEasing$.$EaseInQuad }, $Opacity: 2 };
				_CaptionTransitions["RTT|10"] = { $Duration: 900, $Zoom: 11, $Rotate: 1, $Easing: { $Zoom: $JssorEasing$.$EaseInExpo, $Opacity: $JssorEasing$.$EaseLinear, $Rotate: $JssorEasing$.$EaseInExpo }, $Opacity: 2, $Round: { $Rotate: 0.8} };

				_CaptionTransitions["RTTL|BR"] = { $Duration: 900, $Zoom: 11, $Rotate: 1, $FlyDirection: 10, $Easing: { $Left: $JssorEasing$.$EaseInCubic, $Top: $JssorEasing$.$EaseInCubic, $Zoom: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear, $Rotate: $JssorEasing$.$EaseInCubic }, $ScaleHorizontal: 0.6, $ScaleVertical: 0.6, $Opacity: 2, $Round: { $Rotate: 0.8} };

				_CaptionTransitions["T|IE*IE"] = { $Duration: 1800, $Zoom: 11, $Rotate: -1.5, $FlyDirection: 4, $Easing: { $Top: $JssorEasing$.$EaseInOutElastic, $Zoom: $JssorEasing$.$EaseInElastic, $Rotate: $JssorEasing$.$EaseInOutElastic }, $ScaleVertical: 0.8, $Opacity: 2, $During: { $Zoom: [0, 0.8], $Opacity: [0, 0.7] }, $Round: { $Rotate: 0.5} };

				_CaptionTransitions["RTTS|R"] = { $Duration: 900, $Zoom: 1, $Rotate: 1, $FlyDirection: 2, $Easing: { $Left: $JssorEasing$.$EaseInQuad, $Zoom: $JssorEasing$.$EaseInQuad, $Rotate: $JssorEasing$.$EaseInQuad, $Opacity: $JssorEasing$.$EaseOutQuad }, $ScaleHorizontal: 0.6, $Opacity: 2, $Round: { $Rotate: 1.2} };
				_CaptionTransitions["RTTS|T"] = { $Duration: 900, $Zoom: 1, $Rotate: 1, $FlyDirection: 4, $Easing: { $Top: $JssorEasing$.$EaseInQuad, $Zoom: $JssorEasing$.$EaseInQuad, $Rotate: $JssorEasing$.$EaseInQuad, $Opacity: $JssorEasing$.$EaseOutQuad }, $ScaleVertical: 0.6, $Opacity: 2, $Round: { $Rotate: 1.2} };

				_CaptionTransitions["DDGDANCE|RB"] = { $Duration: 1800, $Zoom: 1, $FlyDirection: 10, $Easing: { $Left: $JssorEasing$.$EaseInJump, $Top: $JssorEasing$.$EaseInJump, $Zoom: $JssorEasing$.$EaseOutQuad }, $ScaleHorizontal: 0.3, $ScaleVertical: 0.3, $Opacity: 2, $During: { $Left: [0, 0.8], $Top: [0, 0.8] }, $Round: { $Left: 0.8, $Top: 2.5} };
				_CaptionTransitions["ZMF|10"] = { $Duration: 900, $Zoom: 11, $Easing: { $Zoom: $JssorEasing$.$EaseInExpo, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2 };
				_CaptionTransitions["DDG|TR"] = { $Duration: 1200, $Zoom: 1, $FlyDirection: 6, $Easing: { $Left: $JssorEasing$.$EaseInJump, $Top: $JssorEasing$.$EaseInJump, $Zoom: $JssorEasing$.$ }, $ScaleHorizontal: 0.3, $ScaleVertical: 0.3, $Opacity: 2, $During: { $Left: [0, 0.8], $Top: [0, 0.8] }, $Round: { $Left: 0.8, $Top: 0.8} };

				_CaptionTransitions["FLTTR|R"] = { $Duration: 900, $FlyDirection: 10, $Easing: { $Left: $JssorEasing$.$EaseLinear, $Top: $JssorEasing$.$EaseInWave }, $ScaleHorizontal: 0.2, $ScaleVertical: 0.1, $Opacity: 2, $Round: { $Top: 1.3} };
				_CaptionTransitions["FLTTRWN|LT"] = { $Duration: 1800, $Zoom: 1, $FlyDirection: 5, $Easing: { $Left: $JssorEasing$.$EaseInOutSine, $Top: $JssorEasing$.$EaseInWave, $Zoom: $JssorEasing$.$EaseInOutQuad }, $ScaleHorizontal: 0.5, $ScaleVertical: 0.2, $Opacity: 2, $During: { $Left: [0, 0.7], $Top: [0.1, 0.7] }, $Round: { $Top: 1.3} };

				_CaptionTransitions["ATTACK|BR"] = { $Duration: 1500, $Zoom: 1, $FlyDirection: 10, $Easing: { $Left: $JssorEasing$.$EaseOutWave, $Top: $JssorEasing$.$EaseInExpo }, $ScaleHorizontal: 0.1, $ScaleVertical: 0.5, $Opacity: 2, $During: { $Left: [0.3, 0.7], $Top: [0, 0.7] }, $Round: { $Left: 1.3} };

				_CaptionTransitions["FADE"] = { $Duration: 900, $Opacity: 2 };

				var options = {
					$AutoPlay: <?php if($autoplay_slider == "false") { echo 'false';} else { echo 'true'; } ?>,                                    //[Optional] Whether to auto play, to enable slideshow, this option must be set to true, default value is false
					$AutoPlaySteps: 1,                                  //[Optional] Steps to go for each navigation request (this options applys only when slideshow disabled), the default value is 1
					$AutoPlayInterval: <?php echo $autoplay_intervalslider; ?>,                            //[Optional] Interval (in milliseconds) to go for next slide since the previous stopped if the slider is auto playing, default value is 3000
					$PauseOnHover: 1,                                   //[Optional] Whether to pause when mouse over if a slider is auto playing, 0 no pause, 1 pause for desktop, 2 pause for touch device, 3 pause for desktop and touch device, default value is 3

					$ArrowKeyNavigation: true,   			            //[Optional] Allows keyboard (arrow key) navigation or not, default value is false
					$SlideEasing: $JssorEasing$.$EaseOutQuint,
					$SlideDuration: 1500,                                //[Optional] Specifies default duration (swipe) for slide in milliseconds, default value is 500
					$MinDragOffsetToSlide: 20,                          //[Optional] Minimum drag offset to trigger slide , default value is 20
					//$SlideWidth: 600,                                 //[Optional] Width of every slide in pixels, default value is width of 'slides' container
					//$SlideHeight: 300,                                //[Optional] Height of every slide in pixels, default value is height of 'slides' container
					$SlideSpacing: 0, 					                //[Optional] Space between each slide in pixels, default value is 0
					$DisplayPieces: 1,                                  //[Optional] Number of pieces to display (the slideshow would be disabled if the value is set to greater than 1), the default value is 1
					$ParkingPosition: 0,                                //[Optional] The offset position to park slide (this options applys only when slideshow disabled), default value is 0.
					$UISearchMode: 1,                                   //[Optional] The way (0 parellel, 1 recursive, default value is 1) to search UI components (slides container, loading screen, navigator container, direction navigator container, thumbnail navigator container etc).
					$PlayOrientation: 1,                                //[Optional] Orientation to play slide (for auto play, navigation), 1 horizental, 2 vertical, default value is 1
					$DragOrientation: 3,                                //[Optional] Orientation to drag slide, 0 no drag, 1 horizental, 2 vertical, 3 either, default value is 1 (Note that the $DragOrientation should be the same as $PlayOrientation when $DisplayPieces is greater than 1, or parking position is not 0)

					$SlideshowOptions: {                                //[Optional] Options to specify and enable slideshow or not
						$Class: $JssorSlideshowRunner$,                 //[Required] Class to create instance of slideshow
						$Transitions: _SlideshowTransitions,            //[Required] An array of slideshow transitions to play slideshow
						$TransitionsOrder: 1,                           //[Optional] The way to choose transition to play slide, 1 Sequence, 0 Random
						$ShowLink: true                                    //[Optional] Whether to bring slide link on top of the slider when slideshow is running, default value is false
					},

					$CaptionSliderOptions: {                            //[Optional] Options which specifies how to animate caption
						$Class: $JssorCaptionSlider$,                   //[Required] Class to create instance to animate caption
						$CaptionTransitions: _CaptionTransitions,       //[Required] An array of caption transitions to play caption, see caption transition section at jssor slideshow transition builder
						$PlayInMode: 1,                                 //[Optional] 0 None (no play), 1 Chain (goes after main slide), 3 Chain Flatten (goes after main slide and flatten all caption animations), default value is 1
						$PlayOutMode: 3                                 //[Optional] 0 None (no play), 1 Chain (goes before main slide), 3 Chain Flatten (goes before main slide and flatten all caption animations), default value is 1
					},

					$DirectionNavigatorOptions: {                       //[Optional] Options to specify and enable direction navigator or not
						$Class: $JssorDirectionNavigator$,              //[Requried] Class to create direction navigator instance
						$ChanceToShow: 1,                               //[Required] 0 Never, 1 Mouse Over, 2 Always
						$AutoCenter: 2,                                 //[Optional] Auto center arrows in parent container, 0 No, 1 Horizontal, 2 Vertical, 3 Both, default value is 0
						$Steps: 1                                       //[Optional] Steps to go for each navigation request, default value is 1
					},

					$NavigatorOptions: {                                //[Optional] Options to specify and enable navigator or not
						$Class: $JssorNavigator$,                       //[Required] Class to create navigator instance
						$ChanceToShow: 2,                               //[Required] 0 Never, 1 Mouse Over, 2 Always
						$AutoCenter: 1,                                 //[Optional] Auto center navigator in parent container, 0 None, 1 Horizontal, 2 Vertical, 3 Both, default value is 0
						$Steps: 1,                                      //[Optional] Steps to go for each navigation request, default value is 1
						$Lanes: 1,                                      //[Optional] Specify lanes to arrange items, default value is 1
						$SpacingX: 4,                                   //[Optional] Horizontal space between each item in pixel, default value is 0
						$SpacingY: 4,                                   //[Optional] Vertical space between each item in pixel, default value is 0
						$Orientation: 1                                 //[Optional] The orientation of the navigator, 1 horizontal, 2 vertical, default value is 1
					}
				};

				var jssor_slider1 = new $JssorSlider$("bigs_slider1_container", options);
				//responsive code begin
				//you can remove responsive code if you don't want the slider scales while window resizes
				function ScaleSlider() {
					var parentWidth = jssor_slider1.$Elmt.parentNode.clientWidth;
					if (parentWidth)
						jssor_slider1.$SetScaleWidth(Math.max(Math.min(parentWidth, 980), 300));
					else
						window.setTimeout(ScaleSlider, 30);
				}

				ScaleSlider();

				if (!navigator.userAgent.match(/(iPhone|iPod|iPad|BlackBerry|IEMobile)/)) {
					$(window).bind('resize', ScaleSlider);
				}
				//responsive code end
			});
			</script>
			<?php
			return ob_get_clean();
		}		
	}
}
	
function bigs_master_gallery_images_load() {
        global $mfpd;
        $mfpd = new Banner_Introduction_Slider();
}
add_action( 'plugins_loaded', 'bigs_master_gallery_images_load' );