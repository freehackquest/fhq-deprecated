
function reloadPageByTimer() {
	window.location.href = "index.php";
}

function startTimer()
{
	var objTime = {
		"days" : 0,
		"hours" : 0,
		"minutes" : 0,
		"seconds" : 0
	};

	for (var k in objTime)
		objTime[k] = parseInt(document.getElementById(k).innerHTML);
	
	var secs = objTime.seconds;
	secs += 60 * objTime.minutes;
	secs += 60 * 60 * objTime.hours;
	secs += 60 * 60 * 24 * objTime.days;
		
	if (secs > 0)
		secs--;

	objTime.seconds = secs % 60;
	objTime.minutes = Math.floor(secs / 60) % 60;
	objTime.hours = Math.floor(secs / (60*60)) % 24;
	objTime.days = Math.floor(secs / (60*60*24));
	
	for (var k in objTime)
		document.getElementById(k).innerHTML = objTime[k];

	if (objTime.seconds == 0 && objTime.minutes == 0 && objTime.hours == 0 && objTime.days == 0) 
		setTimeout(reloadPageByTimer, 2000);
	else
		setTimeout(startTimer, 1000);
}
