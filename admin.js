var $j = jQuery;

$j(document).ready(function(){

	// Deselects all pending rows for deletion
	function deselectAllDeletes() {

		// hide delete guest options
		$j(".cb-delete-guest").hide( 0, function(){

			$j( ".cb-delete-request" ).show();

			// remove delete select class
			$j(".cb-guest-row").removeClass("cb-delete-row");
		});
	}

	// set date picker for events
	$j( "#event_date" ).datepicker({ dateFormat: "dd/mm/yy" });

	$j( ".cb-delete-request" ).click(function(){

		event.preventDefault();

		deselectAllDeletes();

		$j( "#" + this.id ).hide(0,function(){

			$j( this ).next().show();
			$j( this ).parents(".cb-guest-row").addClass("cb-delete-row");
		});
	});

	$j( ".cb-delete-no" ).click(function(){

		event.preventDefault()

		deselectAllDeletes();		
	});

	alert

	// Validate guest form
	$j("#cb_guest_form").validate({

		// Validation rules
		rules: {
			// Passphrase
    		passphrase: {
    			
    			// Ensure the field is not empty
      			required: true,

      			// Make sure the passphrase isn't already used
      			remote: {

      				url: cbWeddingAjax.ajaxurl,
      				type: 'post',
      				data: {

      					action: 'validPassphrase',
      					security: cbWeddingAjax.ajax_nonce,
      					passphrase: function() {
      						return $j("#passphrase").val();
      					}
      				}
      			}
  			}
  		},

  		// Validation error messages
  		messages: {
			
			firstname: " Please enter a firstname",
			surname: " Please enter a surname", 
        	passphrase: {

        		required: " A passphrase is required",
        		remote: " This passphrase already exists"
        		
        	}
        }
	});

	$j("#cb-role-form").validate({

		messages: {

			role: " Please enter a role name"
		}
	});
});