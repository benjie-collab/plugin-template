<?php
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