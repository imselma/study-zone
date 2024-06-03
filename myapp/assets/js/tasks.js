var TaskService = {

  tasksArray: [],

  init: function () {

    TaskService.displayTasks();

    $("#addTasks-form").validate({
      rules: {
        definition: {
          required: true
        },
        details: {
          required: true
        },
        deadline: {
          required: true,
        }
      },
      messages: {
        definition: {
          required: "Please enter the name of your task."
        },
        details: {
          required: "Please enter the details."
        },
        deadline: {
          required: "Please enter the deadline."
        }
      },
      submitHandler: function (form) {
        var entity = {
          title: $("input[name='definition']").val(),
          details: $("input[name='details']").val(),
          deadline: $("input[name='deadline']").val(),
          users_id: localStorage.getItem('users_id')
        };

        TaskService.addTask(entity); //Calling the function for registering user with the payload provided
      }

    });
  },


  //Add logic
  addTask: function (entity) {
    $.ajax({
      url: Constants.get_api_base_url() + "addTask",
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

        var newTask = result; // Assuming the server returns the newly created task
        TaskService.tasksArray.push(newTask);

        $("input[name='definition']").val(''),
          $("input[name='details']").val(''),
          $("input[name='deadline']").val(''),
          alert("Task saved sucessfully!");
        $('.modal').removeClass('is-active');
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        alert("Failed to save the task: " + XMLHttpRequest.responseText);
      }
    });
  },

  //Display logic
  displayTasks: function () {
    $.ajax({
      url: Constants.get_api_base_url() + "getTaskByUserId",
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

        //Data is wraped in an object with key: result and result is an array with tips
        TaskService.tasksArray = data.result;
        console.log("array", TaskService.tasksArray);

        var output = '';
        TaskService.tasksArray.forEach((task, index) => {
          console.log("taskid",task.id);
          output += `
            <div class="card" id="task-card" task-id="${task.id}" style="margin-bottom: 40px; width: 450px;">
                  <header class="card-header">
                    <p class="card-header-title">
                    ${task.title}
                    </p>
                  </header>
                  <div class="card-content">
                    <div class="content" style = "display:flex; flex-direction: column; align-items: center;">
                        <time class="deadline-task" datetime="2024-03-12" style = "margin-bottom: 10px;"><b>Deadline:</b> ${task.deadline}</time>
                        <div class= "view-buttons" style = "display: flex; flex-direction: row;">
                          <div class="dropdown is-hoverable">
                            <div class="dropdown-trigger">
                                <button class="button is-success" id="view-task-details" aria-haspopup="true" aria-controls="dropdown-menu3" style="background-color: #0272a1; color: #eaeaea;">View
                                  <span class="icon is-small">
                                    <i class="fas fa-angle-down" aria-hidden="true"></i>
                                  </span>
                                </button>
                            </div>
                            <div class="dropdown-menu" id="dropdown-menu3" role="menu" style="box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);">
                              <div class="dropdown-content" style="text-align: left;">
                                <p class="details-task" tyle="margin-left: 10px; margin-right: 10px;">${task.details}</p>
                              </div>
                            </div>
                          </div>
                          <button class="button is-success edit-task" id="edit-task-details" data-task-index="${index}" style="margin-left: 30px; background-color: #eaeaea; color: #0272a1; border: 2px solid #0272a1">Edit</button>
                          <button class="button is-success delete-task" id="delete-task-details" data-task-index="${index}" style="margin-left: 30px; background-color: #eaeaea; color: #0272a1; border: 2px solid #0272a1">Delete</button>
                        </div>
                    </div>
                  </div>
            </div>

            <div class="modal" id="editTaskModal">
            <div class="modal-background"></div>
            <div class="modal-card">
              <header class="modal-card-head">
                <p class="modal-card-title">Edit Task</p>
                <button type="button" class="delete" id="x-close" aria-label="close" onClick="$('#editTaskModal').removeClass('is-active')"></button>
              </header>
              <form id="editTasks-form">
                <section class="modal-card-body">
                <input class="input" type="hidden" name="id" >
                  <div class="modalfield">
                    <p class="has-icons-right">
                      <input class="input" type="text" name="definition1">
                    </p>
                  </div>
                  <div class="modalfield">
                    <p class="control has-icons-right">
                      <input class="input" type="text" name="details1">
                    </p>
                  </div>
                  <div class="modalfield">
                    <p class="control has-icons-right">
                      <input class="input" type="text" name="deadline1">
                    </p>
                  </div>
                </section>
                <footer class="modal-card-foot">
                  <div class="buttons">
                    <button type="button" class="button is-success" id="save-btn-task1" onClick="TaskService.submitEditForm()" style="background-color: #0272a1; color: #eaeaea;" >Save changes</button>
                    <button type="button" class="button" id="cancel-btn2" onClick="$('#editTaskModal').removeClass('is-active')" style="margin-left: 30px; background-color: #eaeaea; color: #0272a1; border: 2px solid #0272a1">Cancel</button>
                  </div>
                </footer>
            </form>
          </div>
      </div>
      
      <div class="modal" id="deleteTaskModal">
        <div class="modal-background"></div>
        <div class="modal-card">
          <header class="modal-card-head">
            <p class="modal-card-title">Delete Task</p>
            <button type="button" class="delete" aria-label="close" onClick="$('#deleteTaskModal').removeClass('is-active')"></button>
          </header>
          <section class="modal-card-body">
          <input class="input" type="hidden" name="id2" >
           <p>Are you sure you want to delete the task?<p>
          </section>
          <footer class="modal-card-foot">
            <div class="buttons">
            <button type="button" class="button is-success" id="delete-btn-task1"onClick="TaskService.submitDelete()" style="margin-left: 30px; background-color: #eaeaea; color: #0272a1; border: 2px solid #0272a1">Delete</button>
            <button type="button" class="button" id="cancel-btn3" onClick="$('#deleteTaskModal').removeClass('is-active')" style="background-color: #0272a1; color: #eaeaea;">Cancel</button>
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
      TaskService.tasksArray.forEach((task, index) => {
        output2 += `
        <div class="card" id="exam-card" style=" margin-top: 10px; margin-bottom: 10px; background-color: #eaeaea; border: 1px solid #0272a1;">
        <header class="card-header" style="background-color: #0272a1; color: #eaeaea;">
          <p class="card-header-title" style="color: #eaeaea;">
            ${task.title}
          </p>
        </header>
        <div class="card-content" style="color: #0272a1; display: flex; flex-direction: column; align-items: center;">
          <div class="content">
            ${task.details}
          </div>
          <div class="time" style="font-weight: bold; text-align: left;">
            ${task.deadline}
          </div>
        </div>
    </div>
    `;
    });}

    $("#dashTasks").append(output2);

        //Fetch task data based on index
        $(document).on('click', '.edit-task', function () {
          var taskIndex = $(this).data('task-index');
          var taskData = TaskService.tasksArray[taskIndex];
          console.log("task data:", taskData);
          // Fill the modal with task data
          $('#editTaskModal').find('input[name="definition1"]').val(taskData.title);
          $('#editTaskModal').find('input[name="details1"]').val(taskData.details);
          $('#editTaskModal').find('input[name="deadline1"]').val(taskData.deadline);
          $('#editTaskModal').find('input[name="id"]').val(taskData.id);
          
          // Open the modal
          $('#editTaskModal').addClass('is-active');
        })

        //Fetch task data based on index
        $(document).on('click', '.delete-task', function () {
          var taskIndex = $(this).data('task-index');
          var taskData2 = TaskService.tasksArray[taskIndex];
          console.log("task data:", taskData2);
          // Fill the modal with task data
          $('#deleteTaskModal').find('input[name="id2"]').val(taskData2.id);
          
          // Open the modal
          $('#deleteTaskModal').addClass('is-active');
        })

        $('#task-container').html(output);
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        alert("Failed to fetch tasks: " + XMLHttpRequest.responseText);
      }
    });
  },


  //Edit logic
  submitEditForm: function () {
    var editEntity = {
      title: $("input[name='definition1']").val(),
      details: $("input[name='details1']").val(),
      deadline: $("input[name='deadline1']").val(),
      users_id: localStorage.getItem('users_id')
    };
    let taskId = $("input[name='id']").val()

    TaskService.editTask(editEntity, taskId);
  },

  editTask: function (entity, taskId) {
    $.ajax({
      url: Constants.get_api_base_url() + "editTask/" + taskId,
      type: "PUT",
      data: JSON.stringify(entity),
      contentType: "application/json",
      beforeSend: function(xhr) {
        if(localStorage.getItem('current_user')){
          xhr.setRequestHeader("Authentication", localStorage.getItem('token'));
        }
      },
      success: function (result) {

        //Automatically reload the content on page, without the forced reload
        var editedTaskIndex = TaskService.tasksArray.findIndex(task => task.id === taskId);
        TaskService.tasksArray[editedTaskIndex] = entity;

        // Update the data of the card -> find the card and than update 
        var $taskElement = $(`#task-card[task-id="${taskId}"]`);
        $taskElement.find('.card-header-title').text(entity.title);
        $taskElement.find('.deadline-task').text(entity.deadline);
        $taskElement.find('.details-task').text(entity.details);
        alert("Task edited sucessfully!");
        $('#editTaskModal').removeClass('is-active');
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        alert("Failed to edit the task: " + XMLHttpRequest.responseText);
      }
    });
  },


  //Delete logic

  submitDelete: function () {
    let taskId = $("input[name='id2']").val();
    TaskService.deleteTask(taskId);
  },

  deleteTask: function (taskId) {
    $.ajax({
      url: Constants.get_api_base_url() + "deleteTask/" + taskId,
      type: "DELETE",
      contentType: "application/json",
      beforeSend: function(xhr) {
        if(localStorage.getItem('current_user')){
          xhr.setRequestHeader("Authentication", localStorage.getItem('token'));
        }
      },
      success: function (result) {

        var $taskElement = $(`#task-card[task-id="${taskId}"]`);
        $taskElement.remove();

        alert("Task deleted sucessfully!");
        $('#deleteTaskModal').removeClass('is-active');
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        alert("Failed to delete the task: " + XMLHttpRequest.responseText);
      }
    });
  },

};

$(document).ready(function () {
  TaskService.init();
});
