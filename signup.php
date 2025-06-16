<?php require_once 'includes/views/partials/header.php'; ?>
<?php require_once 'includes/views/partials/navbar.php'; ?>

<div class="container">
  <h2 class="form-title">Create Your Account</h2>
  <form action="includes/controllers/signup.controller.php" method="POST" class="signup-form" novalidate>

    <div class="form-group">
      <label for="first_name">First Name</label>
      <input type="text" name="first_name" id="first_name" required>
      <div class="error-tooltip error-msg" id="first_name_error"></div>
    </div>

    <div class="form-group">
      <label for="last_name">Last Name</label>
      <input type="text" name="last_name" id="last_name" required>
      <div class="error-tooltip error-msg" id="last_name_error"></div>
    </div>

    <div class="form-group">
      <label for="username">Username</label>
      <input type="text" name="username" id="username" required pattern="^[a-zA-Z0-9_]{4,20}$">
      <div class="error-tooltip error-msg" id="username_error"></div>
    </div>

    <div class="form-group">
      <label for="email">Email Address</label>
      <input type="email" name="email" id="email" required>
      <div class="error-tooltip error-msg" id="email_error"></div>
    </div>

    <div class="form-group">
      <label for="password">Password</label>
      <input type="password" name="password" id="password" required minlength="8">
            <ul class="password-requirements hidden error-tooltip password-tooltip" id="password-reqs">

                <li id="length-check" class="invalid"></li>
                <li id="uppercase-check" class="invalid"></li>
                <li id="number-check" class="invalid"></li>
                <li id="special-check" class="invalid"></li>
            </ul>
        <div class="error-msg" id="password_error"></div>
    </div>

    <div class="form-group">
      <label for="confirm_password">Re-enter Password</label>
      <input type="password" name="confirm_password" id="confirm_password" required>
      <div class="error-tooltip error-msg" id="confirm_password_error"></div>
    </div>

    <div class="form-group">
      <label for="dob">Date of Birth</label>
      <input type="date" id="dob" name="dob" required>
      <div class="error-tooltip error-msg" id="dobError"></div>
    </div>

    <div class="form-group">
      <label for="referral">Referral Code (optional)</label>
      <input type="text" name="referral" id="referral">
      <div class="error-tooltip error-msg" id="referral_error"></div>
    </div>

    <div class="form-group">
      <label for="phone">Phone Number</label>
      <div class="phone-group">
        <select name="country_code" id="country_code" required>
          <option value="+94">🇱🇰 Sri Lanka (+94)</option>
          <option value="+1">🇺🇸 USA (+1)</option>
          <option value="+44">🇬🇧 UK (+44)</option>
          <option value="+91">🇮🇳 India (+91)</option>
        </select>
        <input type="tel" name="phone" id="phone" required>
      </div>
      <div class=" error-msg" id="phone_error"></div>
    </div>

    <div class="form-group">
      <label for="account_type">Account Type</label>
      <select name="account_type" id="account_type" required>
        <option value="" disabled selected>Choose Account Type</option>
        <option value="A">Account A</option>
        <option value="B">Account B</option>
        <option value="C">Account C</option>
      </select>
      <div class="error-tooltip error-msg" id="account_type_error"></div>
    </div>

    <div class="form-group">
      <!-- Google reCAPTCHA -->
      <div class="g-recaptcha" data-sitekey="6Lc4X2IrAAAAAKFO2Kmcql_beV0robdSCGRE0bPM"></div>
      <div class="error-tooltip error-msg" id="recaptcha_error"></div>
    </div>

    <input type="submit" value="Sign Up" class="btn-submit">
  </form>
</div>

<?php require_once 'includes/views/partials/footer.php'; ?>

<!-- Load Google reCAPTCHA script -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script src="assets/js/signupValidation.js"></script>