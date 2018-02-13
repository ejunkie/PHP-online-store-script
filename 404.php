<?php
header("HTTP/1.1 404 Not Found");
?>
<!DOCTYPE html>
<html lang="en">
<html>
    <head>
		<title>404 - Nothing Found</title>
        <meta name="description" content="404 - No Shop Found">
        <meta name="generator" content="E-junkie"/>
        <link rel="icon" type="image/png" href="https://static.e-junkie.com/favicon-16x16.png?v=5.0" sizes="16x16">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="chrome=1">
        <meta property="og:type" content="website">
		<meta name="theme-color" content="#ffffff">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" crossorigin="anonymous">
		<link href="https://fonts.googleapis.com/css?family=Raleway:300,600" rel="stylesheet" type="text/css">
		<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet">
		<link href="/shop/public/css/bootstrap.min.css" rel="stylesheet">
		<style>
		body{
			min-height: 100vh;
		    background-color: rgba(238, 238, 238, 0.8);
		    text-align: center;
		    border-top: 5px solid #E54A16;
		    border-bottom: 5px solid #E54A16;
		    margin: 0 auto;
		    padding-top: 20vh;
		}
		body img{
			max-width: 100px;
		}
		h1{
		    font-size: 20px;
		    font-weight: bold;
		    color: #555;
		}
		p{
		    font-size: 14px;
    		color: #999;
		}
		p a{
		    background-color: #E54A16;
		    padding: 5px 10px;
		    border-radius: 3px;
		    margin: 5px;
		    color: white;
		}
		p a:hover{
			color: white;
		}
		</style>
	</head>
	<body>
		<div class="container">
			<img src="/shop/public/e-junkie-404.png">
			<h1>Argh! We were unable to locate the page you were trying to load.</h1>
			<p>To save your last click, you must invest another.
			Go to <a href="<?php echo str_replace('/404','',$_SERVER['REQUEST_URI']) ?>">Shop</a> and do a search.</p>
			<img src="https://www.e-junkie.com/wiki/user/themes/Wiki/images/logo3.png">
		</div>
	</body>
</html>