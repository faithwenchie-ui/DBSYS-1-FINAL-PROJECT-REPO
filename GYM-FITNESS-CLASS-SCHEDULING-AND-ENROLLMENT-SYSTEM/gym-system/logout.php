<?php

// End the current session and log the user out of the system.
session_start();

// Remove all session data and destroy the session cookie.
session_destroy();

// Send the user back to the login screen.
header("Location: login.php");

exit();

?>