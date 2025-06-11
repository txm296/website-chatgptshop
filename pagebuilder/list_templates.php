<?php
session_start();
if(!isset($_SESSION['admin'])){ http_response_code(403); exit('Forbidden'); }
require __DIR__.'/../inc/db.php';
$rows=$pdo->query('SELECT id,name FROM builder_templates ORDER BY name')->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($rows);
