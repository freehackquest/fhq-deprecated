<?php

include_once dirname(__FILE__)."/../tex.php";

$bShow = false;
if (!isset($doc)) {
	$bShow = true;
	$doc = array();
}

$doc['quests'] = array(
	'name' => 'Quests',
	'description' => 'Methods for processing quests.',
  'uri' => 'api/quests/',
	'methods' => array(
    // take
    'take' => array(
			'name' => 'Take Quest',
			'description' => 'Methods for take quest',
			'uri' => 'api/quests/take.php',
			'access' => 'authorized users',
			'input' => array(
				'questid' => array(
					'type' => 'integer',
					'description' => 'Identificator of quest',
				),
				'token' => array(
					'type' => 'string',
					'description' => 'Access token',
				),
			),       
			'output' => array(
				'errors' => array(
					'3001' => 'checkGameDates (TODO)',
					'3002' => 'Not found parameter "questid"',
  				'3003' => 'Parameter "questid" must be numeric',
  				'3004' => 'quest already takes',
  				'3005' => 'not found quest',
  				'3006' => 'Database errors',
				),
				'successfull' => array(
					'result' => 'ok',
          // TODO
				),
			),
		),

    // pass
    'pass' => array(
			'name' => 'Try Pass Quest',
			'description' => 'Methods for try pass quest',
			'uri' => 'api/auth/pass.php',
			'access' => 'authorized users',
			'input' => array(      
        'questid' => array(
					'type' => 'integer',
					'description' => 'Identificator of quest',
				),
        'answer' => array(
					'type' => 'string',
					'description' => 'Answer to quest',
				),
        'token' => array(
					'type' => 'string',
					'description' => 'Access token for user',
				),
      ),
			'output' => array(
				'errors' => array(
          '3101' => 'Check Game Dates (TODO)',
					'3102' => 'Not found parameter "questid"',
  				'3103' => 'Not found parameter "answer"',
  				'3104' => 'Parameter "answer" must be not empty',
  				'3105' => 'Parameter "questid" must be numeric',
  				'3106' => 'Answer incorrect',
          '3107' => 'Quest already passed',
          '3108' => 'Not found quest',
          '3109' => 'Database errors',
				),
				'successfull' => array(
					'result' => 'ok',
          // TODO
				),
			),
		),
    // end
	),
);


if ($bShow)
	print_doc($doc);
