<?php
session_start();

// Check if session variables are set
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

// Validate inputs
if ($amountToTransfer <= 0 || $usersID < 1) {
    die("Invalid input.");
}

if ($amountToTransfer > $amount) {
    die("Insufficient funds.");
}
if ($usersID == $userId) {
    die("Invalid input.");
}

// Check if the recipient user exists in the database
try {
    // Create a new database connection
    $db = new PDO("sqlite:bank.db");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the recipient exists
    $stmtCheck = $db->prepare("SELECT * FROM form WHERE id = :usersID AND id<> :userId");
    $stmtCheck->execute([
        ':usersID' => $usersID,
        ':userId' => $userId
    ]);
    $recipient = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if (!$recipient) {
        die("User ID $usersID not found.");
    }

    // Start a transaction to ensure both updates happen atomically
    $db->beginTransaction();

    // Update sender's balance
    $stmt1 = $db->prepare("UPDATE form SET amount = amount - :amt WHERE id = :id");
    $stmt1->execute([
        ':amt' => $amountToTransfer,
        ':id' => $userId
    ]);

    // Check if the first query affected any rows
    if ($stmt1->rowCount() == 0) {
        echo "No rows updated for sender (ID: $userId). Make sure the user exists and has sufficient funds.";
        die();
    }

    // Update receiver's balance
    $stmt2 = $db->prepare("UPDATE form SET amount = amount + :amt WHERE id = :usersID");
    $stmt2->execute([
        ':amt' => $amountToTransfer,
        ':usersID' => $usersID
    ]);

    // Check if the second query affected any rows
    if ($stmt2->rowCount() == 0) {
        echo "No rows updated for receiver (ID: $usersID). Make sure the receiver exists.";
        die();
    }

    // Commit the transaction if both queries are successful
    $db->commit();

    // Update session amount (optional)
    $_SESSION['amount'] -= $amountToTransfer;

    echo "Successfully transferred $amountToTransfer to user ID $usersID 😊.";

} catch (PDOException $e) {
    // Rollback the transaction in case of any error
    $db->rollBack();
    echo "Error: " . $e->getMessage();
}
?>