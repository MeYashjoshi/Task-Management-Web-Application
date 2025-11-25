<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>My Tasks - TaskApp</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="assets/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/jquery.dataTables.min.css">
</head>

<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>My Tasks</h3>
            <div>
                <a class="btn btn-primary" href="task_form.php">Add Task</a>
                <a class="btn btn-outline-secondary" href="logout.php">Logout</a>
            </div>
        </div>

        <form id="filterForm" class="row g-2 my-3 justify-content-center">
            <div class="col-md-3">
                <select name="status" id="statusFilter" class="form-select">
                    <option value="">All Status</option>
                    <option>To Do</option>
                    <option>In Progress</option>
                    <option>Completed</option>
                </select>
            </div>

            <div class="col-md-3">
                <select name="category" id="categoryFilter" class="form-select">
                    <option value="">All Category</option>
                    <option>Work</option>
                    <option>Personal</option>
                </select>
            </div>

            <div class="col-md-4">
                <input name="search" id="searchFilter" class="form-control" placeholder="Search title...">
            </div>

            <div class="col-md-2">
                <button class="btn btn-primary w-100" type="submit">Filter</button>
            </div>
        </form>

        <div id="msg"></div>

        <table id="tasksTable" class="display table table-bordered">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Due Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <script src="assets/jquery-3.7.1.min.js"></script>
    <script src="assets/jquery.dataTables.min.js"></script>

    <script>
        var table;

        function loadTasks() {
            var filters = {
                status: $("#statusFilter").val(),
                category: $("#categoryFilter").val(),
                search: $("#searchFilter").val()
            };

            $.ajax({
                url: "fetch_tasks.php",
                type: "GET",
                data: filters,
                dataType: "json",
                success: function (res) {

                    table.clear();

                    res.data.forEach(function (task) {

                        var badgeClass = "bg-secondary";
                        if (task.status === "To Do") badgeClass = "bg-warning";
                        if (task.status === "In Progress") badgeClass = "bg-primary";
                        if (task.status === "Completed") badgeClass = "bg-success";

                        table.row.add([
                            task.title,
                            task.category,
                            '<span class="badge ' + badgeClass + '">' + task.status + '</span>',
                            task.due_date ?? "-",
                            `
                            <a href="task_form.php?id=${task.id}" class="btn btn-sm btn-info">Edit</a>
                            <button class="btn btn-sm btn-danger" onclick="deleteTask(${task.id})">Delete</button>
                            `
                        ]);
                    });

                    table.draw();
                }
            });
        }

        function deleteTask(id) {
            if (!confirm("Are you sure you want to delete this task?")) return;

            $.ajax({
                url: "delete_task.php",
                type: "POST",
                data: { id: id },
                dataType: "json",
                success: function (res) {
                    if (res.status === "success") {
                        $("#msg").html('<div class="alert alert-success">' + res.message + '</div>');
                        loadTasks();
                    } else {
                        $("#msg").html('<div class="alert alert-danger">' + res.message + '</div>');
                    }
                }
            });
        }

        $(document).ready(function () {
            table = $('#tasksTable').DataTable();
            loadTasks();
        });

        $("#filterForm").on("submit", function (e) {
            e.preventDefault();
            loadTasks();
        });

    </script>
</body>

</html>
