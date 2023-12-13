/*!
  * global stuff - likely from bootstrap
  */

window.ATL_JQ_PAGE_PROPS = {
    "triggerFunction": function(showCollectorDialog) {
//Requires that jQuery is available!
        $("#altwdg-trigger").click(function(e) {
            e.preventDefault();
            showCollectorDialog();
        });
    },
    fieldValues: {

        recordWebInfo: '1', // field Name
        recordWebInfoConsent: ['1'] // field Id

    }

};

/* IN-851 The figure needs the alignment class for the figcaption. Copying it from the article when it is there. */
jQuery("figure.caption article.align-right").parent().addClass('align-right');
jQuery("figure.caption article.align-left").parent().addClass('align-left');
jQuery("figure.caption article.align-center").parent().addClass('align-center');

/* Update the anchor tag for form links. */
var cleanURL = jQuery('#form-href-source a').prop('href');
jQuery('#form-href-link').attr('href', cleanURL);

jQuery('.field--name-body table').addClass('usa-table');
