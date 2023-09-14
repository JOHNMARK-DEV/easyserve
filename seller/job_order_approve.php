<?php
	include 'includes/session.php';

	if(isset($_POST['approve'])){
		$id = $_POST['job_id'];
		echo $id;
		$conn = $pdo->open();

		try{
			$stmt = $conn->prepare("UPDATE job_order SET status='A' WHERE id=:id");
			$stmt->execute(['id'=>$id]);

			$_SESSION['success'] = 'Service Approved successfully';
		}
		catch(PDOException $e){
			$_SESSION['error'] = $e->getMessage();
		}

		$pdo->close();
	}
	else{
		$_SESSION['error'] = 'Select services to Approved first';
	}

	header('location: job_order.php');
	
?>