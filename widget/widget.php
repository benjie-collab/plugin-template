<?php
defined( 'ABSPATH' ) OR exit;

/**
class wppt_plugin extends WP_Widget {

	// constructor
	function wppt_plugin() {
		parent::WP_Widget(false, $name = __('WPPT Visualize', 'wppt_widget_plugin') );
	}

	// widget form creation
	function form($instance) {		
		global $wpptopts;
		global $wpdb;
		global $wppt_db_name;		
		
		$wpdb->show_errors();
		$charts = $wpdb->get_results( "SELECT * FROM " .  $wpdb->prefix . $wppt_db_name); 
		//$charts = reset($charts);

		wp_localize_script( 'wppt-charts', 'wppt_charts',  array_merge(array('charts' => $charts), $wpptopts) );
		wp_enqueue_script(array('jquery', 'wppt-charts'));
	
		// Check values
		if( $instance) {
			 $title = esc_attr($instance['title']);
			 $id = esc_attr($instance['id']);
		} else {
			 $title = '';
			 $id = '';
			 $textarea = '';
		}
		
		?>

		<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'wppt_widget_plugin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		
		<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Select Viz:', 'wppt_widget_plugin'); ?></label>
		<select class="widefat" id="<?php echo $this->get_field_id('id'); ?>" name="<?php echo $this->get_field_name('id'); ?>">
			<?php 
				foreach($charts as $key =>$viz):
					echo '<option value="' . $viz->id . '" ' . (($id===$viz->id)? 'selected': '' ). '>' . $viz->name . '</option>';
				endforeach;
			?>
		</select>		
		</p>		

		<?php
	}

	// widget update
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
			  // Fields
			  $instance['title'] = strip_tags($new_instance['title']);
			  $instance['id'] = strip_tags($new_instance['id']);
			 return $instance;
	}

	// widget display
	function widget($args, $instance) {
		
		extract( $args );
	   // these are the widget options  
	   $title = apply_filters('widget_title', $instance['title']);
	   $id = $instance['id'];	   
	   
	   global $wpptopts;
	   global $wpdb;
	   global $wppt_db_name;
	   
	   $charts = $wpdb->get_results( "SELECT * FROM " .  $wpdb->prefix . $wppt_db_name . ' WHERE ID = ' . $id); 	
	   $charts = reset($charts);
	   $randomString = wppt_plugin_getRandomCode();
	
	   echo $before_widget;
	   
	   // Display the widget
	   echo '<div class="widget-text" id="wppt-fe-' . $charts->id . $randomString  . '-container">';	   
	   // Check if title is set
	   if ( $title ) {
		  echo $before_title . $title . $after_title;
	   }	 
		echo '';
	   echo '<div id="wppt-fe-' . $charts->id . $randomString . '" class="">' . '</div>';
	   echo '</div>';
	   
	    
		
	   ?>			
		<script>		
			jQuery(document).ready(function($){
				
				var IS_VALID_OPT = true, IS_VALID_DATA = true;
							   try{
									var nconfig = (new Function("return " + <?php echo JSON_encode($charts->config)?>.replace(/(\r\n|\n|\r|\t|\s+)/gm," ")))();
							   }
							   catch(err){
									IS_VALID_OPT = false;
							   }  
							   
				
							   try{
									var ndata = (new Function("return " + <?php echo JSON_encode($charts->data)?>.replace(/(\r\n|\n|\r|\t|\s+)/gm," ")))();							
							   }
							   catch(err) {
									IS_VALID_DATA = false;
							   }  
							   
				console.log(nconfig);
				if(IS_VALID_OPT === true && IS_VALID_DATA === true){
					var viz = new vizFrontEndModel({
									s			: '<?php echo get_search_query() ?>',
									params		: [],							
									config		: nconfig,
									data		: ndata,
									palette		: '<?php echo $charts->palette ?>',
									type		: '<?php echo $charts->type ?>',
									container	: '#wppt-fe-<?php echo $charts->id . $randomString ?>',							
								});								
						viz.config.subscribe(viz.init);					
						viz.data.subscribe(viz.init);	
						viz.s.subscribe(viz.init);					
						ko.cleanNode($('#wppt-fe-<?php echo $charts->id . $randomString ?>-container')[0]); // clean it again
						ko.applyBindings(viz, jQuery('#wppt-fe-<?php echo $charts->id . $randomString?>-container')[0]);
						viz.init();
				}else{
						alert('Check the chart options as well as Data format');
					}
			
			});
						
		</script>
		
		<?php		
	   echo $after_widget;		   
		
	}
	
	
	
	function wppt_plugin_sc($atts){
		ob_start();
		global $wpptopts;
		global $wpdb;
		global $wppt_db_name;
	   
		$charts = $wpdb->get_results( "SELECT * FROM " .  $wpdb->prefix . $wppt_db_name . ' WHERE ID = ' . $atts['id']); 	
		$charts = reset($charts);
		$randomString = wppt_plugin_getRandomCode();
	
		echo $before_widget;
	   
		// Display the widget
		echo '<div class="widget-text" id="wppt-fe-' . $charts->id . $randomString  . '-container">';	   
		// Check if title is set
		if ( $title ) {
		  echo $before_title . $title . $after_title;
		}	 
		echo '';
		echo '<div id="wppt-fe-' . $charts->id . $randomString . '" class="wppt-container">' . '</div>';
		echo '</div>';
	   ?>			
		<script>		
			jQuery(document).ready(function($){
				
				var IS_VALID_OPT = true, IS_VALID_DATA = true;
							   try{
									var nconfig = (new Function("return " + <?php echo JSON_encode($charts->config)?>.replace(/(\r\n|\n|\r|\t|\s+)/gm," ")))();
							   }
							   catch(err){
									IS_VALID_OPT = false;
							   }  
							   
				
							   try{
									var ndata = (new Function("return " + <?php echo JSON_encode($charts->data)?>.replace(/(\r\n|\n|\r|\t|\s+)/gm," ")))();							
							   }
							   catch(err) {
									IS_VALID_DATA = false;
							   }  
							   
				if(IS_VALID_OPT === true && IS_VALID_DATA === true){
					var viz = new wpptFrontEndModel({
									s			: '<?php echo get_search_query() ?>',
									params		: [],							
									config		: nconfig,
									data		: ndata,
									palette		: '<?php echo $charts->palette ?>',
									type		: '<?php echo $charts->type ?>',
									container	: '#wppt-fe-<?php echo $charts->id . $randomString ?>',							
								});								
						viz.config.subscribe(viz.init);					
						viz.data.subscribe(viz.init);	
						viz.s.subscribe(viz.init);					
						ko.cleanNode($('#wppt-fe-<?php echo $charts->id . $randomString ?>-container')[0]); // clean it again
						ko.applyBindings(viz, jQuery('#wppt-fe-<?php echo $charts->id . $randomString?>-container')[0]);
						viz.init();
				}else{
						alert('Check the chart options as well as Data format');
					}
			
			});
						
		</script>		
		<?php
		
		$output = ob_get_clean();
		return $output;
	}
}
















**/
   
    /* Plugin Name: [Enter name of your plugin here]
    Plugin URI: [Enter your website URL]
    Description: [Enter brief description of plugin]
    Version: [Enter version number of plugin (probably 1.0)]
    Author: [Enter your name]
    Author URI: [Enter your website URL]
    */

    class WPPluginWidget extends WP_Widget {
	
		static $wppt_db_version = '1.0.0';
		static $wppt_db_name = PLGIN_DB_NAME;
		
		
		function WPPluginWidget() {
			$widget_ops = array(
				'classname' => 'WPPluginWidget',
				'description' => '[Enter brief description of plugin]'
			);

			$this->WP_Widget(
				'WPPluginWidget',
				'WP Plugin Widget',
				$widget_ops
			);
		}
		
		function form($instance) {	
			global $wpdb;
			
			$table_name = $wpdb->prefix . self::$wppt_db_name;
			$options = self::getop();
			
			$wpdb->show_errors();
			$viz = $wpdb->get_results( "SELECT * FROM " .  $table_name); 
			//$viz = reset($charts);

			//wp_localize_script( 'wppt-viz', 'wppt_viz',  array_merge(array('viz' => $viz), $options) );
			//wp_enqueue_script(array('jquery', 'wppt-viz'));
		
			// Check values
			if( $instance) {
				 $title = esc_attr($instance['title']);
				 $id = esc_attr($instance['id']);
			} else {
				 $title = '';
				 $id = '';
				 $textarea = '';
			}		
			
			?>
			
			<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'wppt_widget_plugin'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
			</p>
			
			<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Select Viz:', 'wppt_widget_plugin'); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('id'); ?>" name="<?php echo $this->get_field_name('id'); ?>">
				<?php 
					foreach($viz as $key =>$vz):
						echo '<option value="' . $vz->id . '" ' . (($id===$vz->id)? 'selected': '' ). '>' . $vz->name . '</option>';
					endforeach;
				?>
			</select>		
			</p>		

			<?php
		}	
		
		
		
		
		
		
		function widget($args, $instance) { // widget sidebar output
			global $wpdb;
			extract($args, EXTR_SKIP);
			
			$table_name = $wpdb->prefix . self::$wppt_db_name;
			$options = self::getop();
			
			
			
			$title = apply_filters('widget_title', $instance['title']);
		    $id = $instance['id'];	   
		   
		   	$wpdb->show_errors();
			$viz = $wpdb->get_results( "SELECT * FROM " .  $table_name . ' WHERE ID = ' . $id); 	
			$viz = reset($viz);
			$randomString = self::getRandomCode();
			
			
			
			
			echo $before_widget; // pre-widget code from theme
			
			// Display the widget
			echo '<div class="widget-text" id="wppt-fe-' . $viz->id . $randomString  . '-container">';	   
			// Check if title is set
			if ( $title ) {
				echo $before_title . $title . $after_title;
			}	 
			echo '';
			echo '<div id="wppt-fe-' . $viz->id . $randomString . '" class="">' . '</div>';
			echo '</div>';
		   
			
		   ?>			
			<script>		
				jQuery(document).ready(function($){
					
					var IS_VALID_OPT = true, IS_VALID_DATA = true;
								   try{
										var nconfig = (new Function("return " + <?php echo JSON_encode($viz->config)?>.replace(/(\r\n|\n|\r|\t|\s+)/gm," ")))();
								   }
								   catch(err){
										IS_VALID_OPT = false;
								   }  
								   
					
								   try{
										var ndata = (new Function("return " + <?php echo JSON_encode($viz->data)?>.replace(/(\r\n|\n|\r|\t|\s+)/gm," ")))();							
								   }
								   catch(err) {
										IS_VALID_DATA = false;
								   }  
								   
					console.log(nconfig);
					if(IS_VALID_OPT === true && IS_VALID_DATA === true){
						var viz = new vizFrontEndModel({
										s			: '<?php echo get_search_query() ?>',
										params		: [],							
										config		: nconfig,
										data		: ndata,
										palette		: '<?php echo $viz->palette ?>',
										type		: '<?php echo $viz->type ?>',
										container	: '#wppt-fe-<?php echo $viz->id . $randomString ?>',							
									});								
							viz.config.subscribe(viz.init);					
							viz.data.subscribe(viz.init);	
							viz.s.subscribe(viz.init);					
							ko.cleanNode($('#wppt-fe-<?php echo $viz->id . $randomString ?>-container')[0]); // clean it again
							ko.applyBindings(viz, jQuery('#wppt-fe-<?php echo $viz->id . $randomString?>-container')[0]);
							viz.init();
					}else{
							alert('Check the chart options as well as Data format');
						}
				
				});
							
			</script>
			
			<?php		
		   echo $after_widget;
			
			
			echo $after_widget; // post-widget code from theme
		}			
		
		
		function getRandomCode(){
			$an = "abcdefghijklmnopqrstuvwxyz-_";
			$su = strlen($an) - 1;
			return substr($an, rand(0, $su), 1) .
					substr($an, rand(0, $su), 1) .
					substr($an, rand(0, $su), 1) .
					substr($an, rand(0, $su), 1) .
					substr($an, rand(0, $su), 1) .
					substr($an, rand(0, $su), 1);
		}		
		
		public function getop(){
			return get_option('wp_plugin_op');
		}
		
    }

    
		/** Widget **/
		//add_action('widgets_init', create_function('', 'return register_widget("WPPluginWidget");'));
		



