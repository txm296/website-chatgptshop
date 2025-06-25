<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../login.php');
    exit;
}
require '../inc/db.php';
require '../inc/admin_header.php';
// ensure rechte column exists (for older db)
try { $pdo->query("SELECT rechte FROM admins LIMIT 1"); }
catch (PDOException $e) { $pdo->exec("ALTER TABLE admins ADD COLUMN rechte TEXT"); }

// define available rights
$allRights = [
    'add_products' => 'Produkte hinzufügen',
    'edit_prices' => 'Preise ändern',
    'edit_products' => 'Produkte anpassen',
    'manage_categories' => 'Kategorien verwalten',
    'manage_orders' => 'Bestellungen einsehen',
    'edit_pages' => 'Seiten bearbeiten'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'add';
    $rechte = isset($_POST['rechte']) && is_array($_POST['rechte']) ? implode(',', $_POST['rechte']) : '';
    if ($action === 'add') {
        $stmt = $pdo->prepare("INSERT INTO admins (username, passwort, rechte) VALUES (?,?,?)");
        $stmt->execute([
            $_POST['username'] ?? '',
            password_hash($_POST['passwort'] ?? '', PASSWORD_DEFAULT),
            $rechte
        ]);
    } elseif ($action === 'update' && isset($_POST['id'])) {
        $id = intval($_POST['id']);
        if (!empty($_POST['passwort'])) {
            $stmt = $pdo->prepare("UPDATE admins SET passwort=?, rechte=? WHERE id=?");
            $stmt->execute([
                password_hash($_POST['passwort'], PASSWORD_DEFAULT),
                $rechte,
                $id
            ]);
        } else {
            $stmt = $pdo->prepare("UPDATE admins SET rechte=? WHERE id=?");
            $stmt->execute([$rechte, $id]);
        }
        // Wenn eigener Account bearbeitet wurde, Session updaten
        if ($id === intval($_SESSION['admin'])) {
            $_SESSION['rechte'] = $rechte ? explode(',', $rechte) : [];
        }
    } elseif ($action === 'delete' && isset($_POST['id'])) {
        $id = intval($_POST['id']);
        if ($id !== intval($_SESSION['admin'])) {
            $pdo->prepare("DELETE FROM admins WHERE id=?")->execute([$id]);
        }
    }
}

$admins = $pdo->query("SELECT * FROM admins ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Mitarbeiter verwalten – nezbi Admin</title>
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
    <link href="https://fonts.googleapis.com/css?family=Inter:400,600&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 text-gray-900">
    <?php admin_header('mitarbeiter'); ?>
    <main class="max-w-5xl mx-auto px-4 py-10">
        <h1 class="text-2xl font-bold mb-8">Mitarbeiter</h1>
        <form method="post" class="bg-white shadow rounded-xl p-6 mb-10">
            <input type="hidden" name="action" value="add">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input name="username" class="border px-3 py-2 rounded" placeholder="Benutzername" required>
                <input name="passwort" type="password" class="border px-3 py-2 rounded" placeholder="Passwort" required>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <?php foreach ($allRights as $key => $label): ?>
                    <label class="flex items-center gap-2"><input type="checkbox" name="rechte[]" value="<?= $key ?>"> <?= $label ?></label>
                <?php endforeach; ?>
            </div>
            <button class="mt-4 px-5 py-2 rounded-xl bg-blue-600 text-white">Mitarbeiter anlegen</button>
        </form>
        <div class="overflow-x-auto">
        <table class="w-full bg-white shadow rounded-xl min-w-max">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 text-left">ID</th>
                    <th class="p-2 text-left">Benutzer</th>
                    <th class="p-2 text-left">Rechte</th>
                    <th class="p-2">Aktionen</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($admins as $a): $rights = $a['rechte'] ? explode(',', $a['rechte']) : []; ?>
                <form id="u<?= $a['id'] ?>" method="post"></form>
                <tr class="border-t">
                    <td class="p-2"><?= $a['id'] ?></td>
                    <td class="p-2"><?= htmlspecialchars($a['username']) ?></td>
                    <td class="p-2">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        <?php foreach ($allRights as $key => $label): ?>
                            <label class="flex items-center gap-1">
                                <input form="u<?= $a['id'] ?>" type="checkbox" name="rechte[]" value="<?= $key ?>" <?= in_array($key, $rights) ? 'checked' : '' ?>>
                                <span><?= $label ?></span>
                            </label>
                        <?php endforeach; ?>
                        </div>
                        <input form="u<?= $a['id'] ?>" type="password" name="passwort" placeholder="Passwort neu" class="border px-2 py-1 rounded mt-2">
                        <input form="u<?= $a['id'] ?>" type="hidden" name="action" value="update">
                        <input form="u<?= $a['id'] ?>" type="hidden" name="id" value="<?= $a['id'] ?>">
                    </td>
                    <td class="p-2">
                        <button form="u<?= $a['id'] ?>" class="px-3 py-1 bg-blue-600 text-white rounded mr-2">Speichern</button>
                        <?php if ($a['id'] != $_SESSION['admin']): ?>
                        <form method="post" onsubmit="return confirm('Account wirklich löschen?')" class="inline">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $a['id'] ?>">
                            <button class="px-3 py-1 bg-red-600 text-white rounded">Löschen</button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </main>
</body>
</html>
