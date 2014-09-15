<?php
defined( 'ABSPATH' ) OR exit;
/*
Plugin Name: Wordpress Plugin Template
Description: A Class based plugin template for wordpress. Install and add table; uninstall and delete table
Author: <a href="http://www.benznext.com" target="_blank">Benjie Bantecil</a>
Version: 0.1
*/
define('PLGIN_URL', WP_PLUGIN_URL."/".dirname( plugin_basename( __FILE__ ) ) );
define('PLGIN_PATH', plugin_dir_path(__FILE__));  

register_activation_hook( __FILE__, array( 'WPPluginTemplate_Inc', 'on_activation' ) );
register_deactivation_hook( __FILE__, array( 'WPPluginTemplate_Inc', 'on_deactivation' ) );
register_uninstall_hook( __FILE__, array( 'WPPluginTemplate_Inc', 'on_uninstall' ) );


register_activation_hook(__FILE__, array( 'WPPluginTemplate', 'create_plugin_table' ));
//register_activation_hook( __FILE__, array( 'WPPluginTemplate', 'insert_plugin_table')); //Dont add initial Data

register_deactivation_hook( __FILE__, array( 'WPPluginTemplate', 'restore_op' ) );

add_action( 'plugins_loaded', array( 'WPPluginTemplate', 'init' ) );


class WPPluginTemplate {
 	protected static $instance;
	static $wppt_db_version = '1.0.0';
	static $wppt_db_name = 'plugin_template';
	
    public static function init(){		
        is_null( self::$instance ) AND self::$instance = new self;
        return self::$instance;
    }
    public function __construct() {
		self::checkop();
		
 		add_action( current_filter(), array( $this, 'load_files' ), 30 );
		
        load_plugin_textdomain( 'wp-plugin-template', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
        add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_styles' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_scripts' ) );
		
		
		add_action('admin_init', array( $this, 'admininit_callback' ), 20 );
		add_action('admin_menu', array( $this, 'adminmenu_callback' ), 20);
		
		add_action("wp_ajax_nopriv_viz_selecttype", array( $this, 'viz_select_type' ), 20);
		add_action('wp_ajax_viz_selecttype', array( $this, 'viz_select_type' ), 20);
		add_action("wp_ajax_nopriv_vizpreview", array( $this, 'viz_preview' ), 20);
		add_action('wp_ajax_vizpreview', array( $this, 'viz_preview' ), 20);
		add_action("wp_ajax_nopriv_vizsubmit", array( $this, 'viz_submit' ), 20);
		add_action('wp_ajax_vizsubmit', array( $this, 'viz_submit' ), 20);
		
		add_action("wp_ajax_nopriv_viz_remotedata", array( $this, 'viz_remote_data' ), 20);
		add_action('wp_ajax_viz_remotedata', array( $this, 'viz_remote_data' ), 20);

		

        add_filter( 'the_content', array( $this, 'append_post_notification' ) );
    }
	
	
	
	
	
	public function add_options_page_callback(){   
		/* Display our administration screen */
		
		foreach ( glob( PLGIN_PATH.'admin/*.php' ) as $file ){
            require_once $file;
		}
    }		
	
	public function admininit_callback(){ 
		/** Bootstrap **/	
		wp_enqueue_script('bootstrap', PLGIN_URL.'/lib/bootstrap/js/bootstrap.js', array(), '', true );
		wp_enqueue_style( 'bootstrap', PLGIN_URL.'/lib/bootstrap/css/bootstrap.css');
		wp_enqueue_style( 'fontawesome', PLGIN_URL.'/lib/font-awesome-4.2.0/css/font-awesome.min.css');
	
		/** Options Page Library **/
		wp_enqueue_script('knockout', PLGIN_URL.'/lib/knockout-3.2.0.js', array(), '', true );
		wp_enqueue_script('globalize', PLGIN_URL.'/lib/globalize.js', array(), '', true );
		wp_enqueue_script('dxchart', PLGIN_URL.'/lib/dx.all.js', array(), '', true );
		wp_enqueue_script('dxchart-map-world', PLGIN_URL.'/data/world.js', array(), '', true );		
		
		/** Code Mirror **/
		wp_enqueue_script('codemirror', PLGIN_URL.'/lib/codemirror/lib/codemirror.js', array(), '', true  );
		wp_enqueue_script('codemirror-mode-js', PLGIN_URL.'/lib/codemirror/mode/javascript/javascript.js', array(), '', true  );
		wp_enqueue_script('codemirror-addon-activeline', PLGIN_URL.'/lib/codemirror/addon/selection/active-line.js', array(), '', true  );	
		wp_enqueue_script('codemirror-addon-matchbrackets', PLGIN_URL.'/lib/codemirror/addon/edit/matchbrackets.js', array(), '', true  );
		wp_enqueue_script('codemirror-addon-wrap', PLGIN_URL.'/lib/codemirror/addon/wrap/hardwrap.js', array(), '', true  );
		wp_enqueue_style( 'codemirror', PLGIN_URL .'/lib/codemirror/lib/codemirror.css');
		
		/** KO Model Enqueue**/
		wp_enqueue_script('ko-codemirror', PLGIN_URL.'/models/codemirror.js', array(), '', true );		
		wp_enqueue_script('ko-viz', PLGIN_URL.'/models/viz.js', array('jquery'), '', true ); 
		wp_enqueue_script('ko-buildviz', PLGIN_URL.'/models/buildviz.js', array('jquery'), '', true  );
		
		/** Admin Scripts **/
		wp_enqueue_style( 'wpptstyle', PLGIN_URL .'/admin/styles/style.css');
		wp_enqueue_script( 'wpptscript', PLGIN_URL .'/admin/scripts/script.js', array('jquery'), '', true );
		
		/** KO Registers **/
		wp_register_script('adminviz', PLGIN_URL.'/admin/scripts/viz.js' );	
		
		
		add_thickbox();
	}	
	
	public function adminprintscripts_callback(){ 
		wp_localize_script( 'adminviz', 'viz_opts',  array_merge(array('viz' => self::getviz() ), self::getop()) );
		wp_enqueue_script(array('jquery', 'adminviz'));
	}	
	
	
	public function adminmenu_callback(){  
		$page_hook_suffix = add_submenu_page( 'upload.php', // The parent page of this submenu
                                  __( 'WP Plugin Template', 'WPPluginTemplate' ), // The submenu title
                                  __( 'WP Plugin Template', 'WPPluginTemplate' ), // The screen title
								  'manage_options', // The capability required for access to this submenu
								  'wppt-options', // The slug to use in the URL of the screen
                                  array( $this, 'add_options_page_callback' ) // The function to call to display the screen
                               );
		
		add_action('admin_print_scripts-' . $page_hook_suffix, array( $this, 'adminprintscripts_callback' ));	
    }
	
	
	public function getviz(){	
		global $wpdb;
		$table_name = $wpdb->prefix . self::$wppt_db_name;		
		$wpdb->show_errors();
		return $wpdb->get_results( "SELECT * FROM " .  $table_name); 
	}	
	
	
	public function load_files(){
        foreach ( glob( plugin_dir_path( __FILE__ ).'inc/*.php' ) as $file ){
            require_once $file;
		}		
    }
	
	
	public function register_plugin_styles() { 
		//wp_register_style( 'demo-plugin', plugins_url( 'demo-plugin/css/plugin' ) );
		//wp_enqueue_style( 'demo-plugin' );	 
	}
	 
	public function register_plugin_scripts() {
		//wp_register_script( 'demo-plugin', plugins_url( 'demo-plugin/js/display.js' ) );
		//wp_enqueue_script( 'demo-plugin' );
	}
	
	
	
	/**
     * Restores the WP Options to the defaults
     * Deletes the default options set and calls checkop
     */
    public function restore_op() {
        delete_option('wp_plugin_op');
        self::checkop();
    }	
	
	
	
	/**
     * Creates the options
     */
	private function checkop() {
		//the default options
		require_once (PLGIN_PATH.'admin/inc/checkop.php');
	}
	
	public function getop(){
		return get_option('wp_plugin_op');
		}
		
		
	public function create_plugin_table() {	
		require_once (PLGIN_PATH.'admin/inc/create-plugin-table.php');		
    }
	
	public function insert_plugin_table(){
		require_once (PLGIN_PATH.'admin/inc/insert-plugin-table.php');
	}
	
		
	public function viz_select_type(){ 		
        require_once (PLGIN_PATH.'admin/inc/select-type.php');
		die();
	}	
		
		
	public function viz_preview(){ 
		require_once (PLGIN_PATH.'admin/inc/preview.php');
		die();
	}
	
	public function viz_submit(){ 	
		require_once (PLGIN_PATH.'admin/inc/submit.php');
		die();	
	}	
	
	public function viz_remote_data(){ 
		require_once (PLGIN_PATH.'admin/inc/remote-data.php');
		die();
	}
		
		
		
		
		
	public function append_post_notification( $content ) {
 
		$notification = __( 'This message was appended with a Demo Plugin.', 'demo-plugin-locale' );	 
		return $content . $notification;
	 
	}
 
}
//$wpPluginTemplate = new WPPluginTemplate();
//echo $wpPluginTemplate->append_post_notification();
//$wpPluginTemplate->install();
//echo $wpPluginTemplate->getop();