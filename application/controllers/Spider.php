<?php

class SpiderController extends Yaf_Controller_Abstract
{
    private $infomodel;
    private $eliminate_id = array('10');
    public function init()
    {
        $this->infomodel = new InfoModel();
        Yaf_Dispatcher::getInstance()->disableView();
    }
    public function indexAction()
    {
        $data  = urldecode($this->getRequest()->getPost('data'));
	$data = json_decode($data,true);
	for($i=0;$i<count($data);$i++)
	{
            if (!in_array($data[$i]['id'],$this->eliminate_id))
            {
		$block = $this->infomodel->fetchInfoById($data[$i]['id']);
		if (!count($block))
                {
                    $data[$i]['publish_status'] = 1;
                    $this->infomodel->insertInfo($data[$i]);
                }
		else {
			if ($data[$i]['insert_time'] != $block[0]['insert_time'])
                            $this->infomodel->updateInfo($data[$i],array('id' => $data[$i]['id']));
		}
            }
	}
    }
}
?>
