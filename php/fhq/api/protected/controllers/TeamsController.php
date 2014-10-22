<?php

/**
 * usersController is the controller to handle user requests.
 */

class TeamsController extends CController
{
	private $method = 'teams';

	public function actionCreate()
	{
		if (!Yii::app()->params->log_in)
			Message::Error('You are not logged');

		if (!Yii::app()->request->getParam('title'))
			Message::Error("Title is empty");

		
		$teams = new Teams;

		$teams->setIsNewRecord(true);

		$teams->title = Yii::app()->request->getParam('title');

		$teams->owner = Yii::app()->params->user['user_id'];

		$teams->uuid_team = new CDbExpression('UUID()');
		$teams->rating = 0;

		$logo = Yii::app()->request->getParam('logo');
		$teams->logo = (!empty($logo) ? $logo : '');

		$teams->json_data = CJSON::encode(array());
		$teams->json_security_data = CJSON::encode(array());
		
		$teams->date_create = new CDbExpression('NOW()');
		$teams->date_change = new CDbExpression('NOW()');


		if ($teams->save())
			Message::Success(array('id' => $teams->id));
		else
			Message::Error($teams->getErrors());

	}


	public function actionList()
	{
		$teams = Teams::model()->published()->findAll(array(
			'select' => 'id, rating, logo, title',
		));
		
		$array = array();
		$count = 0;

		foreach($teams as $value) {
			$count++;
			// False - return without null values;
			$array[] = $value->getAttributes(false);
		}

		Message::Success(array(
			'count' => $count,
			'items' => $array
		));
	}
	
	public function actionGet()
	{
		if (!Yii::app()->request->getParam('id'))
			Message::Error('Parameter id is missing');

		$id = (int)Yii::app()->request->getParam('id');
		$games = Teams::model()->findByPk($id,array(
			'select' => 'id, json_data, date_create, date_last_signin',
			'condition'=>'id=:id',
    		'params'=>array(':id'=> $id),
		));

		if(empty($games))
			Message::Error('The games does not exist.');
		
		Message::Success($games->getAttributes(false));
	}

	public function actionDelete()
	{
		if (!Yii::app()->params->log_in)
			Message::Error('You are not logged');

		$id = (int)Yii::app()->request->getParam('id');
		if (!$id) 
			Message::Error('Parameter id is missing');

		$teams = Teams::model()->findByPk($id);

		if (!$teams)
			Message::Error("The team doesn't exists");

		print_r($teams);

		// if (!Yii::app()->params->scopes('admin'))
		// 	Message::Error("You do not have sufficient permissions");

		// if (!Yii::app()->request->getParam('id'))
		// 	Message::Error('Parameter id is missing');

		// $users = Users::model()->findByPk((int)Yii::app()->request->getParam('id'));

		// if (empty($users))
		// 	Message::Error('The user does not exist');

		// $users->delete();

		// Message::Success('1');
	}

	// Не готово
	public function actionEdit()
	{
		if (!Yii::app()->request->getParam('id'))
			Message::Error('Parameter id is missing');

		if (!Yii::app()->request->getParam('mail'))
			Message::Error("Mail is empty");

		if (!Yii::app()->request->getParam('nick'))
			Message::Error("Nick is empty");

		// Пока зашитый id, в будущем берем по access_token
		$users = Users::model()->findByPk(19);
		// $users = Users::model()->findByPk((int)Yii::app()->request->getParam('id'));

		if (empty($users))
			Message::Error('The user does not exist');
				
		$users->mail = Yii::app()->request->getParam('mail');
		$users->nick = Yii::app()->request->getParam('nick');

		$users->date_last_signup = new CDbExpression('NOW()');

		
		if ($users->save())
			Message::Success('1');
		else
			Message::Error($users->getErrors());
	}
}