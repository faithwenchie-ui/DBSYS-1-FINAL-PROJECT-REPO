<?php
session_start();
require_once '../db_connect.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $conn->prepare("DELETE FROM supplier WHERE supplier_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
header("Location: index.php");
exit();
?>