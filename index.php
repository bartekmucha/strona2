<?php
require_once "config.php";

if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

// Informacje o użytkowniku
$userAgent = $_SERVER['HTTP_USER_AGENT'];
$ipSource = $_SERVER['REMOTE_ADDR'];
$ipTarget = $_SERVER['SERVER_ADDR'];

// Pobieranie danych z bazy (na razie pokażemy jak, tabela może być pusta)
$stmt = $pdo->query("SELECT imie, nazwisko, wiek, telefon, adres FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="UTF-8">
<title>Panel główny</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="header">
    <p><strong>User-Agent:</strong> <?= htmlspecialchars($userAgent) ?></p>
    <p><strong>IP źródła:</strong> <?= htmlspecialchars($ipSource) ?></p>
    <p><strong>IP serwera:</strong> <?= htmlspecialchars($ipTarget) ?></p>
    <p><a href="logout.php">Wyloguj</a></p>
</div>

<h2>Lista użytkowników</h2>
<table>
<tr>
    <th>Imię</th>
    <th>Nazwisko</th>
    <th>Wiek</th>
    <th>Telefon</th>
    <th>Adres</th>
</tr>
<?php foreach ($users as $u): ?>
<tr>
    <td><?= htmlspecialchars($u['imie']) ?></td>
    <td><?= htmlspecialchars($u['nazwisko']) ?></td>
    <td><?= htmlspecialchars($u['wiek']) ?></td>
    <td><?= htmlspecialchars($u['telefon']) ?></td>
    <td><?= htmlspecialchars($u['adres']) ?></td>
</tr>
<?php endforeach; ?>
</table>
</body>
</html>
