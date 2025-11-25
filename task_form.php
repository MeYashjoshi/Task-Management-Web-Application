<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Add / Edit Task</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="assets/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/jquery-ui.css">
</head>

<body>
    <main class="container mt-5">
        <div class="row justify-content-center mt-4">
            <div class="col-md-6">
                <div class="card p-4">
                    <h3>Add / Edit Task</h3>
                    <div id="msg"></div>

                    <form id="taskForm" class="row g-3">
                        <input type="hidden" name="id" id="task_id" value="">

                        <div class="col-md-6">
                            <label class="form-label">Title</label>
                            <input name="title" id="title" type="text" class="form-control" required maxlength="255"
                                placeholder="Task title">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Category</label>
                            <select name="category" id="category" class="form-select">
                                <option>Work</option>
                                <option>Personal</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="4"></textarea>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option>To Do</option>
                                <option>In Progress</option>
                                <option>Completed</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Due date</label>
                            <input name="due_date" id="due_date" class="form-control" placeholder="YYYY-MM-DD"
                                autocomplete="off">
                        </div>

                        <div class="col-12">
                            <button id="saveBtn" type="submit" class="btn btn-primary">Save</button>
                            <a class="btn btn-secondary" href="tasks.php">Cancel</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </main>



    <script src="assets/jquery-3.7.1.min.js"></script>
    <script src="assets/jquery-ui.min.js"></script>
    <script>
        $(function () {
            $("#due_date").datepicker({ dateFormat: "yy-mm-dd" });
        });

        $("#taskForm").on("submit", function (e) {
            e.preventDefault();
            $("#msg").html('');
            var $btn = $("#saveBtn");
            $btn.prop('disabled', true).text('Saving...');

            $.ajax({
                url: "save_task.php",
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                success: function (res) {
                    if (res.status === "success") {
                        $("#msg").html('<div class="alert alert-success">' + res.message + '</div>');
                        setTimeout(function () {
                            window.location.href = "tasks.php";
                        }, 800);
                    } else {
                        $("#msg").html('<div class="alert alert-danger">' + res.message + '</div>');
                        $btn.prop('disabled', false).text('Save');
                    }
                },
                error: function () {
                    $("#msg").html('<div class="alert alert-danger">Something went wrong. Try again.</div>');
                    $btn.prop('disabled', false).text('Save');
                }
            });
        });
    </script>

    <script src="assets/bootstrap.bundle.min.js"></script>
</body>

</html>