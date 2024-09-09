<?php
$title = 'Create Account'; 
ob_start(); 
?>


<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3 mt-5 mb-5">
            <div class="text-center mb-5 text-dark">Create Your Account</div>
                <div class="card">
                    <div class="card-body">
                        <form method="POST" id="signUpForm">
                            <?php if (isset($error)): ?>
                                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                            <?php endif; ?>
                            

                            <div data-mdb-input-init class="form-outline mb-4">
                                <input type="email" id="email" name="email" class="form-control form-control" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                                <label class="form-label" for="email">Email address</label>
                            </div>

                           
                            <div class="row mb-4 g-4">
                                <div class="col-md-6">
                                    <div data-mdb-input-init class="form-outline">
                                        <input type="text" id="first_name" name="first_name" class="form-control" value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>" required>
                                        <label class="form-label" for="first_name">First Name</label>
                                    </div>
                                </div>
                            
                               

                                <div class="col-md-6">
                                    <div data-mdb-input-init class="form-outline">
                                        <input type="text" id="last_name" name="last_name" class="form-control" value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>" required>
                                        <label class="form-label" for="last_name">Last Name</label>
                                    </div>
                                </div>

                               
                            </div>

                            <div data-mdb-input-init class="form-outline mb-4">
                                <input type="password" id="password" name="password" class="form-control" required>
                                <label class="form-label" for="password">Password</label>
                            </div>

                           

                            <div data-mdb-input-init class="form-outline mb-4">
                                <input type="password" id="password2" name="password2" class="form-control" required>
                                <label class="form-label" for="password2">Confirm Password</label>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block mb-4">Sign Up</button>

                            <div class="text-center">
                                <p>Already have an account? <a href="/login">Login</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
  $content = ob_get_clean(); // Capture content into $content variable
  include 'base.php'; // Include base template
?>