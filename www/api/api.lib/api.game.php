<?php
class APIGame {

	// TODO must be redesign (and move to api.helpers.php)
	static function checkGameDates(&$message) {

		if (APIHelpers::$FHQSESSION != NULL) {
			if (!isset(APIHelpers::$FHQSESSION['game'])) {
				$message = 'Select game please';
				return false;
			}
		} else {
			if (!isset($_SESSION['game'])) {
				$message = 'Select game please';
				return false;
			}
		}

		if (APISecurity::isAdmin() || APISecurity::isTester())
			return true;

		$date_start = new DateTime();
		date_timestamp_set($date_start, strtotime($_SESSION['game']['date_start']));
		$date_stop = new DateTime();
		date_timestamp_set($date_stop, strtotime($_SESSION['game']['date_stop']));
		$date_restart = new DateTime();
		date_timestamp_set($date_restart, strtotime($_SESSION['game']['date_restart']));
		$date_current = new DateTime();
		date_timestamp_set($date_current, time());
		$di_start = $date_current->diff($date_start);
		$di_stop = $date_current->diff($date_stop);
		$di_restart = $date_current->diff($date_restart);

		$bWillBeStarted = ($di_start->invert == 0);
		$bWillBeRestarted = ($di_stop->invert == 1 && $di_restart->invert == 0);
		
		// echo date_diff($date_current, $date_start)."<br>";
		if ( $bWillBeStarted || $bWillBeRestarted) {
			$label = $bWillBeStarted ? 'Game will be started after: ' : 'Game will be restarted after: ';
			$di = $bWillBeStarted ? $di_start : $di_restart;

			$message = $label.'<br>
				<div class="fhq_timer" id="days">'.$di->d.'</div> day(s) 
				<div class="fhq_timer" id="hours">'.$di->h.'</div> hour(s) 
				<div class="fhq_timer" id="minutes">'.$di->i.'</div> minute(s)
				<div class="fhq_timer" id="seconds">'.$di->s.'</div> second(s)<br>
				<div id="reload_content" onclick="startTimer();"/></div> <br><br>
			';
			
			return false;
		}

		return true;
	}
	
	// TODO: will be deprecated
	static function id() {
		if (APIHelpers::$FHQSESSION != NULL) {
			return (isset(APIHelpers::$FHQSESSION['game']) && isset(APIHelpers::$FHQSESSION['game']['id'])) ? APIHelpers::$FHQSESSION['game']['id'] : 0;
		}
		return (isset($_SESSION['game']) && isset($_SESSION['game']['id'])) ? $_SESSION['game']['id'] : 0;
	}
	
	// TODO: will be deprecated
	static function title() {
		return (isset($_SESSION['game']) && isset($_SESSION['game']['title'])) ? $_SESSION['game']['title'] : 'Unknown game';
	}
	
	// TODO: will be deprecated
	static function score() {
		return (isset($_SESSION['user']) && isset($_SESSION['user']['score'])) ? $_SESSION['user']['score'] : 0;
	}
}
