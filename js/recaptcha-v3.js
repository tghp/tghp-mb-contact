jQuery(function ($) {
  "use strict";

  var recaptchaLoaded = false;

  // function loadRecaptcha(key, callback) {
  //   if (recaptchaLoaded) {
  //     callback();
  //   } else {
  //     window.tghpmbcontactOnRecaptchaLoad = function () {
  //       recaptchaLoaded = true;
  //       callback();
  //     };

  //     var scriptElement = document.createElement("script");
  //     scriptElement.src = `https://www.google.com/recaptcha/enterprise.js?render=${key}`;
  //     document.body.appendChild(scriptElement);
  //   }
  // }

  var recaptchaElement = $(".rwmb-recaptcha-v3");

  if (!!recaptchaElement) {
    const key = recaptchaElement.data("key");

    var scriptElement = document.createElement("script");
    scriptElement.src = `https://www.google.com/recaptcha/enterprise.js?render=${key}`;
    document.body.appendChild(scriptElement);

    const submitButton = recaptchaElement
      .parents("form")
      .find("button[type=submit]")
      .get(0);

    $(submitButton).on("click", async (e) => {
      e.preventDefault();

      grecaptcha.enterprise.ready(async () => {
        const token = await grecaptcha.enterprise.execute(
          "6Ldc2YUqAAAAAIJLQic5XG4XEQX6jVHbeaXpoly9",
          { action: "LOGIN" }
        );

        console.log(JSON.stringify(token));

        const fakeField = recaptchaElement
          .siblings('[name="recaptcha_fake"]')
          .get(0);

        $(fakeField).val(token).trigger("change");

        const captureResponseField = recaptchaElement
          .siblings('[name="g-recaptcha-response"]')
          .get(0);

        $(captureResponseField).val(token).trigger("change");

        const form = recaptchaElement.parents("form").get(0);

        const formData = $(form).serialize();

        console.log(JSON.stringify(formData));

        const res = await fetch(`${window.ajaxurl}?${formData}`, {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
        });

        // Form submits the default way - Figure out a way to do this via AJAX

        console.log(res);

        $(form).submit();
      });
    });
  }
});
