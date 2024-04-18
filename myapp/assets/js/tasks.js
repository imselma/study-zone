getTasks = () => {
  $.get("../tasks.json", (data) => {
    let output = '';
    data.forEach((task) => {
      output += `
            <div class="card" id="task-card" style="margin-bottom: 40px; width: 450px;">
            <header class="card-header">
              <p class="card-header-title">
              ${task.name}
              </p>
            </header>
            <div class="card-content">
              <div class="content">
                ${task.description}
                <br>
                <time datetime="2024-03-12">${task.deadline}</time>
              </div>
            </div>
          </div>
    </div>
            `;
    });
    let output2 = '';
    if (data.length === 0) {
      output2 += `
          <p>No tasks to do.</p>
          `;
    } else {
      data.forEach((task) => {
        output2 += `
                <div class="card" id="task-card" style="margin-bottom: 10px; margin-left: 10px;>
            <header class="card-header">
              <p class="card-header-title">
              ${task.name}
              </p>
            </header>
            <div class="card-content">
              <div class="content">
                <time datetime="2024-03-12">${task.deadline}</time>
              </div>
            </div>
          </div>
    </div>
        `;
      });
    }

    $("#task-container").append(output);
    $("#dashTasks").append(output2);
  });
}

getTasks();
