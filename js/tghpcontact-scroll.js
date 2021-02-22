(function ($) {
    $('body').on('tghpcontact:message-shown', function (e, messageElem) {
        if (messageElem) {
            if (messageElem.scrollIntoView) {
                messageElem.scrollIntoView();
            }
        }
    });
})(jQuery);