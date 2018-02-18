<?php
$Header = new stdClass();
if($match['name'] == 'shop' || $match['name'] == 'tags'){
	$Header->title = $EJ->client->shop_name;
	if($match['name'] == 'tags')
	$Header->title = $EJ->client->shop_name." - ".$EJ->appliedTag;
	$Header->description = $EJ->client->shop_name."'s Shop";
	$Header->logo = $EJ->client->logo;
	$Header->author = $EJ->client->name;
	$Header->url = $Page->base;
}else if($match['name'] == 'product'){
	$Header->title = $EJ->products[0]->name;
	$Header->description = $EJ->products[0]->description;
	$Header->logo = $EJ->products[0]->image;
	$Header->author = $EJ->client->shop_name;
	$Header->url = $Page->base.$EJ->products[0]->url;
}
?>
<!DOCTYPE html>
<html lang="en">
<html>
    <head>
		<title><?php echo $Header->title; ?></title>
        <meta name="description" content="<?php echo $Header->description; ?>">
        <link rel="icon" type="image/png" href="<?php echo $EJ->client->logo; ?>" />
        <meta name="robots" content="index, follow">
        <meta name="author" content="<?php echo $Header->author; ?>">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="chrome=1">
        <meta property="og:type" content="website">
        <meta property="og:title" content="<?php echo $Header->title; ?>">
        <meta property="og:url" content="<?php echo $Header->url; ?>">
        <meta property="og:description" content="<?php echo $Header->description; ?>">
        <meta property="og:image" content="<?php echo $Header->logo; ?>">
        <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $Header->logo; ?>">
        <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $Header->logo; ?>">
        <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $Header->logo; ?>">
		<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#009900">
		<meta name="theme-color" content="#ffffff">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" crossorigin="anonymous">
		<link href="https://fonts.googleapis.com/css?family=Raleway:300,600" rel="stylesheet" type="text/css">
		<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet">

		<script src="https://www.e-junkie.com/ecom/box_fb_n.js"></script>
		<script src="/shop/public/js/jquery.min.js"></script>
		<script src="/shop/public/js/bootstrap.min.js"></script>
		<link href="/shop/public/css/style.css" rel="stylesheet">
		<link href="/shop/public/css/contactform.css" rel="stylesheet">
		<link href="/shop/public/css/bootstrap.min.css" rel="stylesheet">
		<script src="/shop/public/js/scripts.js"></script>
	</head>
	<body>

		<div class="contactForm">
			<button type="button" class="closeBtn" onClick="EJ_toggleContactForm(false)"><i class="ion-close-round"></i> Close</button>
	        <p>You can use the form below to send a message to <span class="quiting_text"><?php echo $EJ->client->shop_name; ?></span>.</p>
            <form method="post" onSubmit="return EJ_submitContactForm(e)">
		        <p>
		          <input type="text" id="sender_name" name="sender_name" required="required" placeholder="Name">
		          <span>Name</span>
	    	    </p>
	    	    <p>
		          <input type="email" id="sender_email" name="sender_email" required="required" placeholder="Email">
		          <span>Email</span>
	    	    </p>
	    	    <p>
		          <input type="text" id="sender_subject" name="sender_subject" required="required" placeholder="Subject">
		          <span>Subject</span>
	    	    </p>
	    	    <p>
		          <span>Message</span>
		          <textarea name="sender_message" id="sender_message" rows="9"></textarea>
	    	    </p>
          		<input type="hidden" name="client_id" value="<?php echo $EJ->client->client_id; ?>">
	    	  	<input type="submit" value="Send Message" class="btn send_message">
            </form>
      </div>
