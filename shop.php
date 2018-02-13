<?php
	require_once 'partials/header.php';
	require_once 'partials/navbar.php';
	$EJT->generateShop();
?>	
	<script>
	//Needed for Search in Shop Page
	var EJ_Products = <?php echo json_encode($EJ->products); ?>;</script>
<?	require_once 'partials/footer.php'; ?>