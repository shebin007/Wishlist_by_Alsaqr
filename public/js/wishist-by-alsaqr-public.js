(function( $ ) {
	'use strict';
	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	
	$(function(){
		// var wpUrl = theme.wp_url;
		
		$('.favform').submit(function(e){
			e.preventDefault();
			$.ajax({
				url: alsaqr.ajax_url, // Since WP 2.8 ajaxurl is always defined and points to admin-ajax.php
				type: "POST",
				data: {
					'action': 'add_to_wishlist', // This is our PHP function below
					'data': $(this).serialize(),
					'security' : theme.security,
				},
				// beforeSend: function() {
				// 	$("#loader").addClass('active-loading');
				// },
				success:function(data){

				
					$(this).find('.favourite-btn').toggleClass('fav-prodcut');
					console.log(data);
				
				},
				error: function(errorThrown){
					console.log(errorThrown);
				}
			});
		})



		// 

		
		// 
	})

})( jQuery );
