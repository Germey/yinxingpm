<?php
class CommonAction extends BaseAction{

    function alert($msg) {
        header('Content-type: text/html; charset=UTF-8');
        die(json_encode(array('error' => 1, 'message' => $msg)));
    }

    function ajax_image_dialog(){
        $id = $this->_param("id");
        if($id){
            $image = M("Images")->getById($id);
        }
        $this->assign("image", $image);
        $html = $this->fetch("Common:Public:image_dialog");
        json($html, "dialog");
    }

    function error_page(){
        $this->display("Common:Public:error_page");
    }
}
