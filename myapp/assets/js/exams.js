getExams = () => {
    $.get("../exams.json", (data) => {
        let output = '';
        data.forEach((exam) => {
            output+=  `
            <div class="card" id="task-card" style="margin-bottom: 10px;>
            <header class="card-header">
              <p class="card-header-title">
              ${exam.name}
              </p>
            </header>
            <div class="card-content">
              <div class="content">
                ${exam.description}
                <br>
                <time datetime="2024-03-12">${task.date}</time>
              </div>
            </div>
          </div>
    </div>
            `;
        });
        let output2 = '';
        if(data.length === 0){
            output2+=`
            <p>No upcomming exams.</p>
            `;
        }else{
            data.forEach((exam) => {
                output2 += `
                <div class="card" id="task-card" style="margin-bottom: 10px;>
                <header class="card-header">
                  <p class="card-header-title">
                  ${exam.name}
                  </p>
                </header>
                <div class="card-content">
                  <div class="content">
                    ${exam.description}
                    <br>
                    <time datetime="2024-03-12">${exams.date}</time>
                  </div>
                </div>
              </div>
        </div>
        `;
            });}
            $("#exams-container").append(output);
            $("#dashExams").append(output2);
    });
}

getExams();
