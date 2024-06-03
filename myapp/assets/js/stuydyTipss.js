var getTips = {
    init: function () {
            getTips.displayTips();
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
                    output +=  `
                    <div class="card" id="tips-card" tips-id="${tip.id}" style="margin-bottom: 20px; max-width: 100%; width: 350px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); background-color: #fff; margin: auto;">
                        <header class="card-header" style="padding: 5px; border-top-left-radius: 10px; border-top-right-radius: 10px; background-color: #0272a1;">
                            <p class="card-header-title" style="justify-content: center; height: 40px; margin: 0; font-size: 15px; color: #fff; font-weight: bold;">${tip.title}</p>
                        </header>
                        <div class="card-content" style="padding: 10px;">
                            <div class="content" style="font-size: 16px; color: gray;">Category: ${tip.category}</div>
                        </div>
                        <div class="card-content" style="padding: 10px;">
                            <div class="content" style="display: flex; justify-content: center;">
                                <div class="dropdown is-hoverable" style="width: 100%;">
                                    <div class="dropdown-trigger" style="width: 100%; text-align: center;">
                                        <button class="button is-success is-small" aria-haspopup="true" aria-controls="dropdown-menu" style="background-color: #0272a1; color: #eaeaea; width: 40%; border: none; border-radius: 5px;">
                                            View Details
                                            <span class="icon is-small">
                                                <i class="fas fa-angle-down" aria-hidden="true"></i>
                                            </span>
                                        </button>
                                    </div>
                                    <div class="dropdown-menu" id="dropdown-menu" role="menu" style="box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); width: 100%;">
                                        <div class="dropdown-content" style="text-align: left; padding: 10px; background-color: #fff; border-radius: 5px;">
                                            <p class="details-tips" style="margin: 0; font-size: 14px; color: gray;">${tip.details}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>                
                `;
                

                });
    
                $('#tips-container2').html(output);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert("Failed to fetch study tips: " + XMLHttpRequest.responseText);
            }
        });
    }
}

$(document).ready(function () {
    getTips.init();
});
