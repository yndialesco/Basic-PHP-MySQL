<?php
// Include the database connection file
session_start();
include "service/database.php";
$login_messsage = "";

if (isset($_SESSION["isLogin"])) {
    header("location: dashboard.php");
}

if(isset($_POST['login'])) {
    // Sanitize user input
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password']);
    
    // Prepare SQL statement using prepared statement
    $sql = "SELECT * FROM users WHERE username=? AND password=?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there is a matching user
    if($result->num_rows > 0) {
        // Redirect to dashboard upon successful login
        $data = $result->fetch_assoc();
        $_SESSION["username"] = $data["username"];
        $_SESSION["isLogin"] = true;
        header("location: dashboard.php");
        exit(); // Make sure to stop script execution after redirection
    } else {
        $login_messsage = "Account can't be found, please register.😏";
    }
    $db->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
</head>
<body>
    <?php include "layout/header.html"?>
    <h3>Sign In</h3>
    <i><?=$login_messsage?></i>
    <form action="login.php" method="POST">
        <input type="text" placeholder="Username" name="username"/>
        <br>
        <input type="password" placeholder="Password" name="password"/>
        <br>
        <button type="submit" name="login">Sign in now</button>
    </form>
    <?php include "layout/footer.html"?>
</body>
</html>
