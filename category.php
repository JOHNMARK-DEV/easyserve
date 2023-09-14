<?php include 'includes/session.php'; ?>
<?php
	$slug = $_GET['category'];

	$conn = $pdo->open();

	try{
		$stmt = $conn->prepare("SELECT * FROM category WHERE cat_slug = :slug");
		$stmt->execute(['slug' => $slug]);
		$cat = $stmt->fetch();
		$catid = $cat['id'];
	}
	catch(PDOException $e){
		echo "There is some problem in connection: " . $e->getMessage();
	}

	$pdo->close();

?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">

	<?php include 'includes/navbar.php'; ?>
	 
	  <div class="content-wrapper">
	    <div class="container">

	      <!-- Main content -->
	      <section class="content">
	        <div class="row">
	        	<div class="col-sm-9">
		            <h1 class="page-header"><?php echo $cat['name']; ?></h1>
		       		<?php
		       			
		       			$conn = $pdo->open();

		       			try{
		       			 	$inc = 3;	
						    $stmt = $conn->prepare("SELECT * FROM products LEFT JOIN users ON products.seller_id=users.id LEFT JOIN details ON products.id=details.product_id WHERE products.status='A' AND category_id = :catid");
						    $stmt->execute(['catid' => $catid]);
							echo "<div class='row'>";
						    foreach ($stmt as $row) {
						    	$image = (!empty($row['photo'])) ? 'images/'.$row['photo'] : 'images/noimage.jpg';
						    	// $inc = ($inc == 3) ? 1 : $inc + 1;
	       						if($inc == 1)
									echo "<div class='row'>";
								   if (strlen($row['description']) > 100) { 
									$description = substr($row['description'], 0,30) . " ...";
								}
	       						echo "
								   	<a href='product.php?product=".$row['slug']."'>
										<div class='col-sm-6 col-xs-6 col-md-4'>
											<div class='box box-solid'>
												<div class='box-body prod-body'>
												
													<h5>".$row['firstname']."</h5> 
													<div style='display:flex;justify-content:center'>
														<img src='".$image."' width='100%' height='110px' class='thumbnail rounded-circle' > 
													</div> 
													<h5>".$row['name']."</h5> 
													<div class='rate'>
														<span class='fa fa-star checked'></span>
														<span class='fa fa-star checked'></span>
														<span class='fa fa-star checked'></span>
														<span class='fa fa-star'></span>
														<span class='fa fa-star'></span>
													</div>  
													<p>". 
														$description 
														."</p>
												</div>
												<div class='box-footer'>
													<b>PHP ".number_format($row['price'], 2)."</b>
												</div>
											</div>
										</div>
									</a>
	       						"; 
						    }
							echo "</div>";
						    if($inc == 1) echo "<div class='col-sm-4'></div><div class='col-sm-4'></div></div>"; 
							if($inc == 2) echo "<div class='col-sm-4'></div></div>";
						}
						catch(PDOException $e){
							echo "There is some problem in connection: " . $e->getMessage();
						}

						$pdo->close();

		       		?> 
	        	</div>
	        	<div class="col-sm-3">
	        		<?php include 'includes/sidebar.php'; ?>
	        	</div>
	        </div>
	      </section>
	     
	    </div>
	  </div>
  
  	<?php include 'includes/footer.php'; ?>
</div>

<?php include 'includes/scripts.php'; ?>
<style>
	.checked{
		color: orange;
	}
</style>
</body>
</html>