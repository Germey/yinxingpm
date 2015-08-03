<?php
class TaskAction extends BaseAction {

    function index() {
        $this->status = $status = $filter['status'] = intval($this->_get('status'));
        
        $users = D('UserInfo')->getUserInfos($filter);

        foreach ($users as $k => $u) {
            $f = array();
            $f['object_id'] = $u['id'];
            $f['object_type'] = 'user';
            $users[$k]['task'] = Utility::AssColumn(D("TaskMapping")->where($f)->select(),'task_id');

        }

        $this->task_list = D("Tasks")->where('type="user"')->order('sequence desc')->getField('id,title');
        $this->users = $users;

        $this->display();
    }

    function ajax_finish_task() {
        $data['object_type'] = $this->_get('task_type');
        $data['object_id'] = intval($this->_get('object_id'));
        $data['task_id'] = intval($this->_get('task_id'));
        $exist = M("TaskMapping")->where($data)->find();
        if($exist) {
            $up['id'] = $exist['id'];
            $up['done'] = $exist['done']==1?0:1;
            $up['update_user_id'] = $this->login_user['id'];
            $up['update_time'] = date('Y-m-d H:i:s');
            M("TaskMapping")->save($up);
        } else {
            $data['done'] = 1;
            $data['update_user_id'] = $this->login_user['id'];
            $data['update_time'] = date('Y-m-d H:i:s');
            
            M("TaskMapping")->add($data);
        }

        json(null, "refresh");
    }

    function ajax_task_note() {
        $pks = explode('::',$this->_param('pk'));
        $note = $this->_param('value');

        if($pks[0]) {
            $up['id'] = $pks[0];
            $up['note'] = $note;
            $up['update_user_id'] = $this->login_user['id'];
            $up['update_time'] = date('Y-m-d H:i:s');

            M("TaskMapping")->save($up);            
        } else {
            $data['object_type'] = $pks[3];
            $data['object_id'] = $pks[1];
            $data['task_id'] = $pks[2];
            $data['note'] = $note;
            $data['update_user_id'] = $this->login_user['id'];
            $data['update_time'] = date('Y-m-d H:i:s');

            M("TaskMapping")->add($data);
        }

        json(null, "refresh");
    }
}