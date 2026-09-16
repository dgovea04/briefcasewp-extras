(function($) {
    'use strict';
    $(function() {

        $('.chosen_select').chosen();

        $(document).ready(function() {
            $("body").on("click", ".bewaa_premium_close", function() {
                $(this).parent().hide();
                return false;
            });
            $("body").on("click", ".bewaa_star_button", function() {
                if ($(this).next().is(":visible")) {
                    $(this).next().hide();
                } else {
                    $(".bewaa_premium_feature_note").hide();
                    $(this).next().show();
                }
                return false;
            });
        });

    });

})(jQuery);