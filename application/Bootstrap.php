<?php

/**
 * 所有在Bootstrap类中, 以_init开头的方法, 都会被Ap调用,
 * 这些方法, 都接受一个参数:Yaf_Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 */
class Bootstrap extends Yaf_Bootstrap_Abstract{
	public function _initSession($dispatcher) {
		/*
		 * start a session 
		 */
		Yaf_Session::getInstance()->start();
	}

	public function _initConfig() {
		$config = Yaf_Application::app()->getConfig();
		Yaf_Registry::set("config", $config);
                Yaf_Registry::set("dbconfig",$config->database);
	}

	public function _initPlugin(Yaf_Dispatcher $dispatcher) {
	}

	public function _initRoute(Yaf_Dispatcher $dispatcher) {
		//$router = Yaf_Dispatcher::getInstance()->getRouter();
		/**
		 * add the routes defined in ini config file
		 */
		//$router->addConfig(Yaf_Registry::get("config")->routes);
		/**
		 * test this route by access http://yourdomain.com/product/list/?/?/
		 */
		/*$route  = new Yaf_Route_Rewrite(
			"/product/list/:id/:name",
			array(
				"controller" => "product",
				"action"	 => "info",
			)
		);

		$router->addRoute('dummy', $route);*/
	}


	public function _initDefaultName(Yaf_Dispatcher $dispatcher) {
		/**
		 * actully this is unecessary, since all the parameters here is the default value of Yaf
		 */
		$dispatcher->setDefaultModule("Index")->setDefaultController("Index")->setDefaultAction("index");
	}
}
