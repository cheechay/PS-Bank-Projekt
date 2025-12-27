<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $emailHome = $_POST['emailHome'];
    $inputPassword = $_POST['passwordHome'];

    session_start();

    try {
        // Connect (creates DB file if it does not exist)
        $conn = new PDO("sqlite:bank.db");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create table if it does not exist
        $conn->exec("
            CREATE TABLE IF NOT EXISTS form (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                firstname TEXT NOT NULL,
                lastname TEXT NOT NULL,
                email TEXT NOT NULL,
                passwort TEXT NOT NULL,
                gender TEXT NOT NULL,
                amount REAL DEFAULT 0
            )
        ");

        //Login query
        $sql = "SELECT firstname, lastname, amount, passwort,id
                FROM form
                WHERE email = :emailHome
                AND passwort = :passwordHome";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':emailHome' => $emailHome,
            ':passwordHome' => $inputPassword
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $_SESSION['firstname'] = $user['firstname'];
            $_SESSION['lastname'] = $user['lastname'];
            $_SESSION['amount'] = $user['amount'];
            $_SESSION['id'] = $user['id'];

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
        echo "Error: " . $e->getMessage();
    }
}
?>