<?php
require_once 'includes/config/config.php';
session_unset();
session_destroy();
header("Location: signin.php?success=loggedout");
exit();
