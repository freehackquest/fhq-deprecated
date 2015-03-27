<?php

include_once dirname(__FILE__)."/../tex.php";

$bShow = false;
if (!isset($doc)) {
	$bShow = true;
	$doc = array();
}

$doc['events'] = array(
	'name' => 'Events/News',
	'description' => 'Methods for process with events.',
	'uri' => 'api/events/',
	'methods' => array(

		// count
		'count' => array(
			'name' => 'Count of the events',
			'description' => 'Methods for getting count of the new events in the system',
			'uri' => 'api/events/count.php',
			'access' => 'all',
			'input' => array(
				'id' => array(
					'type' => 'integer',
					'required' => 'yes',
					'description' => 'your latest id (user seen latest id)',
				),
				'type' => array(
					'type' => 'string',
					'required' => 'no',
					'description' => 'if you wish you can filter by type',
				),
			),
			'output' => array(
				'errors' => array(
					'4101' => 'Not found parameter "id"',
					'4102' => 'id must be integer',
					'4103' => '%errors from database%',
				),
				'successfull' => array(
					'result' => 'ok',
					'data' => array("count" => "19",),			
				),
			),
		),

		// types
		'types' => array(
			'name' => 'Types of events',
			'description' => 'Get allows events type',
			'uri' => 'api/events/types.php',
			'access' => 'all',
			'input' => array(
			),
			'output' => array(
				'errors' => array(
				),
				'successfull' => array(
					'result' => 'ok',
					'data' => array('info', 'quest', 'games', 'system', 'user'),
				),
			),
		),

		// list
		'list' => array(
			'name' => 'Get new events',
			'description' => 'Methods for getting new events in the system',
			'uri' => 'api/events/list.php',
			'access' => 'all',
			'input' => array(
				'id' => array(
					'type' => 'integer',
					'required' => 'no',
					'description' => 'your latest id (user seen latest id) or if parameter was not set then will be returned last 50 rows',
				),
				'type' => array(
					'type' => 'string',
					'required' => 'no',
					'description' => 'if you wish you can filter by type',
				),
			),
			'output' => array(
				'errors' => array(
					'4201' => 'Not found parameter "id"',
					'4202' => 'id must be integer',
					'4203' => '%errors from database%',
				),
				'successfull' => array(
					'result' => 'ok',
					'data' => array(
						"new_id" => "1",
						'events' => array(
							array('id' => '1', 'type' => 'info', 'message' => 'test',),
						),
					),
				),
			),
		),
    // end
	),
	
);

if ($bShow)
	print_doc($doc);
