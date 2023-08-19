$(document).ready(function() {
    var registerBtn = $('.register__button');
    var terms = $('#terms');
    terms.click(function() {
        if(terms.is(':checked')){
            registerBtn.css('pointerEvents', 'auto');
        } else {
            registerBtn.css('pointerEvents', 'none');
        }
    });
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    registerBtn.on('click', function(e) {
        e.preventDefault();
        var email = $('#email').val();
        var password = $('#password').val();
        var cpassword = $('#confirm-password').val();
        if( !terms.is(':checked') ) {
            $('.terms__box').after('<span class="text-rose-600 text-sm font-bold terms__msg error">Accept Terms & Conditions to continue.</span>')
            return false;
        } else {
            $('.terms__box').next('.terms__msg').remove();
        }
        if( password != cpassword ) {
            $('.error').remove();
            registerBtn.after('<span class="text-rose-600 text-sm font-bold terms__msg error">Passwords do not match.</span>');
            return false;
        } else {
            $('.error').remove();
        }
        if(!IsEmail(email)){
            $('.error').remove();
            $('#email').after('<span class="text-rose-600 text-sm font-bold terms__msg error">Enter a valid Email.</span>');
            return false;
        }
        var data = {};
        data['email'] = email;
        data['password'] = password;
        data['confirm-password'] = cpassword;
         $.ajax({
            url: "/admin/register",
            type: "POST",
            data: data,
            dataType: 'json',
            beforeSend: function(){
                registerBtn.css('pointerEvents', 'none')
                .attr('disabled', 'disabled').html('<i class="fa-solid fa-spinner fa-spin-pulse"></i>');
            },
            success: function( response ) {
              if(response.status == 1) {
                 registerBtn.next('.terms__msg').remove();
                 registerBtn.css('pointerEvents', 'none')
                 .html('Create an account')
                 .after('<span class="text-green-600 text-sm font-bold">Registration done successfully.</span>');

                 setTimeout(function() {
                    window.location.href = '/admin/login';
                 }, 4000);
              } else {
                registerBtn.css('pointerEvents', 'auto')
                .after('<span class="text-rose-600 text-sm font-bold error">This was not expected.</span>');
              }
            }
        });
    });

    function IsEmail(email) {
        var regex =
/^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (!regex.test(email)) {
            return false;
        }
        else {
            return true;
        }
    }
});
