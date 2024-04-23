var TipsService = {
    init: function () {
        TipsService.displayTips();
    },
    displayTips: function () {
        $.ajax({
            url: "../rest/getAllTips",
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
