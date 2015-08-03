<?php
class NoteAction extends BaseAction {

    private $module_name = NULL;
    private $module_table_name = NULL;
    private $module_object_id_name = NULL;
    private $module_object_id = NULL;

    public function __construct() {
        parent::__construct();

        $this->module_name = $this->_param('module');
        //对应的contact_id，event_id等
        $this->module_table_name = ucfirst($this->module_name) . 'Notes';          
        $this->module_object_id = $this->_param('object_id');          
        $this->module_object_id_name = $this->_param('module') .'_id';

    }
    

    public function index() {
        $this->display();
    }

    public function ajax_save() {
        if(!$this->module_name) return json('bad module', 'alert');
        if(!$this->module_object_id) return json('bad object_id', 'alert');
        $id = D(ucfirst($this->module_name).'Notes')->save($this->module_object_id, $this->_param('title'), $this->_param('content'));

        D('AdminLogs')->saveLog($this->module_name.'_note', $_REQUEST, 'insert', $id);
        return json("save_note_callback($id)","eval");
    }

    public function ajax_delete() {
        $id = intval($this->_param('id'));
        $cid = intval($this->_param('object_id'));
        //permission check

        if(!$id) {
            return json('wrong id?', 'alert');
        }

        // $this->permission_ajaxrefresh($this->module_table_name, "delete", $id);

        D($this->module_table_name)->delete($id);
        
        D('AdminLogs')->saveLog($this->module_name.'_note', $_REQUEST, 'delete', $id);

        json("delete_note_callback('$id')", "eval");
    }

}