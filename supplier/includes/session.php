<?php
	include '../includes/conn.php';
	session_start();

	if(!isset($_SESSION['supplier']) || trim($_SESSION['supplier']) == ''){
		header('location: ../index.php');
		exit();
	}

	$conn = $pdo->open();

	$stmt = $conn->prepare("SELECT * FROM users WHERE id=:id");
	$stmt->execute(['id'=>$_SESSION['supplier']]);
	$admin = $stmt->fetch();

	$pdo->close();

?>