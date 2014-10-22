<?php
class teams extends CActiveRecord {
	public $date_create;

	public $date_change;

	public static function model($classname=__CLASS__)
	{
		return parent::model($classname);
	}

	public function rules()
	{
		return array(

			array('logo','length','max'=>255),
			array('title','length','max'=>255),
			array('uuid_team, rating, title, owner, date_create, date_change', 'required'),
			array('date_create, date_change', 
				'default',
				'value'=>new CDbExpression('NOW()'),
				'setOnEmpty'=>false,
				'on'=>'insert'
				),
			);
	}

	public function published($desc=' DESC')
	{
		$this->getDbCriteria()->mergeWith(array(
			'order' => 'id'.$desc,
		));

		return $this;
	}

	public function beforeSave()
	{
		if ($this->isNewRecord) {
			$this->date_create = new CDbExpression('NOW()');
		}

		$this->date_change = new CDbExpression('NOW()');

		return parent::beforeSave();
	}
	public function primaryKey() 
	{
		return 'id';
	}
	public function teams()
	{
		return 'teams';
	}
}