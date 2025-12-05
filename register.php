<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $passwort = $_POST['passwort'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $userInfo = "Congratulation you have a new account now.
    Your account number is 5";
    $subject = "Account successfully opened.";



    try {
        $conn = new PDO("mysql:host=$servername;dbname=bank", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "INSERT INTO form (Email, Passwort, firstname, lastname, gender) 
                VALUES (:email, :passwort, :firstname, :lastname, :gender)";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':email' => $email,
            ':passwort' => $passwort,
            ':firstname' => $firstname,
            ':lastname' => $lastname,
            ':gender' => $gender
        ]);

        mail("", $subject, $userInfo);
        header("location:Profile.php");
        echo "Connected and data inserted successfully!";
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}
?>