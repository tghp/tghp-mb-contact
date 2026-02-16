(function ($) {
    $('body').on('tghpcontact:message-shown', function (e, messageElem) {
        if (window.tghpcontactDisableScroll) {
            return;
        }

        if (messageElem) {
            if (messageElem.scrollIntoView) {
                messageElem.scrollIntoView();
            }
        }
    });
})(jQuery);