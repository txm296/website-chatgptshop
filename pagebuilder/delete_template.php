<?php
session_start();
if(!isset($_SESSION['admin'])){ http_response_code(403); exit('Forbidden'); }
require __DIR__.'/../inc/db.php';
$id=isset($_POST['id'])?intval($_POST['id']):(isset($_GET['id'])?intval($_GET['id']):0);
if($id){
    $stmt=$pdo->prepare('DELETE FROM builder_templates WHERE id=?');
    $stmt->execute([$id]);
}
echo json_encode(['success'=>true]);
