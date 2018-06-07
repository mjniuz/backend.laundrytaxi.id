<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>IMPORTIR.NET</title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="<?php echo base_url('/asset/css/bootstrap.css')?>" rel="stylesheet">
<link href="<?php echo base_url('/asset/css/shopfrog.css')?>" rel="stylesheet" media="screen">   
<link href="<?php echo base_url('/asset/css/shopfrog-orange.css')?>" rel="stylesheet" media="screen">


<!--

<link href="css/shopfrog-blue.css" rel="stylesheet" media="screen">
<link href="css/shopfrog-brown.css" rel="stylesheet" media="screen">
<link href="css/shopfrog-green.css" rel="stylesheet" media="screen">
<link href="css/shopfrog-orange.css" rel="stylesheet" media="screen">
<link href="css/shopfrog-grey.css" rel="stylesheet" media="screen">
<link href="css/shopfrog-bw.css" rel="stylesheet" media="screen">
-->

<link href="<?php echo base_url('asset/css/rateit.css')?>" rel="stylesheet" media="screen">		       
<link href="<?php echo base_url('asset/css/magnific-popup.css')?>" rel="stylesheet"> 		
<script src="<?php echo base_url('asset/js/respond.min.js')?>"></script>
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
<link href='//fonts.googleapis.com/css?family=PT+Sans' rel='stylesheet' type='text/css'>
<link href='//fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>

<script src="//code.jquery.com/jquery-1.9.1.min.js"></script>    
<script src="<?php echo base_url('asset/js/modernizr.min.js')?>"></script>	
<script src="<?php echo base_url('asset/js/imagesloaded.min.js')?>"></script>	
<script src="<?php echo base_url('asset/js/jquery.masonry.min.js')?>"></script>	
<script src="<?php echo base_url('asset/js/jquery.rateit.min.js')?>"></script>		
<script src="<?php echo base_url('asset/js/jquery.magnific-popup.min.js')?>"></script>				
<script src="<?php echo base_url('asset/js/bootstrap.js')?>"></script>
<script src="<?php echo base_url('asset/js/shopfrog.js')?>"></script>

</head>
<body class="page-general">
			
	<header class="navbar navbar-fixed-top clearfix">
		
	<a class="brand" href=" <?php echo base_url('catalog') ?>" ><font color=black>IMPORTIR.NET</font></a>

	<div id="nav-basket" class="basket">
		<a href="<?php echo base_url('catalog/cart') ?>" class="basket-link">
			<div class="basket-count" name="basket_counter"></div>
		</a>
		
	</div>
	
	<button type="button" class="btn navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
	</button>

	<nav class="navbar-collapse collapse" id="main-nav">
		<ul class="nav">
			
			
			
			
			<?php 
			foreach($kategori as $rows){
			?> 
			<li>
				<a href="<?php echo base_url('catalog/kategori/'.$rows->slug)?>" class="top-level"><?php echo $rows->nama_kategori ?></a>
				
			</li>
			<?php } ?>
			
					</ul>
				</div>	
			</li>
				
			
		</ul>
	</nav><!--/.nav-collapse -->
	
</header>