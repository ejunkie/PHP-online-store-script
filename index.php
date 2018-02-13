<?php
require_once 'AltoRouter.php';
require_once 'EJParser.php';
require_once 'EJTemplate.php';
$router = new AltoRouter();

function show404(){	
	header('Location: /shop3/404');
	die();
}

$Page = new stdClass();
$Page->nav = './templates/navbar.ej';
$Page->shop = './templates/shop.ej';
$Page->product = './templates/product.ej';
$Page->maxRelated = 5;
$Page->showSearch = true;
$Page->selectedCategory = null;
$Page->clientId = 328984;
$Page->showTags = true;
$Page->base = '/shop3';

$router->setBasePath($Page->base);
$router->map( 'GET', '/', null, 'shop');
$router->map( 'GET', '/404', null, '404');
$router->map( 'GET', '/tags/[*:tag]', null, 'tags');
$router->map( 'GET', '/[a:item]/[*:slug]?', null, 'product'); //slug isn't compulsory for routing

$match = $router->match();
if($match)
{
	if($match['name'] == 'redirect') show404();

	if($match['name'] == '404'){ include '404.php';	die(); }

	$tag = null;
	$item = null;
	if($match['name'] == 'tags') $tag = $match['params']['tag'];
	if($match['name'] == 'product') $item = $match['params']['item'];
	$Page->selectedCategory = $tag;

	$EJ = new EJParser($Page->clientId, $tag, $item, $Page);
	if($EJ->client == null || $EJ->totalCount == 0) show404();

	$EJT = new EJTemplate($Page, $EJ);

	if($match['name'] == 'shop' || $match['name'] == 'tags'){
		if(!$EJ->appliedTag) $EJ->getProducts(); //no tag is applied, means shop page
		else $EJ->getTagProducts(); //if tag is applied, then its a tag page
		require_once 'header.php';
		$EJT->generateNavbar();
		$EJT->generateShop();
		//the below line is needed to implement product search in shop page
		echo "\n<script>\nvar EJ_Products = ".json_encode($EJ->products)."\n</script>";
		$EJT->generateFooter();
	}
	else if($match['name'] == 'product'){
		$EJ->getProduct();
		if(count($EJ->products) == 0) show404(); //no product found
		$Page->showSearch = false;
		require_once 'header.php';
		$EJT->generateNavbar();
		$EJT->generateProduct();
		$EJT->generateFooter();
	}
}
else
	show404();
