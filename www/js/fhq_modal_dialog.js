
function showModalDialog(content)
{
	// document.getElementById('modal_dialog').style.top = document.body.
	document.getElementById('modal_dialog').style.visibility = 'visible';
	document.getElementById('modal_dialog_content').innerHTML = content;
	document.documentElement.style.overflow = 'hidden';  // firefox, chrome
    document.body.scroll = "no"; // ie only
    document.onkeydown = function(evt) {
		if (evt.keyCode == 27)
			closeModalDialog();
	}
}

function closeModalDialog()
{
	document.getElementById('modal_dialog').style.visibility = 'hidden';
	document.documentElement.style.overflow = 'auto';  // firefox, chrome
    document.body.scroll = "yes"; // ie only
    document.onkeydown = null;
    document.getElementById('modal_dialog_content').innerHTML = "";
}
