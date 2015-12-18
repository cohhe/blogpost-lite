jQuery(document).ready(function() {
    var blogpost_aboutpage = blogpostWelcomeScreenCustomizerObject.aboutpage;
    var blogpost_nr_actions_required = blogpostWelcomeScreenCustomizerObject.nr_actions_required;

    /* Number of required actions */
    if ((typeof blogpost_aboutpage !== 'undefined') && (typeof blogpost_nr_actions_required !== 'undefined') && (blogpost_nr_actions_required != '0')) {
        jQuery('#accordion-section-themes .accordion-section-title').append('<a href="' + blogpost_aboutpage + '"><span class="welcome-screen-actions-count">' + blogpost_nr_actions_required + '</span></a>');
    }

    /* Upsell in Customizer (Link to Welcome page) */
    if ( !jQuery( ".blogpost-upsells" ).length ) {
        jQuery('#customize-theme-controls > ul').prepend('<li class="accordion-section blogpost-upsells">');
    }
    if (typeof blogpost_aboutpage !== 'undefined') {
        jQuery('.blogpost-upsells').append('<a style="width: 80%; margin: 5px auto 5px auto; display: block; text-align: center;" href="' + blogpost_aboutpage + '" class="button" target="_blank">{themeinfo}</a>'.replace('{themeinfo}', blogpostWelcomeScreenCustomizerObject.themeinfo));
    }
    if ( !jQuery( ".blogpost-upsells" ).length ) {
        jQuery('#customize-theme-controls > ul').prepend('</li>');
    }
});