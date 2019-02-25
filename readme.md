# Generate Shop using E-junkie PHP Script

### Files Required
- EJParser.php
* Script is used for fetching public products or all products(if *API Key* is present) from E-junkie.

- EJTemplate.php
* Script is used for rendering Shop/Product pages. Support themes and [https://www.e-junkie.com/wiki/e-junkie-template-engine](template variables).

### Example

*XXXXX* denotes E-junkie Client ID

- Fetch products 
		<?php
		require_once "EJParser.php";
		$EJ = new EJParser(XXXXX); 
		$Products = $EJ->getProducts();
- Fetch tag specific products
		<?php
		require_once "EJParser.php";
		$EJ = new EJParser(XXXXX, TAG_NAME); //any tag your products have
		$Products = $EJ->getTagProducts();

- Fetch specific product
		<?php
		require_once "EJParser.php";
		$EJ = new EJParser(XXXXX, null, PRODUCT_NUMBER); //any product number in your account
		$Product = $EJ->getProduct();

>You can also provide your API Key as the 5th Parameter in EJParser constructor to fetch all products from your E-junkie account.


- Generate Shop/Product Page
	<?php
	require_once 'EJParser.php';
	require_once 'EJTemplate.php';
	$Page = new stdClass();
	$Page->url = ""; //base url or sub-folder name
	$Page->location->templates = "./templates";
	$Page->EJ = (object) array(
        	"clientId"=> XXXXX, #your e-junkie client ID
        	"maxRelated"=> 5,
        	"pref" => array( # Item numbers in respective array 
                	"pinned" => [],
                	"pinned_down" => [],
                	"hidden" => [],
                	"hide_out_of_stock"=> true,
        	),
        	"apiKey"=> null #provide key to show all products, you can get it from Seller Admin > Products API
	);
	$EJ = new EJParser($Page->EJ->clientId, null, null);
	$EJT = new EJTemplate($Page, null, $EJ);
	$EJT->generateShop();

> index.php contains the overall code for running a proper shop, with tags, products and page variables. [http://altorouter.com/](AltoRouter) is included for routing.


