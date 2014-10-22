<?php

/**
 * usersController is the controller to handle user requests.
 */

class QuestController extends CController
{
	private $method = 'quest';

	public function __construct() {
		if (!Yii::app()->params->log_in) {
			Message::Error('You are not logged');
		}
	}

	public function actionCreate()
	{
		if (!Yii::app()->params->scopes('admin'))
			Message::Error("You do not have sufficient permissions");

		if (!Yii::app()->request->getParam('title'))
			Message::Error('Parameter title is missing');

		if (!(int)Yii::app()->request->getParam('section'))
			Message::Error('Parameter section is missing');

		if (!Yii::app()->request->getParam('short_text'))
			Message::Error('Parameter short_text is missing');
		
		if (!Yii::app()->request->getParam('full_text'))
			Message::Error('Parameter full_text is missing');

		if (!Yii::app()->request->getParam('answer'))
			Message::Error('Parameter answer is missing');

		if (!(int)Yii::app()->request->getParam('score'))
			Message::Error('Parameter score is missing');

		$section = QuestSection::model()->findByPk((int)Yii::app()->request->getParam('section'));
		if (!$section)
			Message::Error('Quest section does not exists');

		$quest = new Quests;
		$quest->setIsNewRecord(true);

		$quest->uuid = new CDbExpression('UUID()');

		$quest->title = Yii::app()->request->getParam('title');

		$quest->section = $section->id;

		$quest->owner = Yii::app()->params->user['user_id'];
		$quest->moderate = Yii::app()->params->user['user_id'];

		$quest->short_text = Yii::app()->request->getParam('short_text');
		$quest->full_text = Yii::app()->request->getParam('full_text');
		$quest->answer = Yii::app()->request->getParam('answer');
		$quest->score = (int)Yii::app()->request->getParam('score');

		$quest->time = new CDbExpression('NOW()');

		if($quest->save())
			Message::Success($quest->id);
		else
			Message::Error($quest->getErrors());
	}

	public function actionGet() 
	{
		$id = (int)Yii::app()->request->getParam('id');
		if (!$id)
			Message::Error('Parameter id is missing');

		$quest = Quests::model()->with('stitle')->findByPk($id, array(
			'select' => 'id, title, section, short_text, full_text, score'
		));
	
		if(!$quest)
			Message::Error('Quest is not found');

		$out = $quest->getAttributes(false);

		$out['section'] = array(
			'id' => $out['section'],
			'title' => $quest->stitle->title
		);
		
		Message::Success($out);
	}

	public function actionSave() 
	{
		if (!Yii::app()->params->scopes('admin'))
			Message::Error("You do not have sufficient permissions");

		$id = (int)Yii::app()->request->getParam('id');
		if (!$id)
			Message::Error('Parameter id is missing');

		$quest = $this->getQuest($id);

		if (!Yii::app()->request->getParam('title'))
			Message::Error('Parameter title is missing');

		if (!(int)Yii::app()->request->getParam('section'))
			Message::Error('Parameter section is missing');

		if (!Yii::app()->request->getParam('short_text'))
			Message::Error('Parameter short_text is missing');
		
		if (!Yii::app()->request->getParam('full_text'))
			Message::Error('Parameter full_text is missing');

		if (!Yii::app()->request->getParam('answer'))
			Message::Error('Parameter answer is missing');

		if (!(int)Yii::app()->request->getParam('score'))
			Message::Error('Parameter score is missing');
		
		$section = QuestSection::model()->findByPk((int)Yii::app()->request->getParam('section'));
		if (!$section)
			Message::Error('Quest section does not exists');

		$quest->title = Yii::app()->request->getParam('title');

		$quest->section = $section->id;

		$quest->short_text = Yii::app()->request->getParam('short_text');
		$quest->full_text = Yii::app()->request->getParam('full_text');
		$quest->answer = Yii::app()->request->getParam('answer');
		$quest->score = (int)Yii::app()->request->getParam('score');
		
		if($quest->save())
			Message::Success($quest->id);
		else
			Message::Error($quest->getErrors());
	}

	public function actionDelete() 
	{
		if (!Yii::app()->params->scopes('admin'))
			Message::Error("You do not have sufficient permissions");

		$id = (int)Yii::app()->request->getParam('id');
		if (!$id)
			Message::Error('Parameter id is missing');

		$quest = $this->getQuest($id);

		$quest->delete();

		Message::Success('1');
	}


	public function actionList()
	{
		if (!Yii::app()->params->scopes('admin'))
			Message::Error("You do not have sufficient permissions");
		
		$section = Quests::model()->
			published('')->
			with(array(
				'stitle' => array(
					'select' => 'id, title'
				)
			))->
			paginator()->
			findAll(array('select' => 'id, title, section, short_text, full_text, score'));
		// print_r($section);
		$array = array();
		$count = 0;

		foreach($section as $value) {
			$count++;
			// False - return without null values;
			$arr = $value->getAttributes(false);
			$arr['section'] = $value->stitle->getAttributes(false);

			$array[] = $arr;
		}

		Message::Success(array(
			'count' => $count,
			'items' => $array
		));
	}

	public function actionTake()
	{
		$id = (int)Yii::app()->request->getParam('id');
		if (!$id)
			Message::Error('Parameter id is missing');

		$check_user_quest = UserQuest::model()->find('quest=:quest_id AND user=:user_id', array(
			':quest_id'=>$id,
			':user_id'=>Yii::app()->params->user['user_id'],
		));
		
		if ($check_user_quest) {
			Message::Error('You are already take this quest');
		}

		$quest = $this->getQuest($id, array('with' => 'stitle'));
		$attr = $quest->attributes;
		$attr['section'] = $quest->getRelated('stitle');

		$user_quest = new UserQuest();

		$user_quest->user = Yii::app()->params->user['user_id'];
		$user_quest->quest = $quest->id;
		$user_quest->start_time = time();
		$user_quest->end_time = 0;

		if ($user_quest->save())	
			Message::Success($attr);
		else
			Message::Error($user_quest->getErrors());
	}
	public function actionPass()
	{
		$id = (int)Yii::app()->request->getParam('id');
		if (!$id)
			Message::Error('Parameter id is missing');

		$answer = Yii::app()->request->getParam('answer');
		if (!$answer)
			Message::Error('Parameter answer is missing');

		$quest = $this->getQuest($id);

		$user_quest = UserQuest::model()->find('user=:user_id AND quest=:quest_id', array(
				':user_id' => Yii::app()->params->user['user_id'],
				':quest_id' => $id
			)
		);

		if(!$user_quest) {
			Message::Error('You are not take this quest');
		}

		$attempts = new Attempts();

		$attempts->user = Yii::app()->params->user['user_id'];
		$attempts->quest = $quest->id;
		
		$attempts->user_answer = $answer;
		$attempts->real_answer = $quest->answer;

		$attempts->time = new CDbExpression('NOW()');

		if(!$attempts->save()) 
		{
			Message::Error($attempts->getErrors());			
		}

		$success = ($quest->answer == $answer);

		if ($success) {
			$user_quest->end_time = time();
			
			if (!$user_quest->save())
				Message::Error($user_quest->getErrors());

		}
		
		

		Message::Success($success);
	}

	public function actionAddGames() {
		if (!Yii::app()->params->scopes('admin'))
			Message::Error("You do not have sufficient permissions");
		
		$id = (int)Yii::app()->request->getParam('id');
		if (!$id)
			Message::Error('Parameter id is missing');

		$games_id = (int)Yii::app()->request->getParam('games_id');
		if (!$games_id)
			Message::Error('Parameter games_id is missing');

		$quest = $this->getQuest($id);
		
		$games = Games::model()->findByPk($games_id);
		
		if(!$games)
			Message::Error('Games is not found');

		$quest->games = $games->id;

		if ($quest->save())
			Message::Success(array('id' => $quest->id));
		else
			Message::Error($quest->getErrors());
	}

	public function actionRemoveGames() {
		if (!Yii::app()->params->scopes('admin'))
			Message::Error("You do not have sufficient permissions");
		
		$id = (int)Yii::app()->request->getParam('id');
		if (!$id)
			Message::Error('Parameter id is missing');

		$quest = $this->getQuest($id);

		$quest->games = 0;

		if ($quest->save())
			Message::Success(array('id' => $quest->id));
		else
			Message::Error($quest->getErrors());
	}
	public function actionListSection()
	{
		if (!Yii::app()->params->scopes('admin'))
			Message::Error("You do not have sufficient permissions");
		// Быть может добавим пагинатор позже, но сейчас это не нужно
		$section = QuestSection::model()->published('')->findAll(array(
			'select' => 'id, title',
		));
		
		$array = array();
		$count = 0;

		foreach($section as $value) {
			$count++;
			// False - return without null values;
			$array[] = $value->getAttributes(false);
		}

		Message::Success(array(
			'count' => $count,
			'items' => $array
		));
	}

	public function actionGetSection()
	{
		if (!Yii::app()->params->scopes('admin'))
			Message::Error("You do not have sufficient permissions");

		$id = (int)Yii::app()->request->getParam('id');
		if (!$id)
			Message::Error('Parameter id is missing');

		$section = QuestSection::model()->findByPk($id,
			array('select' => 'id, title')
		);
		
		if (!$section)
			Message::Error('Quest section does not exists');

		Message::Success($section->getAttributes(false));
	}

	public function actionAddSection()
	{
		if (!Yii::app()->params->scopes('admin'))
			Message::Error("You do not have sufficient permissions");

		$title = Yii::app()->request->getParam('title');
		if (!$title)
			Message::Error('Parameter title is missing');

		$section = new QuestSection;
		
		$section->setIsNewRecord(true);
		$section->title = $title;
		$section->uuid = new CDbExpression('UUID()');

		if ($section->save())
			Message::Success(array('id' => $section->id));
		else
			Message::Error($section->getErrors());

	}

	public function actionEditSection()
	{
		if (!Yii::app()->params->scopes('admin'))
			Message::Error("You do not have sufficient permissions");

		$id = (int)Yii::app()->request->getParam('id');
		if (!$id)
			Message::Error('Parameter id is missing');

		$title = Yii::app()->request->getParam('title');
		if (!$title)
			Message::Error('Parameter title is missing');

		$section = QuestSection::model()->findByPk($id);

		if (!$section)
			Message::Error('Quest section does not exists');

		$section->title = $title;

		if ($section->save())
			Message::Success(array('id' => $section->id));
		else
			Message::Error($section->getErrors());

	}

	public function actionDeleteSection()
	{
		if (!Yii::app()->params->scopes('admin'))
			Message::Error("You do not have sufficient permissions");

		$id = (int)Yii::app()->request->getParam('id');
		if (!$id)
			Message::Error('Parameter id is missing');

		$section = QuestSection::model()->findByPk($id);

		if (empty($section))
			Message::Error('Quest section does not exist');

		$section->delete();

		Message::Success('1');
	}

	/*
	 * $id - id quests
	 * $options - array 
	*/
	private function getQuest($id, $options=array())
	{
		$quest = Quests::model()->with(isset($options['with']) ? $options['with'] : false)->findByPk($id);
		
		if(!$quest)
			Message::Error('Quest is not found');

		return $quest;
	}
}