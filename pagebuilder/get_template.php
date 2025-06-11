<?php
session_start();
if(!isset($_SESSION['admin'])){ http_response_code(403); exit('Forbidden'); }
require __DIR__.'/../inc/db.php';
$id=isset($_GET['id'])?intval($_GET['id']):0;
$stmt=$pdo->prepare('SELECT html FROM builder_templates WHERE id=?');
$stmt->execute([$id]);
$html=$stmt->fetchColumn();
if($html===false){ http_response_code(404); echo json_encode(['error'=>'not found']); }
else echo json_encode(['html'=>$html]);
