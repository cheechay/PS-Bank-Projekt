<?php
session_start();
$firstname = $_SESSION['firstname'] ?? '';
$lastname = $_SESSION['lastname'] ?? '';
$amount = $_SESSION['amount'] ?? '';
$id = $_SESSION['id'] ?? '';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PS-Bank Konto</title>
    <link rel="stylesheet" href="profile.css">
</head>

<body>
    <div class="container">
        <h1>Hello <?php echo $firstname; ?>! Welcome to your Account </h1>
        <div class="konto-area">

            <div class="left">
                <button name="withdraw" value="1" type="submit" onclick="getWithdraw()">
                    <img src="withdraw.png" alt="PNG withdrawal">
                    <label for="withdrawal">Cash Withdrawal</label>
                </button>
            </div>
            <div class="right">
                <button name="deposit" value="1" type="submit" onclick="getDeposit()">
                    <img src="deposit.png" alt="PNG Deposit">
                    <label for="Deposit">Deposit</label>
                </button>
            </div>
            <div class="left">

                <button name="balance" type="submit" onclick="getAmount()">
                    <img src="Balance+.png" alt="PNG Balance"> <!-- need to change the icon-->
                    <label for="Balance Check">Balance Check</label>
                </button>

            </div>
            <div class="right">
                <button name="transfer" value="1" type="submit" onclick="getTransfer()">
                    <img src="trasfer.png" alt="PNG Transfer">
                    <label for="Money Transfer">Money Transfer</label>
                </button>
            </div>

        </div>


    </div>
</body>

</html>

<script>
    function getAmount() {
        alert('Your balance is ' + <?php echo json_encode($amount); ?>);
    }

    function getDeposit() {
        let depositAmount = prompt("Please enter the amount to be deposited");

        if (depositAmount != null && depositAmount.trim() !== "") {
            fetch('deposit.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'amount=' + encodeURIComponent(depositAmount)
            })
                .then(response => response.text())
                .then(data => {
                    alert("Server says: " + data);
                    // Optional: reload page or update UI
                    location.reload();
                })
                .catch(error => console.error(error));
        } else {
            alert("No amount entered");
        }
    }
    function getWithdraw() {
        let withdrawAmount = prompt("Please enter the amount to be deposited");

        if (withdrawAmount != null && withdrawAmount.trim() !== "") {
            fetch('withdraw.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'amount=' + encodeURIComponent(withdrawAmount)
            })
                .then(response => response.text())
                .then(data => {
                    alert("Server says: " + data);
                    // Optional: reload page or update UI
                    location.reload();
                })
                .catch(error => console.error(error));
        } else {
            alert("No amount entered");
        }
    }
    function getTransfer() {
        // Prompt for the transfer amount and the usersID
        let transferAmount = prompt("Please enter the amount to be transferred");
        let usersID = prompt("Please enter the UserID");

        // Check if both values are not null or empty
        if (transferAmount != null && usersID != null && transferAmount.trim() !== "" && usersID.trim() !== "") {
            // Send the data using fetch to 'transfer.php'
            fetch('transfer.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'amount=' + encodeURIComponent(transferAmount) +
                    '&usersID=' + encodeURIComponent(usersID)
            })
                .then(response => response.text())
                .then(data => {
                    // Show the server response
                    alert("Server says: " + data);
                    // Optionally: reload page or update UI based on response
                    location.reload(); // Refreshes the page after the transfer
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Something went wrong.');
                });
        } else {
            // Handle case where one of the inputs is invalid
            if (usersID == null || usersID.trim() === "") {
                alert("Invalid UserID");
            } else if (transferAmount == null || transferAmount.trim() === "") {
                alert("Invalid Amount");
            }
        }
    }








</script>