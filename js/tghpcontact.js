(function ($) {
    var $form = $('.form');
    var hasValueClass = 'has-value';
    var inputSelector = 'input:not([type="checkbox"]), textarea, select';

    $form.on('focus', inputSelector, function () {
        var $this = $(this);

        $this.addClass(hasValueClass);
        $this.parents('.rwmb-field').addClass(hasValueClass);
    });

    $form.on('blur', inputSelector, function () {
        var $this = $(this);

        if ($this.val()) {
            $this.addClass(hasValueClass);
            $this.parents('.rwmb-field').addClass(hasValueClass);
        } else {
            $this.removeClass(hasValueClass);
            $this.parents('.rwmb-field').removeClass(hasValueClass);
        }
    });

    var checkAndScrollToForm = function () {
        if (!window.location.search) {
            return
        }

        if (window.location.search.match(/rwmb-form-submitted=([^&]*)/) || window.location.search.match(/rwmb-form-error=([^&]*)/)) {
            var $message = $('.rwmb-confirmation, .rwmb-error');

            if ($message.length) {
                $('body').trigger('tghpcontact:message-shown', $message.get(0));
            }
        }
    };

    $(window).on('load', function () {
        checkAndScrollToForm();
    });
})(jQuery);