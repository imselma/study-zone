var getUser = {
    init: function () {
            getUser.displayUser();
    },


    displayUser: function () {

        let userId = localStorage.getItem("users_id");
        console.log("id",userId);
        $.ajax({
            url: "../rest/getUserById/" + userId,
            type: "GET",
            contentType: "application/json",
            dataType: "json",
            beforeSend: function(xhr) {
                if(localStorage.getItem('current_user')){
                  xhr.setRequestHeader("Authentication", localStorage.getItem('token'));
                }
              },
            success: function (data) {
                console.log("data", data);
                let output = '';
                output +=  `
                <div class="card" style="width: 400px; padding: 20px; border: 1px solid #ccc; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                    <div style="border-bottom: 1px solid #ccc; padding-bottom: 20px;">
                        <img src="../images/circle.png" alt="User Photo" class="profile-photo" style="width: 140px; height: 140px; border-radius: 50%; border: 4px solid #0272a1; margin-bottom: 20px;">
                    </div>
                    <div class="profile-details" style="padding-left: 20px;">
                        <p class="name" style="font-size: 25px; margin-bottom: 10px;">${data.result.first_name} ${data.result.last_name}</p>
                        <p class="name" style="font-size: 20px; color: #555;">Username: <span style="color: #777; font-size: 18px; margin-top: 1px;">${data.result.username}</span></p>
                        <p class="name" style="font-size: 20px; color: #555;">Email: <span style="color: #777; font-size: 18px; margin-top: 1px;">${data.result.email}</span></p>
                        <p class="name" style="font-size: 20px; color: #555;">University: <span style="color: #777; font-size: 18px; margin-top: 1px;">${data.result.university}</span></p>
                        <p class="name" style="font-size: 20px; color: #555;">Department: <span style="color: #777; font-size: 18px; margin-top: 1px;">${data.result.department}</span></p>
                    </div>
                </div>
            `;
            
            
                $("#profile").append(output);

            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert("Failed to fetch user data: " + XMLHttpRequest.responseText);
            }
        });
    },
}

$(document).ready(function () {
    getUser.init();
});