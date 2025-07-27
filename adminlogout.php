<?php
session_start();
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

// Prevent back button from accessing previous pages
echo "<script>
         window.location.href = 'adminDashboard.php';
         window.history.replaceState(null, null, window.location.href);
      </script>";
exit();
?>