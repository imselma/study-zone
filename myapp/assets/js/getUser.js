getUser = () => {
    $.get("../user.json", (data) => {
        let output = '';
            output +=  `
                <img src="${data.picture}" alt="User Photo" class="profile-photo" style="width: 110px; height: 100px;">
                <div class="profile-details">
                    <p class="name" style="font-size: 25px;">${data.name}</p>
                    <p class="name" style="font-size: 20px;">Username: <span id="info" style="color: gray; font-size: 18px; margin-top: 1px;">${data.username}</span></p>
                    <p class="name" style="font-size: 20px;">Email: <span id="info" style="color: gray; font-size: 18px; margin-top: 1px;">${data.email}</span></p>
                    <p class="name" style="font-size: 20px;">University: <span id="info" style="color: gray; font-size: 18px; margin-top: 1px;">${data.university}</span></p>
                    <p class="name" style="font-size: 20px;">Department: <span id="info" style="color: gray; font-size: 18px; margin-top: 1px;">${data.department}</span></p>
                    
                </div>
            `;
            $("#profile").append(output);
    });
}

getUser();
