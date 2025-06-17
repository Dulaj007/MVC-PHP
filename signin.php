<?php
require_once 'includes/config/config.php'; // Secure session logic

if (isset($_SESSION['user'])) {
    header("Location: profile.php");
    exit();
}
?>
<?php require_once 'includes/views/partials/header.php'; ?>
<?php require_once 'includes/views/partials/navbar.php'; ?>

<div class="container">
  <h2 class="form-title">Sign In to Your Account</h2>

  <form action="includes/signin.inc.php" method="POST" class="signup-form" novalidate>
    
    <div class="form-group">
      <label for="input1">Username or Email</label>
      <input type="text" name="input1" id="input1" required>
      <div class="error-tooltip error-msg" id="input1_error"></div>
    </div>

    <div class="form-group">
      <label for="password">Password</label>
      <input type="password" name="password" id="password" required>
      <div class="error-tooltip error-msg" id="password_error"></div>
    </div>

    <?php if (isset($_GET['error'])): ?>
      <div class="error-msg">
        <?php
          switch ($_GET['error']) {
            case 'emptyfields':
              echo "Please fill in all fields.";
              break;
            case 'invalidcredentials':
              echo "Incorrect username/email or password.";
              break;
            case 'lockedout':
              echo "Too many failed attempts. Try again after 6 hours.";
              break;
            case 'toomanyattempts':
              echo "You have 2 attempts left.";
              break;
            case 'invalidaccess':
              echo "Access denied.";
              break;
            default:
              echo "Something went wrong. Please try again.";
          }
        ?>
      </div>
    <?php endif; ?>

    <?php if (isset($_GET['success']) && $_GET['success'] === 'accountcreated'): ?>
      <div class="error-msg" style="color: green;">Account created successfully! You can now sign in.</div>
    <?php endif; ?>

    <input type="submit" value="Sign In" class="btn-submit">
  </form>
</div>

<?php require_once 'includes/views/partials/footer.php'; ?>
<script src="assets/js/signinValidation.js"></script>
