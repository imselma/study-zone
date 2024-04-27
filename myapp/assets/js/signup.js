var UserService = {
    init: function() {
        //Validation for registartion form
        $("#registration-form").validate({
            rules: {
                name: {
                    required: true
                },
                surname: {
                    required: true
                },
                username: {
                    required: true,
                },
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 8
                },
                university: {
                    required: true
                },
                department: {
                    required: true
                }

            },
            messages: {
                name: {
                    required: "Please enter your first name."
                },
                surname: {
                    required: "Please enter your last name."
                },
                surname: {
                    required: "Please enter your user name."
                },
                email: {
                    required: "Please enter an email address.",
                    email: "Please enter a valid email address."
                },
                password: {
                    required: "Please enter a password.",
                    minlength: "Your password must be at least 8 characters long."
                },
                university: {
                    required: "Please enter your university's name."
                },
                department: {
                    required: "Please enter the field of study."
                }
            },
            submitHandler: function(form){
                var entity = {
                    user_type: 'user',
                    first_name: $("input[name='name']").val(),
                    last_name: $("input[name='surname']").val(),
                    username: $("input[name='username']").val(),
                    email: $("input[name='email']").val(),
                    password: $("input[name='password']").val(),
                    university: $("input[name='university']").val(),
                    department: $("input[name='department']").val(),
                };
                UserService.register(entity); //Calling the fubction for registering user with the payload provided
            }

        });

    },

    register: function(entity) {
        $.ajax({
            url: "../rest/addUser",
            type: "POST",
            data: JSON.stringify(entity),
            contentType: "application/json",
            dataType: "json",
            success: function(result) {
                window.location.hash = '#login';
                alert("Registration successfull!");
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert("Registration failed: " + XMLHttpRequest.responseText);
            }
        });
    }
};

$(document).ready(function() {
    UserService.init();
});