<?php
class AttachmentAction extends BaseAction {

    private $module_name = NULL;
    private $module_table_name = NULL;
    private $module_object_id_name = NULL;
    private $module_object_id = NULL;

    public function __construct() {
        parent::__construct();

        $this->module_name = $this->_param('module');
        //对应的contact_id，event_id等
        $this->module_table_name = ucfirst($this->module_name) . 'Attachments';          
        $this->module_object_id = $this->_param('object_id');          
        $this->module_object_id_name = $this->_param('module') .'_id';

        $this->attachment_types = array('user'=>'成员', 'project'=>'项目');
        $this->type = $type = $this->_get('type')?$this->_get('type'):'user';
        $this->dirs = D("AttachmentsDir")->where('type="'.$type.'"')->getField('id,dir_name',true);

        $this->all_columns = array(
            "dir_id" => array("display_name" => "目录", 'type'=>'select', 'options'=>$this->dirs),
            "title" => array("display_name" => "文件名", ),
            "related_name" => array("display_name" => "所属", 'order'=>$type.'_id'),
            "size" => array("display_name" => "大小",),
            "create_user_name" => array("display_name" => "创建人", 'order'=>'create_user_id'),
            "create_time" => array("display_name" => "创建时间"),
            "content" => array("display_name" => "备注"),
        );

        $this->list_views = array_keys($this->all_columns);
    }


    public function index() {
        $type = $this->type;

        $order = $this->_param("order");
        if($order){
            $tmp = explode('|', $order);            
            if($this->all_columns[$tmp[0]]['order']) {
                $tmp[0] = $this->all_columns[$tmp[0]]['order'];
            }
            $order = implode(' ', $tmp);
        }
        
        $filter = array();
        foreach(array_keys($this->all_columns) as $v) {
            $value = trim(htmlspecialchars($this->_get($v)));
            if(!$value) continue;
            $filter[$v] = array('like', '%'.$value.'%');
        }

        $count = D(ucfirst($type) . 'Attachments')->where($filter)->count();
        list($pagesize, $page_num, $this->pagestring) = pagestring($count, 20);
        $this->attachments = D("Attachments")->getModuleAttachments($type, $filter, $page_num, $pagesize, $order);
        // var_dump($attachments);
        $this->display();
    }

    public function ajax_save() {
        if(!$this->module_name) return json('bad module', 'alert');
        if(!$this->module_object_id) return json('bad object_id', 'alert');

        $file['path'] = $this->_param('path');
        $file['size'] = $this->_param('size');
        $file['title'] = $this->_param('title');
        $file['content'] = $this->_param('content');
        $file['dir_id'] = $this->_param('dir_id');

        $pid = $this->_get('object_id');
        $type = $this->_get('module');
        $file_info = pathinfo($file['title']);
        $title = $file_info['filename'];
        $ext = $file_info['extension'];
        $where[$type.'_id'] = $pid;

//         $where['title'] = array(array('like',"%$title%"),array('like',"%$ext%"));

//         $count = M($type.'Attachments')->where($where)->getField('count(title)');
// var_dump(M($type.'Attachments')->getLastSql());
        
//         if($count >= 1){
//             $file['title'] = $file_info['filename'].'('.$count.').'.$file_info['extension'];
//         }
        $id = D(ucfirst($this->module_name).'Attachments')->save($this->module_object_id, $file);

        $mapid = $this->_param('mapid');
        $dir_id = $file['dir_id'];
        D('AdminLogs')->saveLog($this->module_name.'_attachment', $_REQUEST, 'insert', $id);

        return json("save_attatchment_callback('$id', '$mapid','$dir_id')","eval");
    }

    public function ajax_delete() {
        $id = intval($this->_param('id'));
        $cid = intval($this->_param('object_id'));
        //permission check

        if(!$id) {
            return json('wrong id?', 'alert');
        }

        // if(!$this->allow_to_action($cid)) {
        //     return json('Permission Denied', "alert");
        //     return;
        // }

        D($this->module_table_name)->delete($id);
        D('AdminLogs')->saveLog($this->module_name.'_attachment', $_REQUEST, 'delete', $id);
        json("delete_attachment_callback('$id')", "eval");
    }

    public function ajax_update_dir(){
        $id = $this->_param('id');
        $type = ucfirst($this->_param('type'));

        // 不可以用D->saveOrUpdate方法，因为多重继承的缘故~  待查
        $id = M($type.'Attachments')->where(array('id'=>$id))->save($_POST);
        json("window_refresh()",'eval');
    }

    public function ajax_delete_dir(){
        $id = $this->_param('id');
        M('AttachmentsDir')->where(array('id' => $id))->delete();
    }

    public function check_attachment_exist(){
        $pid = $this->_param('id');
        $title = $this->_param('title');
        $type = $this->_param('type');
        $textId = $this->_param('textId');
        $attachments = M($type.'Attachments')->where(array($type.'_id' => $pid,'title' => $title))->find();
        $title = $attachments['title'];
        if($attachments){
            json("dir_duplicate_alert_callback('$title','$textId')",'eval');
        }
    }

    public function save_dir(){
        $data['dir_name'] = $this->_param('dir_name');
        $data['object_id'] = $this->_param('object_id');
        $id = $this->_param('id');
        $data['type'] = $this->_param('type');
        $attachment = M('AttachmentsDir')->where(array('id' => $id))->select();
        if($attachment){
           M('AttachmentsDir')->where(array('id' => $id))->save($data);
        }else{
            M('AttachmentsDir')->add($data);
        }
    }

    public function render_grant_record() {
        $id = $this->_get('id');
        $type = ucfirst($this->_get('type'));
        $partner_id = intval($this->_param("id"));
        $partner = M($type.'s')->getById($partner_id);
        $this->title = '附件目录';

        // 取消个人限制，目录做成通用的
        $dir_data = D('AttachmentsDir')->where(array('type' => $type))->select();
        // $count = D($type.'Attachments')->where(array('object_id' => $id, 'type' => $type))->group('dir_id')->getField('dir_id,count(id)');
        // foreach($dir_data as $k => &$v){
        //     $v['count'] = 0;
        //     foreach($count as $key => $value){
        //         if($v['id'] == $key){
        //             $v['count'] = $value;
        //         }
        //     }
        // }

        $this->dir_data=$dir_data;
        $this->modal_style= 'style="width:500px"';
        $html = $this->fetch('attachment_dir');
        $j = array(
            array("data" => $html, "type" => "dialog"),
            array("data" => "attachment_initialize()", "type" => "eval")
        );
        json($j, "mix");
    }

    private function allow_to_action($object_id) {
        if(!$object_id) return false;
        //查询对应的Contacts表和Events表中的user_group_id允许不
        $related_table_name = ucfirst($this->module_name).'s';
        $team_id = M($related_table_name)->where('id='.$object_id)->getField('team_id');
        if($team_id==$this->login_user['team_id']) {
            return true;
        }
        return false;
    }

    public function download() {
        $id = intval($this->_get('id'));
//ADD权限判断
        $attachment = M($this->module_table_name)->getById($id);
        if(!$attachment) return;
        $path = $attachment["path"];
        $name = $attachment["title"];

        //download it
        header('Content-type: ' . mime_content_type($path));
        header('Content-Disposition: attachment; filename="' . $name . '"');

        $full_path = UPLOAD_ROOT  . $path;
        readfile($full_path);
    }

    public function batch_download() {
        $related_table_name = ucfirst($this->module_name).'s';
        $as =  D($this->module_table_name)->getByProjectId($this->module_object_id);
        $title =  M($related_table_name)->where('id='.$this->module_object_id)->getField('title');
        $zip = new ZipArchive();
        $zip_name = $title . '-附件-' .  time().".zip"; // Zip name
        $zip_path = UPLOAD_ROOT . '/' . urlencode($zip_name);
        $zip->open($zip_path,  ZipArchive::CREATE);
        foreach ($as as $file) {
            $path = UPLOAD_ROOT . $file["path"];
            if(file_exists($path)) {
                $zip->addFromString(iconv("UTF-8","GB2312//IGNORE",$file['title'])  ,  file_get_contents($path));  
            }
        }
        $zip->close();
        header('Content-type: ' . mime_content_type($zip_name));
        header('Content-Disposition: attachment; filename="' . urlencode($zip_name) . '"');

        readfile($zip_path);
        unlink($zip_path);
    }

}