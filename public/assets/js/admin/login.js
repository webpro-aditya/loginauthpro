$(document).ready(function() {
    var loginBtn = $('.login__button');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    loginBtn.on('click', function(e) {
        e.preventDefault();
        var email = $('#email').val();
        var password = $('#password').val();
        var rememberMe = $('#remember_me').val();
        var data = {};
        data['email'] = email;
        data['password'] = password;
        data['rememberMe'] = rememberMe;
         $.ajax({
            url: "/admin/login",
            type: "POST",
            data: data,
            dataType: 'json',
            beforeSend: function(){
                loginBtn.css('pointerEvents', 'none')
                .html('<i class="fa-solid fa-spinner fa-spin-pulse"></i>');
            },
            success: function( response ) {
              if(response.status == 1) {
                 loginBtn.css('pointerEvents', 'none')
                 .html('Sign in')
                 .after('<span class="text-green-600 text-sm font-bold">You are now logged in.</span>');
                sessionStorage.setItem('logged_in', true);
                 setTimeout(function() {
                    window.location.href = '/admin/dashboard';
                 }, 2000);
              } else {
                    loginBtn.css('pointerEvents', 'auto')
                .html('Sign in');
                loginBtn.after('<span class="text-rose-600 text-sm font-bold error">Email and password do not match.</span>');
              }
            }
        });
    });
});
