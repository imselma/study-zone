var getTips = {
    init: function () {
            getTips.displayTips();
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
                    output +=  `
                    <div class="card" id="tips-card" tips-id="${tip.id}" style="margin-bottom: 20px; max-width: 350px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); background-color: #fff;">
                        <header class="card-header" style="padding: 5px; border-top-left-radius: 10px; border-top-right-radius: 10px; background-color: #0272a1;">
                            <p class="card-header-title" style="margin: 0; font-size: 18px; color: #fff;">${tip.title}</p>
                        </header>
                        <div class="card-content" style="padding: 10px;">
                            <div class="content" style="font-size: 16px;"> Category: ${tip.category}</div>
                        </div>
                        <div class="card-content" style="padding: 10px;">
                            <div class="content" style="display: flex; justify-content: center;">
                                <div class="dropdown is-hoverable">
                                    <div class="dropdown-trigger">
                                        <button class="button is-success is-small" aria-haspopup="true" aria-controls="dropdown-menu" style="background-color: #0272a1; color: #eaeaea;">
                                            View Details
                                            <span class="icon is-small">
                                                <i class="fas fa-angle-down" aria-hidden="true"></i>
                                            </span>
                                        </button>
                                    </div>
                                    <div class="dropdown-menu" id="dropdown-menu" role="menu" style="box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);">
                                        <div class="dropdown-content" style="text-align: left;">
                                            <p class="details-tips" style="margin: 0; margin-left: 10px; margin-right: 10px; font-size: 14px;">${tip.details}</p>
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

  // Add event listener for dropdown trigger buttons
  $(document).on('click', '.dropdown-trigger button', function () {
    $(this).closest('.dropdown').toggleClass('is-active');
  });

  // Close dropdowns when clicking outside of them
  $(document).on('click', function (e) {
    if (!$(e.target).closest('.dropdown').length) {
      $('.dropdown').removeClass('is-active');
    }
  });