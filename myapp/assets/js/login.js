var UserService = {
    init: function() {

        //Validation for login form
        $("#login-form").validate({
            rules: {
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 8
                }
            }, 
            messages: {
                email: {
                    required: "Please enter your email address.",
                    email: "Please enter a valid email address."
                },
                password: {
                    required: "Please enter a password.",
                    minlength: "Your password must be at least 8 characters long."
                },
            },
            submitHandler: function(form){
                var entity = {
                    email: $("input[name='email']").val(),
                    password: $("input[name='password']").val()
                };
                console.log("entity:", entity);
                UserService.login(entity); //Calling the fubction for registering user with the payload provided
            }

        });
    },

    login: function(entity) {
        $.ajax({
            url: "../rest/authLogin",
            type: "POST",
            data: JSON.stringify(entity),
            contentType: "application/json",
            dataType: "json",
            beforeSend: function(xhr) {
                if(localStorage.getItem('current_user')){
                  xhr.setRequestHeader("Authentication", localStorage.getItem('token'));
                }
              },
            success: function(result) {

                $("input[name='email']").val(''),
                $("input[name='password']").val('')
                localStorage.setItem('current_user', JSON.stringify(result));
                localStorage.setItem('user', result.user_type);
                localStorage.setItem('users_id', result.id);
                localStorage.setItem('token', result.token);
                
                if(result){
                    alert("Login successfull!");
                    if(result.user_type === 'admin'){
                        window.location.hash = '#adminPanel';
                    }
                    else{
                        window.location.hash = '#dashboard';
                    }
                }
            },
            error: function(result) {
                alert("Login failed due to wrong credentials!");
                
            }
        });
    }
};

$(document).ready(function() {
    UserService.init();
});