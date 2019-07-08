jQuery( function ($) {
    'use strict';

    var recaptchaLoaded = false;

    function loadRecaptcha(callback) {
        if(recaptchaLoaded) {
            callback();
        } else {
            window.tghpmbcontactOnRecaptchaLoad = function () {
                recaptchaLoaded = true;
                callback();
            };

            var scriptElement = document.createElement('script');
            scriptElement.src = 'https://www.google.com/recaptcha/api.js?onload=tghpmbcontactOnRecaptchaLoad&render=explicit';
            document.body.appendChild(scriptElement);
        }
    }

    var $recaptcha = $('.rwmb-recaptcha');

    if($recaptcha.length) {
        loadRecaptcha(function () {
            $recaptcha.each(function () {
                var $this = $(this);

                window[$this.data('callback')] = function (token) {
                    $this.siblings('[name="recaptcha_fake"]').val(token).trigger('change');
                };

                window[$this.data('expired-callback')] = function () {
                    $this.siblings('[name="recaptcha_fake"]').val('').trigger('change');
                };

                $this.data(
                    'recaptcha',
                    grecaptcha.render(
                        $this.get(0),
                        {
                            'sitekey': $this.data('key'),
                            'theme': 'light'
                        }
                    )
                );
            });
        });
    }

} );
