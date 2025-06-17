<!-- index.php -->
<?php require_once 'includes/views/partials/header.php'; ?>
<?php require_once 'includes/views/partials/navbar.php'; ?>

<main class="main-content">
  <h1>Welcome to Our Secure PHP Website</h1>
  <p>Explore modern PHP MVC structure with highest security standards.</p>

  <div class="cards-wrapper">
    <?php
      $cardTitle = "Login";
      $cardDesc = "Access your account securely.";
      $cardLink = "signin.php";
      require 'includes/views/components/card.php';

      $cardTitle = "Sign Up";
      $cardDesc = "Create a new account with us.";
      $cardLink = "signup.php";
      require 'includes/views/components/card.php';
    ?>
  </div>

</main>

<?php require_once 'includes/views/partials/footer.php'; ?>
