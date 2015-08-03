<?php

class AttachmentDirModel extends BaseModel{

    protected $autoCheckFields = false;

    public function getDirectories($type,$id) {

        // if($type != 'partner' && $type != 'project'){
        //     echo '参数type传入不正确';
        //     return null;
        // }
        if(!$id){
            echo '参数id传入不正确';
            return null;
        }

        $data = M('AttachmentsDir')->where('type="%s"',$type)->select();
        $atts = D($type.'Attachments')->where(array($type.'_id' => $id))->select();

        $diretories = array();
        foreach($data as $key => $value){
            $diretories[$key]['dirname'] = $value['dir_name'];
            $diretories[$key]['id'] = $value['id'];
            foreach($atts as $k => $v){
                if($value['id'] == $v['dir_id']){
                    $diretories[$key]['child'][] = $v;
                }
            }
        }
        $nodir = M($type.'Attachments')->where(array('type' => $type, 'dir_id' => 0,$type.'_id' => $id))->select();
        $dir_data = D('AttachmentsDir')->where(array('type' => $type))->select();
        
        return array('diretories' => $diretories,'dir_data' => $dir_data,'nodir' => $nodir);
    }
}
