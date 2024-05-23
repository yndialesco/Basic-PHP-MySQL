<?php
session_start();
$register_message = "";
include "service/database.php";

if (isset($_SESSION["isLogin"])) {
    header("location: dashboard.php");
}

if(isset($_POST['register'])) {
    // Validate input
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    // Check if username already exists
    $check_username_sql = "SELECT * FROM users WHERE username = ?";
    $check_stmt = $db->prepare($check_username_sql);
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    if($check_result->num_rows > 0) {
        $register_message = "Username already exists. Please choose another one.😔";
    } else {
        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and execute the SQL statement to insert new user
        $insert_sql = "INSERT INTO users (username, password) VALUES (?, ?)";
        $insert_stmt = $db->prepare($insert_sql);
        $insert_stmt->bind_param("ss", $username, $hashed_password);
        if($insert_stmt->execute()) {
            $register_message = "Your account was successfully registered!😍 Please sign in...";
        } else {
            $register_message = "Failed to register your account. Please try again.😔";
        }
    }
    $db->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php include "layout/header.html"?>
    <h3>Sign Up</h3>
    <i><?=$register_message?></i>
    <form action="regist.php" method="POST">
        <input type="text" placeholder="username" name="username"/>
        <br>
        <input type="password" placeholder="password" name="password"/>
        <br>
        <button type="submit" name="register">Sign Up Now</button>
    </form>
    <?php include "layout/footer.html"?>
</body>
</html>