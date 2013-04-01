<?php

class InfoController extends Yaf_Controller_Abstract {

    private $infomodel;
    private $PAGEMAX = 100;
    public function init()
    {
        $this->infomodel = new InfoModel();
        Yaf_Dispatcher::getInstance()->disableView();
    }

    public function infoAction()
    {
        $typeid = $this->getRequest()->getParam('typeid');
        $pager = intval($this->getRequest()->getParam('pager'));
        
        $pager = $pager == 0 ? 1 : $pager;
        if ($typeid =='')
        {
            $data = $this->infomodel->fetchInfo(($pager - 1)*$this->PAGEMAX, $this->PAGEMAX);
            $infonum = $this->infomodel->fetchInfoNum();
        }
        else {
            $data = $this->infomodel->fetchInfoByTypeId($typeid,($pager - 1)*$this->PAGEMAX, $this->PAGEMAX);
            $infonum = $this->infomodel->fetchInfoNumByTypeId
                    ($typeid);
        }
        $pagenum = round(0.4999 + $infonum / $this->PAGEMAX);
        
        $backdata = json_encode(array(
            'data'=> $data,
            'infonum' => $infonum,
            'pagenum' => $pagenum
        ));
        echo 'datacbfunc('.$backdata.')';
    }
    public function typeAction() {
        $result = $this->infomodel->fetchInfoType();
        $backdata = json_encode(array(
            'type' => $result
        ));
        echo 'typecbfunc('.$backdata.')';
    }

}
