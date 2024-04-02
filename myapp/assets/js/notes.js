getNotes = () => {
    $.get("../notes.json", (data) => {
        let output = '';
        data.forEach((note) => {
            output += `
            <div class="card" id="task-card" style="margin-bottom: 10px;>
            <header class="card-header">
              <p class="card-header-title">
              ${note.title}
              </p>
            </header>
            <div class="card-content">
              <div class="content">
                ${note.content}
              </div>
            </div>
          </div>
    </div>
            `;
        });
        let output2 = '';
        data.forEach((note) => {
            output2 += `
            <div class="card" id="task-card" style="margin-bottom: 10px;>
            <header class="card-header">
              <p class="card-header-title">
              ${note.title}
              </p>
            </header>
            <div class="card-content">
              <div class="content">
                ${note.content}
              </div>
            </div>
          </div>
    </div>
        `;
        });

        $("#note-container").append(output);
        $("#dashNotes").append(output2);
    });
}

getNotes();
