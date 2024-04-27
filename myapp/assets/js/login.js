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
            url: "../rest/login",
            type: "POST",
            data: JSON.stringify(entity),
            contentType: "application/json",
            dataType: "json",
            success: function(result) {

                if(result.user){
                    localStorage.setItem('user', JSON.stringify(result.user['user_type']));
                    if(result.user['user_type'] === 'admin'){
                        window.location.hash = '#adminPanel';
                    }
                    else{
                        window.location.hash = '#dashboard';
                    }
                    alert("Login successfull!");
                }else {
                    alert("Login failed! Wrong credentials or the user doesn't exist!");
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert("Login failed: " + XMLHttpRequest.responseText);
            }
        });
    }
};

$(document).ready(function() {
    UserService.init();
});