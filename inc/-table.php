<?php
defined( 'ABSPATH' ) OR exit;

class WPPluginTemplate_Inc_Create_Tables {
    static $wppt_db_version = '1.0.0';
	static $wppt_db_name = 'plugin_template';
	
    static function create_wppt_template_table() {
        global $wpdb;
		global $wppt_db_name;
		
		echo "dvhdvk";
		$table_name = $wpdb->prefix . $wppt_db_name;
	
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
	
		/**
	
        $table_syn_result = $wpdb->prefix . "rsg_syn_result";
        if($wpdb->get_var("show tables like '$table_syn_result'") != $table_syn_result){
            $sql = "CREATE TABLE " . $table_syn_result . " (
                'id' int(10) unsigned NOT NULL AUTO_INCREMENT,
                'page_no' int(11) DEFAULT NULL,
                'language' varchar(30) NOT NULL,
                'position' int(11) DEFAULT NULL,
                'gdomain' varchar(100) DEFAULT NULL,
                'keyword' varchar(500) DEFAULT NULL,
                'synonym' varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                'keyword_id' int(11) DEFAULT NULL,
                'type' enum('heading','description') DEFAULT NULL,
                'created' datetime DEFAULT NULL,
                'updated' datetime DEFAULT NULL,
                PRIMARY KEY ('id')
            );";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            if( !get_option( "gripper_db_version" ) ) {
                add_option( "gripper_db_version", self::$gripper_db_version );
            }
        }**/
    }
}