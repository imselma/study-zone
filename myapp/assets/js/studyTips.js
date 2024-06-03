var TipsService = {
    init: function () {
        TipsService.displayTips();
        
        $("#tips-form").validate({
            rules: {
                category: {
                    required: true
                },
                title: {
                    required: true
                },
                details: {
                    required: true,
                }
      
            },
            messages: {
                category: {
                    required: "Please select the category."
                },
                title: {
                    required: "Please enter the title."
                },
                details: {
                    required: "Please enter the details."
                }
            },
            submitHandler: function(form){
                var entity = {
                    category: $("select[name='category']").val(),
                    title: $("input[name='title']").val(),
                    details: $("input[name='details']").val(),
                };
                console.log("entity:", entity);
                TipsService.addTips(entity); //Calling the fubction for adding tips with the payload provided
            }
      
        });
    },

    addTips: function (entity) {
        $.ajax({
            url: Constants.get_api_base_url() + "addTip",
            type: "POST",
            data: JSON.stringify(entity),
            contentType: "application/json",
            dataType: "json",
            beforeSend: function(xhr) {
                if(localStorage.getItem('current_user')){
                  xhr.setRequestHeader("Authentication", localStorage.getItem('token'));
                }
              },
            success: function (result) {
                console.log("Success! Redirecting to admin panel...");
                $('.modal').removeClass('is-active');
                $('input[name="title"]').val('');
                $('input[name="details"]').val('');
                $('select[name="category"]').val('');
                $("#selected-value").text('');
                alert("Study tip added successfull!");
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert("Failed to add study tip: " + XMLHttpRequest.responseText);
            }
        });
    },

    displayTips: function () {
        $.ajax({
            url: Constants.get_api_base_url() + "getAllTips",
            type: "GET",
            contentType: "application/json",
            dataType: "json",
            success: function (data) {
                console.log("Success! Data received:", data);
            
                //Data is wraped in an object with key: result and result is an array with tips
                var tipsArray = data.result;
            
                var output = '';
                tipsArray.forEach((tip) => {
                    output += `
                        <div class="card" id="task-card" style="margin-bottom: 40px; width: 450px;">
                            <header class="card-header" style="background-color: #0272a1; color: #eaeaea">
                                <p class="card-header-title" style="color: #eaeaea">
                                    ${tip.title}
                                </p>
                            </header>
                            <div class="card-content">
                                <div class="content">
                                    ${tip.category}
                                </div>
                            </div>
                        </div>
                    `;
                });
            
                $('#tips-container').html(output);
                $('#tips-container2').html(output);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert("Failed to fetch study tips: " + XMLHttpRequest.responseText);
            }
        });
    }
};

$(document).ready(function () {
    TipsService.init();
});
