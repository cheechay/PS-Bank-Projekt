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

        <div class="atm-layout">
            <!-- LEFT BUTTONS -->
            <div class="btn-leftside">
                <button class="atm-btn" name="withdraw" value="1" type="submit" onclick="getWithdraw()">
                    <img src="withdraw.png" alt="withdrawal">

                </button>

                <button class="atm-btn" name="balance" type="submit" onclick="getAmount()">
                    <img src="Balance+.png" alt="Balance">

                </button>
            </div>

            <!-- SCREEN -->
            <div class="konto-area" id="screen">
                <div class="menu-item left">🔷 Cash Withdrawal</div>
                <div class="menu-item right">Deposit 🔷</div>
                <div class="menu-item left">🔷 Balance Check</div>
                <div class="menu-item right">Money Transfer 🔷</div>
            </div>

            <!-- RIGHT BUTTONS -->
            <div class="btn-rightside">
                <button class="atm-btn" name="deposit" value="1" type="submit" onclick="getDeposit()">
                    <img src="deposit.png" alt="Deposit">

                </button>

                <button class="atm-btn" name="transfer" value="1" type="submit" onclick="getTransfer()">
                    <img src="trasfer.png" alt="Transfer">

                </button>
            </div>
        </div>

        <!-- CASH SLOT -->
        <div class="slot cash">
            <div class="slot-label">CASH</div>
            <div class="slot-mouth"></div>

            <div class="cash-stack">
                <div class="bill"><img src="5euro.png" alt="5euro"></div>
                <div class="bill"><img src="5euro.png" alt="5euro"></div>
                <div class="bill"><img src="5euro.png" alt="5euro"></div>
                <div class="bill"><img src="5euro.png" alt="5euro"></div>
            </div>
        </div>

        <a href="login.html"> <button class="back-btn">Abbrechen </button></a>

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
            dispenseCash();
            fetch('deposit.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'amount=' + encodeURIComponent(depositAmount)
            })
                .then(response => response.text())
                .then(data => {
                    alert(data);
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
            dispenseCash();
            fetch('withdraw.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'amount=' + encodeURIComponent(withdrawAmount)

            })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    dispenseCash();
                    location.reload();


                })
                .catch(error => console.error(error));
        } else {
            alert("No amount entered");
        }

    }
    function getTransfer() {
        let transferAmount = prompt("Please enter the amount to be transferred");
        let usersID = prompt("Please enter the UserID");

        // Check if both values are not null or empty
        if (transferAmount != null && usersID != null && transferAmount.trim() !== "" && usersID.trim() !== "") {
            // Send the data using fetch to 'transfer.php'
            dispenseCash();
            fetch('transfer.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'amount=' + encodeURIComponent(transferAmount) +
                    '&usersID=' + encodeURIComponent(usersID)
            })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Something went wrong.');
                });
        } else {

            if (usersID == null || usersID.trim() === "") {
                alert("Invalid UserID");
            } else if (transferAmount == null || transferAmount.trim() === "") {
                alert("Invalid Amount");
            }
        }
    }

    function dispenseCash() {
        const stack = document.querySelector(".cash-stack");
        if (!stack) return;

        // restart animation
        stack.classList.remove("dispense");
        void stack.offsetWidth; // force reflow
        stack.classList.add("dispense");

        // retract bills after 3 seconds
        setTimeout(() => {
            stack.classList.remove("dispense");
        }, 6000);
    }



</script>