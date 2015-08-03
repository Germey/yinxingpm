<?php
class AdminlogAction extends BaseAction {

    public function index() {
        $data['module_maps'] = array(
            'project' => '资助项目',
            'project_status_change' => '资助项目状态变更',
            'project_manager_change' => '资助项目官员更换',
            'project_grant_record' => '资助项目拨款记录',
            'partner' => '机构',
            'contact_note'  => '联系人备注',
            'partner_note'  => '活动备注',
            'contact_attachment' => '联系人附件',
            'partner_attachment' => '活动附件',
        );

        $raw_users = D("Users")->getGroupUsers($this->login_user['manage_group_ids']);
        foreach ($raw_users as $u) {
            $users[$u['id']] = $u['realname'];
        }
        $data['all_columns'] = array(
                'user_id' => array('display_name' => '用户', 'type'=>'select', 'options' => $users),
                'module' => array('display_name' => '操作模块', 'type'=>'select', 'options' => $data['module_maps']),
                'operation' => array('display_name' => '操作'),
                'module_id' => array('display_name' => '关联ID'),
                'module_name' => array('display_name' => '关联数据'),
            );
        $data['list_views'] = array('user_id', 'module', 'module_id', 'module_name', 'operation');

        foreach ($data['list_views'] as $v) {
            $value = trim(htmlspecialchars($this->_get($v)));
            if(!$value) continue;

            $filter[$v] = array('like', '%'.$value.'%');
        }

        $filter['user_group_id'] = array('in', $this->login_user['manage_group_ids']);

        list($pagesize, $page_num, $pagestring) = pagestring(D('AdminLogs')->where($filter)->count(), 20);
        $data['visits'] = D('AdminLogs')->getAdminLogs($filter, $page_num, $pagesize);

        $data['pagestring'] = $pagestring;

        $this->assign($data);
        $this->display();
    }

    public function render_detail() {
        $id = $this->_param('id');
        if(!$id) return;

        $data['title'] = L('adminlog_non_view');
        $data['adminlog'] = M('AdminLogs')->getById($id);
        $data['data'] = unserialize($data['adminlog']['data']);

        unset($data['data']['_URL_']);

        $this->assign($data);

        $html = $this->fetch('adminlog_detail_dialog');
        $j = array(
            array("data" => $html, "type" => "dialog"),
            array("data" => "dialog_validator()", "type" => "eval")
        );
        json($j, "mix");
    }

    public function massdelete() {
        $ids_str = strval($this->_get('ids'));
        $ids = explode(',', $ids_str);

        $filter['user_group_id'] = array('in', $this->login_user['manage_group_ids']);
        $all_ids = M('AdminLogs')->where($filter)->getField('id', true);

        foreach ($ids as $id) {
            if(in_array($id, $all_ids)) {
                M('AdminLogs')->delete($id);
            }
        }
        json(NULL, 'refresh');
    }
}