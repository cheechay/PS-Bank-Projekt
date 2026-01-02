<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $passwort = $_POST['passwort'];

    $userInfo = "Congratulation you have a new account now.\nYour account number is 5";
    $subject = "Account successfully opened.";

    try {
        // Connect to SQLite (creates bank.db if not exists)
        $conn = new PDO("sqlite:bank.db");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create table if not exists
        $conn->exec("
            CREATE TABLE IF NOT EXISTS form (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                email TEXT UNIQUE NOT NULL,
                passwort TEXT NOT NULL,
                firstname TEXT NOT NULL,
                lastname TEXT NOT NULL,
                gender TEXT,
                amount REAL DEFAULT 0
            )
        ");


        $sql = "INSERT INTO form (email, passwort, firstname, lastname, gender)
                VALUES (:email, :passwort, :firstname, :lastname, :gender)";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':email' => $email,
            ':passwort' => $passwort,
            ':firstname' => $firstname,
            ':lastname' => $lastname,
            ':gender' => $gender
        ]);

        echo "
            <script>
                alert('Account succesfully created !!!');
                window.location.href='login.html';
            </script>";

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>