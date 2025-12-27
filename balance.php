<?php
session_start();

$firstname = $_SESSION['firstname'] ?? '';
$lastname = $_SESSION['lastname'] ?? '';
$amount = $_SESSION['amount'] ?? '';
$id = $_SESSION['id'] ?? '';

echo "
<script>
    alert(' Your balance is ' + " . json_encode($amount) . ");
    window.location.href='Profile.php';
</script>";
?>