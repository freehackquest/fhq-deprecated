# ProgressBar = require 'progressbar.js'
# $ = require 'jquery'

gameWidth = 415 / 4
$games = $('.gamespanel__games')
$games.css 'left', 0

$('.gamespanel__arrow--left').click ->
	left = Math.min(0, parseFloat($games.css 'left') + gameWidth * 3)
	$games.stop().animate 'left': left, 400

$('.gamespanel__arrow--right').click ->
	left = Math.max(-gameWidth * $games.length, parseFloat($games.css 'left') - gameWidth * 3)
	$games.stop().animate 'left': left, 400


for element in document.getElementsByClassName('gamespanel__game')
	circle = new ProgressBar.Circle element,
		color: '#50e3c2'
		strokeWidth: 4
		trailColor: 'black'
		duration: 1500
		easing: 'elastic'
		fill: 'rgba(0,0,0,.7)'
		text:
			value: '0'
		step: (state, bar) ->
			bar.setText (bar.value() * 100).toFixed 0
		click: -> alert 'cat'
	circle.progress = element.dataset.progress / 100

	setTimeout (-> @animate @progress).bind(circle), 1000
