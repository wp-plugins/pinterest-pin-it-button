//jQuery no-conflict mode
var $j = jQuery.noConflict();

//jQuery doc ready
$j(document).ready(function() {
    //Submit mailchimp form with link button
    $j("#mc-embedded-subscribe-link").click(function(event) {
        event.preventDefault();
        $j("#mc-embedded-subscribe-form").submit();
    });
	
	//Enable collapse/expand toggle of admin boxes (like WP dashboard)
    $j(".inside").show();
	
    $j(".hndle").toggle(function() {
        $j(this).next(".inside").slideToggle("fast");
    }, function () {
        $j(this).next(".inside").slideToggle("fast");
    });
	
    $j(".handlediv").toggle(function() {
        $j(this).next(".hndle").next(".inside").slideToggle("fast");
    }, function() {
        $j(this).next(".hndle").next(".inside").slideToggle("fast");
    });	
});

/*$j(document).ready(function() {
  $j(".inside").show();
  //toggle the componenet with class msg_body
  $j(".handlediv").click(function()
  {
    $j(this).next(".inside").slideToggle();
  });
});
*/

/*
function toggle2(showHideDiv, switchTextDiv) {
	var elementdiv = document.getElementById(showHideDiv);
	var text = document.getElementById(switchTextDiv);
	if(elementdiv.style.display == "block") {
    		elementdiv.style.display = "none";
			elementdiv.className+= "handlediv";
  	}
	else {
		elementdiv.style.display = "block";
		elementdiv.className+= "handlediv";
	}
}
*/
