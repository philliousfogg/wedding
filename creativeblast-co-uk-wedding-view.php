<?php 
if ( !class_exists('cb_wedding_view') )
{
	class cb_wedding_view {

		private $model;
		private $controller;

		public function __construct($model, $controller) {

			// Set the model
			$this->model = $model;

			// Set the controller
			$this->controller = $controller;

			// Add hook to show settings menu
			add_action( 'admin_menu', array ( $this, 'init_admin_menus' ) );

			// Register Admin Scripts
			wp_register_script('admin-js-script', plugins_url( 'admin.js', __FILE__ ));

			// Register validator
			wp_register_script('jquery-validator', plugins_url( 'jquery.validate.min.js', __FILE__ ));

			// Register Style Sheet
			wp_register_style( 'adminStylesheet', plugins_url('/css/style.css', __FILE__) );
		}	

		// Sets wedding setting menu
		public function init_admin_menus() {

			// Add it's own section
			add_action('admin_menu','admin_menu_separator');

			// New menu section
			add_menu_page( 'Wedding', 'Wedding', 'manage_categories', 'wedding', '', '', '100' );
			$page_hook_suffix1 = add_submenu_page( 'wedding', 'Guests', 'Guests', 'manage_categories', 'wedding-guest-list', array ( $this, 'guest_list' ) );
			$page_hook_suffix2 = add_submenu_page( 'wedding', 'New Guest', 'New Guest', 'manage_categories', 'wedding-new-guest', array ( $this, 'guest' ) );
			$page_hook_suffix3 = add_submenu_page( 'wedding', 'Roles', 'Roles', 'manage_categories', 'wedding-roles-list', array ( $this, 'roles_list' ) );
			$page_hook_suffix4 = add_submenu_page( 'wedding', 'New Roles', 'New Role', 'manage_categories', 'wedding-new-role', array ( $this, 'role' ) );
			$page_hook_suffix5 = add_submenu_page( 'wedding', 'Events', 'Events', 'manage_categories', 'wedding-event-list', array ( $this, 'event_list' ) );
			$page_hook_suffix6 = add_submenu_page( 'wedding', 'New Event', 'New Event', 'manage_categories', 'wedding-new-event', array ( $this, 'event' ) );
			

			// Add JavaScript files 
			add_action('admin_print_scripts-' . $page_hook_suffix1, array ( $this, 'plugin_admin_scripts' ) );
			add_action('admin_print_scripts-' . $page_hook_suffix2, array ( $this, 'plugin_admin_scripts' ) );
			add_action('admin_print_scripts-' . $page_hook_suffix3, array ( $this, 'plugin_admin_scripts' ) );
			add_action('admin_print_scripts-' . $page_hook_suffix4, array ( $this, 'plugin_admin_scripts' ) );
			add_action('admin_print_scripts-' . $page_hook_suffix5, array ( $this, 'plugin_admin_scripts' ) );
			add_action('admin_print_scripts-' . $page_hook_suffix6, array ( $this, 'plugin_admin_scripts' ) );

			// Add it's own section
			add_action('admin_menu',array ( $this, 'admin_menu_separator' ));
		}

		// Enqueue Admin Scripts
		public function plugin_admin_scripts() {

			// Link Registered StyleSheets to page
			wp_enqueue_style( 'adminStylesheet' );
			wp_enqueue_style( 'jQueryUiStyle', '//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css');

			// Link registered scripts to page
			wp_enqueue_script("jQueryUi","//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js");

			wp_enqueue_script('jquery-validator');

			wp_enqueue_script ( 'admin-js-script' );
		}







		// Guest Views________________________________________________________________________________________

		// Creates a guest list form
		public function guest_list() {
			if ( !current_user_can( 'manage_options' ) )  {
				wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
			}
			?>
			<div class="wrap">
				<h2>Guests <a href="<?php echo admin_url('admin.php?page=wedding-new-guest'); ?>" class="add-new-h2">Add New</a></h2>
				
				<?php
				// Success/Fail Banner
				switch ($this->controller->getActionResult()) {
					case '0': {
				 		?>
						<div id="message" class="error below-h2">
							<p>There was a problem deleting the guest</p>
						</div>
						<?php
				 		break;
				 	}				 	

				 	case '1': {
				 		?>
						<div id="message" class="updated below-h2">
							<p>The guest has been deleted</p>
						</div>
						<?php
				 		break;
				 	}
				 	default:
				 		# code...
				 		break;
				} 
				?>

				<table class="wp-list-table widefat fixed">
					<thead>
						<tr>
							<th id="selectAll" ><input type="checkbox"></th>
							<th id="firstname" class=" ">Firstname</th>
							<th id="surname" class=" ">Surname</th>
							<th id="passphrase" class=" ">Passphrase</th>
							<th id="role" class=" ">Wedding Party</th>
							<th id="table" class=" ">Table</th>
							<th id="edit" class=" "></th>
							<th id="delete" class=" "></th>
						</tr>
					</thead>
					<tbody>
						<?php 
						
						// get guest list from the model
						$rows = $this->model->getGuestList();

						// display guest list
						foreach( $rows as $row) { 
							
							// get role
							$role = $this->model->getRole($row->role_Id);
						?>
							<tr class="cb-guest-row" id="guest_Id<?php echo $row->guest_Id; ?>">
								<td id="selectAll" ><input type="checkbox"></td>
								<td class=" "><?php echo $row->firstname; ?></td>
								<td class=" "><?php echo $row->surname; ?></td>
								<td class=" "><?php echo $row->passphrase; ?></td>
								<td class=" "><?php echo $role->name; ?></td>
								<td class=" "><?php echo $row->table_Id; ?></td>
								<td class=" "><a href="<?php echo admin_url('admin.php?page=wedding-new-guest&id=' . $row->guest_Id ); ?>">edit</a></td>
								<td class=" ">
									<a id="cb_delete_request_<?php echo $row->guest_Id; ?>"  class="cb-delete-request" href="#">delete</a>
									<div class="cb-delete-guest">
										<div>Remove Guest?</div>
										<div>
											<a id="cb_delete_guest_<?php echo $row->guest_Id; ?>" href="<?php echo admin_url('admin.php?page=wedding-guest-list&cb-wedding-action=deleteGuest&id=' . $row->guest_Id ); ?>">yes</a> | 
											<a class="cb-delete-no" href="#">no</a>
										</div>
									</div>
								</td>
							</tr>
						<?php
						}
						?>
					</tbody>
				</table>

			</div>	
			<?php
		}

		// Creates a form to add/update guests 
		public function guest() {
			if ( !current_user_can( 'manage_options' ) )  {
				wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
			}

			// set form function
			$function = array(
							
							'action' 	=> 'addGuest',
							'formTitle' => 'New Wedding Guest',
							'firstname'	=> 'placeholder="Firstname"',
							'surname'	=> 'placeholder="Surname"',
							'passphrase'=> 'placeholder="Passphrase"',
							'role_Id'	=> 0
						);

			// are we editing a guest
			if ( isset($_GET['id']) ) {

				// get row for id 
				$row = $this->model->getGuest($_GET['id']);

				// populate fields for editing
				$function['action'] 	= 'editGuest&id=' . $_GET['id'];
				$function['formTitle']	= 'Edit Wedding Guest';
				$function['firstname']	= 'value="' . $row->firstname . '"';
				$function['surname']	= 'value="' . $row->surname . '"';
				$function['passphrase']	= 'value="' . $row->passphrase . '"';
				$function['role_Id']	= $row->role_Id;
			}

			?>
			<div class="wrap">
				<h2><?php echo $function['formTitle'] ?></h2>
				
				<?php
				switch ($this->controller->getActionResult()) {
					case '0': {
				 		?>
						<div id="message" class="error below-h2">
							<p>There was a problem adding your guest</p>
						</div>
						<?php
				 		break;
				 	}				 	

				 	case '1': {
				 		?>
						<div id="message" class="updated below-h2">
							<p>The guest details have been saved <a href="<?php echo admin_url('admin.php?page=wedding-guest-list') ?>">View Guest List</a></p>
						</div>
						<?php
				 		break;
				 	}
				 	default:
				 		# code...
				 		break;
				} 
				?>
				<form id="cb_guest_form" method="post" action="<?php echo admin_url('admin.php?page=wedding-new-guest&cb-wedding-action='.$function['action'])?> ">
					<table class="form-table">
						<tr>
							<td><label for="firstname">Firstname</label></td>
							<td><input id="firstname" type="text" name="firstname" <?php echo $function['firstname'] ?> required/></td>
						</tr>
						<tr>
							<td><label for="surname">Surname</label></td>
							<td><input id="surname" type="text" name="surname" <?php echo $function['surname'] ?> required/></td>
						</tr>
						<tr>
							<td><label for="passphrase">Passphrase</label></td>
							<td>
								<div><input id="passphrase" type="text" name="passphrase" <?php echo $function['passphrase'] ?>/><span id="passphrase_invalid"></span></div>
								<p class="description">This is a passphrase that will allow the guest to see which event they are invited to.</p>
							</td>
						</tr>
						<tr>
							<td><label for="role">Wedding Party</label></td>
							<td>
								<select name="roleId">
								<?php 
						
								// get guest list from the model
								$rows = $this->model->getRoleList();

								// display guest list
								foreach( $rows as $row) { 
									
									// Selected Variable for editing
									$selected = '';
									
									// if we are editing a guest
									if ( isset($_GET['id']) ) {

										// If this option is the current selected role
										if ( $function['role_Id'] == $row->role_Id ) {

											$selected = 'selected';

										}
									}
								?>
									
									<option value="<?php echo $row->role_Id ?>" <?php echo $selected ?> ><?php echo $row->name ?></option>
								<?php
								}
								?>
								</select>
								<p class="description">Does guest have a role within the wedding party? For example the Bride and Groom</p>
							</td>
						</tr>
						<tr>
							<td><label for="table">Table</label></td>
							<td>
								<select name="tableId"> 
									<option value="0">Table 1</option>
									<option value="1">Table 2</option>
									<option value="2">Table 3</option>
								</select>
								<p class="description">The table the guest is seated at.</p>
							</td>
						</tr>
						<tr>
							<td></td><td><input id="submit" type="submit" name="addGuest" value="Save" class="button button-primary button-large"/></td>
						</tr>
					</table>
				</form>
			</div>
			<?php
		}







		// Role Views_________________________________________________________________________________________________

		// Creates a form to add role
		public function role() {
			if ( !current_user_can( 'manage_options' ) )  {
				wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
			}

			// Set default form function - add guest
			$function = array (

								'formTitle' => 'Add Guest Role',
								'action'	=> 'addRole',
								'role'		=> 'placeholder="role"' 
			);

			// are we editing a guest
			if ( isset($_GET['id']) ) {

				// get row for id 
				$row = $this->model->getRole($_GET['id']);

				// populate fields for editing
				$function['action'] 	= 'editRole&id=' . $_GET['id'];
				$function['formTitle']	= 'Edit Guest Role';
				$function['role']		= 'value="' . $row->name . '"';
			}

			?>
			<div class="wrap">
				<h2><?php echo $function['formTitle'] ?></h2>
				
				<?php
				// Success/Fail Banner
				switch ($this->controller->getActionResult()) {
					case '0': {
				 		?>
						<div id="message" class="error below-h2">
							<p>There was a problem saving the role</p>
						</div>
						<?php
				 		break;
				 	}				 	

				 	case '1': {
				 		?>
						<div id="message" class="updated below-h2">
							<p>The role has been saved <a href="<?php echo admin_url('admin.php?page=wedding-roles-list') ?>">View Roles</a></p>
						</div>
						<?php
				 		break;
				 	}
				 	default:
				 		# code...
				 		break;
				} 
				?>

				<form id="cb-role-form" method="post" action="<?php echo admin_url('admin.php?page=wedding-new-role&cb-wedding-action=' . $function['action'])?> ">
					<table class="form-table">
						<tr>
							<td><label for="role">Role</label></td>
							<td>
								<input id="role" type="text" name="role" <?php echo $function['role'] ?> required />
								<p class="description">for example bride, groom etc</p>
							</td>
						</tr>
						<tr>
							<td></td><td><input type="submit" name="roleForm" value="Save" class="button button-primary button-large"/></td>
						</tr>
					</table>
				</form>
			</div>
			<?php
		}


		// Role List 
		public function roles_list()
		{
			if ( !current_user_can( 'manage_options' ) )  {
				wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
			}
			?>
			<div class="wrap">
				<h2>Roles <a href="<?php echo admin_url('admin.php?page=wedding-new-role'); ?>" class="add-new-h2">Add New</a></h2>
				
				<?php
				// Success/Fail Banner
				switch ($this->controller->getActionResult()) {
					case '0': {
				 		?>
						<div id="message" class="error below-h2">
							<p>There was a problem deleting the role</p>
						</div>
						<?php
				 		break;
				 	}				 	

				 	case '1': {
				 		?>
						<div id="message" class="updated below-h2">
							<p>The role has been deleted</p>
						</div>
						<?php
				 		break;
				 	}
				 	default:
				 		# code...
				 		break;
				} 
				?>

				<table class="wp-list-table widefat fixed">
					<thead>
						<tr>
							<th id="selectAll" ><input type="checkbox"></th>
							<th id="role" class=" ">Role</th>
							<th id="edit" class=" "></th>
							<th id="delete" class=" "></th>
						</tr>
					</thead>
					<tbody>
						<?php 
						
						// get guest list from the model
						$rows = $this->model->getRoleList();

						// display guest list
						foreach( $rows as $row) { 
						?>
							<tr class="cb-guest-row" id="role_Id<?php echo $row->role_Id; ?>">
								<td id="selectAll" ><input type="checkbox"></td>
								<td class=" "><?php echo $row->name; ?></td>
								<td class=" "><a href="<?php echo admin_url('admin.php?page=wedding-new-role&id=' . $row->role_Id ); ?>">edit</a></td>
								<td class=" ">
									<a id="cb_delete_request_<?php echo $row->role_Id; ?>"  class="cb-delete-request" href="#">delete</a>
									<div class="cb-delete-guest">
										<div>Remove Role?</div>
										<div>
											<a id="cb_delete_guest_<?php echo $row->role_Id; ?>" href="<?php echo admin_url('admin.php?page=wedding-roles-list&cb-wedding-action=deleteRole&id=' . $row->role_Id ); ?>">yes</a> | 
											<a class="cb-delete-no" href="#">no</a>
										</div>
									</div>
								</td>
							</tr>
						<?php
						}
						?>
					</tbody>
				</table>

			</div>	
			<?php			

		}










		// Event Views__________________________________________________________________________________________

		// Creates a form to add event
		public function event() {
			if ( !current_user_can( 'manage_options' ) )  {
				wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
			}

			// Minute Options
			$op_minutes	= array(

				1 => '00',
				2 => '15',
				3 => '30',
				4 => '45'
			);

			// AM/PM Options
			$op_ampm = array(

				1 => 'am',
				2 => 'pm'
			);

			// Set default form function - add event
			$function = array (

								'formTitle' 	=> 'Add New Event',
								'action'		=> 'addEvent',
								'name'			=> 'placeholder="Event Name"',
								'description'	=> 'placeholder="Description"',
								'location'		=> 'placeholder="Location"',
								'postcode'  	=> 'placeholder="postcode"',
								'date'			=> 'placeholder="dd/mm/yyyy"',
								'weddingDay'	=> ''
			);

			$date 		= '';

			$hours 		= '';
			$minutes 	= '';

			// are we editing a guest
			if ( isset($_GET['id']) ) {

				// get row for id 
				$row = $this->model->getEvent($_GET['id']);

				$date 		= date("d-m-Y", strtotime($row->time));

				$hours 		= date("G", strtotime($row->time));
				$minutes 	= date("i", strtotime($row->time));

				// populate fields for editing
				$function['action'] 	= 'editEvent&id=' . $_GET['id'];
				$function['formTitle']	= 'Edit Event';
				$function['name']		= 'value="' . $row->name . '"';
				$function['description']= 'value="' . $row->description . '"';
				$function['location']	= 'value="' . $row->location . '"';
				$function['postcode']	= 'value="' . $row->postcode . '"';
				$function['date']		= 'value="' . $date. '"';
				$function['weddingDay'] = $row->wedding_day == '1' ? 'checked' : '';
			}

			?>
			<div class="wrap">
				<h2><?php echo $function['formTitle'] ?></h2>
				
				<?php
				// Success/Fail Banner
				switch ($this->controller->getActionResult()) {
					case '0': {
				 		?>
						<div id="message" class="error below-h2">
							<p>There was a problem saving the event</p>
						</div>
						<?php
				 		break;
				 	}				 	

				 	case '1': {
				 		?>
						<div id="message" class="updated below-h2">
							<p>The event has been saved <a href="<?php echo admin_url('admin.php?page=wedding-event-list') ?>">View Events</a></p>
						</div>
						<?php
				 		break;
				 	}
				 	default:
				 		# code...
				 		break;
				} 
				?>
				<form id="cb_event_form" method="post" action="<?php echo admin_url('admin.php?page=wedding-new-event&cb-wedding-action=' . $function['action']) ?> ">
					<table class="form-table">
						<tr>
							<td><label for="event_name">Name</label></td>
							<td><input id="event_name" type="text" name="name" <?php echo $function['name'] ?> /></td>
						</tr>
						<tr>
							<td><label for="event_description">Description</label></td>
							<td><input id="event_description" type="text" name="description" <?php echo $function['description'] ?> /></td>
						</tr>
						<tr>
							<td><label for="event_location">Location</label></td>
							<td>
								<input id="event_location" type="text" name="location" <?php echo $function['location'] ?>/>
								<p class="description">Where the event is going to be.</p>
							</td>
						</tr>
						<tr>
							<td><label for="event_postcode">Postcode</label></td>
							<td>
								<input id="event_postcode" type="text" name="postcode" <?php echo $function['postcode'] ?> />
							</td>
						</tr>
						<tr>
							<td><label for="event_date">Date</label></td>
							<td><input id="event_date" type="text" name="display_date" <?php echo $function['date'] ?> /></td>
						</tr>
						<tr>
							<td><label for="event_date">Time</label></td>
							<td>
								<select name="hours">
									<?php
									for ( $i=0; $i<=23; $i++ )
									{
										$selected = $hours == $i ? 'selected' : ''; 
										echo '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
									}
									?>
								</select>
							:
								<select name="minutes">
									<?php
									for ( $i=1; $i<=4; $i++ )
									{
										$selected = $minutes == $op_minutes[$i] ? 'selected' : ''; 
										echo '<option value="'.$op_minutes[$i].'" '.$selected.'>'.$op_minutes[$i].'</option>';
									}
									?>
								</select>
							</td>
						</tr>
						<tr>
							<td><label for="event_wedding_day">Wedding Day Event</label></td>
							<td>
								<input id="event_wedding_day" type="checkbox" name="wedding_day" value="1" <?php echo $function['weddingDay'] ?> />
								<p class="description">Is this event on the day of the wedding?</p>
							</td>
						</tr>
						<tr>
							<td></td><td><input type="submit" name="eventForm" value="save" class="button button-primary button-large"/></td>
						</tr>
					</table>
				</form>
			</div>
			<?php
		}

		// Event List 
		public function event_list()
		{
			if ( !current_user_can( 'manage_options' ) )  {
				wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
			}
			?>
			<div class="wrap">
				<h2>Events <a href="<?php echo admin_url('admin.php?page=wedding-new-event'); ?>" class="add-new-h2">Add New</a></h2>
				
				<?php
				// Success/Fail Banner
				switch ($this->controller->getActionResult()) {
					case '0': {
				 		?>
						<div id="message" class="error below-h2">
							<p>There was a problem deleting the role</p>
						</div>
						<?php
				 		break;
				 	}				 	

				 	case '1': {
				 		?>
						<div id="message" class="updated below-h2">
							<p>The event has been deleted</p>
						</div>
						<?php
				 		break;
				 	}
				 	default:
				 		# code...
				 		break;
				} 
				?>

				<table class="wp-list-table widefat fixed">
					<thead>
						<tr>
							<th id="selectAll" ><input type="checkbox"></th>
							<th id="event_name" class=" ">Event Name</th>
							<th id="event_date" class=" ">Date</th>
							<th id="event_time" class=" ">Time</th>
							<th id="event_wedding_day" class=" ">Wedding Day</th>	
							<th id="edit" class=" "></th>
							<th id="delete" class=" "></th>
						</tr>
					</thead>
					<tbody>
						<?php 
						
						// get guest list from the model
						$rows = $this->model->getEventList();

						// display guest list
						foreach( $rows as $row) { 
							
							// Break out time and date
							$date = date("l, jS F Y", strtotime($row->time));

							$time = date("g:i a", strtotime($row->time));
						?>
							<tr class="cb-guest-row" id="event_Id<?php echo $row->event_Id; ?>">
								<td id="selectAll" ><input type="checkbox"></td>
								<td class=" "><?php echo $row->name; ?></td>
								<td class=" "><?php echo $date; ?></td>
								<td class=" "><?php echo $time; ?></td>
								<td class=" "><?php echo $row->wedding_day; ?></td>
								<td class=" "><a href="<?php echo admin_url('admin.php?page=wedding-new-event&id=' . $row->event_Id ); ?>">edit</a></td>
								<td class=" ">
									<a id="cb_delete_request_<?php echo $row->event_Id; ?>"  class="cb-delete-request" href="#">delete</a>
									<div class="cb-delete-guest">
										<div>Remove Event?</div>
										<div>
											<a id="cb_delete_guest_<?php echo $row->event_Id; ?>" href="<?php echo admin_url('admin.php?page=wedding-event-list&cb-wedding-action=deleteEvent&id=' . $row->event_Id ); ?>">yes</a> | 
											<a class="cb-delete-no" href="#">no</a>
										</div>
									</div>
								</td>
							</tr>
						<?php
						}
						?>
					</tbody>
				</table>

			</div>	
			<?php			

		}
	}
}
?>