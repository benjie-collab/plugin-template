<?php
defined( 'ABSPATH' ) OR exit;
/*
Plugin Name: Wordpress Plugin Template
Description: A Class based plugin template for wordpress. Install and add table; uninstall and delete table
Author: <a href="http://www.benznext.com" target="_blank">Benjie Bantecil</a>
Version: 0.1
*/


register_activation_hook( __FILE__, array( 'WPPluginTemplate_Inc', 'on_activation' ) );
register_deactivation_hook( __FILE__, array( 'WPPluginTemplate_Inc', 'on_deactivation' ) );
register_uninstall_hook( __FILE__, array( 'WPPluginTemplate_Inc', 'on_uninstall' ) );


register_activation_hook(__FILE__, array( 'WPPluginTemplate', 'create_wppt_template_table' ));
register_activation_hook( __FILE__, array( 'WPPluginTemplate', 'insert_wppt_template_table'));
add_action( 'plugins_loaded', array( 'WPPluginTemplate', 'init' ) );


class WPPluginTemplate {
 	protected static $instance;
	static $wppt_db_version = '1.0.0';
	static $wppt_db_name = 'plugin_template';
	
    public static function init()
    {
        is_null( self::$instance ) AND self::$instance = new self;
        return self::$instance;
    }
    public function __construct() {
 		add_action( current_filter(), array( $this, 'load_files' ), 30 );
 
        load_plugin_textdomain( 'demo-plugin', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
        add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_styles' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_scripts' ) );
 
        add_filter( 'the_content', array( $this, 'append_post_notification' ) );
		
 
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
     * install
     * Do the things
     */
    public function install() {
		$this->checkop();
    }
	
	
	
	/**
     * Restores the WP Options to the defaults
     * Deletes the default options set and calls checkop
     */
    public function restore_op() {
        delete_option('wp_plugin_op');
        $this->checkop();
    }
	
	
	/**
     * Creates the options
     */
	private function checkop() {
		//the default options
		$wp_plugin_op = array(
			'bar' => array('name'=>'Bar','thumb'=>"chart.png",'type'=>"Bar",'palette'=>"Default",'useajax'=>'0','dataurl'=>'','search'=>'0','description'=>"This demo illustrates the bar series type. With this type, data is displayed as sets of rectangular bars with lengths proportional to the values that they represent. ",'config'=>'{dataSource:[],commonSeriesSettings:{argumentField:"state",type:"bar",hoverMode:"allArgumentPoints",selectionMode:"allArgumentPoints",label:{visible:true,format:"fixedPoint",precision:0}},series:[{valueField:"year2004",name:"2004"},{valueField:"year2001",name:"2001"},{valueField:"year1998",name:"1998"}],title:"Great Lakes Gross State Product",legend:{verticalAlignment:"bottom",horizontalAlignment:"center"},pointClick:function(point){this.select()}}','data'=>'[{state:"Illinois",year1998:423.721,year2001:476.851,year2004:528.904},{state:"Indiana",year1998:178.719,year2001:195.769,year2004:227.271},{state:"Michigan",year1998:308.845,year2001:335.793,year2004:372.576},{state:"Ohio",year1998:348.555,year2001:374.771,year2004:418.258},{state:"Wisconsin",year1998:160.274,year2001:182.373,year2004:211.727}]'),
			'pie' => array('name'=>'Pie','thumb'=>"pieChart.png",'type'=>"Pie",'palette'=>"Default",'useajax'=>'0','dataurl'=>'','search'=>'0','description'=>"This demo illustrates the bar series type. With this type, data is displayed as sets of rectangular bars with lengths proportional to the values that they represent. ",'config'=>'{dataSource:[],series:[{argumentField:"country",valueField:"area",label:{visible:true,connector:{visible:true,width:1}}}],title:"Area of Countries"}','data'=>'[{country:"Russia",area:12},{country:"Canada",area:7},{country:"USA",area:7},{country:"China",area:7},{country:"Brazil",area:6},{country:"Australia",area:5},{country:"India",area:2},{country:"Others",area:55}]'),
			'range selector' => array('name'=>'Range Selector','thumb'=>"rangeselector.png",'type'=>"Range Selector",'palette'=>"Default",'useajax'=>'0','dataurl'=>'','search'=>'0','description'=>"This demo illustrates the bar series type. With this type, data is displayed as sets of rectangular bars with lengths proportional to the values that they represent. ",'config'=>'{margin:{top:20},size:{height:140},dataSource:[],dataSourceField:"BirthYear",behavior:{callSelectedRangeChanged:"onMoving"},selectedRangeChanged:function(e){var selectedEmployees=$.grep(employees,function(employee){return employee.BirthYear>=e.startValue&&employee.BirthYear<=e.endValue});showEmployees(selectedEmployees)}}','data'=>'[{LastName:"Davolio",FirstName:"Nancy",BirthYear:1948,City:"Seattle",Title:"Sales Representative"},{LastName:"Fuller",FirstName:"Andrew",BirthYear:1952,City:"Tacoma",Title:"Vice President, Sales"},{LastName:"Leverling",FirstName:"Janet",BirthYear:1963,City:"Kirkland",Title:"Sales Representative"},{LastName:"Peacock",FirstName:"Margaret",BirthYear:1937,City:"Redmond",Title:"Sales Representative"},{LastName:"Buchanan",FirstName:"Steven",BirthYear:1955,City:"London",Title:"Sales Manager"},{LastName:"Suyama",FirstName:"Michael",BirthYear:1963,City:"London",Title:"Sales Representative"},{LastName:"King",FirstName:"Robert",BirthYear:1960,City:"London",Title:"Sales Representative"},{LastName:"Callahan",FirstName:"Laura",BirthYear:1958,City:"Seattle",Title:"Inside Sales Coordinator"},{LastName:"Dodsworth",FirstName:"Anne",BirthYear:1966,City:"London",Title:"Sales Representative"}]'),
			'circular gauges' => array('name'=>'Circular Gauges','thumb'=>"gauge.png",'type'=>"Circular Gauges",'palette'=>"Default",'useajax'=>'0','dataurl'=>'','search'=>'0','description'=>"This demo illustrates the bar series type. With this type, data is displayed as sets of rectangular bars with lengths proportional to the values that they represent. ",'config'=>'{scale:{startValue:50,endValue:150,majorTick:{tickInterval:10}},rangeContainer:{palette:"pastel",ranges:[]},title:{text:"Temperature of the Liquid in the Boiler",font:{size:28}},value:105}','data'=>'[{startValue:50,endValue:90},{startValue:90,endValue:130},{startValue:130,endValue:150}]'),
			'linear gauges' => array('name'=>'Linear Gauges','thumb'=>"linearGauges.png",'type'=>"Linear Gauges",'palette'=>"Default",'useajax'=>'0','dataurl'=>'','search'=>'0','description'=>"This demo illustrates the bar series type. With this type, data is displayed as sets of rectangular bars with lengths proportional to the values that they represent. ",'config'=>'{scale:{startValue:0,endValue:30,majorTick:{color:"#536878",tickInterval:5},label:{indentFromTick:-3}},rangeContainer:{offset:10,ranges:[]},valueIndicator:{offset:20},subvalueIndicator:{offset:-15},title:{text:"Issues Closed (with Min and Max Expected)",font:{size:28}},value:17,subvalues:[5,25]}','data'=>'[{startValue:0,endValue:5,color:"#92000A"},{startValue:5,endValue:20,color:"#E6E200"},{startValue:20,endValue:30,color:"#77DD77"}]'),
			'bar gauge' => array('name'=>'Bar Gauge','thumb'=>"barGauge.png",'type'=>"Bar Gauge",'palette'=>"Default",'useajax'=>'0','dataurl'=>'','search'=>'0','description'=>"This demo illustrates the bar series type. With this type, data is displayed as sets of rectangular bars with lengths proportional to the values that they represent. ",'config'=>'{startValue:0,endValue:100,values:[47.27,65.32,84.59,71.86],label:{indent:30,format:"fixedPoint",precision:1,customizeText:function(arg){return arg.valueText+\'%\'}},title:{text:"Series\' Ratings",font:{size:28}}}','data'=>'[121.4, 135.4, 115.9, 141.1, 127.5]'),
			'sparklines' => array('name'=>'Sparklines','thumb'=>"sparkline.png",'type'=>"Sparklines",'palette'=>"Default",'useajax'=>'0','dataurl'=>'','search'=>'0','description'=>"This demo illustrates the bar series type. With this type, data is displayed as sets of rectangular bars with lengths proportional to the values that they represent. ",'config'=>'{dataSource:[],argumentField:"month",valueField:"2010",type:"line",showMinMax:true}','data'=>'[{month:1,2010:7341,2011:9585,2012:7501},{month:2,2010:7016,2011:10026,2012:8470},{month:3,2010:7202,2011:9889,2012:8591},{month:4,2010:7851,2011:9460,2012:8459},{month:5,2010:7481,2011:9373,2012:8320},{month:6,2010:6532,2011:9108,2012:7465},{month:7,2010:6498,2011:9295,2012:7475},{month:8,2010:7191,2011:9834,2012:7430},{month:9,2010:7596,2011:9121,2012:7614},{month:10,2010:8057,2011:7125,2012:8245},{month:11,2010:8373,2011:7871,2012:7727},{month:12,2010:8636,2011:7725,2012:7880}]'),
			'vector map' => array('name'=>'Vector Map','thumb'=>"vectorMap.png",'type'=>"Vector Map",'palette'=>"Default",'useajax'=>'0','dataurl'=>'','search'=>'0','description'=>"This demo illustrates the bar series type. With this type, data is displayed as sets of rectangular bars with lengths proportional to the values that they represent. ",'config'=>'{mapData:DevExpress.viz.map.sources.world,markers:[],markerSettings:{customize:function(arg){return{text:arg.attributes.name}}}}','data'=>'[{coordinates:[-0.1262,51.5002],attributes:{name:"London"}},{coordinates:[149.1286,-35.2820],attributes:{name:"Canberra"}},{coordinates:[139.6823,35.6785],attributes:{name:"Tokyo"}},{coordinates:[-77.0241,38.8921],attributes:{name:"Washington"}}]')
		);
	 
		//check to see if present already
		if(!get_option('wp_plugin_op')) {
			//option not found, add new
			add_option('wp_plugin_op', $wp_plugin_op);
		} else {
			//option already in the database
			//so we get the stored value and merge it with default
			$old_op = get_option('wp_plugin_op');
			$wp_plugin_op = wp_parse_args($old_op, $wp_plugin_op);
	 
			//update it
			update_option('wp_plugin_op', $wp_plugin_op);
		}
	}
	
	public function getop(){
		return get_option('wp_plugin_op');
		}
		
		
	public function create_wppt_template_table() {
		global $wpdb;
		$table_name = $wpdb->prefix . self::$wppt_db_name;
	
		/*
		 * We'll set the default character set and collation for this table.
		 * If we don't do this, some characters could end up being converted 
		 * to just ?'s when saved in our table.
		 */
		$charset_collate = '';
	
		if ( ! empty( $wpdb->charset ) ) {
		  $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
		}
	
		if ( ! empty( $wpdb->collate ) ) {
		  $charset_collate .= " COLLATE {$wpdb->collate}";
		}
	
		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			name tinytext NOT NULL,
			type varchar (55)DEFAULT 'Bar' NOT NULL,
			palette varchar(55) DEFAULT 'Default' NOT NULL,
			config text NOT NULL,
			data text NOT NULL,
			useajax tinyint (1) DEFAULT '0' NOT NULL,
			dataurl varchar(55) DEFAULT '' NOT NULL,
			search tinyint (1) DEFAULT '0' NOT NULL,
			description text NOT NULL,
			UNIQUE KEY id (id)
		) $charset_collate;";
	
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );	
		
		if( !get_option( "wppt_db_version" ) ) {
                add_option( "wppt_db_version", self::$wppt_db_version );
            }
	
		
    }
	
	public function insert_wppt_template_table(){
		global $wpdb;
		$table_name = $wpdb->prefix . self::$wppt_db_name;
		
		$welcome_name = 'Mr. WordPres';
		$welcome_text = 'Congratulations, you just completed the installation!';
		
		
		$wpdb->insert( 
			$table_name, 
			array( 			
				'time' => current_time( 'mysql' ), 
				'name' => $welcome_name,
				'type' => 'Bar',
				'palette' => 'Default',
				'config' => '{}',
				'data' => '[]',
				'useajax' => '0',
				'dataurl' => '',
				'search' => '0',
				'description' => $welcome_text
			) 
		);
		
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