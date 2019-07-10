(function ($) {
    var checkAndScrollToForm = function () {
        if(!window.location.search) {
            return
        }

        if(window.location.search.match(/rwmb-form-submitted=([^&]*)/) || window.location.search.match(/rwmb-form-error=([^&]*)/)) {
            var $message = $('.rwmb-confirmation, .rwmb-error');

            if($message.length) {
                $('body').trigger('tghpcontact:message-shown', $message.get(0));
            }
        }
    };

    $(window).on('load', function () {
        checkAndScrollToForm();
    });
})(jQuery);