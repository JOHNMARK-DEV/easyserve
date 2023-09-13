<?php include 'includes/session.php'; ?>
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
	        		<?php
	        			if(isset($_SESSION['error'])){
	        				echo "
	        					<div class='alert alert-danger'>
	        						".$_SESSION['error']."
	        					</div>
	        				";
	        				unset($_SESSION['error']);
	        			}
	        		?>
	        		<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
		                <ol class="carousel-indicators">
		                  <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
		                  <li data-target="#carousel-example-generic" data-slide-to="1" class=""></li>
		                  <!-- <li data-target="#carousel-example-generic" data-slide-to="2" class=""></li> -->
		                </ol>
		                <div class="carousel-inner">
		                  <div class="item active">
		                    <img src="images/banner1.png" alt="First slide">
		                  </div>
		                  <div class="item">
		                    <img src="images/banner2.png" alt="Second slide">
		                  </div>
		                  <!-- <div class="item">
		                    <img src="images/banner3.png" alt="Third slide">
		                  </div> -->
		                </div>
		                <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
		                  <span class="fa fa-angle-left"></span>
		                </a>
		                <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
		                  <span class="fa fa-angle-right"></span>
		                </a>
		            </div>
		            <h2>Monthly Top Sellers</h2>
		       		<?php
		       			$month = date('m');
		       			$conn = $pdo->open();

		       			try{
		       			 	$inc = 3;	
						    $stmt = $conn->prepare("SELECT *, SUM(quantity) AS total_qty,users.photo as photo FROM details LEFT JOIN sales ON sales.id=details.sales_id LEFT JOIN products ON products.id=details.product_id LEFT JOIN users ON products.supplier_id=users.id WHERE  products.status = 'A' AND  MONTH(sales_date) = '$month' GROUP BY details.product_id ORDER BY total_qty DESC LIMIT 6");
						    $stmt->execute();
						    foreach ($stmt as $row) {  
						    	$image = (!empty($row['photo'])) ? 'images/'.$row['photo'] : 'images/noimage.jpg';
						    	$inc = ($inc == 3) ? 1 : $inc + 1;

								if (strlen($row['description']) > 100) { 
									$description = substr($row['description'], 0,100) . " ...";
								}
	       						if($inc == 1) echo "<div class='row'>";
	       						echo "
								   	<a href='product.php?product=".$row['slug']."'>
										<div class='col-sm-4'>
											<div class='box box-solid'>
												<div class='box-body prod-body'>
												
													<h5>".$row['firstname']."</h5> 
													<div style='display:flex;justify-content:center'>
														<img src='".$image."' width='100%' height='100px' class='thumbnail rounded-circle' > 
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
	       						if($inc == 3) echo "</div>";
						    }
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