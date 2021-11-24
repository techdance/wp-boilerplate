jQuery(document).ready(function($){
	//custom login form
	$('#loginform input[type="text"]').attr('placeholder', 'Username');
	
	$('#loginform input[type="password"]').attr('placeholder', 'Password');

	$('#loginform label[for="user_login"]').contents().filter(function() {
		return this.nodeType === 3;
	}).remove();
	
	$('#loginform label[for="user_pass"]').contents().filter(function() {
		return this.nodeType === 3;
	}).remove();
	
	$('input[type="checkbox"]').click(function() {
		$(this+':checked').parent('label').css("background-position","0px -20px");
		$(this).not(':checked').parent('label').css("background-position","0px 0px");
	});
	

//thats all folks
})