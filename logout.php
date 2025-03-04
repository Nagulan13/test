<?php
session_start();
session_destroy();
echo "<script>
    alert('You have successfully logged out!');
    window.location.href = 'index.php'; // Redirect to login page
</script>";
?>
