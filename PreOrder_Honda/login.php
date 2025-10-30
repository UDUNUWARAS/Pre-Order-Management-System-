<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Honda Dio Admin - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <div class="login-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="card fade-in">
                        <div class="card-body p-5">
                            <div class="text-center mb-4">
                                <i class="fas fa-motorcycle fa-3x text-danger mb-3"></i>
                                <h3 class="fw-bold">Honda Dio Admin</h3>
                                <p class="text-muted">Pre-Order Management System</p>
                            </div>
                            <form>
                                <div class="mb-3">
                                    <label class="form-label">Username</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input type="text" id="email" class="form-control" required>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" id="password" class="form-control" required>
                                    </div>
                                </div>
                                <a onclick="adminSignIn()" class="btn btn-primary w-100 py-2">
                                    <i class="fas fa-sign-in-alt me-2"></i>Login
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/script.js"></script>
</body>

</html>