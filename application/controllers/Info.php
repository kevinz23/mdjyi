<?php

class InfoController extends Yaf_Controller_Abstract {

    private $infoModel;
    private $PAGEMAX = 100;
    public function init()
    {
        $this->infoModel = new InfoModel();
        Yaf_Dispatcher::getInstance()->disableView();
    }

    public function infoAction()
    {
        $typeid = isset($_GET['typeid']) ? intval($_GET['typeid']) : '';
        $pager = isset($_GET['pager']) ? intval($_GET['pager']) : '';
        
        $pager = $pager == 0 ? 1 : $pager;
        if ($typeid ==''){
            $data = $this->infoModel->fetchInfo(($pager - 1)*$this->PAGEMAX, $this->PAGEMAX);
            $infonum = $this->infoModel->fetchInfoNum();
        }
        else {
            $data = $this->infoModel->fetchInfoByTypeId($typeid,($pager - 1)*$this->PAGEMAX, $this->PAGEMAX);
            $infonum = $this->infoModel->fetchInfoNumByTypeId($typeid);
        }
        $pagenum = round(0.4999 + $infonum / $this->PAGEMAX);
       
        for($i=0;$i<count($data);$i++)
            $data[$i]['content'] = mb_substr($data[$i]['content'], 0, 75, 'utf-8');
        $backdata = json_encode(array(
            'data'=> $data,
            'infonum' => $infonum,
            'pagenum' => $pagenum
        ));
        echo 'datacbfunc('.$backdata.')';
    }
    public function detailAction()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : '';
        if ($id != '')
        {
            $detail = $this->infoModel->fetchInfoById($id);
            $backdata = json_encode($detail);
            echo 'detailcbfunc('.$backdata.')';
        }
    }
    public function typeAction() {
        $result = $this->infoModel->fetchInfoType();
        $backdata = json_encode(array(
            'type' => $result
        ));
        echo 'typecbfunc('.$backdata.')';
    }

}
