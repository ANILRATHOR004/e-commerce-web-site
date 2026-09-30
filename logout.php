<?php
session_start(); // Purani session shuru karein
session_unset(); // Saare session variables (jaise Rahul ka naam) mita dein
session_destroy(); // Poora session khatam kar dein

// Ab user ko seedha Login page par bhej dein
header("Location: login.php");
exit();
?>