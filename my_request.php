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
	        		<h1 class="page-header">YOUR CART</h1>
	        		<div class="box box-solid">
	        			<div class="box-body">
						<table id="example1" class="table table-bordered">
							<thead> 
								<th>Service</th>  
								<th>Price</th>
								<th>Status</th> 
							</thead>
							<tbody>
							<?php
								$conn = $pdo->open();

								try{
								$now = date('Y-m-d');
								$stmt = $conn->prepare("SELECT job_order.request_date,job_order.status as jo_status,users.company,products.* FROM job_order INNER JOIN products on products.id = job_order.product_id INNER JOIN users on products.seller_id = users.id where user_id=".$user['id']);
								$stmt->execute();
								foreach($stmt as $row){
									$image = (!empty($row['photo'])) ? '../images/'.$row['photo'] : '../images/noimage.jpg';
									$counter = ($row['date_view'] == $now) ? $row['counter'] : 0;


									if($row['jo_status'] == 'A'){
									$status = "<button class='btn btn-success btn-sm btn-flat'>Approved</button>";
									}else if($row['jo_status'] == 'D'){
									$status = "<button class='btn btn-success btn-sm btn-flat'>Decline</button>";
									}else if($row['jo_status'] == 'P'){
									$status = "<button class='btn btn-secondary btn-sm btn-flat'>Pending</button>";
									}

									echo "
									<tr> 
										<td>".$row['name']."</td>  
										<td>PHP ".number_format($row['price'], 2)."</td>
										
										<td> 
										$status  
									";
								}
								}
								catch(PDOException $e){
								echo $e->getMessage();
								}

								$pdo->close();
							?>
							</tbody>
						</table>
	        			</div>
	        		</div> 
	        	</div>
	        	<div class="col-sm-3">
	        		<?php include 'includes/sidebar.php'; ?>
	        	</div>
	        </div>
	      </section>
	     
	    </div>
	  </div>
  	<?php $pdo->close(); ?>
  	<?php include 'includes/footer.php'; ?>
</div>

<?php include 'includes/scripts.php'; ?>
<script>
var total = 0;
$(function(){
	$(document).on('click', '.cart_delete', function(e){
		e.preventDefault();
		var id = $(this).data('id');
		$.ajax({
			type: 'POST',
			url: 'cart_delete.php',
			data: {id:id},
			dataType: 'json',
			success: function(response){
				if(!response.error){
					getDetails();
					getCart();
					getTotal();
				}
			}
		});
	});

	$(document).on('click', '.minus', function(e){
		e.preventDefault();
		var id = $(this).data('id');
		var qty = $('#qty_'+id).val();
		if(qty>1){
			qty--;
		}
		$('#qty_'+id).val(qty);
		$.ajax({
			type: 'POST',
			url: 'cart_update.php',
			data: {
				id: id,
				qty: qty,
			},
			dataType: 'json',
			success: function(response){
				if(!response.error){
					getDetails();
					getCart();
					getTotal();
				}
			}
		});
	});

	$(document).on('click', '.add', function(e){
		e.preventDefault();
		var id = $(this).data('id');
		var qty = $('#qty_'+id).val();
		qty++;
		$('#qty_'+id).val(qty);
		$.ajax({
			type: 'POST',
			url: 'cart_update.php',
			data: {
				id: id,
				qty: qty,
			},
			dataType: 'json',
			success: function(response){
				if(!response.error){
					getDetails();
					getCart();
					getTotal();
				}
			}
		});
	});

	getDetails();
	getTotal();

});

function getDetails(){
	$.ajax({
		type: 'POST',
		url: 'my_request_details.php',
		dataType: 'json',
		success: function(response){
			$('#tbody').html(response);
			getCart();
		}
	});
}

function getTotal(){
	$.ajax({
		type: 'POST',
		url: 'my_request_total.php',
		dataType: 'json',
		success:function(response){
			total = response;
		}
	});
}
</script>
<!-- Paypal Express -->
<script> 
// Example function to show a result to the user. Your site's UI library can be used instead.
function resultMessage(message) {
  const container = document.querySelector("#result-message");
  container.innerHTML = message;
}

</script>
</body>
</html>