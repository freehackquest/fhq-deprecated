<?php
class Attempts extends CActiveRecord {

	public static function model($classname=__CLASS__)
	{
		return parent::model($classname);
	}

	public function rules()
	{
		return array(
			// required
			array('user, quest, user_answer, real_answer, time','required'),
			
			//length
			array('user', 'numerical', 'integerOnly'=>true),
			array('quest', 'numerical', 'integerOnly'=>true),
			array('user_answer', 'length','min'=>8,'max'=>255),
			array('real_answer','length','min'=>8,'max'=>255),
			array('user_answer', 'filter', 'filter' => 'trim'),
		);
	}

	// public function relations() 
	// {
	// 	return array(
 //            'stitle' => array(self::BELONGS_TO, 'QuestSection', 'section'),
 //        );
	// }

	public function tableName()
	{
		return '{{attempts}}';
	}
}