<?php

class EJTemplate{

	var $page = null;
	var $shop = null;
	var $product = null;
	var $nav = null;
	var $EJ = null;
	var $nav_template = null;
	var $shop_template = null;
	var $product_template = null;

	function __construct($page, $EJ){
		$this->page = $page;
		if($page->nav != "") $this->nav = $page->nav;
		if($page->shop != "") $this->shop = $page->shop;
		if($page->product != "") $this->product = $page->product;
		$this->EJ = $EJ;
		if($this->nav) $this->nav_template = file_get_contents($this->nav);
		if($this->shop) $this->shop_template = file_get_contents($this->shop);
		if($this->product) $this->product_template = file_get_contents($this->product);
	}	

	function generateNavbar(){
		if($this->nav_template == "") return false;
		$navbar = $this->getTemplateString($this->nav_template, 'Header'); 
		
		if($this->page->embed){
			$navbar = str_replace('{HeaderContainer}'.$this->getTemplateString($navbar, 'HeaderContainer').'{/HeaderContainer}', '<style> body{ padding-top: 0px; } </style>', $navbar);
			$navbar = str_replace('{Footer}'.$this->getTemplateString($navbar, 'HeaderContainer').'{/Footer}', '', $navbar);
		}else{
			$navbar = str_replace('{HeaderContainer}', '', $navbar);
			$navbar = str_replace('{/HeaderContainer}', '', $navbar);
		}

		if($this->page->showTags){
			$navbar = str_replace('{TagsContainer}', '', $navbar);
			$navbar = str_replace('{/TagsContainer}', '', $navbar);
		}else{
			$navbar = str_replace('{TagsContainer}'.$this->getTemplateString($navbar, 'TagsContainer').'{/TagsContainer}', '', $navbar);
			$navbar = str_replace('{TagsContainer}'.$this->getTemplateString($navbar, 'TagsContainer').'{/TagsContainer}', '', $navbar);
		}

		if($this->page->showSearch){
			$navbar = str_replace('{SearchContainer}', '', $navbar);
			$navbar = str_replace('{/SearchContainer}', '', $navbar);
		}else{
			$navbar = str_replace('{SearchContainer}'.$this->getTemplateString($navbar, 'SearchContainer').'{/SearchContainer}', '', $navbar);
		}

		$tags = $this->getTemplateString($navbar, 'Tags');
		if($tags)
			$navbar = $this->generateTags($navbar);
		$navbar = $this->replaceLiterals($navbar);
		
		echo $navbar;
		return true;
	}

	function generateFooter(){
		if($this->nav_template == "") return false;
		$footer = $this->getTemplateString($this->nav_template, 'Footer'); 
		$footer = $this->replaceLiterals($footer);
		echo $footer;
		return true;
	}

	function generateShop(){
		if($this->shop_template == "") return false;
		$products = $this->getTemplateString($this->shop_template, 'Products');
		$has_form = $this->getTemplateString($products, 'Product.Form');
		$final_str = "";
		foreach($this->EJ->products as $product){
			$tstr = str_replace('{Product.Url}', $product->url, $products);
			$tstr = str_replace('{Product.Number}', $product->number, $tstr);
			$tstr = str_replace('{Product.Name}', $product->name, $tstr);
			$tstr = str_replace('{Product.Id}', $product->id, $tstr);
			$tstr = str_replace('{Product.Number}', $product->number, $tstr);
			$tstr = str_replace('{Product.Tagline}', $product->tagline, $tstr);
			if($product->image)
				$tstr = str_replace('{Product.Image}', $product->image, $tstr);
			else
				$tstr = str_replace('{Product.Image}', "https://www.e-junkie.com/ecom/spacer.gif", $tstr);
			$tstr = str_replace('{Product.Price}', $product->price, $tstr);
			$tstr = str_replace('{Product.Currency}', $product->currency, $tstr);
			$tstr = str_replace('{Product.Description}', $product->description, $tstr);
			$tstr = str_replace('{Product.Details}', $product->details, $tstr);
			$tstr = str_replace('{Product.DownloadLink}', $product->download_link, $tstr);
			$tstr = str_replace('{Product.HomepageLink}', $product->homepage_link, $tstr);
			$tstr = str_replace('{Product.Purchased}', $product->purchased, $tstr);

			if($has_form)
				$tstr = $this->generateForm($tstr, $has_form, $product);

			$final_str = $final_str.$tstr;
		}
		
		echo str_replace("{Products}$products{/Products}", $final_str, $this->shop_template);
		return true;
	}

	function generateProduct(){
		if($this->product_template == "") return false;
		$this->product_template = $this->replaceLiterals($this->product_template);
		$products = $this->getTemplateString($this->product_template, 'Product');
		$has_form = $this->getTemplateString($products, 'Product.Form');
		$has_related = $this->getTemplateString($products, 'RelatedProducts');

		$final_str = "";

		if($has_related)
			$final_str = $this->generateRelatedProducts($products, $has_related);

		$product = $this->EJ->products[0]; //as there is only one item when product page is called

		$final_str = str_replace('{Product.Url}', $product->url, $final_str);
		$final_str = str_replace('{Product.Number}', $product->number, $final_str);
		$final_str = str_replace('{Product.Name}', $product->name, $final_str);
		$final_str = str_replace('{Product.Id}', $product->id, $final_str);
		$final_str = str_replace('{Product.Number}', $product->number, $final_str);
		$final_str = str_replace('{Product.Tagline}', $product->tagline, $final_str);
		if($product->image)
			$final_str = str_replace('{Product.Image}', $product->image, $final_str);
		else
			$final_str = str_replace('{Product.Image}', "https://www.e-junkie.com/ecom/spacer.gif", $final_str);
		$final_str = str_replace('{Product.Price}', $product->price, $final_str);
		$final_str = str_replace('{Product.Currency}', $product->currency, $final_str);
		$final_str = str_replace('{Product.Description}', $product->description, $final_str);
		$final_str = str_replace('{Product.Details}', $product->details, $final_str);
		$final_str = str_replace('{Product.DownloadLink}', $product->download_link, $final_str);
		$final_str = str_replace('{Product.HomepageLink}', $product->homepage_link, $final_str);
		$final_str = str_replace('{Product.Purchased}', $product->purchased, $final_str);

		if($has_form)
			$final_str = $this->generateForm($final_str, $has_form, $product);

		echo str_replace("{Product}$products{/Product}", $final_str, $this->product_template);
		return true;
	}

	function generateRelatedProducts($str, $related){
		$related_template = $related;
		
		if(count($this->EJ->relatedProducts) == 0 || $this->page->maxRelated == 0)
			return str_replace("{RelatedProducts}$related{/RelatedProducts}", "", $str);

		$related_product = $this->getTemplateString($related_template, 'RelatedProduct');
		$final_str = "";
		if(count($this->EJ->relatedProducts) < $this->page->maxRelated) $this->page->maxRelated = count($this->EJ->relatedProducts);
		for($x = 0; $x < $this->page->maxRelated; $x++) {
			$tstr = str_replace('{RelatedProduct.Url}', $this->EJ->relatedProducts[$x]->url, $related_product);
			$tstr = str_replace('{RelatedProduct.Number}', $this->EJ->relatedProducts[$x]->number, $tstr);
			$tstr = str_replace('{RelatedProduct.Name}', $this->EJ->relatedProducts[$x]->name, $tstr);
			$tstr = str_replace('{RelatedProduct.Id}', $this->EJ->relatedProducts[$x]->id, $tstr);
			$tstr = str_replace('{RelatedProduct.Number}', $this->EJ->relatedProducts[$x]->number, $tstr);
			$tstr = str_replace('{RelatedProduct.Tagline}', $this->EJ->relatedProducts[$x]->tagline, $tstr);
			if($this->EJ->relatedProducts[$x]->image)
				$tstr = str_replace('{RelatedProduct.Image}', $this->EJ->relatedProducts[$x]->image, $tstr);
			else
				$tstr = str_replace('{RelatedProduct.Image}', "https://www.e-junkie.com/ecom/spacer.gif", $tstr);
			$tstr = str_replace('{RelatedProduct.Price}', $this->EJ->relatedProducts[$x]->price, $tstr);
			$tstr = str_replace('{RelatedProduct.Currency}', $this->EJ->relatedProducts[$x]->currency, $tstr);
			$tstr = str_replace('{RelatedProduct.Description}', $this->EJ->relatedProducts[$x]->description, $tstr);
			$tstr = str_replace('{RelatedProduct.Details}', $this->EJ->relatedProducts[$x]->details, $tstr);
			$tstr = str_replace('{RelatedProduct.DownloadLink}', $this->EJ->relatedProducts[$x]->download_link, $tstr);
			$tstr = str_replace('{RelatedProduct.HomepageLink}', $this->EJ->relatedProducts[$x]->homepage_link, $tstr);
			$tstr = str_replace('{RelatedProduct.Purchased}', $this->EJ->relatedProducts[$x]->purchased, $tstr);

			$final_str = $final_str.$tstr;
		}
		$related_template = str_replace("{RelatedProduct}$related_product{/RelatedProduct}", $final_str, $related_template);

		return str_replace("{RelatedProducts}$related{/RelatedProducts}", $related_template, $str);		
	}

	function generateForm($str, $form, $product){
		$form_template = $form;
		$dropdown_template = $this->getTemplateString($form_template, 'DropDown');
		$textfield_template = $this->getTemplateString($form_template, 'TextField');

		$form_str = "<form action='https://www.e-junkie.com/ecom/gb2.php?' method='POST' target='ej_ejc' accept-charset='UTF-8'>";
		$form_str .= "<input type='hidden' name='c' value='cart'><input type='hidden' name='ejc' value='2'>";
		$form_str .= "<input type='hidden' name='cl' value='".$this->EJ->client->client_id."'><input type='hidden' name='i' value='".$product->number."'>";

		if ($product->needs_advance_options=="true") {
			
			if ($product->option1 && count($product->option1_options)) {
				$form_str .= str_replace('{DropDown.Label}', $product->option1, $dropdown_template);
				$form_str = str_replace('{DropDown.Name}', "o1", $form_str);
				$product->option1_options=array_unique($product->option1_options);
				$ostr = "";
	            foreach($product->option1_options as $op)
	            	$ostr .= "<option value='$op'>$op</option>";
            	$form_str = str_replace('{DropDown.Options}', $ostr, $form_str);
			}

			if ($product->option2 && count($product->option2_options)) {
				$form_str .= str_replace('{DropDown.Label}', $product->option2, $dropdown_template);
				$form_str = str_replace('{DropDown.Name}', "o2", $form_str);
				$product->option2_options=array_unique($product->option2_options);
	            $ostr = "";
	            foreach($product->option2_options as $op)
	            	$ostr .= "<option value='$op'>$op</option>";
            	$form_str = str_replace('{DropDown.Options}', $ostr, $form_str);
			}

			if ($product->option3 && count($product->option3_options)) {
				$form_str .= str_replace('{DropDown.Label}', $product->option3, $dropdown_template);
				$form_str .= str_replace('{DropDown.Name}', "o3", $form_str);
				$product->option3_options=array_unique($product->option3_options);
	         	$ostr = "";
	            foreach($product->option3_options as $op)
	            	$ostr .= "<option value='$op'>$op</option>";
            	$form_str = str_replace('{DropDown.Options}', $ostr, $form_str);
			}
		}

		if ($product->needs_options=="true") {
			if ($product->on0) {
				$product->on0=htmlspecialchars($product->on0,ENT_COMPAT,'UTF-8');
				$product->on0_options = explode("\n", $product->on0_options);
				$form_str .= '<input type="hidden" name="on0" value="'.$product->on0.'">';
				if(count($product->on0_options) >= 1) {
					$form_str .= str_replace('{DropDown.Label}', $product->on0, $dropdown_template);
					$form_str = str_replace('{DropDown.Name}', "os0", $form_str);
					$ostr = "";
	            		foreach($product->on0_options as $on) $ostr .= "<option value='$on'>$on</option>";
	            	$form_str = str_replace('{DropDown.Options}', $ostr, $form_str);
				}else{
					$form_str .= str_replace('{TextField.Name}', "os0", $textfield_template);
					$form_str = str_replace('{TextField.PlaceHolder}', $product->on0, $form_str);
				}
			}
			if ($product->on1) {
				$product->on1=htmlspecialchars($product->on1,ENT_COMPAT,'UTF-8');
				$product->on1_options = explode("\n", $product->on1_options);
				$form_str .= '<input type="hidden" name="on1" value="'.$product->on1.'">';
				if(count($product->on1_options) >= 1) {
					$form_str .= str_replace('{DropDown.Label}', $product->on1, $dropdown_template);
					$form_str = str_replace('{DropDown.Name}', "os1", $form_str);
					$ostr = "";
	            		foreach($product->on1_options as $on) $ostr .= "<option value='$on'>$on</option>";
	            	$form_str = str_replace('{DropDown.Options}', $ostr, $form_str);
				}else{
					$form_str .= str_replace('{TextField.Name}', "os1", $textfield_template);
					$form_str = str_replace('{TextField.PlaceHolder}', $product->on1, $form_str);
				}
			}
			if ($product->on2) {
				$product->on2=htmlspecialchars($product->on2,ENT_COMPAT,'UTF-8');
				$product->on2_options = explode("\n", $product->on2_options);
				$form_str .= '<input type="hidden" name="on2" value="'.$product->on2.'">';
				if(count($product->on2_options) >= 1) {
					$form_str .= str_replace('{DropDown.Label}', $product->on2, $dropdown_template);
					$form_str = str_replace('{DropDown.Name}', "os2", $form_str);
					$ostr = "";
	            		foreach($product->on2_options as $on) $ostr .= "<option value='$on'>$on</option>";
	            	$form_str = str_replace('{DropDown.Options}', $ostr, $form_str);
				}else{
					$form_str .= str_replace('{TextField.Name}', "os2", $textfield_template);
					$form_str = str_replace('{TextField.PlaceHolder}', $product->on2, $form_str);
				}
			}
		}

		$form_str .= $this->getTemplateString($form_template, 'BuyNowButton');
		$form_str = $form_str."</form>";

		return str_replace("{Product.Form}$form_template{/Product.Form}", $form_str, $str);
	}

	function generateTags($str){
		$tag_template = $this->getTemplateString($str, 'Tags');
		$final_str = "";
		if($this->page->selectedCategory)
			$final_str = '<a href="'.$this->page->base.'">All Products</a>';
		foreach($this->EJ->availableTags as $tags){
			$tstr = str_replace("{Tag.Url}", $this->page->base."/tags/".urlencode($tags), $tag_template);
			$tstr = str_replace("{Tag.Name}", $tags, $tstr);
			$final_str = $final_str.$tstr;
		}
		return str_replace("{Tags}$tag_template{/Tags}", $final_str, $str);
	}

	function replaceLiterals($str){
		$str = str_replace("{Client.ShopUrl}", $this->page->base, $str);
		$str = str_replace("{Client.Logo}", $this->EJ->client->logo != "" ? $this->EJ->client->logo : "https://www.e-junkie.com/ecom/spacer.gif", $str);
		$str = str_replace("{Client.ShopName}", $this->EJ->client->shop_name, $str);
		$str = str_replace("{Client.ViewCartUrl}", "https://www.e-junkie.com/ecom/gb2.php?c=cart&ejc=2&cl=".$this->EJ->client->client_id, $str);
		return $str;
	}

	function getTemplateString($str, $needle){
		$starting_needle = "{".$needle."}"; 
		$ending_needle = "{/".$needle."}";
		$s_n = strpos($str, $starting_needle);
		$e_n = strpos($str, $ending_needle);
		$str = substr($str, $s_n, ($e_n-$s_n));
		$str = str_replace($starting_needle, "", $str);
		$str = str_replace($ending_needle, "", $str);
		if($str == "") return false;
		return $str;
	}

}
?>
