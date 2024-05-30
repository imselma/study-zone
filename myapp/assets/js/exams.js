var ExamService = {

  examsArray: [],

  init: function () {

    ExamService.displayExams();

    $("#addExams-form").validate({
      rules: {
        definition: {
          required: true
        },
        details: {
          required: true
        },
        date_time: {
          required: true,
        }
      },
      messages: {
        definition: {
          required: "Please enter the name of your exam."
        },
        details: {
          required: "Please enter the details."
        },
        date_time: {
          required: "Please enter the deadline."
        }
      },
      submitHandler: function (form) {
        var entity = {
          title: $("input[name='examDefinition']").val(),
          details: $("input[name='examDetails']").val(),
          date_time: $("input[name='timeDetails']").val(),
          users_id: localStorage.getItem('users_id')
        };

        ExamService.addExam(entity); //Calling the function for registering user with the payload provided
      }

    });
  },


  //Add logic
  addExam: function (entity) {
    $.ajax({
      url: "../rest/addExam",
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

        var newExam = result; // Assuming the server returns the newly created task
        ExamService.examsArray.push(newExam);

        $("input[name='examDefinition']").val(''),
        $("input[name='examDetails']").val(''),
        $("input[name='timeDetails']").val(''),
        alert("Exam saved sucessfully!");
        $('.modal').removeClass('is-active');
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        alert("Failed to save the exam: " + XMLHttpRequest.responseText);
      }
    });
  },

  //Display logic
  displayExams: function () {
    $.ajax({
      url: "../rest/getAllExams",
      type: "GET",
      contentType: "application/json",
      dataType: "json",
      beforeSend: function(xhr) {
        if(localStorage.getItem('current_user')){
          xhr.setRequestHeader("Authentication", localStorage.getItem('token'));
        }
      },
      success: function (data) {
        console.log("Success! Data received:", data);

        //Data is wraped in an object with key: result and result is an array with notes
        ExamService.examsArray = data.result;
        console.log("array", ExamService.examsArray);

        var output = '';
        ExamService.examsArray.forEach((exam, index) => {
          console.log("examid",exam.id);
          output += `
            <div class="card" id="exam-card" exam-id="${exam.id}" style="margin-bottom: 40px; width: 450px;">
                  <header class="card-header">
                    <p class="card-header-title">
                    ${exam.title}
                    </p>
                  </header>
                  <div class="card-content">
                    <div class="content" style = "display:flex; flex-direction: column; align-items: center;">
                    <time class="deadline-exam" datetime="2024-03-12" style = "margin-bottom: 10px;"><b>Deadline:</b> ${exam.date_time}</time>
                        <div class= "view-buttons" style = "display: flex; flex-direction: row;">
                          <div class="dropdown">
                            <div class="dropdown-trigger">
                                <button class="button is-success" id="view-exam-details" aria-haspopup="true" aria-controls="dropdown-menu3" style="background-color: #0272a1; color: #eaeaea;">View
                                  <span class="icon is-small">
                                    <i class="fas fa-angle-down" aria-hidden="true"></i>
                                  </span>
                                </button>
                            </div>
                            <div class="dropdown-menu" id="dropdown-menu4" role="menu" style="box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);">
                              <div class="dropdown-content" style="text-align: left;">
                                <p class="details-exam" tyle="margin-left: 10px; margin-right: 10px;">${exam.details}</p>
                              </div>
                            </div>
                          </div>
                          <button class="button is-success edit-exam" id="edit-exam-details" data-exam-index="${index}" style="margin-left: 30px; background-color: #eaeaea; color: #0272a1; border: 2px solid #0272a1">Edit</button>
                          <button class="button is-success delete-exam" id="delete-exam-details" data-exam-index="${index}" style="margin-left: 30px; background-color: #eaeaea; color: #0272a1; border: 2px solid #0272a1">Delete</button>
                        </div>
                    </div>
                  </div>
            </div>

            <div class="modal" id="editExamModal">
            <div class="modal-background"></div>
            <div class="modal-card">
              <header class="modal-card-head">
                <p class="modal-card-title">Edit Exam</p>
                <button type="button" class="delete" id="x-close" aria-label="close" onClick="$('#editExamModal').removeClass('is-active')"></button>
              </header>
              <form id="editExams-form">
                <section class="modal-card-body">
                <input class="input" type="hidden" name="id" >
                  <div class="modalfield">
                    <p class="has-icons-right">
                      <input class="input" type="text" name="definition3">
                    </p>
                  </div>
                  <div class="modalfield">
                    <p class="control has-icons-right">
                      <input class="input" type="text" name="details3">
                    </p>
                  </div>
                  <div class="modalfield">
                  <p class="control has-icons-right">
                    <input class="input" type="text" name="deadline3">
                  </p>
                </div>
                </section>
                <footer class="modal-card-foot">
                  <div class="buttons">
                    <button type="button" class="button is-success" id="save-btn-exam1" onClick="ExamService.submitEditForm()" style="background-color: #0272a1; color: #eaeaea;" >Save changes</button>
                    <button type="button" class="button" id="cancel-btn2" onClick="$('#editExamModal').removeClass('is-active')" style="margin-left: 30px; background-color: #eaeaea; color: #0272a1; border: 2px solid #0272a1">Cancel</button>
                  </div>
                </footer>
            </form>
          </div>
      </div>
      
      <div class="modal" id="deleteExamModal">
        <div class="modal-background"></div>
        <div class="modal-card">
          <header class="modal-card-head">
            <p class="modal-card-title">Delete Exam</p>
            <button type="button" class="delete" aria-label="close" onClick="$('#deleteExamModal').removeClass('is-active')"></button>
          </header>
          <section class="modal-card-body">
          <input class="input" type="hidden" name="id4" >
           <p>Are you sure you want to delete the exam?<p>
          </section>
          <footer class="modal-card-foot">
            <div class="buttons">
            <button type="button" class="button is-success" id="delete-btn-exam1"onClick="ExamService.submitDelete()" style="margin-left: 30px; background-color: #eaeaea; color: #0272a1; border: 2px solid #0272a1">Delete</button>
            <button type="button" class="button" id="cancel-btn3" onClick="$('#deleteExamModal').removeClass('is-active')" style="background-color: #0272a1; color: #eaeaea;">Cancel</button>
            </div>
          </footer>
        </div>
      </div>`;
      });

      let output2 = '';
        if(data.length === 0){
          output2+=`
          <p>No exams to display.</p>
          `;
      }else{
        ExamService.examsArray.forEach((exam, index) => {
            output2 += `
            <div class="card" id="exam-card" style=" margin-top: 10px; margin-bottom: 10px; background-color: #eaeaea; border: 1px solid #0272a1;">
            <header class="card-header" style="background-color: #0272a1; color: #eaeaea;">
              <p class="card-header-title" style="color: #eaeaea;">
                ${exam.title}
              </p>
            </header>
            <div class="card-content" style="color: #0272a1; display: flex; flex-direction: column; align-items: center;">
              <div class="content">
                ${exam.details}
              </div>
              <div class="time" style="font-weight: bold; text-align: left;">
                ${exam.date_time}
              </div>
            </div>
        </div>
        `;
        });}

        $("#dashExams").append(output2);

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


        //Fetch task data based on index
        $(document).on('click', '.edit-exam', function () {
          var examIndex = $(this).data('exam-index');
          var examData = ExamService.examsArray[examIndex];
          console.log("Exam data:", examData);
          // Fill the modal with exam data
          $('#editExamModal').find('input[name="definition3"]').val(examData.title);
          $('#editExamModal').find('input[name="details3"]').val(examData.details);
          $('#editExamModal').find('input[name="deadline3"]').val(examData.deadline);
          $('#editExamModal').find('input[name="id"]').val(examData.id);
          
          // Open the modal
          $('#editExamModal').addClass('is-active');
        })

        //Fetch exam data based on index
        $(document).on('click', '.delete-exam', function () {
          var examIndex = $(this).data('exam-index');
          var examData2 = ExamService.examsArray[examIndex];
          console.log("exam data:", examData2);
          // Fill the modal with exam data
          $('#deleteExamModal').find('input[name="id4"]').val(examData2.id);
          
          // Open the modal
          $('#deleteExamModal').addClass('is-active');
        })

        $('#exams-container').html(output);
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        alert("Failed to fetch exam: " + XMLHttpRequest.responseText);
      }
    });
  },


  //Edit logic
  submitEditForm: function () {
    var editEntity = {
      title: $("input[name='definition3']").val(),
      details: $("input[name='details3']").val(),
      date_time: $("input[name='deadline3']").val(),
      users_id: localStorage.getItem('users_id')
    };
    let examId = $("input[name='id']").val();

    ExamService.editExam(editEntity, examId);
  },

  editExam: function (entity, examId) {
    $.ajax({
      url: "../rest/editExam/" + examId,
      type: "PUT",
      data: JSON.stringify(entity),
      contentType: "application/json",
      dataType: "json",
      beforeSend: function(xhr) {
        if(localStorage.getItem('current_user')){
          xhr.setRequestHeader("Authentication", localStorage.getItem('token'));
        }
      },
      success: function (result) {

        //Automatically reload the content on page, without the forced reload
        var editedExamIndex = ExamService.examsArray.findIndex(exam => exam.id === examId);
        ExamService.examsArray[editedExamIndex] = entity;

        // Update the data of the card -> find the card and than update 
        var $examElement = $(`#exam-card[exam-id="${examId}"]`);
        $examElement.find('.card-header-title').text(entity.title);
        $examElement.find('.deadline-exam').text(entity.deadline);
        $examElement.find('.details-exam').text(entity.details);
        alert("Exam edited sucessfully!");
        $('#editExamModal').removeClass('is-active');
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        alert("Failed to edit the exam: " + XMLHttpRequest.responseText);
      }
    });
  },


  //Delete logic

  submitDelete: function () {
    let examId = $("input[name='id4']").val();
    ExamService.deleteExam(examId);
  },

  deleteExam: function (examId) {
    $.ajax({
      url: "../rest/deleteExam/" + examId,
      type: "DELETE",
      contentType: "application/json",
      beforeSend: function(xhr) {
        if(localStorage.getItem('current_user')){
          xhr.setRequestHeader("Authentication", localStorage.getItem('token'));
        }
      },
      success: function (result) {

        var $examElement = $(`#exam-card[exam-id="${examId}"]`);
        $examElement.remove();

        alert("Exam deleted sucessfully!");
        $('#deleteExamModal').removeClass('is-active');
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        alert("Failed to delete the exam: " + XMLHttpRequest.responseText);
      }
    });
  },

};

$(document).ready(function () {
  ExamService.init();
});

