<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$title = 'Login'; 
ob_start(); 


?>

<form method="POST" id="loginForm">
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3 mt-5 mb-5">
                <div class="text-center mb-5 text-dark">Login to Continue.</div>
                <div class="card">
                    <div class="card-body">

                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>
                        <!-- Email input -->
                        <div data-mdb-input-init class="form-outline mb-4">
                            <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required />
                            <label class="form-label" for="email">Email address</label>
                        </div>
                    
                        <!-- Password input -->
                        <div data-mdb-input-init class="form-outline mb-4">
                            <input type="password" id="password" name="password" class="form-control" required />
                            <label class="form-label" for="password">Password</label>
                        </div>
                    
                        <!-- 2 column grid layout for inline styling -->
                        <div class="row mb-4">
                            <div class="col d-flex justify-content-center">
                                <!-- Checkbox -->
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember" name="remember" />
                                    <label class="form-check-label" for="remember"> Remember me </label>
                                </div>
                            </div>
                    
                            <div class="col">
                                <!-- Simple link -->
                                <a href="/forgot-password">Forgot password?</a>
                            </div>
                        </div>
                    
                        <!-- Submit button -->
                        <button type="submit" class="btn btn-primary btn-block mb-4">Sign in</button>
                    
                        <!-- Register buttons -->
                        <div class="text-center">
                            <p>Don't have an account? <a href="/sign-up">Register</a></p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<?php
  $content = ob_get_clean(); // Capture content into $content variable
  include 'base.php'; // Include base template
?>