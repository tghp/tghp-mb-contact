jQuery( function ($) {
    'use strict';

    var recaptchaLoaded = false;

    function loadRecaptcha(callback) {
        if(recaptchaLoaded) {
            callback();
        } else {
            window.onRecaptchaLoad = function () {
                recaptchaLoaded = true;
                callback();
            };

            var scriptElement = document.createElement('script');
            scriptElement.src = 'https://www.google.com/recaptcha/api.js?onload=onRecaptchaLoad&render=explicit';
            document.body.appendChild(scriptElement);
        }
    }

    var $recaptcha = $('.rwmb-recaptcha');

    if($recaptcha.length) {
        loadRecaptcha(function () {
            $recaptcha.each(function () {
                var $this = $(this);

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
