
function showModalDialog(content)
{
	document.getElementById('modal_dialog').style.visibility = 'visible';
	document.getElementById('modal_dialog_content').innerHTML = content;
}


function closeModalDialog()
{
	document.getElementById('modal_dialog').style.visibility = 'hidden';
}
