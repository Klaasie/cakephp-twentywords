var test = new function(){
	this.question;
	this.score;

	this.start = function(){
		$.ajax({
			type: 'get',
			url: '/twentywords/courses/start',
			dataType: "json",
		}).done(function(data){
			test.question = data;
			test.show();
		});
	}

	this.get = function(){
		$.ajax({
			type: 'get',
			url: '/twentywords/courses/getQuestion',
			dataType: "json",
		}).done(function(data){
			test.questions = data;
			test.show();
		});
	};

	this.show = function(){
		var currentLang = '';
		var learnLang = '';

		currentLang += '<span class="part1">' + test.question['current']['front'] + '</span>';
		currentLang += '<span class="asking">' + test.question['current']['word'] + '</span>';
		currentLang += '<span class="part2">' + test.question['current']['back'] + '</span>';

		learnLang += '<span class="part1">' + test.question['learn']['front'] + '</span>';
		learnLang += '<input class="answer" autocomplete="off" type="text">';
		learnLang += '<span class="part2">' + test.question['learn']['back'] + '</span>';

		$('.currentLanguage').html(currentLang);
		$('.toTranslate').html(learnLang);

		TweenMax.to($('.currentLanguage'), 1, {autoAlpha:1});
		TweenMax.to($('.toTranslate'), 1, {autoAlpha:1});

		console.log(test.question);
	}
}


$(document).ready(function(){
	console.log('Document is ready');

	test.start();

});