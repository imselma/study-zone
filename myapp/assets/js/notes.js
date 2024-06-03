var NoteService = {

  notesArray: [],

  init: function () {

    NoteService.displayNotes();

    $("#addNotes-form").validate({
      rules: {
        definition: {
          required: true
        },
        details: {
          required: true
        }
      },
      messages: {
        definition: {
          required: "Please enter the name of your note."
        },
        details: {
          required: "Please enter the details."
        }
      },
      submitHandler: function (form) {
        var entity = {
          title: $("input[name='noteDefinition']").val(),
          details: $("input[name='noteDetails']").val(),
          users_id: localStorage.getItem('users_id')
        };

        NoteService.addNote(entity); //Calling the function for registering user with the payload provided
      }

    });
  },


  //Add logic
  addNote: function (entity) {
    $.ajax({
      url: Constants.get_api_base_url() + "addNote",
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

        var newNote = result; // Assuming the server returns the newly created task
        NoteService.notesArray.push(newNote);

        $("input[name='noteDefinition']").val(''),
        $("input[name='noteDetails']").val(''),
        alert("Note saved sucessfully!");
        $('.modal').removeClass('is-active');
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        alert("Failed to save the note: " + XMLHttpRequest.responseText);
      }
    });
  },

  //Display logic
  displayNotes: function () {
    $.ajax({
      url: Constants.get_api_base_url() + "getNoteByUserId",
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
        NoteService.notesArray = data.result;
        console.log("array", NoteService.notesArray);

        var output = '';
        NoteService.notesArray.forEach((note, index) => {
          console.log("noteid",note.id);
          output += `
            <div class="card" id="note-card" note-id="${note.id}" style="margin-bottom: 40px; width: 450px;">
                  <header class="card-header">
                    <p class="card-header-title">
                    ${note.title}
                    </p>
                  </header>
                  <div class="card-content">
                    <div class="content" style = "display:flex; flex-direction: column; align-items: center;">
                        <div class= "view-buttons" style = "display: flex; flex-direction: row;">
                          <div class="dropdown is-hoverable">
                            <div class="dropdown-trigger">
                                <button class="button is-success" id="view-note-details" aria-haspopup="true" aria-controls="dropdown-menu3" style="background-color: #0272a1; color: #eaeaea;">View
                                  <span class="icon is-small">
                                    <i class="fas fa-angle-down" aria-hidden="true"></i>
                                  </span>
                                </button>
                            </div>
                            <div class="dropdown-menu" id="dropdown-menu4" role="menu" style="box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);">
                              <div class="dropdown-content" style="text-align: left;">
                                <p class="details-notes" tyle="margin-left: 10px; margin-right: 10px;">${note.details}</p>
                              </div>
                            </div>
                          </div>
                          <button class="button is-success edit-note" id="edit-note-details" data-note-index="${index}" style="margin-left: 30px; background-color: #eaeaea; color: #0272a1; border: 2px solid #0272a1">Edit</button>
                          <button class="button is-success delete-note" id="delete-note-details" data-note-index="${index}" style="margin-left: 30px; background-color: #eaeaea; color: #0272a1; border: 2px solid #0272a1">Delete</button>
                        </div>
                    </div>
                  </div>
            </div>

            <div class="modal" id="editNoteModal">
            <div class="modal-background"></div>
            <div class="modal-card">
              <header class="modal-card-head">
                <p class="modal-card-title">Edit Note</p>
                <button type="button" class="delete" id="x-close" aria-label="close" onClick="$('#editNoteModal').removeClass('is-active')"></button>
              </header>
              <form id="editNotes-form">
                <section class="modal-card-body">
                <input class="input" type="hidden" name="id" >
                  <div class="modalfield">
                    <p class="has-icons-right">
                      <input class="input" type="text" name="definition2">
                    </p>
                  </div>
                  <div class="modalfield">
                    <p class="control has-icons-right">
                      <input class="input" type="text" name="details2">
                    </p>
                  </div>
                </section>
                <footer class="modal-card-foot">
                  <div class="buttons">
                    <button type="button" class="button is-success" id="save-btn-note1" onClick="NoteService.submitEditForm()" style="background-color: #0272a1; color: #eaeaea;" >Save changes</button>
                    <button type="button" class="button" id="cancel-btn2" onClick="$('#editNoteModal').removeClass('is-active')" style="margin-left: 30px; background-color: #eaeaea; color: #0272a1; border: 2px solid #0272a1">Cancel</button>
                  </div>
                </footer>
            </form>
          </div>
      </div>
      
      <div class="modal" id="deleteNoteModal">
        <div class="modal-background"></div>
        <div class="modal-card">
          <header class="modal-card-head">
            <p class="modal-card-title">Delete Note</p>
            <button type="button" class="delete" aria-label="close" onClick="$('#deleteNoteModal').removeClass('is-active')"></button>
          </header>
          <section class="modal-card-body">
          <input class="input" type="hidden" name="id3" >
           <p>Are you sure you want to delete the note?<p>
          </section>
          <footer class="modal-card-foot">
            <div class="buttons">
            <button type="button" class="button is-success" id="delete-btn-note1"onClick="NoteService.submitDelete()" style="margin-left: 30px; background-color: #eaeaea; color: #0272a1; border: 2px solid #0272a1">Delete</button>
            <button type="button" class="button" id="cancel-btn3" onClick="$('#deleteNoteModal').removeClass('is-active')" style="background-color: #0272a1; color: #eaeaea;">Cancel</button>
            </div>
          </footer>
        </div>
      </div>`;
      });

      let output2 = '';
        if(data.length === 0){
          output2+=`
          <p>No notes to display.</p>
          `;
      }else{
        NoteService.notesArray.forEach((note, index) => {
            output2 += `
            <div class="card" id="note-card" style=" margin-top: 10px; margin-bottom: 10px; background-color: #eaeaea; border: 1px solid #0272a1;">
            <header class="card-header" style="background-color: #0272a1; color: #eaeaea;">
              <p class="card-header-title" style="color: #eaeaea;">
                ${note.title}
              </p>
            </header>
            <div class="card-content" style="color: #0272a1; display: flex; flex-direction: column; align-items: center;">
              <div class="content">
                ${note.details}
              </div>
            </div>
        </div>
        `;
        });}

        $("#dashNotes").append(output2);
      

       /* // Add event listener for dropdown trigger buttons
        $(document).on('click', '.dropdown-trigger button', function () {
          $(this).closest('.dropdown').toggleClass('is-active');
        });

        // Close dropdowns when clicking outside of them
        $(document).on('click', function (e) {
          if (!$(e.target).closest('.dropdown').length) {
            $('.dropdown').removeClass('is-active');
          }
        });*/


        //Fetch task data based on index
        $(document).on('click', '.edit-note', function () {
          var noteIndex = $(this).data('note-index');
          var noteData = NoteService.notesArray[noteIndex];
          console.log("Note data:", noteData);
          // Fill the modal with note data
          $('#editNoteModal').find('input[name="definition2"]').val(noteData.title);
          $('#editNoteModal').find('input[name="details2"]').val(noteData.details);
          $('#editNoteModal').find('input[name="id"]').val(noteData.id);
          
          // Open the modal
          $('#editNoteModal').addClass('is-active');
        })

        //Fetch note data based on index
        $(document).on('click', '.delete-note', function () {
          var noteIndex = $(this).data('note-index');
          var noteData2 = NoteService.notesArray[noteIndex];
          console.log("note data:", noteData2);
          // Fill the modal with note data
          $('#deleteNoteModal').find('input[name="id3"]').val(noteData2.id);
          
          // Open the modal
          $('#deleteNoteModal').addClass('is-active');
        })

        $('#note-container').html(output);
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        alert("Failed to fetch notes: " + XMLHttpRequest.responseText);
      }
    });
  },


  //Edit logic
  submitEditForm: function () {
    var editEntity = {
      title: $("input[name='definition2']").val(),
      details: $("input[name='details2']").val(),
      users_id: localStorage.getItem('users_id')
    };
    let noteId = $("input[name='id']").val();

    NoteService.editNote(editEntity, noteId);
  },

  editNote: function (entity, noteId) {
    $.ajax({
      url: Constants.get_api_base_url() + "editNote/" + noteId,
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
        var editedNoteIndex = NoteService.notesArray.findIndex(note => note.id === noteId);
        NoteService.notesArray[editedNoteIndex] = entity;

        // Update the data of the card -> find the card and than update 
        var $noteElement = $(`#note-card[note-id="${noteId}"]`);
        $noteElement.find('.card-header-title').text(entity.title);
        $noteElement.find('.details-note').text(entity.details);
        alert("Note edited sucessfully!");
        $('#editNoteModal').removeClass('is-active');
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        alert("Failed to edit the note: " + XMLHttpRequest.responseText);
      }
    });
  },


  //Delete logic

  submitDelete: function () {
    let noteId = $("input[name='id3']").val();
    NoteService.deleteNote(noteId);
  },

  deleteNote: function (noteId) {
    $.ajax({
      url: Constants.get_api_base_url() + "deleteNote/" + noteId,
      type: "DELETE",
      contentType: "application/json",
      beforeSend: function(xhr) {
        if(localStorage.getItem('current_user')){
          xhr.setRequestHeader("Authentication", localStorage.getItem('token'));
        }
      },
      success: function (result) {

        var $noteElement = $(`#note-card[note-id="${noteId}"]`);
        $noteElement.remove();

        alert("Note deleted sucessfully!");
        $('#deleteNoteModal').removeClass('is-active');
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        alert("Failed to delete the note: " + XMLHttpRequest.responseText);
      }
    });
  },

};

$(document).ready(function () {
  NoteService.init();
});

