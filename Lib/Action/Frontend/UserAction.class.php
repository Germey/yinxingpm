<?php
class UserAction extends BaseAction {

    protected $data = array();

    public function __construct() {
        parent::__construct();
        
        $this->data['action_name'] = ACTION_NAME;

        $this->data['all_columns'] = D('CustomColumns')->getUserInfoModuleColumns();
        $this->data['recommend_columns'] = D('CustomColumns')->getUserRecommendModuleColumns();

        $this->data['status'] = $this->_get('status')?$this->_get('status'):'all';

        $this->data['list_views'] = D('CustomListViews')->getListView('UserInfo', $this->data['status']);

        $this->status_count = D('UserRecommends')->getStatusCountMap();
        $this->user_statuses = D('UserStatuses')->getStatusIdNameMap();

        foreach ($this->user_statuses as $key => $value) {
            $sub_types[$key] = $value . '（'.intval($this->status_count[$key]).'）';
        }
        $sub_types['all'] = '所有（'. array_sum(array_values($this->status_count)) .'）';
        $this->sub_types = $sub_types;

        $this->data['selector'] = "user/".ACTION_NAME;

        $this->audit_opinions = array(1=>'通过', 2=>'未通过', 3=>'待定');

        // sorry hack
        $this->all_audit_opinions = array(
                1=>array('name'=>'通过', 'label-class'=>'success'), 
                2=>array('name'=>'未通过', 'label-class'=>'important'),  
                3=>array('name'=>'待定', 'label-class'=>'warning'),  
                4=>array('name'=>'驳回修改', 'label-class'=>'inverse'),
                );
    }


    /**
     * 在用户提交申请表之前，准伙伴们的信息在user_recommends   status<60
     *                 之后，准伙伴们的信息在user_info表，状态分隔符：status>=60
     */
    
    public function recommend() {

        $status = $this->data['status'];

        $filter['status'] = $status;
        $order = $this->_param("order");
        if($order){
            $order = str_replace("|",' ',$order);
        }

        foreach(array_keys($this->data['recommend_columns']) as $v) {
            $value = trim(htmlspecialchars($this->_get($v)));
            if(!$value) continue;
            $filter["user_recommends." . $v] = array('like', '%'.$value.'%');
        }

        $count = D('UserRecommends')->getCount($filter);
        list($pagesize, $page_num, $this->pagestring) = pagestring($count, 20);
        $this->users = D('UserRecommends')->gets($filter, $page_num, $pagesize, $order);

        $this->list_columns = $this->data['recommend_columns'];

        $this->assign($this->data);

        // D('AdminVisits')->saveVisit('userinfo_list',$page_num);
        $this->display('index');
    }


    public function index() {
        $filter = null;
        $status = $filter['status'] = intval($this->_get('status'));
        $order = $this->_param("order");
        if($order){
            $order = str_replace("|",' ',$order);
        }
        foreach(array_keys($this->data['all_columns']) as $v) {
            $value = trim(htmlspecialchars($this->_get($v)));
            if(!$value) continue;
            $filter["user_info." . $v] = array('like', '%'.$value.'%');
        }

        $count = D('UserInfo')->getUserInfoCount($filter);
        list($pagesize, $page_num, $this->pagestring) = pagestring($count, 20);
        $this->users = D('UserInfo')->getUserInfos($filter, $page_num, $pagesize, $order);

        $this->assign($this->data);

        D('AdminVisits')->saveVisit('userinfo_list',$page_num);
        $this->display('index');
    }


    public function todo() {
        $this->data['is_todo'] = 'Y';

        $filter['status'] = array('between','2,60');
        if($this->login_user['show_only_related_me']) {
            $tmpfilter['_string'] = 'audit_user_id='.$this->login_user['id'] . ' or actual_audit_user_id='.$this->login_user['id'];
            $all_user_ids = D("UserAudits")->where($tmpfilter)->getField('user_id',true);
            $filter['id'] = array('in', $all_user_ids);
        }
        $this->users = $users = D('UserInfo')->where($filter)->order('update_time desc')->select();
        
        $this->display_status = D("UserStatuses")->getAuditStatusIdNameMap();

        $user_ids = Utility::GetColumn($users);
        $audit_filter['user_id'] = array('in', $user_ids);
        $raw_audits = D("UserAudits")->where($audit_filter)->select();    
        foreach ($raw_audits as $v) {
            $audits[$v['user_id']][$v['status']] = $v;
        }        
        $this->audits = $audits;

        $audit_users = D("Users")->getProjectManager();
        foreach ($audit_users as $key => $value) {
            $temp_array[] = array('value'=>$key, 'text'=>$value);
        }
        $this->audit_user_json_string = json_encode($temp_array);

        $this->data['selector'] = 'user/todo';
        $this->assign($this->data);
        $this->display('index');
    }

    /**
     * - 修改评委
     * [ajax_change_audit_user description]
     * @return [type] [description]
     */
    public function ajax_change_audit_user()
    {
        $audit_user_id = intval($_POST['value']);
        $temp = explode('-',$_POST['name']);
        $user_id = $temp[0];
        $status = $temp[1];

        $f['audit_user_id'] = $data['audit_user_id'] = $audit_user_id;
        $f['status'] = $data['status'] = $status;
        $f['user_id'] = $data['user_id'] = $user_id;

        $data['audit_user_name'] = M('Users')->getFieldById($data['audit_user_id'],'realname');
        $data['create_time'] = date('Y-m-d H:i:s');
        $data['create_user_id'] = $this->login_user['id'];

        $exist = M("UserAudits")->where($f)->find();
        if($exist) {
            $data['id'] = $exist['id'];
            unset($data['create_time']);
            unset($data['create_user_id']);
        }

        M("UserAudits")->saveOrUpdate($data);
    }

    // 详情页
    public function detail() {
        $id = $this->_get('id');
        $this->user = D('UserRecommends')->getRecommend($id);
        $this->userinfo = $this->user['userinfo'];

        $this->data['notes'] = D('UserNotes')->getByUserId($id);
        $this->data['attachments'] = D('UserAttachments')->getByUserId($id);

        $this->data['attachment_title'] = "附件";
        $this->data['note_title'] = "备注";
        $this->data['selector'] = "user/index?apply_type_id=".$user['apply_type_id'];
        

        /**附件分目录*********************************************/
        $attachmentDir = D("AttachmentDir")->getDirectories('user',$id);
        $this->diretories = $attachmentDir['diretories'];
        $this->dir_data = $attachmentDir['dir_data'];
        $this->nodir = $attachmentDir['nodir'];
        /*****************************************/

        $this->user_status_xedit_str =  json_encode_for_xedit($this->user_statuses);

        $this->assign($this->data);

        if($this->userinfo) {
            $this->display('detail');
        } else {
            $this->display('detail_recommend');
        }
    }

    // 修改评审状态
    public function ajax_save_status() {
        //permission
        $u['id'] = $this->_param('pk');
        $u['status'] = $this->_param('value');
        $u['update_time'] = date('Y-m-d H:i:s');

        if(!$u['id'] || !$u['status']) return;
        M('UserRecommends')->saveOrUpdate($u);

        $status_name = $user_statuses[$u['status']];

        D('AdminLogs')->saveLog('user_status_change', array('修改状态为->'=>$status_name), 'update', $u['id']);
        
        return;
    }

    function ajax_save_status_note() {
        //permission
        $u['id'] = $this->_param('pk');
        $u['status_note'] = $this->_param('value');

        if(!$u['id']) return;
        M('UserRecommends')->saveOrUpdate($u);

        return;
    }

    function ajax_save_extra_link() {
        //permission
        $u['id'] = $this->_param('pk');
        $u['extra_file_link'] = $this->_param('value');

        if(!$u['id']) return;
        M('UserRecommends')->saveOrUpdate($u);

        return;
    }

    // 提交评审意见
    public function submit_audit() {
        $data = $_POST;
        // $dimensions = D("UserAuditDimensions")->gets($data['status']);

        $could_submit = 0;
        if(trim($data['audit_content'])) {
            $could_submit = 1;
        }

        if(!$could_submit) {
            Session::Set('error', '考核意见不能为空');
            $this->redirect('/user/detail/'.intval($data['id']));
        }
    
        // foreach ($dimensions as $k => $v) {
        //     $dim_result[$v['id']] = $data['dimension_detail_'.$v['id']];
        // }
        // if($dim_result) {
        //     $data['dimension_detail'] = serialize($dim_result);
        // }

        $data['update_user_id'] = $this->login_user['id'];
        $data['audit_time'] = $data['update_time'] = date('Y-m-d H:i:s');

        $audit_id = D('UserAudits')->saveOrUpdate($data);

        // 更新状态，待定的时候不更新状态
        $up['id'] = $data['user_id'];
        $up['update_time'] = date('Y-m-d H:i:s');
        if($data['audit_result']<3) {
            if($data['audit_result'] == 2) {  // 失败
                $up['status'] = 100;
                $up['fail_via_status'] = $data['status'];
                $up['status_note'] = str_replace('待','',$this->user_statuses[$data['status']]) . '失败';
            } else {
                $f['id'] = $data['status'];
                $up['status'] = M("UserStatuses")->where($f)->getField('success_to');
            }

            D('UserRecommends')->saveOrUpdate($up);
        }
        if($data['audit_result']==4) {
            $up['editable'] = 1;
            D('UserRecommends')->saveOrUpdate($up);
        }

        $msg = '提交成功';
        if($this->user_statuses[$up['status']]) {
            $msg .= '，' . $this->user_statuses[$up['status']];
        }

        $userinfo = D("UserRecommends")->getRecommend($data['user_id']);

        $email_status_map = array(
                1 => 'success',
                2 => 'fail',
                4 => 'neededit',
            );

        $mail_info = D("UserStatuses")->getById($data['status']);

        $mailto     = $userinfo[$mail_info[$email_status_map[$data['audit_result']].'_email_to']];
        $subject    = $mail_info[$email_status_map[$data['audit_result']].'_email_subject'];
        $body       = $mail_info[$email_status_map[$data['audit_result']].'_email_body'];

        $body = str_replace(array('[#recommender_name#]','[#name#]','[#audit_content#]', '[#invite_code#]','img src="/uploads'), 
                            array($userinfo['recommender_name'], $userinfo['name'], 
                                    nl2br($data['audit_content']), 
                                    $userinfo['invite_code'], 
                                    'img src="'.D("Options")->getOption("webroot_apply").'/uploads',
                                    ),
                        $body);

        // email notification 通知给
        if($mailto && $subject && $body) {
            $res = Mailer::SmtpMail(NULL, $mailto, $subject, $body, null, array('guorunmiao@justering.com'));
            if($res) {
                $msg .= '，通知邮件已发送给：' . $mailto;
                $audit_up['id'] = $audit_id;
                $audit_up['audit_email'] = serialize(array('to'=>$mailto, 'subject'=>$subject, 'body'=>$body));
                M("UserAudits")->save($audit_up);
            }
        }

        Session::Set('success', $msg);

        // 告知下一个人和管理员邮箱
        // $enable_notice_auditor = D("Options")->getOption('audit_email_enable_realtime_notice_auditor');
        // if($enable_notice_auditor) {
        //     $tos[] = $this->login_user['email'];
        //     $default_to_email = D("Options")->getOption('audit_success_notify_email');
        //     if($default_to_email) $tos[] = $default_to_email;

        //     if($data['audit_result']==1) {            
        //         $f['status'] = $data['status'];
        //         $f['user_id'] = $data['user_id'];
        //         $next_user = M("UserAudits")->where($f)->field('audit_user_id,audit_user_name')->find();
        //         if($next_user) {
        //             $tos[] = M("Users")->where("id=%d",$next_user['audit_user_id'])->getField('email');
        //         }
        //     }

        //     $next_name = $next_user?$next_user['audit_user_name']:'未分配';
        //     $subject = '【面试通知】' . $this->audit_opinions[$data['audit_result']]
        //                 . ' - [申请人]' . $userinfo['name'] 
        //                 . ' - [当前状态]' . $this->user_statuses[$up['status']?$up['status']:$data['status']]
        //                 . ' - [面试人]' . $this->login_user['realname']
        //                 . ' - [下一位面试人]' . $next_name;
        //     $body = '【评审意见】<br />' . nl2br($data['audit_content']);
        //     $body .= '<p>点击查看详情：' . D("Options")->getOption("webroot_pm") . '/user/detail/' . $userinfo['id'] . '</p>';
            
        //     Mailer::SmtpMail(NULL, $tos, $subject, $body);
        // }

        D('AdminLogs')->saveLog('user_audit', $data, 'insert', $data['user_id']);
        $this->redirect('/user/detail/'.intval($this->_post('user_id')));
    }

    public function ajax_delete_audit() {
        $id = intval(I('id'));
        //permission
        M("UserAudits")->where('id='.$id)->delete();
        json("delete_audit_callback('$id');", 'eval');
    }


    // 更新合同相关信息
    public function submit_contract() {
        $id = D("UserInfo")->saveOrUpdate($_POST);
        Session::Set("success", "保存合同信息成功，请在“1. 基本资料”最后部分查看");
        D('AdminLogs')->saveLog('userinfo', $_POST, 'update', $id);

        $this->redirect('/user/detail/' . $id);
    }

    public function massdelete() {
        $ids_str = strval($this->_get('ids'));
        $ids = explode(',', $ids_str);
        foreach ($ids as $id) {
            $up['id'] = intval($id);
            $up['status'] = 0;
            M("UserInfo")->save($up);
        }
        $ids_str = trim(implode(',', $ids),',');

        D('AdminLogs')->saveLog('userinfo', $ids, 'delete');
        json("massdelete_callback('$ids_str');", 'eval');
    }


    public function delete() {
        $id = intval($this->_get('id'));
        
        $this->permission_ajaxrefresh("Projects", "delete", $id);
        D('Projects')->deleteProject($id);
        Session::Set("success", L('success'));

        //log
        D('AdminLogs')->saveLog('project', $id, 'delete');        
        json(null, "refresh");
    }

    
    // 导出用户
    public function export_users() {

        $ids = strval($this->_param('ids'));
        $status = intval($this->_param('status'));
        $apply_type_id = intval($this->_param('apply_type_id'));

        if($ids) $filter['id'] = array('in', explode(',', $ids));
        if($status) $filter['status'] = $status;
        if($apply_type_id) $filter['apply_type_id'] = $apply_type_id;

        $users = D("UserInfo")->getUserInfos($filter);

        if($this->_param('infotype')=='school') {
            return $this->_export_user_schools($users);
        }

        $columns = D("CustomColumns")->getUserInfoModuleColumns();
 
        $no_needs = array('self_introduction', 'experience', 'edu_info', 'apply_type_id', 'howto_know');
        foreach ($columns as $k => $v) {
            if(in_array($k, $no_needs)) continue;
            $keynames[$k] = $v['display_name'];
        }

        $column_data = array();
        foreach ($users as $one) {
            $single_data = array();
            foreach ($keynames as $key => $name) {
                if(!$one[$key]) $one[$key] = '';
                $single_data[] = str_replace('&nbsp;','',$one[$key]);
            }

            $column_data[] = $single_data;
        }

        D('AdminLogs')->saveLog('project', Utility::GetColumn($projects, 'title'), 'user_export');
        Excel::createExcel($column_data, "会员", "会员导出-" . date('Y-m-d'), array_values($keynames));
    }


    function _export_user_schools($users) {

        $keynames['identifier'] = '编号';
        $keynames['name'] = '姓名';
        $keynames['shool_name'] = '学校名称';
        $keynames['shool_leader'] = '校长';
        $keynames['shool_leader_contact'] = '校长联系方式';

        $detail_info_key = D("Schools")->get_map_detail_group_info();
        foreach ($detail_info_key as $k => $v) {
            $keynames[$k] = $v['name'];
        }

        $users = Utility::AssColumn($users);
        $user_ids = Utility::GetColumn($users);
        foreach ($user_ids as $uid) {
            $user_schools = D("Schools")->getSchoolUserInfo($uid);
            foreach($user_schools as $one) {
                $details = unserialize($one['teach_detail']);
                foreach ($details as $line) {
                    $single_data = array();
                    $single_data[] = $users[$uid]['identifier'];
                    $single_data[] = $users[$uid]['name'];
                    $single_data[] = $one['school']['name'];
                    $single_data[] = $one['school']['leader_name'];
                    $single_data[] = $one['school']['leader_contact'];

                    foreach($detail_info_key as $dk=>$dv) {
                        $single_data[] = $line[$dk];
                    }

                    $column_data[] = $single_data;
                }
            }
        }

        D('AdminLogs')->saveLog('user_school_info', Utility::GetColumn($users, 'name'), 'user_export');
        Excel::createExcel($column_data, "会员任教详情", "会员任教详情导出-" . date('Y-m-d'), array_values($keynames));    
    }


    function ajax_save_content_block_toggle() {
        $id = intval(I("id"));
        $block = I("block");

        $toggle = unserialize(M("UserRecommends")->where('id='.$id)->getField('pm_display_toggle'));

        if($toggle[$block]) {
            $toggle[$block] = 0;
        } else {
            $toggle[$block] = 1;
        }
        $data['id'] = $id;
        $data['pm_display_toggle'] = serialize($toggle);

        M('UserRecommends')->save($data);
    }

    function ajax_add_school() {
        $data['user_id'] = intval($this->_param('pk'));
        $data['school_id'] = intval($this->_param('value'));
        $data['create_user_id'] = $this->login_user['id'];
        $data['create_time'] = date('Y-m-d H:i:s');

        D("SchoolUserMapping")->add($data);
        return;
    }

    function ajax_edit_mapping_note() {
        $data['id'] = intval($this->_param('pk'));
        $data['note'] = $this->_param('value');

        D("SchoolUserMapping")->save($data);
        return;        
    }

    // 
    public function render_map_teach_detail() {

        $this->title = '修改任教详情';
        $this->modal_style= 'style="width:800px"';
        $this->school_id = $this->_get('id');
        $this->user_id = $this->_get('uid');

        $value = D("SchoolUserMapping")->where('id=%d',$this->school_id)->getField('teach_detail');
        
        // var_dump($value);

        $this->edit_part = dashboard_group_edit('teach_map',NULL, $this->map_teach_detail_group_option, $value);

        $html = $this->fetch('change_tech_detail_dialog');
        $j = array(
            array("data" => $html, "type" => "dialog"),
            array("data" => "dialog_validator()", "type" => "eval")
        );
        json($j, "mix");
    }

    public function submit_map_teach_detail() {
        $options = $this->map_teach_detail_group_option;
        for($i=0; $i<100; $i++) {
            $keep = 0;                  //有完全空的行，直接忽略
            foreach ($options as $k => $v) {
                $groups[$i][$k] = trim($_POST['teach_map_group_'.$k][$i]);
                if($groups[$i][$k]) {
                    $keep = 1;
                }
            }
            if(!$keep) {
                unset($groups[$i]);
            }
        }

        $data['id'] = $this->_post('id');
        $data['teach_detail'] = serialize($groups);

        D("SchoolUserMapping")->save($data);

        $uid = $this->_post('uid');
        redirect('/user/detail/'.$uid.'#schoolinfo');
    }

    public function ajax_only_me() {
        $data['show_only_related_me'] = intval($this->_get('to'));
        $data['id'] = $this->login_user['id'];
        D("Users")->saveOrUpdate($data);

        // 更新回登陆用户
        $user = $this->login_user;
        $user['show_only_related_me'] = intval($this->_get('to'));
        Session::set("login_user", $user);

        json(NULL, 'refresh');
    }


    // @TODO 同步用户到灵析
    public function sync_users_to_lingxi() {
        $last_sync_user_time = D("Options")->getOption('last_sync_user_time');

    }


    // 同步灵析的表单来
    public function sync_form_via_lingxi() {
        $forms = unserialize(D("Options")->getOption('sync_lingxi_forms'));
        if(!$forms) return;

        $lx_api = new LingxiApi();
        foreach ($forms as $k => $v) {

            $param['id'] = $v['id'];
            $param['from_time'] = D('LingxiFormFills')->max('create_time');
            $res = $lx_api->getForm($param);

            if(!$res) {
                var_dump('No data or bad id: ' . $v['id']);
                continue;
            }

            $_member_field = 0;
            foreach($res['fields'] as $field_key => $field) {
                if($field['alias']==='member') {
                    $_member_field = $field_key;
                    break;
                }
            }
            if(!$_member_field) {
                var_dump('No field "member" in form ' . $v['id']);
                continue;
            }

            // 更新form表
            $form_up = $res;
            $form_up['eid'] = $v['id'];
            $form_up['fields'] = serialize($form_up['fields']);
            $exist=D("LingxiForms")->getById($res['id']);
            if(!$exist) {
                D("LingxiForms")->add($form_up);
            } else {
                D("LingxiForms")->save($form_up);
            }

            // 更新form_fills表
            foreach ($res['feedbacks'] as $fk => $fv) {
                $member_value = $fv['detail'][$_member_field];
                $local_user_id = D("UserInfo")->where('identifier="%s"',$member_value)->getField('id');

                if(!$local_user_id) {
                    var_dump('Fail to get user by member: ' . $member_value);
                    continue;
                }
                $fv['detail'] = serialize($fv['detail']);
                $fv['user_id'] = $local_user_id;
                $fv['form_id'] = $res['id'];

                D("LingxiFormFills")->add($fv);
            }
        }
    }
}