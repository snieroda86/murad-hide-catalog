jQuery(document).ready(function($) {
   $('#register-check-all').change(function() {
        if ($(this).is(':checked')) {
            $('.consent-field-sn').prop('checked', true);
        } else {
            $('.consent-field-sn').prop('checked', false);
        }
    }); 
})