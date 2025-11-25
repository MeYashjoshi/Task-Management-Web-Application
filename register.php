<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Register - TaskApp</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="assets/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <main class="container mt-5">

        <div class="row justify-content-center mt-4">
            <div class="col-md-6">
                <div class="card p-4">
                    <form method="post" action="#" class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Full name</label>
                            <input name="name" type="text" class="form-control" required placeholder="Your name">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Email</label>
                            <input name="email" type="email" class="form-control" required placeholder="you@example.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password</label>
                            <input name="password" type="password" class="form-control" required minlength="6">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirm password</label>
                            <input name="confirm_password" type="password" class="form-control" required minlength="6">
                        </div>

                        <div class="col-12">
                            <button class="btn btn-primary">Register</button>
                            <a class="btn btn-link" href="login.html">Already have account?</a>
                        </div>

                        
                    </form>
                </div>
            </div>
    </main>

    <script src="assets/bootstrap.bundle.min.js"></script>
</body>

</html>