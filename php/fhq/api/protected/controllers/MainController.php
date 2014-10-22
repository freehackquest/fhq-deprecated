<?php

/**
 * SiteController is the default controller to handle user requests.
 */
class MainController extends CController
{
	function __construct() {
		echo 'hello';
	}
	/**
	 * Index action is the default action in a controller.
	 */
	public function actionIndex()
	{

		echo 'Hello World';
	}

	public function actionMain()
	{
		echo 'This is main action';
	}
}