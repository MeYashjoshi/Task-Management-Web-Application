<?php
require_once "db.php";

if (empty($_SESSION['user']['id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user']['id'];

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$task = [
    "id" => "",
    "title" => "",
    "description" => "",
    "category" => "Work",
    "status" => "To Do",
    "due_date" => ""
];

if ($id > 0) {
    $stmt = $mysqli->prepare("SELECT id, title, description, category, status, due_date FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $userId);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($row = $res->fetch_assoc()) {
        $task = $row;
    }
}
?>

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
                    <h3><?php echo $task["id"] ? "Edit Task" : "Add Task"; ?></h3>
                    <div id="msg"></div>

                    <form id="taskForm" class="row g-3">
                        <input type="hidden" name="id" id="task_id" value="<?php echo $task['id']; ?>">

                        <div class="col-md-6">
                            <label class="form-label">Title</label>
                            <input name="title" id="title" type="text" class="form-control"
                                value="<?php echo htmlspecialchars($task['title']); ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Category</label>
                            <select name="category" id="category" class="form-select">
                                <option <?php echo ($task['category'] == "Work") ? 'selected' : ''; ?>>Work</option>
                                <option <?php echo ($task['category'] == "Personal") ? 'selected' : ''; ?>>Personal</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control"
                                rows="4"><?php echo htmlspecialchars($task['description']); ?></textarea>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option <?php echo ($task['status'] == "To Do") ? 'selected' : ''; ?>>To Do</option>
                                <option <?php echo ($task['status'] == "In Progress") ? 'selected' : ''; ?>>In Progress</option>
                                <option <?php echo ($task['status'] == "Completed") ? 'selected' : ''; ?>>Completed</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Due date</label>
                            <input name="due_date" id="due_date"
                                value="<?php echo $task['due_date']; ?>"
                                class="form-control" placeholder="YYYY-MM-DD" autocomplete="off">
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
        $("#due_date").datepicker({ dateFormat: "yy-mm-dd" });

        $("#taskForm").on("submit", function (e) {
            e.preventDefault();
            $("#msg").html('');
            $("#saveBtn").prop('disabled', true).text('Saving...');

            $.ajax({
                url: "save_task.php",
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                success: function (res) {
                    if (res.status === "success") {
                        $("#msg").html('<div class="alert alert-success">' + res.message + '</div>');
                        setTimeout(() => location.href = "tasks.php", 800);
                    } else {
                        $("#msg").html('<div class="alert alert-danger">' + res.message + '</div>');
                        $("#saveBtn").prop('disabled', false).text('Save');
                    }
                },
                error: function () {
                    $("#msg").html('<div class="alert alert-danger">Something went wrong</div>');
                    $("#saveBtn").prop('disabled', false).text('Save');
                }
            });
        });
    </script>

    <script src="assets/bootstrap.bundle.min.js"></script>
</body>

</html>
