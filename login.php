<?php
require_once "config.php";

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Tymczasowo loginy w pliku XML (przykład)
    $xml = simplexml_load_file('dane.xml');
    $found = false;
    foreach ($xml->user as $u) {
        if ((string)$u->username === $username && (string)$u->password === $password) {
            $found = true;
            break;
        }
    }

    if ($found) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        header('Location: index.php');
        exit;
    } else {
        $message = "Nieprawidłowy login lub hasło";
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Logowanie</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Logowanie</h1>
    <?php if($message) echo "<p>$message</p>"; ?>
    <form method="post">
        <label>Username:<br><input type="text" name="username" required></label><br><br>
        <label>Password:<br><input type="password" name="password" required></label><br><br>
        <input type="submit" value="Zaloguj">
    </form>
</body>
</html>
