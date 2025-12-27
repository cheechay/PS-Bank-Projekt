<?php
session_start();
$firstname = $_SESSION['firstname'] ?? '';
$lastname = $_SESSION['lastname'] ?? '';
$amount = $_SESSION['amount'] ?? '';
$id = $_SESSION['id'] ?? '';



// Check if user is logged in
if (!isset($_SESSION['id'])) {
    die("Not logged in");
}

$userId = $_SESSION['id'];
$amountToTransfer = (int) ($_POST['amount'] ?? 0);
$usersID = (int) ($_POST['usersID'] ?? 0);

if ($amountToTransfer <= 0) {
    die("Invalid amount");
}

try {
    $db = new PDO("sqlite:bank.db");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Update user balance
    $stmt = $db->prepare("UPDATE form SET amount = amount - :amt WHERE id = :id");
    $stmt->execute([
        ':amt' => $amountToWithdraw,
        ':id' => $userId
    ]);

    // Update session amount (optional)
    $_SESSION['amount'] -= $amountToWithdraw;

    echo "Successfully withdraw  $amountToWithdraw ";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>