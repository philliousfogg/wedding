<?php
/**
 * @package creativeblast-co-uk-wedding
 * @version 0.1
 */
/*
Plugin Name: Wedding 
Plugin URI: www.creativeblast.co.uk
Description: 
Author: Phil Gorman
Version: 0.1
Author URI: 
*/


if ( !class_exists('cb_wedding_installation') )
{
	class cb_wedding_installation {

		public function __construct() {

			global $cb_wedding_db_version;
			$cb_wedding_db_version = '1.0';

			// register activation hook for creating database tables
			register_activation_hook( __FILE__,  array ( $this, 'cb_wedding_install' ) );
			register_activation_hook( __FILE__,  array ( $this, 'cb_wedding_install_data' ) );
		}

		// Installation of the Database
		public function cb_wedding_install() {
			
			global $wpdb;
			global $cb_wedding_db_version;

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

			$charset_collate = '';

			if ( ! empty( $wpdb->charset ) ) {
			  $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
			}

			if ( ! empty( $wpdb->collate ) ) {
			  $charset_collate .= " COLLATE {$wpdb->collate}";
			}

			// Guest Table_______________________________________________

			$table_guests = $wpdb->prefix . 'cb_wedding_guests';

			$sql1 = "CREATE TABLE IF NOT EXISTS $table_guests (
				guest_Id mediumint(9) NOT NULL AUTO_INCREMENT,
				firstname VARCHAR(64) NOT NULL,
				surname VARCHAR(64) NOT NULL,
				passphrase VARCHAR(64) NOT NULL,
				role_Id mediumint(9) NOT NULL,
				table_Id mediumint(9) NOT NULL,
				PRIMARY KEY  (guest_Id)
			) $charset_collate;";

			dbDelta( $sql1 );

			// End of Guest Table________________________________________



			// Event Table_______________________________________________

			$table_events = $wpdb->prefix . 'cb_wedding_events';

			$sql2 = "CREATE TABLE IF NOT EXISTS $table_events (
				event_Id mediumint(9) NOT NULL AUTO_INCREMENT,
				name VARCHAR(64) NOT NULL,
				description VARCHAR(64) NOT NULL,
				time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				location VARCHAR(64),
				postcode VARCHAR(16),
				wedding_day BOOL NOT NULL,
				PRIMARY KEY  (event_Id)
			) $charset_collate;";

			dbDelta( $sql2 );

			// End of Event Table_________________________________________



			// Roles Table________________________________________________

			$table_roles = $wpdb->prefix . 'cb_wedding_roles';

			$sql3 = "CREATE TABLE IF NOT EXISTS $table_roles (
				role_Id mediumint(9) NOT NULL AUTO_INCREMENT,
				name VARCHAR(64) NOT NULL,
				PRIMARY KEY  (role_Id)
			) $charset_collate;";

			dbDelta( $sql3 );

			// End of Roles Table_________________________________________



			// Event Link Table___________________________________________

			$table_events_link = $wpdb->prefix . 'cb_wedding_events_link';

			$sql4 = "CREATE TABLE IF NOT EXISTS $table_events_link (
				event_Id mediumint(9) NOT NULL,
				guest_Id mediumint(9) NOT NULL,
				rsvp_status BOOL NOT NULL,
				PRIMARY KEY  (event_Id)
			) $charset_collate;";

			dbDelta( $sql4 );

			// End of Event Link Table_____________________________________




			// "Table Plan" Table__________________________________________

			$table_table_plan = $wpdb->prefix . 'cb_wedding_table_plan';

			$sql5 = "CREATE TABLE IF NOT EXISTS $table_table_plan (
				table_Id mediumint(9) NOT NULL AUTO_INCREMENT,
				name VARCHAR(64) NOT NULL,
				PRIMARY KEY  (table_Id)
			) $charset_collate;";

			dbDelta( $sql5 );

			// End of "Table Plan" Table___________________________________



			// Order of Service Table__________________________________________

			$table_service = $wpdb->prefix . 'cb_wedding_service';

			$sql6 = "CREATE TABLE IF NOT EXISTS $table_service (
				section_Id mediumint(9) NOT NULL AUTO_INCREMENT,
				name VARCHAR(64) NOT NULL,
				description VARCHAR(64) NOT NULL,
				PRIMARY KEY  (section_Id)
			) $charset_collate;";

			dbDelta( $sql6 );

			// End of Order of Service Table___________________________________


			// Update DB version number
			add_option( 'cb_wedding_db_version', $cb_wedding_db_version );
		}

		// initialises data
		public function cb_wedding_install_data() {

			global $wpdb;
	
			$role = 'Guest';
			
			$table_name = $wpdb->prefix . 'cb_wedding_roles';
			
			$wpdb->insert( 
				$table_name, 
				array ( 
					'name' => $role 
				) 
			);
		}
	}
}

// include model class
require_once ( 'creativeblast-co-uk-wedding-model.php' );

// include controller class
require_once ( 'creativeblast-co-uk-wedding-controller.php' );

// include API class
require_once ( 'creativeblast-co-uk-wedding-api.php' );

// include view class
require_once ( 'creativeblast-co-uk-wedding-view.php' );


// Initialise classes
if ( class_exists('cb_wedding_installation') )
{
	$cb_wedding_model = new cb_wedding_installation($model);
}

if ( class_exists('cb_wedding_model') )
{
	$cb_wedding_model = new cb_wedding_model();
}

if ( class_exists('cb_wedding_controller'))
{
	$cb_wedding_controller = new cb_wedding_controller($cb_wedding_model);
}

// if there is an action run method on the controller
if ( isset($_GET['cb-wedding-action']) && !empty($_GET['cb-wedding-action']) ) {

	$cb_wedding_controller->{$_GET['cb-wedding-action']}();
}


// Run API commands if they exist
if ( class_exists('cb_wedding_api') )
{
	$cb_wedding_api = new cb_wedding_api($cb_wedding_model);
}

// Run views after running the controller
if ( class_exists('cb_wedding_view') )
{
	$cb_wedding_view = new cb_wedding_view($cb_wedding_model, $cb_wedding_controller);
}


?>
