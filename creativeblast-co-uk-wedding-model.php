<?php
if ( !class_exists('cb_wedding_model') )
{
	class cb_wedding_model {

		public function __construct() {

			global $cb_wedding_db_version;
			$cb_wedding_db_version = '1.0';	

			// register activation hook for creating database tables
			register_activation_hook( __FILE__,  array ( $this, 'cb_wedding_install' ) );
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

		// Updates a edit data
		public function update($table, $data, $Id) {

			global $wpdb;

			$table = $wpdb->prefix . $table; 

			// Inserts data into the selected table
			return $wpdb->update( $table, $data, $Id );
		}

		// Deletes a row from a table
		public function delete($table, $Id) {

			global $wpdb;

			$table = $wpdb->prefix . $table;

			// Deletes a row from a table
			return $wpdb->delete( $table, $Id );
		}

		// Inserts a new data
		public function insert($table, $data) {

			global $wpdb;

			$table = $wpdb->prefix . $table; 

			// Inserts data into the selected table
			return $wpdb->insert( $table, $data);
		}


		// Guest Model Methods________________________________________________________________________________

		// Get a guest from Id
		public function getGuest($guest_Id) {

			global $wpdb;

			$table = $wpdb->prefix . 'cb_wedding_guests';

			// Return results 
			return $wpdb->get_row("	SELECT * 
									FROM $table 
									WHERE guest_Id = $guest_Id ");
		}

		// Gets data from a table
		public function getGuestList() {

			global $wpdb;

			$table = $wpdb->prefix . 'cb_wedding_guests';

			// Return results 
			return $wpdb->get_results( "
										SELECT * 
										FROM $table

									   " );
		}	

		// Get guest where from passphrase
		public function getGuestPassphrase($passphrase) {

			global $wpdb;

			$table = $wpdb->prefix . 'cb_wedding_guests';

			// Return results 
			return $wpdb->get_row("	SELECT * 
									FROM $table 
									WHERE passphrase = '$passphrase' ");
		}


		// Get a role from Id
		public function getRole($role_Id) {

			global $wpdb;

			$table = $wpdb->prefix . 'cb_wedding_roles';

			// Return results 
			return $wpdb->get_row("	SELECT * 
									FROM $table 
									WHERE role_Id = $role_Id ");
		}

		// Gets data from a table
		public function getRoleList() {

			global $wpdb;

			$table = $wpdb->prefix . 'cb_wedding_roles';

			// Return results 
			return $wpdb->get_results( "
										SELECT * 
										FROM $table

									   " );
		}	
	}
}
?>