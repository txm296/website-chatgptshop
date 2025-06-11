<?php
function get_builder_layout(PDO $pdo, $slug) {
    try {
        $stmt = $pdo->prepare('SELECT layout FROM builder_pages WHERE slug=?');
        $stmt->execute([$slug]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $data = json_decode($row['layout'], true);
            return $data['html'] ?? '';
        }
    } catch (Exception $e) {
        // ignore errors and fall back to default
    }
    return null;
}
?>
