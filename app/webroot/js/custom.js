$('.showLogin').click(function(e){
	e.preventDefault();
	$(this).hide();
	var loginElement = document.getElementById('login');
	TweenLite.to(loginElement, 0.8, {autoAlpha: 1})
});