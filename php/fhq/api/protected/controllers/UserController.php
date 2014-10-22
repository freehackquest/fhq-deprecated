<?php

/**
 * usersController is the controller to handle user requests.
 */

class UserController extends CController
{
	private $method = 'user';

	public function actionList()
	{
		$users = Users::model()->published('')->paginator()->findAll(array(
			'select' => 'id, role, nick, activated',
			//'condition' => 'deleted=0' //проверка на удаление 
		));
		
		$array = array();
		$count = 0;

		foreach($users as $value) {
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

		$users = Users::model()->findByPk(Yii::app()->request->getParam('id'),array(
			'select' => 'id, role, nick, activated, json_data, date_create, date_last_signin',
			'condition'=>'id=:id',
    		'params'=>array(':id'=> Yii::app()->request->getParam('id')),
		));

		if(empty($users))
			Message::Error('The user does not exist.');
		
		Message::Success($users->getAttributes(false));
	}

	public function actionDelete()
	{
		if (!Yii::app()->params->log_in)
			Message::Error('You are not logged');

		if (!Yii::app()->params->scopes('admin'))
			Message::Error("You do not have sufficient permissions");

		if (!Yii::app()->request->getParam('id'))
			Message::Error('Parameter id is missing');

		$users = Users::model()->findByPk((int)Yii::app()->request->getParam('id'));

		if (empty($users))
			Message::Error('The user does not exist');

		$users->delete();

		Message::Success('1');
	}

	// Не готово
	public function actionEdit()
	{
		exit('method closed');
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

	public function actionSearch() 
	{
		$nick = Yii::app()->request->getParam('nick');
		if (!$nick)
			Message::Error("Nick is empty");


		$users = Users::model()->search($nick)->paginator()->findAll(array(
		    'select' => 'id, role, nick, activated',
		));

		$array = array();
		$count = 0;

		foreach($users as $value) {
			$count++;
			// False - return without null values;
			$array[] = $value->getAttributes(false);
		}

		Message::Success(array(
			'count' => $count,
			'items' => $array
		));
	}
}