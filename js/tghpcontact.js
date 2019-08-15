(function ($) {
    var $form = $('.form');
    $form.on('blur', 'input:not([type="checkbox"]), textarea, select', function () {
        var $this = $(this);

        if($this.val()) {
            $this.addClass('has-value');
        } else {
            $this.removeClass('has-value');
        }
    });

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