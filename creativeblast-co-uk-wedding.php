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

// include model class
require_once ( 'creativeblast-co-uk-wedding-model.php' );

// include controller class
require_once ( 'creativeblast-co-uk-wedding-controller.php' );

// include API class
require_once ( 'creativeblast-co-uk-wedding-api.php' );

// include view class
require_once ( 'creativeblast-co-uk-wedding-view.php' );

// Initialise classes

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
