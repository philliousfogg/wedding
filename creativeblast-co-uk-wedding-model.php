<?php
if ( !class_exists('cb_wedding_model') )
{
	class cb_wedding_model {

		public function __construct() {

			
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




		// Gets just one event from event_Id
		public function getEvent($event_Id) {

			global $wpdb;

			$table = $wpdb->prefix . 'cb_wedding_events';

			// Return results 
			return $wpdb->get_row("	SELECT * 
									FROM $table 
									WHERE event_Id = $event_Id ");
		}

		// Gets all the events 
		public function getEventList() {

			global $wpdb;

			$table = $wpdb->prefix . 'cb_wedding_events';

			// Return results 
			return $wpdb->get_results( "
										SELECT * 
										FROM $table

									   " );
		}
	}
}
?>