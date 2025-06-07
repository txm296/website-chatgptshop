<?php
session_start();
require 'inc/db.php';
require 'inc/settings.php';
$siteSettings = load_settings();
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username=?");
    $stmt->execute([$_POST['username']]);
    $admin = $stmt->fetch();
    if ($admin && password_verify($_POST['passwort'], $admin['passwort'])) {
        $_SESSION['admin'] = $admin['id'];
        header('Location: admin/dashboard.php'); exit;
    } else {
        $error = "Login fehlgeschlagen.";
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Admin-Login – nezbi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          fontFamily: {
            sans: ['Inter', 'system-ui', 'sans-serif'],
          }
        }
      }
    </script>
    <?php $f = $siteSettings['font_family'] ?? 'Inter'; $fLink = str_replace(' ', '+', $f); ?>
    <link href="https://fonts.googleapis.com/css?family=<?= $fLink ?>:400,600&display=swap" rel="stylesheet">
    <style>
      body { font-family: '<?= htmlspecialchars($f) ?>', sans-serif; }
      :root { --accent-color: <?= htmlspecialchars($siteSettings['primary_color']) ?>; }
      .accent-bg { background-color: var(--accent-color); }
      .accent-bg-hover:hover { background-color: var(--accent-color); }
    </style>
</head>
<body class="bg-gray-50 text-gray-900">
<form method="post" class="max-w-sm mx-auto mt-20 bg-white p-8 rounded-2xl shadow">
    <div class="text-2xl font-bold mb-6 text-center">Admin-Login</div>
    <?php if ($error): ?><div class="mb-4 text-red-600"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <input type="text" name="username" class="block w-full mb-4 px-4 py-2 border rounded" placeholder="Benutzername" required>
    <input type="password" name="passwort" class="block w-full mb-6 px-4 py-2 border rounded" placeholder="Passwort" required>
    <button class="w-full px-5 py-2 rounded-xl accent-bg text-white font-semibold accent-bg-hover">Anmelden</button>
</form>
</body>
</html>
