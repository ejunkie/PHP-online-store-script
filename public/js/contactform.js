function EJ_toggleContactForm(ch){
	if(ch)
		document.getElementsByClassName('contactForm')[0].className = 'contactForm visible';
	else
		document.getElementsByClassName('contactForm')[0].className = 'contactForm';
}

function EJ_showTagsSidebar(ch){
	if(ch)
		document.getElementsByClassName('tag_sidebar')[0].className = 'tag_sidebar visible';
	else
		document.getElementsByClassName('tag_sidebar')[0].className = 'tag_sidebar';	
}


function EJ_submitContactForm(e){
	
	e.preventDefault();
	
	$.post("#!",
	{
	    sender_name: $('#sender_name').val(),
	    sender_email: $('#sender_email').val(),
	    sender_subject: $('#sender_subject').val(),
	    sender_message: $('#sender_message').val(),
	    send_message: true
	},
	function(data, status){
	    console.log(data)
	});	

	return false;
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