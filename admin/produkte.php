<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../login.php');
    exit;
}
$rights = $_SESSION['rechte'] ?? [];
if (!in_array('add_products', $rights) && !in_array('edit_products', $rights) && !in_array('edit_prices', $rights)) {
    echo '<p class="p-4">Keine Berechtigung.</p>';
    exit;
}
require '../inc/db.php';
require '../inc/admin_header.php';
// Automatische Aktualisierung der Datenbank um neue Spalten
try { $pdo->query("SELECT rabatt FROM produkte LIMIT 1"); }
catch (PDOException $e) { if (strpos($e->getMessage(),'rabatt')!==false) { $pdo->exec("ALTER TABLE produkte ADD COLUMN rabatt DECIMAL(10,2) DEFAULT NULL"); } }
try { $pdo->query("SELECT kategorie_id FROM produkte LIMIT 1"); }
catch (PDOException $e) { if (strpos($e->getMessage(),'kategorie_id')!==false) { $pdo->exec("ALTER TABLE produkte ADD COLUMN kategorie_id INT DEFAULT NULL"); } }
try { $pdo->query("SELECT id FROM kategorien LIMIT 1"); }
catch (PDOException $e) { $pdo->exec("CREATE TABLE IF NOT EXISTS kategorien (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) NOT NULL)"); }

// Ältere Datenbanken automatisch um fehlende Spalten erweitern
try {
    $pdo->query("SELECT menge FROM produkte LIMIT 1");
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'menge') !== false) {
        // Spalte `menge` nachrüsten, falls sie in bestehenden Installationen fehlt
        $pdo->exec("ALTER TABLE produkte ADD COLUMN menge INT DEFAULT NULL");
    }
}

// Aktionen für Produkte verarbeiten
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'add';
    if ($action === 'add') {
        $stmt = $pdo->prepare("INSERT INTO produkte (name, beschreibung, preis, rabatt, bild, menge, aktiv, kategorie_id) VALUES (?,?,?,?,?,?,?,?)");
        $menge = isset($_POST['menge']) && $_POST['menge'] !== '' ? intval($_POST['menge']) : null;
        $rabatt = isset($_POST['rabatt']) && $_POST['rabatt'] !== '' ? floatval($_POST['rabatt']) : null;
        $kat = $_POST['kategorie_id'] !== '' ? intval($_POST['kategorie_id']) : null;
        $stmt->execute([
            $_POST['name'] ?? '',
            $_POST['beschreibung'] ?? '',
            floatval($_POST['preis'] ?? 0),
            $rabatt,
            $_POST['bild'] ?? '',
            $menge,
            isset($_POST['aktiv']) ? 1 : 0,
            $kat
        ]);
    } elseif ($action === 'update' && isset($_POST['id'])) {
        $stmt = $pdo->prepare("UPDATE produkte SET preis=?, rabatt=?, menge=?, kategorie_id=? WHERE id=?");
        $menge = isset($_POST['menge']) && $_POST['menge'] !== '' ? intval($_POST['menge']) : null;
        $rabatt = isset($_POST['rabatt']) && $_POST['rabatt'] !== '' ? floatval($_POST['rabatt']) : null;
        $kat = $_POST['kategorie_id'] !== '' ? intval($_POST['kategorie_id']) : null;
        $stmt->execute([
            floatval($_POST['preis'] ?? 0),
            $rabatt,
            $menge,
            $kat,
            intval($_POST['id'])
        ]);
    } elseif ($action === 'delete' && isset($_POST['id'])) {
        $stmt = $pdo->prepare("DELETE FROM produkte WHERE id=?");
        $stmt->execute([intval($_POST['id'])]);
    }
}

$produkte = $pdo->query("SELECT * FROM produkte ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$kategorien = $pdo->query("SELECT * FROM kategorien ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Produkte verwalten – nezbi Admin</title>
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
    <?php admin_header("produkte"); ?>
    <main class="max-w-5xl mx-auto px-4 py-10">
        <h1 class="text-2xl font-bold mb-8">Produkte</h1>
        <form method="post" class="bg-white shadow rounded-xl p-6 mb-10">
            <input type="hidden" name="action" value="add">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input name="name" class="border px-3 py-2 rounded" placeholder="Name" required>
                <input name="preis" type="number" step="0.01" class="border px-3 py-2 rounded" placeholder="Preis" required>
                <input name="rabatt" type="number" step="0.01" class="border px-3 py-2 rounded" placeholder="Rabattpreis (optional)">
                <input name="menge" type="number" min="0" class="border px-3 py-2 rounded" placeholder="Verfügbare Menge (optional)">
                <input name="bild" class="border px-3 py-2 rounded md:col-span-2" placeholder="Bildpfad z.B. assets/products/bild.jpg" required>
                <select name="kategorie_id" class="border px-3 py-2 rounded" required>
                    <option value="">Kategorie wählen</option>
                    <?php foreach ($kategorien as $kat): ?>
                        <option value="<?= $kat['id'] ?>"><?= htmlspecialchars($kat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <textarea name="beschreibung" class="border px-3 py-2 rounded md:col-span-2" placeholder="Beschreibung"></textarea>
                <label class="flex items-center gap-2 md:col-span-2"><input type="checkbox" name="aktiv" checked> Aktiv</label>
            </div>
            <button class="mt-4 px-5 py-2 rounded-xl bg-blue-600 text-white">Produkt hinzufügen</button>
        </form>
        <div class="overflow-x-auto">
        <table class="w-full bg-white shadow rounded-xl min-w-max">
            <thead class="bg-gray-100">
                <tr><th class="p-2 text-left">ID</th><th class="p-2 text-left">Name</th><th class="p-2">Preis</th><th class="p-2">Rabatt</th><th class="p-2">Menge</th><th class="p-2">Kategorie</th><th class="p-2">Aktiv</th><th class="p-2">Aktionen</th></tr>
            </thead>
            <tbody>
                <?php foreach ($produkte as $p): ?>
                <form id="u<?= $p['id'] ?>" method="post"></form>
                <tr class="border-t">
                    <td class="p-2"><?= $p['id'] ?></td>
                    <td class="p-2"><?= htmlspecialchars($p['name']) ?></td>
                    <td class="p-2">
                        <input form="u<?= $p['id'] ?>" type="hidden" name="action" value="update">
                        <input form="u<?= $p['id'] ?>" type="hidden" name="id" value="<?= $p['id'] ?>">
                        <input form="u<?= $p['id'] ?>" name="preis" type="number" step="0.01" value="<?= number_format($p['preis'],2,'.','') ?>" class="border px-2 py-1 rounded w-24 text-right">
                    </td>
                    <td class="p-2">
                        <input form="u<?= $p['id'] ?>" name="rabatt" type="number" step="0.01" value="<?= htmlspecialchars($p['rabatt']) ?>" class="border px-2 py-1 rounded w-24 text-right" placeholder="-">
                    </td>
                    <td class="p-2">
                        <input form="u<?= $p['id'] ?>" name="menge" type="number" min="0" value="<?= htmlspecialchars($p['menge']) ?>" class="border px-2 py-1 rounded w-20" placeholder="frei">
                    </td>
                    <td class="p-2">
                        <select form="u<?= $p['id'] ?>" name="kategorie_id" class="border px-2 py-1 rounded">
                            <option value="">-</option>
                            <?php foreach ($kategorien as $kat): ?>
                                <option value="<?= $kat['id'] ?>" <?= $p['kategorie_id']==$kat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($kat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td class="p-2 text-center"><?= $p['aktiv'] ? 'Ja' : 'Nein' ?></td>
                    <td class="p-2">
                        <button form="u<?= $p['id'] ?>" class="px-3 py-1 bg-blue-600 text-white rounded mr-2">Speichern</button>
                        <form method="post" onsubmit="return confirm('Produkt wirklich l\u00f6schen?')" class="inline">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $p['id'] ?>">
                            <button class="px-3 py-1 bg-red-600 text-white rounded">Löschen</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </main>
</body>
</html>
