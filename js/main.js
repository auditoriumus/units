$(document).ready(function () {
    $("#register-form").submit(function(event) {
        $('.invalid-feedback').css('display', 'none');
        event.preventDefault();
        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                data = JSON.parse(data)
                if (data.email) {
                    $('.email-notice').html(data.email);
                    $('.email-notice').css('display', 'block');
                }
                if (data.password) {
                    $('.password-notice').html(data.password);
                    $('.password-notice').css('display', 'block');
                }
                if (data.password_confirm) {
                    $('.password-confirm-notice').html(data.password_confirm);
                    $('.password-confirm-notice').css('display', 'block');
                }
                if (data.success) {
                    $('#register-form').slideUp('fast')
                    $('.success-message').html(data.success);
                    $('.success-message').css('display', 'block');
                }
            }
        });
    });




    $("#auth-form").submit(function(event) {
        $('.invalid-feedback').css('display', 'none');
        event.preventDefault();
        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                data = JSON.parse(data)
                if (data.email) {
                    $('.email-notice').html(data.email);
                    $('.email-notice').css('display', 'block');
                }
                if (data.password) {
                    $('.password-notice').html(data.password);
                    $('.password-notice').css('display', 'block');
                }
                if (data.success) {
                    setTimeout(function () {
                        window.location.href = "/personal.php"
                    }, 1000)
                    $('#auth-form').slideUp('fast')
                    $('.success-message').html(data.success);
                    $('.success-message').css('display', 'block');
                }
            }
        });
    });




    $("#update-form").submit(function(event) {
        $('.invalid-feedback').css('display', 'none');
        event.preventDefault();
        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                data = JSON.parse(data)
                if (data.changes) {
                    $('.changes-notice').html(data.changes);
                    $('.changes-notice').css('display', 'block');
                }
                if (data.avatar) {
                    $('.avatar-notice').html(data.avatar);
                    $('.avatar-notice').css('display', 'block');
                }
                if (data.password) {
                    $('.password-notice').html(data.password);
                    $('.password-notice').css('display', 'block');
                }
                if (data.success) {
                    setTimeout(function () {
                        window.location.href = "/"
                    }, 1500)
                    $('.success-message').html(data.success);
                    $('.success-message').css('display', 'block');
                }
            }
        });
    });
})
