<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emailHome = $_POST['emailHome'];
    $inputPassword = $_POST['passwordHome'];

    $servername = "localhost";
    $username = "root";
    $password = "";

    session_start(); // Start session to pass data across pages

    try {
        $conn = new PDO("mysql:host=$servername;dbname=bank", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT firstname, lastname, amount, passwort FROM form 
            WHERE email = :emailHome  And passwort = :passwordHome";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':emailHome' => $emailHome,
            ':passwordHome' => $inputPassword

        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Store in session
            $_SESSION['firstname'] = $user['firstname'];
            $_SESSION['lastname'] = $user['lastname'];
            $_SESSION['amount'] = $user['amount'];

            // Redirect to index
            header("Location: Profile.php");
            exit();
        } else {
            echo "
        <script>
            alert('Invalid login !!!');
            window.location.href='login.html';
        </script>";
        }

    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}
?>