<!-- forgot_password.php -->
<?php
$title = 'Forgot Password';
ob_start();
?>

<form method="POST" id="forgotPasswordForm">
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3 mt-5 mb-5">
                <div class="text-center mb-5 text-dark">Forgot Password</div>
                <div class="card">
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>

                        <!-- First Name input -->
                        <div data-mdb-input-init class="form-outline mb-4">
                            <input type="text" id="first_name" name="first_name" class="form-control" required />
                            <label class="form-label" for="first_name">First Name</label>
                        </div>
                        
                        <!-- Last Name input -->
                        <div data-mdb-input-init class="form-outline mb-4">
                            <input type="text" id="last_name" name="last_name" class="form-control" required />
                            <label class="form-label" for="last_name">Last Name</label>
                        </div>
                        
                        <!-- Email input -->
                        <div data-mdb-input-init class="form-outline mb-4">
                            <input type="email" id="email" name="email" class="form-control" required />
                            <label class="form-label" for="email">Email address</label>
                        </div>

                        <!-- Submit button -->
                        <button type="submit" class="btn btn-primary btn-block mb-4">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<?php
    $content = ob_get_clean();
    include 'base.php';
?>
