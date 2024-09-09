<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>


<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="../assets/css/icons.css" rel="stylesheet">
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/admin_panel.css" rel="stylesheet">
    <title>ChronoLuxe Admin</title>
  </head>
  <body>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <h2 class="text-center mt-4">Admin Login</h2>     
                    <div class="card-body">
                        <?php if (isset($_SESSION['login_error'])): ?>
                            <div class="alert alert-danger">
                                <?php echo $_SESSION['login_error']; ?>
                            </div>
                            <?php unset($_SESSION['login_error']); // Clear error after displaying ?>
                        <?php endif; ?>
                        <form method="POST">
                            <div class="mb-3">
                                <input type="text" class="form-control" id="email" name="email" placeholder="Email Address" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                            </div>
                            <div class="mb-3">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
  

    <script src="../assets/js/jquery.slim.min.js"></script>
    <script src="../assets/js/popper.min.js"></script>
    <script src="../assets/js/admin_panel.js"></script>
    <!-- Bootstrap JavaScript CDN -->
    <script src="../assets/js/bootstrap.min.js"></script>
    
    
  </body>
</html>