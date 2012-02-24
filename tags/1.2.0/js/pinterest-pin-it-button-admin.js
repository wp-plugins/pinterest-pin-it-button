
//jQuery doc ready
//See http://digwp.com/2011/09/using-instead-of-jquery-in-wordpress/
jQuery(document).ready(function($) {
    //Submit mailchimp form with link button
    $("#mc-embedded-subscribe-link").click(function(event) {
        event.preventDefault();
        $("#mc-embedded-subscribe-form").submit();
    });
	
	//Enable collapse/expand toggle of admin boxes (like WP dashboard)
    $(".inside").show();
	
    $(".hndle").toggle(function() {
        $(this).next(".inside").slideToggle("fast");
    }, function () {
        $(this).next(".inside").slideToggle("fast");
    });
	
    $(".handlediv").toggle(function() {
        $(this).next(".hndle").next(".inside").slideToggle("fast");
    }, function() {
        $(this).next(".hndle").next(".inside").slideToggle("fast");
    });	
});
