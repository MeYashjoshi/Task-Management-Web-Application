<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Login - TaskApp</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="assets/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <main class="container mt-5">
        <div class="row justify-content-center mt-4">
            <div class="col-md-6">
                <div class="card p-4">

                    <!-- message area -->
                    <div id="msg"></div>

                    <form id="loginForm" class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Email</label>
                            <input name="email" type="email" class="form-control" required placeholder="you@example.com">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Password</label>
                            <input name="password" type="password" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <button id="btnLogin" type="submit" class="btn btn-primary">Login</button>
                            <a class="btn btn-link" href="register.php">Register</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </main>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $("#loginForm").on("submit", function (e) {
            e.preventDefault();
            $("#msg").html('');

            var $btn = $("#btnLogin");
            $btn.prop('disabled', true).text('Please wait...');

            $.ajax({
                url: "login_process.php",
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
                        $btn.prop('disabled', false).text('Login');
                    }
                },
                error: function () {
                    $("#msg").html('<div class="alert alert-danger">Something went wrong. Try again.</div>');
                    $btn.prop('disabled', false).text('Login');
                }
            });
        });
    </script>

    <script src="assets/bootstrap.bundle.min.js"></script>
</body>

</html>
