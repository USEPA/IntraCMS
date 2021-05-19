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