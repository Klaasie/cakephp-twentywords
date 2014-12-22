var test = new function(){
	this.question;
	this.score = [];
	this.score['false'] = 0;
	this.score['good'] = 0;

	this.start = function(){
		$.ajax({
			type: 'get',
			url: '/courses/start',
			dataType: "json",
		}).done(function(data){
			test.question = data;
			test.show();
		});
	}

	this.show = function(){ 
		var currentLang = '';
		var learnLang = '';

		if(test.question['result'] == "completed"){
			$('ul.test').hide();
			$('.test_finished').show();
		} else{
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
		}
		

		// Fading in.
		/*TweenMax.to($('.currentLanguage'), 0.5, {autoAlpha:1, delay: 0.5});
		TweenMax.to($('.toTranslate'), 0.5, {autoAlpha:1, delay: 0.5});*/

	}

	this.checkAnswer = function() {
		// Checking if we're in the middle of displaying the answer.
		if($('.answer').hasClass('showAnswer')){
			return false;
		}

		var givenAnswer = $('.answer').val().toLowerCase();
		var correctAnswer = this.question['learn']['word'].toLowerCase();

		var correctAnswer = $("<div/>").html(correctAnswer).text();

		if(givenAnswer == correctAnswer){
			// Answer is correct
			console.log('Correct!');
			this.score['good'] = 1;
			this.save();
		} else if(this.score['false'] == 1){
			// Move to next question anyway.
			console.log('Lets just move on!');

			this.score['false'] += 1;

			this.save();
		} else {
			console.log('Incorrect!');
			this.showAnswer();

			this.score['false'] += 1;
//			console.log(this.score);

			// Hide correct answer
			setTimeout(function(){
				test.hideAnswer();
			},3000);
		}

	};

	this.showAnswer = function() {
		// Decoding
		var correctAnswer = $("<div/>").html(this.question['learn']['word']).text();

		// Show correct answer
		$('.answer').addClass('showAnswer');
		$('.answer').val(correctAnswer);
		$('.answer').attr('disabled', true);
	};

	this.hideAnswer = function() {
		$('.answer').removeClass('showAnswer');
		$('.answer').val('');
		$('.answer').attr('disabled', false);
	};

	this.save = function() {
		var data = [];
		data['Input'] = [];

		data['Input']['sentence_id'] = this.question['learn']['id'];
		data['Input']['good'] = this.score['good'];
		data['Input']['false'] = this.score['false'];

		$.ajax({
			type: 'post',
			data: {
				'sentence_id': this.question['learn']['id'],
				'good': this.score['good'],
				'false': this.score['false']
			},
			url: '/courses/save',
		}).done(function(data){
			test.nextQuestion();
		});
	};

	this.nextQuestion = function(){

		$.ajax({
			type: 'get',
			url: '/courses/nextQuestion',
			dataType: "json",
		}).done(function(data){
			test.score['good'] = 0;
			test.score['false'] = 0;

			test.question = data;
			test.show();
		});
	}

}


$(document).ready(function(){

	test.start();

	$('.checkAnswer').click(function(){
		test.checkAnswer();
	});

});