<!-- reset_password.php -->
<?php
$title = 'Reset Password';
ob_start();

// Get the token from the URL
$token = $_GET['token'] ?? '';
if (empty($token)) {
    echo 'Invalid token.';
    exit;
}

?>

<form method="POST" id="resetPasswordForm">
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3 mt-5 mb-5">
                <div class="text-center mb-5 text-dark">Reset Password</div>
                <div class="card">
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>
                        
                        <!-- New Password input -->
                        <div class="form-outline mb-4">
                            <input type="password" id="new_password" name="new_password" class="form-control" required />
                            <label class="form-label" for="new_password">New Password</label>
                        </div>

                        <!-- Confirm Password input -->
                        <div class="form-outline mb-4">
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control" required />
                            <label class="form-label" for="confirm_password">Confirm Password</label>
                        </div>

                        <!-- Hidden Token input -->
                        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

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
