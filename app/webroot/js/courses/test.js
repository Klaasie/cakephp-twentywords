var test = new function(){
	this.question;
	this.score = [];
	this.score['false'] = 0;

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
		// Hiding previous question with fade out.
		TweenMax.to($('.currentLanguage'), 1, {autoAlpha:0});
		TweenMax.to($('.toTranslate'), 1, {autoAlpha:0});

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

		// Setting id for score component
		this.score['id'] = this.question['learn']['id'];

		currentLang += '<span class="part1">' + test.question['current']['front'] + '</span>';
		currentLang += '<span class="asking">' + test.question['current']['word'] + '</span>';
		currentLang += '<span class="part2">' + test.question['current']['back'] + '</span>';

		learnLang += '<span class="part1">' + test.question['learn']['front'] + '</span>';
		learnLang += '<input class="answer" autocomplete="off" type="text">';
		learnLang += '<span class="part2">' + test.question['learn']['back'] + '</span>';

		// Appending html
		$('.currentLanguage').html(currentLang);
		$('.toTranslate').html(learnLang);

		// Setting listener
		$('.answer').bind("cut copy paste",function(e) {
			e.preventDefault();
		});

		// Fading in.
		TweenMax.to($('.currentLanguage'), 0.5, {autoAlpha:1, delay: 0.5});
		TweenMax.to($('.toTranslate'), 0.5, {autoAlpha:1, delay: 0.5});

		console.log(test.question);
	}

	this.checkAnswer = function() {
		// Checking if we're in the middle of displaying the answer.
		if($('.wrongAnswer').css('visibility') == 'visible'){
			return false;
		}

		var givenAnswer = $('.answer').val().toLowerCase();
		var correctAnswer = this.question['learn']['word'].toLowerCase();

		if(givenAnswer == correctAnswer){
			// Answer is correct
			console.log('Correct!');
			//this.save();
			//this.nextQuestion();
		} else {
			console.log('Incorrect!');
			this.showAnswer();
		}

	}

	this.showAnswer = function() {
		// Show correct answer
		$('.wrongAnswer').css({'visibility': 'visible'});
		$('.answer').val(this.question['learn']['word']);
		$('.answer').attr('disabled', true);

		this.score['false'] += 1;

		console.log(this.score);

		// Hide correct answer
		setTimeout(function(){
			$('.wrongAnswer').css({'visibility': 'hidden'});
			$('.answer').val('');
			$('.answer').attr('disabled', false);
		},3000)

	}
}


$(document).ready(function(){

	test.start();

	$('.checkAnswer').click(function(){
		test.checkAnswer();
	});

});