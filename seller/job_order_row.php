<?php 
	include 'includes/session.php';

	if(isset($_POST['id'])){
		$id = $_POST['id'];
		
		$conn = $pdo->open();

		$stmt = $conn->prepare("SELECT job_order.request_description as description, job_order.id AS prodid, products.name AS prodname, category.name AS catname FROM job_order INNER JOIN products on products.id = job_order.product_id LEFT JOIN category ON category.id=products.category_id WHERE job_order.id=:id");
		$stmt->execute(['id'=>$id]);
		$row = $stmt->fetch();
		
		$pdo->close();

		echo json_encode($row);
	}
?>