<?
include_once "fhq_class_security.php";
include_once "fhq_class_database.php";


class fhq_page_income
{
	function echo_head()
	{
		include "config/config.php";
		echo '
		<script language="Javascript" type="text/javascript" src="js/jquery-1.4.1.js"></script>
		<script language="Javascript" type="text/javascript" src="js/jquery.lwtCountdown-1.0.js"></script>
		<script language="Javascript" type="text/javascript" src="js/misc.js"></script>
		<link rel="Stylesheet" type="text/css" href="styles/precour.css"></link>
		<script>
			function exit()
			{
			  if (window.XMLHttpRequest)
			  {// code for IE7+, Firefox, Chrome, Opera, Safari
				 xmlhttp=new XMLHttpRequest();
			  };  
			  xmlhttp.onreadystatechange=function()
			  {
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					if(xmlhttp.responseText == "OK")
					{
						window.location.href = \'index.php\';				
					}
				}
			  }
			  xmlhttp.open("GET","index.php?exit",true);
			  xmlhttp.send();
			};
		</script>
	';
	}
	
	function title()
	{
		return 'coming soon...<br><font size=2><a class="btn btn-small btn-info" href="javascript:void(0);" onclick="exit();">logout</a></font> ';
	}

	function echo_content()
	{
		include "config/config.php";
		$income = new fhq_income();
		$date = $income->getDate();
		
		echo '
		<!-- Начало панели счетчика -->
		<div id="countdown_dashboard">
			<div class="dash weeks_dash">
				<span class="dash_title">недель</span>
				<div class="digit">'.$date['weeks'][0].'</div>
				<div class="digit">'.$date['weeks'][1].'</div>
			</div>

			<div class="dash days_dash">
				<span class="dash_title">дней</span>
				<div class="digit">'.$date['days'][0].'</div>
				<div class="digit">'.$date['days'][1].'</div>
			</div>

			<div class="dash hours_dash">
				<span class="dash_title">hours</span>
				<div class="digit">'.$date['hours'][0].'</div>
				<div class="digit">'.$date['hours'][1].'</div>
			</div>

			<div class="dash minutes_dash">
				<span class="dash_title">minutes</span>
				<div class="digit">'.$date['mins'][0].'</div>
				<div class="digit">'.$date['mins'][1].'</div>
			</div>

			<div class="dash seconds_dash">
				<span class="dash_title">seconds</span>
				<div class="digit">'.$date['secs'][0].'</div>
				<div class="digit">'.$date['secs'][1].'</div>
			</div>
		</div>
		';
	}
	
	function echo_onBodyEnd() {
			include "config/config.php";
			echo '<script language="javascript" type="text/javascript">
			jQuery(document).ready(function() {
				$(\'#countdown_dashboard\').countDown({
					targetDate: {
						\'day\': 		'.$config['targetDate']['day'].',
						\'month\': 	'.$config['targetDate']['month'].',
						\'year\': 	'.$config['targetDate']['year'].',
						\'hour\': 	'.$config['targetDate']['hour'].',
						\'min\': 		'.$config['targetDate']['minute'].',
						\'sec\': 		'.$config['targetDate']['second'].'
					}
				});
			});
		</script>';
	}
};
?>
