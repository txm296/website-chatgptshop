<?php
session_start();
if(!isset($_SESSION['admin'])){ http_response_code(403); exit('Forbidden'); }
require __DIR__.'/../inc/db.php';
$input=json_decode(file_get_contents('php://input'),true);
if(!$input || !isset($input['name']) || !isset($input['html'])){
    http_response_code(400);
    echo json_encode(['error'=>'invalid']);
    exit;
}
$stmt=$pdo->prepare('INSERT INTO builder_templates (name,html) VALUES (?,?)');
$stmt->execute([$input['name'],$input['html']]);
echo json_encode(['id'=>$pdo->lastInsertId()]);
