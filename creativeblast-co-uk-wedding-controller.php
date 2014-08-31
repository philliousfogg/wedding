<?php
if ( !class_exists('cb_wedding_controller') )
{
	class cb_wedding_controller {

		private $model;
		private $actionResult;

		public function __construct($model) {

			// Set the model
			$this->model = $model;
		}

		// Getters
		public function getActionResult() {

			return $this->actionResult;
		}





		// Guest List Methods_____________________________________________________

		// Adds guest to the  guest list.
		public function addGuest() {

			// if the add guest form has been submitted
			if ( isset( $_POST['addGuest'] ) ) {

				// set table
				$table = 'cb_wedding_guests';
				
				// create columns array
				$data = array (

					'firstname' => $_POST['firstname'],
					'surname'	=> $_POST['surname'],
					'passphrase'=> $_POST['passphrase'],
					'role_Id'	=> $_POST['roleId'],
					'table_Id'	=> $_POST['tableId']
				);

				// pass data to to model
				$this->actionResult = $this->model->insert( $table, $data );
			}
		}

		// Edits a guest on the list.
		public function editGuest() {

			// if the edit guest form has been submitted
			if ( isset( $_GET['id'] ) ) {

				// set table
				$table = 'cb_wedding_guests';
				
				// create columns array
				$data = array (

					'firstname' => $_POST['firstname'],
					'surname'	=> $_POST['surname'],
					'passphrase'=> $_POST['passphrase'],
					'role_Id'	=> $_POST['roleId'],
					'table_Id'	=> $_POST['tableId']
				);

				// update data to the model
				$this->actionResult = $this->model->update( $table, $data, array ( 'guest_Id' => $_GET['id'] ) );
			}
		}

		// Deletes a guest from the guest list
		public function deleteGuest() {

			// if the guest id exists 
			if ( isset( $_GET['id'] ) ) {

				// set table
				$table = 'cb_wedding_guests';

				// update data to the model
				$this->actionResult = $this->model->delete( $table, array ( 'guest_Id' => $_GET['id'] ) );
			}
		}






		// Role Methods________________________________________________________________

		// Adds a role
		public function addRole() {

			// if the add role form has been submitted
			if ( isset( $_POST['roleForm'] ) ) {

				// set table
				$table = 'cb_wedding_roles';
				
				// create columns array
				$data = array (

					'name' => $_POST['role']
				);

				// pass data to to model
				$this->actionResult = $this->model->insert( $table, $data );
			}
		}

		// Edits a role on the list.
		public function editRole() {

			// if the edit guest form has been submitted
			if ( isset( $_GET['id'] ) ) {

				// set table
				$table = 'cb_wedding_roles';
				
				// create columns array
				$data = array (

					'name' => $_POST['role']
				);

				// update data to the model
				$this->actionResult = $this->model->update( $table, $data, array ( 'role_Id' => $_GET['id'] ) );
			}
		}

		// Deletes a role
		public function deleteRole() {

			// if the role id exists 
			if ( isset( $_GET['id'] ) ) {

				// set table
				$table = 'cb_wedding_roles';

				// update data to the model
				$this->actionResult = $this->model->delete( $table, array ( 'role_Id' => $_GET['id'] ) );
			}
		}




		// Event Methods____________________________________________________________________________________________

		// Adds an event
		public function addEvent() {

			// if the add role form has been submitted
			if ( isset( $_POST['eventForm'] ) ) {

				// set table
				$table = 'cb_wedding_events';
				
				// create columns array
				$data = array (

					'name' => $_POST['role']
				);

				// pass data to to model
				$this->actionResult = $this->model->insert( $table, $data );
			}
		}

	}
}	
?>