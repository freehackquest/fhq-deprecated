(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
var $games, circle, element, gameWidth, i, len, ref;

gameWidth = 415 / 4;

$games = $('.gamespanel__games');

$games.css('left', 0);

$('.gamespanel__arrow--left').click(function() {
  var left;
  left = Math.min(0, parseFloat($games.css('left')) + gameWidth * 3);
  return $games.stop().animate({
    'left': left
  }, 400);
});

$('.gamespanel__arrow--right').click(function() {
  var left;
  left = Math.max(-gameWidth * $games.length, parseFloat($games.css('left')) - gameWidth * 3);
  return $games.stop().animate({
    'left': left
  }, 400);
});

ref = document.getElementsByClassName('gamespanel__game');
for (i = 0, len = ref.length; i < len; i++) {
  element = ref[i];
  circle = new ProgressBar.Circle(element, {
    color: '#50e3c2',
    strokeWidth: 4,
    trailColor: 'black',
    duration: 1500,
    easing: 'elastic',
    fill: 'rgba(0,0,0,.7)',
    text: {
      value: '0'
    },
    step: function(state, bar) {
      return bar.setText((bar.value() * 100).toFixed(0));
    },
    click: function() {
      return alert('cat');
    }
  });
  circle.progress = element.dataset.progress / 100;
  setTimeout((function() {
    return this.animate(this.progress);
  }).bind(circle), 1000);
}


},{}]},{},[1])
//# sourceMappingURL=data:application/json;charset:utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIm5vZGVfbW9kdWxlcy9icm93c2VyaWZ5L25vZGVfbW9kdWxlcy9icm93c2VyLXBhY2svX3ByZWx1ZGUuanMiLCIvVXNlcnMvbml0aXZlL0Rlc2t0b3Avc2VhLWtnL3NyYy9qcy9hcHAuY29mZmVlIl0sIm5hbWVzIjpbXSwibWFwcGluZ3MiOiJBQUFBO0FDR0EsSUFBQTs7QUFBQSxTQUFBLEdBQVksR0FBQSxHQUFNOztBQUNsQixNQUFBLEdBQVMsQ0FBQSxDQUFFLG9CQUFGOztBQUNULE1BQU0sQ0FBQyxHQUFQLENBQVcsTUFBWCxFQUFtQixDQUFuQjs7QUFFQSxDQUFBLENBQUUsMEJBQUYsQ0FBNkIsQ0FBQyxLQUE5QixDQUFvQyxTQUFBO0FBQ25DLE1BQUE7RUFBQSxJQUFBLEdBQU8sSUFBSSxDQUFDLEdBQUwsQ0FBUyxDQUFULEVBQVksVUFBQSxDQUFXLE1BQU0sQ0FBQyxHQUFQLENBQVcsTUFBWCxDQUFYLENBQUEsR0FBZ0MsU0FBQSxHQUFZLENBQXhEO1NBQ1AsTUFBTSxDQUFDLElBQVAsQ0FBQSxDQUFhLENBQUMsT0FBZCxDQUFzQjtJQUFBLE1BQUEsRUFBUSxJQUFSO0dBQXRCLEVBQW9DLEdBQXBDO0FBRm1DLENBQXBDOztBQUlBLENBQUEsQ0FBRSwyQkFBRixDQUE4QixDQUFDLEtBQS9CLENBQXFDLFNBQUE7QUFDcEMsTUFBQTtFQUFBLElBQUEsR0FBTyxJQUFJLENBQUMsR0FBTCxDQUFTLENBQUMsU0FBRCxHQUFhLE1BQU0sQ0FBQyxNQUE3QixFQUFxQyxVQUFBLENBQVcsTUFBTSxDQUFDLEdBQVAsQ0FBVyxNQUFYLENBQVgsQ0FBQSxHQUFnQyxTQUFBLEdBQVksQ0FBakY7U0FDUCxNQUFNLENBQUMsSUFBUCxDQUFBLENBQWEsQ0FBQyxPQUFkLENBQXNCO0lBQUEsTUFBQSxFQUFRLElBQVI7R0FBdEIsRUFBb0MsR0FBcEM7QUFGb0MsQ0FBckM7O0FBS0E7QUFBQSxLQUFBLHFDQUFBOztFQUNDLE1BQUEsR0FBYSxJQUFBLFdBQVcsQ0FBQyxNQUFaLENBQW1CLE9BQW5CLEVBQ1o7SUFBQSxLQUFBLEVBQU8sU0FBUDtJQUNBLFdBQUEsRUFBYSxDQURiO0lBRUEsVUFBQSxFQUFZLE9BRlo7SUFHQSxRQUFBLEVBQVUsSUFIVjtJQUlBLE1BQUEsRUFBUSxTQUpSO0lBS0EsSUFBQSxFQUFNLGdCQUxOO0lBTUEsSUFBQSxFQUNDO01BQUEsS0FBQSxFQUFPLEdBQVA7S0FQRDtJQVFBLElBQUEsRUFBTSxTQUFDLEtBQUQsRUFBUSxHQUFSO2FBQ0wsR0FBRyxDQUFDLE9BQUosQ0FBWSxDQUFDLEdBQUcsQ0FBQyxLQUFKLENBQUEsQ0FBQSxHQUFjLEdBQWYsQ0FBbUIsQ0FBQyxPQUFwQixDQUE0QixDQUE1QixDQUFaO0lBREssQ0FSTjtJQVVBLEtBQUEsRUFBTyxTQUFBO2FBQUcsS0FBQSxDQUFNLEtBQU47SUFBSCxDQVZQO0dBRFk7RUFZYixNQUFNLENBQUMsUUFBUCxHQUFrQixPQUFPLENBQUMsT0FBTyxDQUFDLFFBQWhCLEdBQTJCO0VBRTdDLFVBQUEsQ0FBVyxDQUFDLFNBQUE7V0FBRyxJQUFDLENBQUEsT0FBRCxDQUFTLElBQUMsQ0FBQSxRQUFWO0VBQUgsQ0FBRCxDQUF1QixDQUFDLElBQXhCLENBQTZCLE1BQTdCLENBQVgsRUFBaUQsSUFBakQ7QUFmRCIsImZpbGUiOiJnZW5lcmF0ZWQuanMiLCJzb3VyY2VSb290IjoiIiwic291cmNlc0NvbnRlbnQiOlsiKGZ1bmN0aW9uIGUodCxuLHIpe2Z1bmN0aW9uIHMobyx1KXtpZighbltvXSl7aWYoIXRbb10pe3ZhciBhPXR5cGVvZiByZXF1aXJlPT1cImZ1bmN0aW9uXCImJnJlcXVpcmU7aWYoIXUmJmEpcmV0dXJuIGEobywhMCk7aWYoaSlyZXR1cm4gaShvLCEwKTt2YXIgZj1uZXcgRXJyb3IoXCJDYW5ub3QgZmluZCBtb2R1bGUgJ1wiK28rXCInXCIpO3Rocm93IGYuY29kZT1cIk1PRFVMRV9OT1RfRk9VTkRcIixmfXZhciBsPW5bb109e2V4cG9ydHM6e319O3Rbb11bMF0uY2FsbChsLmV4cG9ydHMsZnVuY3Rpb24oZSl7dmFyIG49dFtvXVsxXVtlXTtyZXR1cm4gcyhuP246ZSl9LGwsbC5leHBvcnRzLGUsdCxuLHIpfXJldHVybiBuW29dLmV4cG9ydHN9dmFyIGk9dHlwZW9mIHJlcXVpcmU9PVwiZnVuY3Rpb25cIiYmcmVxdWlyZTtmb3IodmFyIG89MDtvPHIubGVuZ3RoO28rKylzKHJbb10pO3JldHVybiBzfSkiLCIjIFByb2dyZXNzQmFyID0gcmVxdWlyZSAncHJvZ3Jlc3NiYXIuanMnXG4jICQgPSByZXF1aXJlICdqcXVlcnknXG5cbmdhbWVXaWR0aCA9IDQxNSAvIDRcbiRnYW1lcyA9ICQoJy5nYW1lc3BhbmVsX19nYW1lcycpXG4kZ2FtZXMuY3NzICdsZWZ0JywgMFxuXG4kKCcuZ2FtZXNwYW5lbF9fYXJyb3ctLWxlZnQnKS5jbGljayAtPlxuXHRsZWZ0ID0gTWF0aC5taW4oMCwgcGFyc2VGbG9hdCgkZ2FtZXMuY3NzICdsZWZ0JykgKyBnYW1lV2lkdGggKiAzKVxuXHQkZ2FtZXMuc3RvcCgpLmFuaW1hdGUgJ2xlZnQnOiBsZWZ0LCA0MDBcblxuJCgnLmdhbWVzcGFuZWxfX2Fycm93LS1yaWdodCcpLmNsaWNrIC0+XG5cdGxlZnQgPSBNYXRoLm1heCgtZ2FtZVdpZHRoICogJGdhbWVzLmxlbmd0aCwgcGFyc2VGbG9hdCgkZ2FtZXMuY3NzICdsZWZ0JykgLSBnYW1lV2lkdGggKiAzKVxuXHQkZ2FtZXMuc3RvcCgpLmFuaW1hdGUgJ2xlZnQnOiBsZWZ0LCA0MDBcblxuXG5mb3IgZWxlbWVudCBpbiBkb2N1bWVudC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKCdnYW1lc3BhbmVsX19nYW1lJylcblx0Y2lyY2xlID0gbmV3IFByb2dyZXNzQmFyLkNpcmNsZSBlbGVtZW50LFxuXHRcdGNvbG9yOiAnIzUwZTNjMidcblx0XHRzdHJva2VXaWR0aDogNFxuXHRcdHRyYWlsQ29sb3I6ICdibGFjaydcblx0XHRkdXJhdGlvbjogMTUwMFxuXHRcdGVhc2luZzogJ2VsYXN0aWMnXG5cdFx0ZmlsbDogJ3JnYmEoMCwwLDAsLjcpJ1xuXHRcdHRleHQ6XG5cdFx0XHR2YWx1ZTogJzAnXG5cdFx0c3RlcDogKHN0YXRlLCBiYXIpIC0+XG5cdFx0XHRiYXIuc2V0VGV4dCAoYmFyLnZhbHVlKCkgKiAxMDApLnRvRml4ZWQgMFxuXHRcdGNsaWNrOiAtPiBhbGVydCAnY2F0J1xuXHRjaXJjbGUucHJvZ3Jlc3MgPSBlbGVtZW50LmRhdGFzZXQucHJvZ3Jlc3MgLyAxMDBcblxuXHRzZXRUaW1lb3V0ICgtPiBAYW5pbWF0ZSBAcHJvZ3Jlc3MpLmJpbmQoY2lyY2xlKSwgMTAwMFxuIl19
