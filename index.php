<?php
error_reporting(~E_ALL);
require_once 'EJParser.php';
require_once 'EJTemplate.php';
require_once 'AltoRouter.php';

$Page = new stdClass();
$Page->url = ""; //base url or sub-folder name
$Page->location->templates = "./templates";
$Page->EJ = (object) array(
	"clientId"=> 328984, #your e-junkie client ID
	"maxRelated"=> 5,
	"pref" => array( # Item numbers in respective array 
		"pinned" => [],
		"pinned_down" => [],
		"hidden" => [],
		"hide_out_of_stock"=> true, 
	),
	"apiKey"=> null #provide key to show all products
);
$Page->EJ->selectedCategory = null;
$Page->EJ->selectedProduct = null;
$Page->EJ->showTags = true;
$Page->EJ->showSearch = true;
$Page->EJ->shop = "?shop"; #shop page url, ex. store, products, shop
$Page->EJ->product = "?product="; #product page url prefix


if($_GET['shop'] || $_GET['tag'])
	$Page->name == "shop"; #If shop page or tag page should be rendered 
else if($_GET['product'])
	$Page->name == "product"; #If product page should be rendered

if(!isset($_GET['page']))
    $Page->pageNo = 1;
else
    $Page->pageNo = intval($_GET['page']);

if($Page->pageNo == 0) $Page->pageNo++;

#Set Product or Tag is any is present
if(isset($_GET['product'])) $Page->EJ->selectedProduct = $_GET['product'];
if(isset($_GET['tag'])) $Page->EJ->selectedCategory = $_GET['tag'];

$EJ = new EJParser($Page->EJ->clientId, $Page->EJ->selectedCategory, $Page->EJ->selectedProduct, $Page, $Page->EJ->apiKey);

$EJT = new EJTemplate($Page, null, $EJ);

if(isset($_GET['shop']))
        $EJ->getProducts();
else if(isset($_GET['tag']))
        $EJ->getTagProducts();
else if(isset($_GET['product']))
        $EJ->getProduct();

if(count($EJ->products) == 0){
	header("HTTP/1.1 404 Not Found");
	die("No products found");
}else{
	$EJT->generateShop();
}
die();