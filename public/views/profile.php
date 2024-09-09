<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$title = 'Profile'; 
ob_start(); 

$section = isset($_GET['section']) ? $_GET['section'] : 'profile'; // Default to 'profile'

$city = isset($data['address']['city']) ? $data['address']['city'] : '';
$state = isset($data['address']['state']) ? $data['address']['state'] : '';
$country = isset($data['address']['country']) ? $data['address']['country'] : '';

$error = $_SESSION['profile_error'] ?? '';
unset($_SESSION['profile_error']); 

$success = $_SESSION['profile_success'] ?? '';
unset($_SESSION['profile_success']);

$update = $_SESSION['profile_update'] ?? '';
unset($_SESSION['profile_update']); 

$profile_update_error = $_SESSION['profile_update_error'] ?? '';
unset($_SESSION['profile_update_error']);



include 'auth.php';

// Check if user is authenticated
restrictAccess();

// Rest of your page content
?>




<div class="container-xl px-4 mt-4">
    <div class="row">
        <div class="col-xl-3">
            <div class="card mb-4">
                <div class="card-header">User Menu</div>
                <div class="list-group list-group-flush">
                    <a class="list-group-item list-group-item-action <?php echo ($section === 'profile') ? 'active' : ''; ?>" href="?section=profile">User Profile</a>
                    <a class="list-group-item list-group-item-action <?php echo ($section === 'change_password') ? 'active' : ''; ?>" href="?section=change_password">Change Password</a>
                    <a class="list-group-item list-group-item-action" href="/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout
                    </a>
                </div>
                <form id="logout-form" action="/logout" method="POST" style="display: none;">
                </form>
            </div>
        </div>
        <div class="col-xl-9">
            <!-- User Profile Section -->
            <?php if ($section === 'profile'): ?>
                <div class="card mb-4">
                    <div class="card-header">Account Details</div>
                    <div class="card-body">
                        <?php if ($update): ?>
                                <div class="alert alert-success">
                                    <?php echo htmlspecialchars($update); ?>
                                </div>
                        <?php endif; ?>

                        <?php if ($profile_update_error): ?>
                                <div class="alert alert-danger">
                                    <?php echo htmlspecialchars($profile_update_error); ?>
                                </div>
                        <?php endif; ?>
                        
                        <form method="POST" id="update-form" enctype="multipart/form-data">
                            <input type="hidden" name="update_profile" value="1">

                            <div class="form-group mb-3">
                                <label for="username">Username</label>
                                <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($data['user']['username']); ?>" disabled>
                            </div>
                            
                            
                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <label class="small mb-1" for="first_name">First name</label>
                                    <input class="form-control" id="first_name" name="first_name" type="text" value="<?= htmlspecialchars($data['user']['first_name']) ?>" placeholder="First name" required>
                                </div>
                            
                                <div class="col-md-6">
                                    <label class="small mb-1" for="last_name">Last name</label>
                                    <input class="form-control" id="last_name" name="last_name" type="text" value="<?= htmlspecialchars($data['user']['last_name']) ?>" placeholder="Last name" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="small mb-1" for="address_line">Address Line</label>
                                <input class="form-control" id="address_line" name="address_line" type="text" value="<?= htmlspecialchars($data['address']['address_line']) ?>" placeholder="Address" required>
                            </div>

                            <div class="row gx-3 mb-3">
                                <div class="col-md-4">
                                    <label for="city">City</label>
                                    <select id="city" name="city" class="form-control" required>
                                        <option value="" <?= empty($city) ? 'selected' : '' ?>>Select City</option>
                                        <option value="Cavite" <?= $city === 'Cavite' ? 'selected' : '' ?>>Cavite</option>
                                        <!-- Add other options here -->
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="state">State</label>
                                    <select id="state" name="state" class="form-control" required>
                                        <option value="" <?= empty($state) ? 'selected' : '' ?>>Select State</option>
                                        <option value="Gentri" <?= $state === 'Gentri' ? 'selected' : '' ?>>Gentri</option>
                                        <option value="Aliang" <?= $state === 'Aliang' ? 'selected' : '' ?>>Aliang</option>
                                        <option value="Conchu" <?= $state === 'Conchu' ? 'selected' : '' ?>>Conchu</option>
                                        <option value="De Ocampo" <?= $state === 'De Ocampo' ? 'selected' : '' ?>>De Ocampo</option>
                                        <option value="Indang" <?= $state === 'Indang' ? 'selected' : '' ?>>Indang</option>
                                        <!-- Add other options here -->
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="country">Country</label>
                                    <select id="country" name="country" class="form-control" required>
                                        <option value="" <?= empty($country) ? 'selected' : '' ?>>Select Country</option>
                                        <option value="Philippines" <?= $country === 'Philippines' ? 'selected' : '' ?>>Philippines</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="username">Phone Number</label>
                                <input type="text" id="phone_number" name="phone_number" class="form-control" value="<?php echo htmlspecialchars($data['user']['phone_number']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="small mb-1" for="email">Email address</label>
                                <input class="form-control" id="email" name="email" type="email" value="<?= htmlspecialchars($data['user']['email']) ?>" placeholder="Enter your email address" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Change Password Section -->
            <?php if ($section === 'change_password'): ?>
                
                <div class="card mb-4">
                    <div class="card-header">Change Password</div>
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger">
                                <?php echo htmlspecialchars($error); ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($success): ?>
                            <div class="alert alert-success">
                                <?php echo htmlspecialchars($success); ?>
                            </div>
                        <?php endif; ?>
                        <form method="POST" id="password-form">
                        <input type="hidden" name="change_password" value="1">
                            

                            <!-- Form Group (old password)-->
                            <div class="mb-3">
                                <label class="small mb-1" for="inputOldPassword">Old Password</label>
                                <input class="form-control" id="inputOldPassword" name="old_password" type="password" placeholder="Enter your old password" required>
                            </div>

                            <!-- Form Group (new password)-->
                            <div class="mb-3">
                                <label class="small mb-1" for="inputNewPassword">New Password</label>
                                <input class="form-control" id="inputNewPassword" name="new_password" type="password" placeholder="Enter your new password" required>
                            </div>

                            <!-- Form Group (confirm new password)-->
                            <div class="mb-3">
                                <label class="small mb-1" for="inputConfirmPassword">Confirm New Password</label>
                                <input class="form-control" id="inputConfirmPassword" name="confirm_password" type="password" placeholder="Confirm your new password" required>
                            </div>

                            <!-- Save changes button-->
                            <button class="btn btn-primary" type="submit">Change Password</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php
  $content = ob_get_clean(); // Capture content into $content variable
  include 'base.php'; // Include base template
?>
