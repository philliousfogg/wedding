<?php
if ( !class_exists('cb_wedding_api') )
{
	class cb_wedding_api {

		private $model;

		public function __construct( $model ) {

			// get model
			$this->model = $model;

			// Initialise Ajax scripts
			add_action( 'admin_enqueue_scripts', array($this,'init_scripts') );

			// Register Ajax action
			add_action('wp_ajax_validPassphrase', array($this,'validPassphrase'));
		}

		public function init_scripts() {

			// load our jquery ajax file
			wp_enqueue_script( 'ajax-query', plugins_url( 'ajax_query.js', __FILE__ ), array( 'jquery' ) );

			$ajax_nonce = wp_create_nonce('yowser2k14');

			$parameters = array( 
				'ajaxurl' => admin_url( 'admin-ajax.php'),
				'ajax_nonce' => wp_create_nonce('yowser2k14')
			);

			// make the jaxurl var available to the ajax script file 
			wp_localize_script( 'ajax-query', 'cbWeddingAjax', $parameters );
		}


		// Guest Methods_______________________________________________

		// returns the row for depending on arguments
		public function validPassphrase()
		{	
			// Check nouce is correct
			check_ajax_referer( 'yowser2k14', 'security' );

			if ( isset( $_POST["passphrase"]) )
			{
				$row = $this->model->getGuestPassphrase($_POST["passphrase"]);

				if ( $row != null ) {

					echo 'false';
				} else {
					echo 'true';
				}

				die();
			} else {

				echo 'false';

				die();
			}
		}
	}
}
?>