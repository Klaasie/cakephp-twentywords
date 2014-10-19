var test = new function(){
	this.questions;
	this.score;

	this.get = function(){

		$.ajax({
			type: 'get',
			url: '/twentywords/courses/getQuestions',
			dataType: "json",
		}).done(function(data){
			test.questions = data;
			test.show();
		});
	};

	this.show = function(){
		var html = '';
// console.log(test.questions['SentencesNed']);
		html += '<div class="test_block">';

			html += '<div class="curLanguage">';
				html += '<p>' + test.questions['SentencesNed'][0]['SentencesNed']['front'] + test.questions['SentencesNed'][0]['SentencesNed']['word'] + test.questions['SentencesNed'][0]['SentencesNed']['back'] + '</p>';
			html += '</div>';

			html += '<div class="learnLanguage">';
				html += '<p>' + test.questions['SentencesSpa'][0]['SentencesSpa']['front'] + '<input type="text" name="" class="">' + test.questions['SentencesSpa'][0]['SentencesSpa']['back'] + '</p>';
			html += '</div>';

		html += '</div>';
// console.log(html);
		$('.page-content').html(html);

		console.log(test.questions);
	}
}


$(document).ready(function(){
	console.log('Document is ready');

	test.get();

});