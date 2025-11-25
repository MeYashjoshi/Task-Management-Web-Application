<?php
require_once "db.php";

$logo = "";
$favicon = "";

$q = $mysqli->query("SELECT logo, favicon FROM settings WHERE id = 1");
if ($q && $q->num_rows > 0) {
    $row = $q->fetch_assoc();
    $logo = $row['logo'];
    $favicon = $row['favicon'];
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Upload Logo</title>
    <link href="assets/bootstrap.min.css" rel="stylesheet">

    <link rel="icon" href="uploads/<?php echo $favicon ? $favicon : 'default_favicon.ico'; ?>">

    <style>
        .preview-img{
            height: 60px;
            border:1px solid #ddd;
            padding:5px;
            margin-right:10px;
        }
    </style>
</head>
<body>

<div class="container mt-4">
    <h3>Upload Logo & Favicon</h3>

    <div id="msg"></div>

    <div class="mb-3">
        <h5>Current Uploaded Files:</h5>

        <?php if ($logo) { ?>
            <div>
                <strong>Logo:</strong><br>
                <img src="uploads/<?php echo $logo; ?>" class="preview-img">
            </div>
        <?php } else { ?>
            <p>No logo uploaded.</p>
        <?php } ?>

        <?php if ($favicon) { ?>
            <div class="mt-2">
                <strong>Favicon:</strong><br>
                <img src="uploads/<?php echo $favicon; ?>" class="preview-img">
            </div>
        <?php } else { ?>
            <p>No favicon uploaded.</p>
        <?php } ?>
    </div>

    <form id="uploadForm" class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Logo</label>
            <input type="file" name="logo" class="form-control">
        </div>

        <div class="col-md-6">
            <label class="form-label">Favicon</label>
            <input type="file" name="favicon" class="form-control">
        </div>

        <div class="col-12">
            <button id="uploadBtn" class="btn btn-primary">Upload</button>
            <a href="tasks.php" class="btn btn-secondary">Back</a>
        </div>
    </form>

</div>

<script src="assets/jquery-3.7.1.min.js"></script>
<script>
$("#uploadForm").on("submit", function(e){
    e.preventDefault();

    var formData = new FormData(this);
    $("#msg").html('');
    $("#uploadBtn").prop('disabled', true).text('Uploading...');

    $.ajax({
        url: "upload_logo.php",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function(res){
            if (res.status === "success") {
                $("#msg").html('<div class="alert alert-success">' + res.message + '</div>');
                setTimeout(() => {
                    location.reload();
                }, 800);
            } else {
                $("#msg").html('<div class="alert alert-danger">' + res.message + '</div>');
            }
            $("#uploadBtn").prop('disabled', false).text('Upload');
        },
        error: function(){
            $("#msg").html('<div class="alert alert-danger">Error uploading file</div>');
            $("#uploadBtn").prop('disabled', false).text('Upload');
        }
    });
});
</script>

</body>
</html>
