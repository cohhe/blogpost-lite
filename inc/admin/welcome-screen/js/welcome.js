jQuery(document).ready(function() {
	
	/* If there are required actions, add an icon with the number of required actions in the About blogpost page -> Actions required tab */
    var blogpost_nr_actions_required = blogpostWelcomeScreenObject.nr_actions_required;

    if ( (typeof blogpost_nr_actions_required !== 'undefined') && (blogpost_nr_actions_required != '0') ) {
        jQuery('li.welcome-screen-w-red-tab a').append('<span class="welcome-screen-actions-count">' + blogpost_nr_actions_required + '</span>');
    }

    /* Dismiss required actions */
    jQuery(".blogpost-dismiss-required-action").click(function(){

        var id= jQuery(this).attr('id');
        console.log(id);
        jQuery.ajax({
            type       : "GET",
            data       : { action: 'blogpost_dismiss_required_action',dismiss_id : id },
            dataType   : "html",
            url        : blogpostWelcomeScreenObject.ajaxurl,
            beforeSend : function(data,settings){
				jQuery('.welcome-screen-tab-pane#actions_required h1').append('<div id="temp_load" style="text-align:center"><img src="' + blogpostWelcomeScreenObject.template_directory + '/inc/admin/welcome-screen/img/ajax-loader.gif" /></div>');
            },
            success    : function(data){
				jQuery("#temp_load").remove(); /* Remove loading gif */
                jQuery('#'+ data).parent().remove(); /* Remove required action box */

                var blogpost_actions_count = jQuery('.welcome-screen-actions-count').text(); /* Decrease or remove the counter for required actions */
                if( typeof blogpost_actions_count !== 'undefined' ) {
                    if( blogpost_actions_count == '1' ) {
                        jQuery('.welcome-screen-actions-count').remove();
                        jQuery('.welcome-screen-tab-pane#actions_required').append('<p>' + blogpostWelcomeScreenObject.no_required_actions_text + '</p>');
                    }
                    else {
                        jQuery('.welcome-screen-actions-count').text(parseInt(blogpost_actions_count) - 1);
                    }
                }
            },
            error     : function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR + " :: " + textStatus + " :: " + errorThrown);
            }
        });
    });
	
	/* Tabs in welcome page */
	function blogpost_welcome_page_tabs(event) {
		jQuery(event).parent().addClass("active");
        jQuery(event).parent().siblings().removeClass("active");
        var tab = jQuery(event).attr("href");
        jQuery(".welcome-screen-tab-pane").not(tab).css("display", "none");
        jQuery(tab).fadeIn();
	}
	
	var blogpost_actions_anchor = location.hash;
	
	if( (typeof blogpost_actions_anchor !== 'undefined') && (blogpost_actions_anchor != '') ) {
		blogpost_welcome_page_tabs('a[href="'+ blogpost_actions_anchor +'"]');
	}
	
    jQuery(".welcome-screen-nav-tabs a").click(function(event) {
        event.preventDefault();
		blogpost_welcome_page_tabs(this);
    });

});