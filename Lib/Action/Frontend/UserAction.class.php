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
        $this->status_count = D('UserRecommends')->getStatusCountMap($this->login_user);
        $this->user_statuses = D('UserStatuses')->getStatusIdNameMap();
        foreach ($this->user_statuses as $key => $value) {
            $sub_types[$key] = $value . '（'.intval($this->status_count[$key]).'）';
        }
        $sub_types['all'] = '所有（'. array_sum(array_values($this->status_count)) .'）';
        $this->sub_types = $sub_types;

        $this->data['selector'] = "user/".ACTION_NAME;

        $this->audit_opinions = array(1=>'通过', 2=>'未通过进入备选', 3=>'待定');

        // sorry hack
        $this->all_audit_opinions = array(
                1=>array('name'=>'通过', 'label-class'=>'success'), 
                2=>array('name'=>'未通过', 'label-class'=>'important'),  
                3=>array('name'=>'待定', 'label-class'=>'warning'),  
                4=>array('name'=>'申请表驳回修改', 'label-class'=>'inverse'),
                );

        $this->user_classify_xedit_str = json_encode_for_xedit(explode(',', D("Options")->getOption("user_classifies")));

        $this->could_edit = 1;
        $this->is_volunteer = $this->login_user['role']=='role_volunteer'?1:0;
        if($this->is_volunteer) {
            $this->could_edit = 0;
        }
        $this->survey_users_xedit_str = json_encode_for_xedit(D("Users")->getField('id,realname'));
    }

    /**
     * 在用户提交申请表之前，准伙伴们的信息在user_recommends   status<60
     *                 之后，准伙伴们的信息在user_info表，状态分隔符：status>=60
     */
    public function recommend() {
        $status = $this->data['status'];
        $filter['status'] = $status;
        $order = $this->_param("order");
        if($order)  $order = str_replace("|",' ',$order);

        if($status=='all') {
            $this->data['list_views'] = array_merge($this->data['list_views'], array('status_name','fail_rank'));
        }
        
        if($this->is_volunteer) {
            $filter['survey_user_id'] = $this->login_user['id'];
            $filter['status'] = 30;
        }

        foreach(array_keys($this->data['recommend_columns']) as $v) {
            $value = trim(htmlspecialchars($this->_get($v)));
            if(!$value) continue;
            //修复通过查询推荐人查询不到的问题，起初原因是查找的recommender_name字段，而字段并不存在
            if ($v == "recommender_name") {
                $value = D("UserInfo")->getIdByName($value);
                $v = "recommend_user_id";
            }
            //因为recommend_user_id是int类型，所以不能用like来匹配
            if ($v == "recommend_user_id") {
                $filter["user_recommends." . $v] = array('eq', $value);
            } else {
                $filter["user_recommends." . $v] = array('like', '%'.$value.'%');
            }
        }

        $count = D('UserRecommends')->getCount($filter);
        list($pagesize, $page_num, $this->pagestring) = pagestring($count, 20);
        $this->users = D('UserRecommends')->gets($filter, $page_num, $pagesize, $order, $this->login_user);
        $this->list_columns = $this->data['recommend_columns'];
        $this->assign($this->data);
        
        // D('AdminVisits')->saveVisit('userinfo_list',$page_num);
        $this->display('index');
    }


    //推荐人列表
    public function recomlist() {
        $columns = $this->_getRecommendFilterColumns();
        $this->columns = $columns;
        $filter = array();
        foreach(array_keys($columns) as $v) {
            $value = trim(htmlspecialchars($this->_get($v)));
            if(!$value) continue;
            $filter["user_info." . $v] = array('like', '%'.$value.'%');
        }

        $count = D('UserRecommends')->getRecommedCount($filter);
        list($pagesize, $page_num, $this->pagestring) = pagestring($count, 10);
        $this->users = D('UserRecommends')->getRecommendInfos($filter, $page_num, $pagesize, $order);
        foreach ($this->users as $key => $value) {
            $f['recommend_user_id'] = $value['id'];
            $f['status'] = array('gt', 1);
            $recommend_count[$value['id']] = D('UserRecommends')->where($f)->select();
        }
        $this->recommend_count = $recommend_count;
        // var_dump($this->recommend_count);
        $this->list_columns = $this->_getListColumns();
        $this->display("recomlist");
    }

    private function _getListColumns() {
         $columns = array(
            "name" => array("display_name" => "姓名" , ),
            "org" => array("display_name" => "机构" , ),
            "duty" => array("display_name" => "职务" , ),
            "mobile" => array("display_name" => "电话", ),
            "email" => array("display_name" => "邮箱", ),
            "address_province" => array("display_name" => "省份", ),
            "recommend_count" => array("display_name" => "被推荐人", ),
        );

        return $columns;
    }

    //推荐人
    private function _getRecommendFilterColumns() {
         $columns = array(
            "name" => array("display_name" => "姓名" , ),
            "org" => array("display_name" => "工作单位" , ),
            "address_province" => array("display_name" => "省份", ),
        );
        return $columns;
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

    // 详情页
    public function detail() {
        $id = $this->_get('id');
        $this->user = D('UserRecommends')->getRecommend($id);

        // 重复的候选人
        if($this->user['same_as_id'] && !$_GET['force']) {
            session('success', '已经标记为重复的候选人，跳转到汇总的那个去...');
            redirect('/user/detail/'.$this->user['same_as_id']);
        }

        $this->userinfo = $this->user['userinfo'];

        $this->data['notes'] = D('UserNotes')->getByUserId($id);
        $this->data['attachments'] = D('UserAttachments')->getByUserId($id);
        $this->date['login_user'] = $this->login_user;
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
        D('UserRecommends')->saveOrUpdate($u);

        // 同时也保存到user_audits表
        $audit = array(
                'user_id' => $u['id'],
                'status' => $u['status'],
                'audit_user_id' => $this->login_user['id'],
                'audit_user_name' => $this->login_user['realname'],
                'audit_time' => date('Y-m-d H:i:s'),
                'audit_content' => '直接修改状态',
                'audit_result' => 1,
                'create_user_id' => $this->login_user['id'],
                'create_time' => date('Y-m-d H:i:s'),
                'update_user_id' => $this->login_user['id'],
                'update_time' => date('Y-m-d H:i:s'),
            );
        M("UserAudits")->add($audit);

        D('AdminLogs')->saveLog('user_status_change', array('修改状态为->'=>$user_statuses[$u['status']]), 'update', $u['id']);
        return;
    }

    public function render_audit_history() {
        $id = intval(I('id'));
        $this->audits = D("UserAudits")->order('create_time')->getsByUserId($id);
        $this->title = '审核流水记录';
        $this->audit_results = array(1=>'通过', 2=>'未通过进入备选', 3=>'待定', 4=>'申请表驳回修改');
        $html = $this->fetch("audit_history_dialog");
        json($html, "dialog");
    }

    // 修改指派的背景调查人员
    public function ajax_set_servey_user() {
        $u['id'] = $this->_param('pk');
        $status = $this->_get('status');

        $status_uid_key = 'status_'.$status.'_uid';
        $u[$status_uid_key] = $this->_param('value');

        if(!$u['id'] || !$u[$status_uid_key]) return;

        $user = D("Users")->getById($u[$status_uid_key]);
        $u['status_'.$status.'_uname'] = $user['realname'];

        M('UserRecommends')->saveOrUpdate($u);

        // 发邮件给被调差志愿者
        $body = "点击查看详情：" . D("Options")->getOption("webroot_pm")."/user/detail/".$u['id'];
        $res = Mailer::SmtpMail(NULL, $user['email'], '【银杏伙伴】候选人背景调查安排', $body);       
        return;
    }

    public function ajax_change_classify() {
        $id = I("post.pk");
        $new_classify = I("post.value");
        if(!$id || !$new_classify) return;
        $data['id'] = $id;
        $data['classify'] = $new_classify;
        D("UserRecommends")->saveOrUpdate($data);
    }

    public function ajax_change_year_jie() {
        $id = I("post.pk");
        $year_jie = I("post.value");
        if(!$id || !$year_jie) return;
        $data['id'] = $id;
        $data['year_jie'] = $year_jie;
        D("UserRecommends")->saveOrUpdate($data);
    }

    public function ajax_enable_edit() {
        $data['id'] = intval(I('id'));
        $data['editable'] = intval(I("to"));
        D("UserRecommends")->saveOrUpdate($data);
        json(NULL, 'refresh');
    }

    public function ajax_save_status_note() {
        //permission
        $u['id'] = $this->_param('pk');
        $u['status_note'] = $this->_param('value');

        if(!$u['id']) return;
        M('UserRecommends')->saveOrUpdate($u);
        return;
    }

    public function ajax_save_fail_rank() {
        //permission
        $u['id'] = $this->_param('pk');
        $u['fail_rank'] = $this->_param('value');

        if(!$u['id']) return;
        M('UserRecommends')->saveOrUpdate($u);
        return;
    }

    public function ajax_send_finish_bg_survey_email() {
        $name = I("name");
        $mailto     = D("Options")->getOption("contact_email");
        $subject    = "【银杏伙伴】背景调查已经填写完毕，请审阅 - {$name}";
        $body       = "点击查看详情：" . D("Options")->getOption("webroot_pm")."/user/detail/".I("id");
        if($mailto && $subject && $body) {
            $res = Mailer::SmtpMail(NULL, $mailto, $subject, $body);
        }
        json(null, 'refresh');
    }

    public function ajax_send_mail_to_audits() {
        $audits = I("post.audits");
        foreach ($audits as $key => $value) {
            $where['username'] = $value;
            $mailto = D("Users")->where($where)->getField("email");
            $subject    = "请大家一同来参与".I("post.name")."的审核吧";
            $body       = "<br>".I("post.msg")."<br>".$_SERVER['HTTP_HOST']."/user/detail/".I("post.id");
            if($mailto && $subject && $body) {
                $res = Mailer::SmtpMail(NULL, $mailto, $subject, $body, null, array('guorunmiao@justering.com'));
                if($res) {
                    $msg .= '通知邮件已发送给' . $value;
                    $audit_up['id'] = $audit_id;
                    $audit_up['audit_email'] = serialize(array('to'=>$mailto, 'subject'=>$subject, 'body'=>$body));
                    M("UserAudits")->save($audit_up);
                    echo $msg;
                }
            }
        }
    }

    function ajax_save_extra_link() {
        //permission
        $u['id'] = $this->_param('pk');
        $u['extra_file_link'] = $this->_param('value');

        if(!$u['id']) return;
        M('UserRecommends')->saveOrUpdate($u);

        return;
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


    // 提交评审意见
    public function submit_audit() {
        $data = $_POST;
    
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
                // $up['status_note'] = str_replace('待','',$this->user_statuses[$data['status']]) . '失败';
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

    //银杏伙伴
    public function success() {

        $this->data['status'] = $this->_get('status')?$this->_get('status'):99;

        $this->data['list_views'] = array('name','identifier','mobile','email','classify','status_note');
        $this->recommend();
    }

    //银杏伙伴
    public function current() {

        $this->data['status'] = $this->_get('status')?$this->_get('status'):30;
        $this->recommend();
    }

    //备选库
    public function fail() {
        $this->data['status'] = $this->_get('status')?$this->_get('status'):100;

        $this->data['list_views'] = array_merge($this->data['list_views'], array('age','fail_rank'));

        $this->recommend();
    }


    public function ajax_delete_audit() {
        $id = intval(I('id'));
        //permission
        M("UserAudits")->where('id='.$id)->delete();
        json("delete_audit_callback('$id');", 'eval');
    }

    public function ajax_delete_user() {

        $up['id'] = intval(I('id'));
        $up['status'] = 0;
        M("UserRecommends")->save($up);

        D('AdminLogs')->saveLog('userinfo', $id, 'delete');

        Session::set('success', '删除候选人成功');
        json("window.location.href='/user/recommend';", 'eval');
    }

    public function massdelete() {
        $ids_str = strval($this->_get('ids'));
        $ids = explode(',', $ids_str);
        foreach ($ids as $id) {
            $up['id'] = intval($id);
            $up['status'] = 0;
            M("UserRecommends")->save($up);
        }
        $ids_str = trim(implode(',', $ids),',');

        D('AdminLogs')->saveLog('userinfo', $ids, 'delete');
        json("massdelete_callback('$ids_str');", 'eval');
    }

    public function ajax_set_same_recommend($value='')
    {
        $up['id'] = intval(I('current_rid'));
        $up['same_as_id'] = intval(I('same_rid'));
        $up['identifier'] = '';
        
        D("UserRecommends")->saveOrUpdate($up);
        json(NULL, 'refresh');
    }

    public function render_edit_avatar() {
        $this->id = I('id');
        $this->title = '编辑头像';
        $html = $this->fetch("edit_avatar_dialog");
        json($html, "dialog");
    }

    public function save_avatar() {
        var_dump($_POST);
        var_dump($_FILES);

        $avatar = upload_file('avatar');
        var_dump($avatar);

        $id = $_POST['id'];
        D("UserRecommends")->where('id='.$id)->setField('avatar', $avatar);
        redirect('/user/detail/'.$id);
    }

    public function render_add_status_file() {
        $this->id = I('id');
        $this->status = I('status');

        $this->title = '添加附件';
        $html = $this->fetch("add_file_dialog");
        json($html, "dialog");
    }

    public function save_status_file() {

        $path = upload_file('file');

        $id = $_POST['id'];
        $status = $_POST['status'];

        $attach = array(
                'type' => 'user',
                'path' => $path,
                'title' => $_FILES['file']['name'],
                'size' => $_FILES['file']['size'],
                'create_user_id' => $this->login_user['id'],
                'create_time' => date('Y-m-d H:i:s'),
            );

        $status_name = 'status_'.$status.'_files';
        $file = M("UserRecommends")->where('id='.$id)->getField($status_name);
        if($file) {
            $file = unserialize($file);
        }

        $file[time()] = $attach;
        D("UserRecommends")->where('id='.$id)->setField($status_name, serialize($file));

        redirect('/user/detail/'.$id.'#audits');
    }


    public function delete_status_file() {
        $id = intval(I('id'));
        $status_name = 'status_'.I('status').'_files';
        $file = unserialize(M("UserRecommends")->where('id='.$id)->getField($status_name));
        unset($file[I('key')]);
        D("UserRecommends")->where('id='.$id)->setField($status_name, serialize($file));
        
        session('success', '删除成功');
        json(null, 'refresh');
    }

    public function ajax_save_status_content() {
        var_dump($_POST);
        $id = intval(I('id'));
        $content_name = 'status_'.I('status').'_content';
        D("UserRecommends")->where('id='.$id)->setField($content_name, $_POST['content']);
        
        echo 1;
    }

    // @TODO 同步用户到灵析
    public function sync_users_to_lingxi() {
        $last_sync_user_time = D("Options")->getOption('last_sync_user_time');
    }

}