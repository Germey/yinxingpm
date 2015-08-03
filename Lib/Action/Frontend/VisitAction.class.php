<?php
class VisitAction extends BaseAction {
    public function index() {

        $data['module_maps'] = array(
            'contact_list' => '联系人列表',
            'contact_detail' => '联系人详情',
            'organization_list' => '机构列表',
            'organization_detail' => '机构详情',
            'event_list' => '活动列表',
            'event_detail' => '活动详情',
            'home'  => '首页',
            'mail_index'  => '邮件首页',
            'sms_index'  => '短信首页',
        );

        $raw_users = D("Users")->getGroupUsers($this->login_user['manage_group_ids']);
        foreach ($raw_users as $u) {
            $users[$u['id']] = $u['realname'];
        }
        $data['all_columns'] = array(
                'user_id' => array('display_name' => '用户', 'type'=>'select', 'options' => $users),
                'module' => array('display_name' => '操作模块', 'type'=>'select', 'options' => $data['module_maps']),
                'module_id' => array('display_name' => '关联ID'),
                'ipaddress' => array('display_name' => 'IP Address'),
                'extra_data' => array('display_name' => '关联数据'),
            );
        $data['list_views'] = array('user_id', 'module', 'module_id', 'extra_data', 'ipaddress');

        foreach ($data['list_views'] as $v) {
            $value = trim(htmlspecialchars($this->_get($v)));
            if(!$value) continue;

            $filter[$v] = array('like', '%'.$value.'%');
        }
        $filter['user_group_id'] = array('in', $this->login_user['manage_group_ids']);        

        list($pagesize, $page_num, $pagestring) = pagestring(D('AdminVisits')->where($filter)->count(), 20);
        $data['visits'] = D('AdminVisits')->getVisits($filter, $page_num, $pagesize);
        $data['pagestring'] = $pagestring;

        $this->assign($data);
        $this->display();
    }


    public function massdelete() {
        $ids_str = strval($this->_get('ids'));
        $ids = explode(',', $ids_str);

        $filter['user_group_id'] = array('in', $this->login_user['manage_group_ids']);
        $all_ids = M('Visits')->where($filter)->getField('id', true);

        foreach ($ids as $id) {
            if(in_array($id, $all_ids)) {
                M('Visits')->delete($id);
            }
        }
        json(NULL, 'refresh');
    }

}