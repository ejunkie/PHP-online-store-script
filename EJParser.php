<?php

class EJParser{

	var $json_url = "https://s3.amazonaws.com/json.e-junkie.com/";
	// var $json_url = "http://localhost/shop/";
	var $clientId = null;
	var $products = array();
	var $availableTags = array();
	var $client = null;
	var $totalCount = 0;
	var $appliedTag = null;
	var $selectedProduct = null;
	var $validCall = false;
	var $relatedProducts = array();
	var $extraURLFlags = array();
	var $page = null;

	function __construct($client, $tag, $item, $page){
		$this->clientId = intval($client);
		$this->json_url = $this->json_url.$this->clientId;
		if($tag != null)
		$this->appliedTag = urldecode($tag);
		if($item != null)
		$this->selectedProduct = $item;
		$this->page = $page;
		$this->fetchJSON();
	}	

	function fetchJSON(){
		if($this->clientId == 0)
			return false;

		$ch = curl_init();
		$timeout = 5;
		curl_setopt($ch, CURLOPT_URL, $this->json_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$data = curl_exec($ch);
		curl_close($ch);
		if($data != ""){
			$data = json_decode($data);
			$this->client = $data->client;
			$this->totalCount = $data->count;
			$this->products = $data->items;
			$this->validCall = true;
			return true;
		}
		else return false;
	}

	function getSlug($str){
		return urlencode(str_replace(' ','-',$str));
	}

	function getURL($product){
		$url = $this->page->base."/".$product->number."/".$product->slug;
		return $url;	
	}

	function getAvailableTags(){	
		foreach($this->products as $product){
			foreach($product->tags as $tag){
				if(!in_array($tag, $this->availableTags) && $tag != $this->appliedTag)
					$this->availableTags[] = $tag;
			}
		}
	}

	function getProduct(){
		if($this->validCall){
			$temp_product = null;
			foreach($this->products as $product){
				if($product->number == $this->selectedProduct){
					$product->slug = $this->getSlug($product->name);
					$product->url = $this->getURL($product);
					$temp_product = $product;
					break;
				}
			}
			foreach($this->products as $product)
				foreach($temp_product->tags as $tag)
					if(in_array($tag, $product->tags) && $product->number != $temp_product->number && !in_array($product, $this->relatedProducts)){
						$product->slug = $this->getSlug($product->name);
						$product->url = $this->getURL($product);
						$this->relatedProducts[] = $product;
					}

			if($temp_product)
				$this->products = array($temp_product);
			else 
				$this->products = array();
			$this->getAvailableTags();
			return true;
		}
		return false;
	}

	function getTagProducts(){
		if($this->validCall){
			$temp_array = array();
			foreach($this->products as $product){
				if($this->appliedTag){
					if(!in_array($this->appliedTag, $product->tags))
						continue;
				}
				$product->slug = $this->getSlug($product->name);
				$product->url = $this->getURL($product);
				$temp_array[] = $product;
			}
			$this->products = $temp_array;
			$this->getAvailableTags();
			return true;
		}
		return false;
	}

	function getProducts(){
		if($this->validCall){
			$temp_array = array();
			foreach($this->products as $product){
				$product->slug = $this->getSlug($product->name);
				$product->url = $this->getURL($product);
				$temp_array[] = $product;
			}
			$this->products = $temp_array;
			$this->getAvailableTags();
			return true;
		}
		return false;
	}

	function debugger($obj){
		echo "<pre>";
		print_r($obj);
		die();
	}

}
?>
