<?php

class IndexController extends Yaf_Controller_Abstract {
	/**
	 * Action Map
	 */
	public $actions = array(
		"action" => "actions/Index.php"
	);

	/**
	 * 如果定义了控制器的init的方法, 会在__construct以后被调用
	 */
	public function init() {
		$config = Yaf_Application::app()->getConfig();
	}

	public function indexAction() {

	}

}
