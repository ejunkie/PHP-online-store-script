function EJ_showTagsSidebar(ch){
	if(ch)
		document.getElementsByClassName('tag_sidebar')[0].className = 'tag_sidebar visible';
	else
		document.getElementsByClassName('tag_sidebar')[0].className = 'tag_sidebar';	
}

$( document ).ready(function() {
    var el=document.getElementById("EJ_search_input");
	el.addEventListener("keyup", EJ_searchProducts, false);
});

function EJ_searchProducts(y){
	var searchString = y.target.value.toLowerCase();
	EJ_Products.forEach(function(x){
		if(x.name.toLowerCase().indexOf(searchString) > -1)
			document.getElementById('EJ_Product_'+x.number).hidden = false;
		else
			document.getElementById('EJ_Product_'+x.number).hidden = true;
	})
}
