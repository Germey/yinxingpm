<?php
class ProjectAction extends BaseAction {

    protected $data = array();

    public function __construct() {
        parent::__construct();
        
        $this->data['all_columns'] = D('CustomColumns')->getProjectModuleColumns();
        $this->data['list_views'] = D('CustomListViews')->getListView('Projects');

        $this->status_count = D('Projects')->getStatusCountMap($this->data['apply_type_id']);
        $this->project_statuses = D('ProjectStatuses')->getStatusIdNameMap();

        foreach ($this->project_statuses as $key => $value) {
            $sub_types[$key] = $value . '（'.intval($this->status_count[$key]).'）';
        }

        $sub_types['all'] = '所有（'. array_sum(array_values($this->status_count)) .'）';
        $this->sub_types = $sub_types;

        $this->data['action_name'] = ACTION_NAME;
        $this->data['status'] = $this->_get('status')?$this->_get('status'):'all';
        $this->data['selector'] = 'project/index';
    }

    //home page
    public function index() {

        $order = $this->_param("order");
        if($order){
            $order = explode("|", $order);
        }
        foreach(array_keys($this->data['all_columns']) as $v) {
            $value = trim(htmlspecialchars($this->_get($v)));
            if(!$value) continue;

            $filter[$v] = array('like', '%'.$value.'%');
        }

        // hack, 在全部的搜索中filter错了
        $status = $this->_get('status');
        if('all'==$status) {
            unset($filter['status']);
        }

        $count = D('Projects')->getProjectsCount($filter);
        list($pagesize, $page_num, $pagestring) = pagestring($count, 20);
        $projects = D('Projects')->getProjects($filter, $page_num, $pagesize, $order);

        $this->data['projects'] = $projects;
        $this->data['pagestring'] = $pagestring;

        $this->assign($this->data);

        D('AdminVisits')->saveVisit('project_list',$page_num);
        $this->display('index');
    }

    public function detail() {

        $id = $this->_get('id');
        // $this->permission_redirect("Projects", "view", $id);
        $project = D('Projects')->getProjectById($id, true);
        $this->data['partner'] = $project['partner'];

        if(!$project){
            Session::set("无此项目");
            redirect("/project");
        }

        $this->data['project_statuses'] = D('ProjectStatuses')->getStatusIdNameMap();
        
        $this->data['notes'] = D('ProjectNotes')->getByProjectId($id);
        $this->data['attachments'] = D('ProjectAttachments')->getByProjectId($id);

        // 已经签订合同的，显示监测钱，监测记录什么的
        $this->data['is_contract_project'] = 'N';
        if(in_array($project['status'], array_merge($project_steps['ing_status_ids'], $project_steps['end_status_ids']))) {
            $this->data['is_contract_project'] = 'Y';
        } else {
            $this->data['records_key'] = 'audits';
        }
        if($project['status']==STATUS_NEED_MEETING) {
            $this->data['records_key'] = 'meetings';
        }

        if($this->_get('records_key')) {
            $this->data['records_key'] = $this->_get('records_key');
        }

        $this->data['project'] = $project;

        // 审批意见的意见
        $this->data['audit_opinions'] = array(1=>'通过', 2=>'未通过', 3=>'退回修改',4=>'弃权');

        $audit_types = array(
                STATUS_NEED_SCREEN => 'screen',
                STATUS_NEED_AUDIT => 'audit',
                // STATUS_NEED_CONFIRM => 'confirm',
            );

        $this->data['audit_type'] = $audit_types[$project['status']];

        //编辑Meeting
        if($this->_get('meeting_id')) {
            $this->data['meeting'] = D('ProjectMeetings')->getById($this->_get('meeting_id'));
        }

        $this->data['attachment_title'] = "附件";
        $this->data['note_title'] = "备注";

        $this->assign($this->data);
        D('AdminVisits')->saveVisit('project_detail',$id, $project['name']);


        /**附件分目录*************************************************/
        $attachmentDir = D("AttachmentDir")->getDirectories('project',$id);
        $this->diretories = $attachmentDir['diretories'];
        $this->dir_data = $attachmentDir['dir_data'];
        $this->nodir = $attachmentDir['nodir'];
        /*****************************************/

        $this->display();
    }


    public function render_grant_records() {
        $pid = intval($this->_param("id"));

        $data['project'] = $project = M('Projects')->getById($pid);

        $all = D('ProjectGrantRecords')->getGrantRecords($pid);
        $data['records'] = $all['actual'];
        
        $data['status_tos'] = D('ProjectStatuses')->getStatusIdNameMap();
        //删除第一次拨款之前的
        foreach ($data['status_tos'] as $k => $v) {
            if($k <= STATUS_NEED_FIRST_GRANT) {
                unset($data['status_tos'][$k]);
            }
        }

        $data['title'] = $project['title'] . ' - 拨款记录';


        $data['modal_style']= 'style="width:700px"';

        $this->assign($data);

        $html = $this->fetch('edit_project_grant_records_dialog');
        $j = array(
            array("data" => $html, "type" => "dialog"),
            array("data" => "dialog_validator()", "type" => "eval")
        );
        json($j, "mix");
    }

    public function submit_actual_grant_record() {

        $pid = intval($this->_post('project_id'));

        D('ProjectGrantRecords')->update($pid,0,$_POST);

        $new_status = $this->_post('status_to');
        if($new_status) {
            $u['id'] = $pid;
            $u['status'] = $new_status;
            M('Projects')->saveOrUpdate($u);
            $all_statuses = D('ProjectStatuses')->getStatusIdNameMap();
            Session::Set('success', '提交成功，当前项目状态为：' . $all_statuses[$new_status]);
        }

        D('AdminLogs')->saveLog('project_grant_record', $_POST, 'update', intval($this->_post('project_id')));
        $this->redirect('/project/detail/' . $pid);
    }


    public function ajax_save_status() {
        //permission
        $u['id'] = $this->_get('pid');
        $u['status'] = $this->_get('sid');

        if(!$u['id'] || !$u['status']) return;
        M('Projects')->saveOrUpdate($u);

        $project_statuses = D('ProjectStatuses')->getStatusIdNameMap();
        $status_name = $project_statuses[$u['status']];

        D('AdminLogs')->saveLog('project_status_change', array('修改状态为->'=>$status_name), 'update', $u['id']);

        $j = array(
            array("data" => "save_status_callback('$status_name');", "type" => "eval"),
            array("data" => null, "type" => "refresh")
        );

        json($j, 'mix');
    }


    public function submit_meeting() {
        $data = $_POST;
        if(trim($data['meeting_content'])) {
            $data['create_user_id'] = $this->login_user['id'];
            $data['create_time']  = date('Y-m-d H:i:s');     
            M('ProjectMeetings')->saveOrUpdate($data);
        }

        $this->redirect('/project/detail/'.intval($this->_post('project_id')).'?records_key='.$data['meeting_type'].'s#somerecords');
    }


    //Edit
    public function edit() {
        $id = intval($this->_get('id'));
        // $this->permission_redirect("Projects", "edit", $id);
        $project = D('Projects')->getProjectById($id);

        $this->data['project'] = $project;

        $this->assign($this->data);
        $this->display('edit');
    }

    public function submit() {
        $_POST['contract_budget'] = str_replace(',','', $_POST['contract_budget']);
        if($_POST['status']) {
            $_POST['status'] = 20;
        }
        $id = D("Projects")->saveOrUpdate($_POST);
        if($id) {
            Session::Set("success", "保存成功");
        } else {
            Session::Set("error", "保存失败");
        }
        
        D('AdminLogs')->saveLog('project', $_POST, intval($_POST['id'])?'update':'insert', $id);

        //直接到详细页面
        $this->redirect('project/detail/' . $id);
    }

    public function massdelete() {
        $ids_str = strval($this->_get('ids'));
        $ids = explode(',', $ids_str);
        foreach ($ids as $id) {
            $id = intval($id);
            // $this->permission_ajaxrefresh("Projects", "delete", $id);
            D('Projects')->deleteProject($id);
        }
        $ids_str = trim(implode(',', $ids),',');

        D('AdminLogs')->saveLog('project', $ids, 'delete');
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

    
    public function export_projects() {

        $ids = strval($this->_param('ids'));
        $status = intval($this->_param('status'));

        if($ids) $filter['id'] = array('in', explode(',', $ids));
        if($status) $filter['status'] = $status;

        $projects = D("Projects")->getProjects($filter);

        $columns = D("CustomColumns")->getProjectModuleColumns();
 
        $no_needs = array('status', 'manager_id', );
        foreach ($columns as $k => $v) {
            if(in_array($k, $no_needs)) continue;
            $keynames[$k] = $v['display_name'];
        }


        $column_data = array();
        foreach ($projects as $one) {
            $single_data = array();
            foreach ($keynames as $key => $name) {
                if(!$one[$key]) $one[$key] = '';
                $single_data[] = str_replace('&nbsp;','',$one[$key]);
            }

            $column_data[] = $single_data;
        }

        D('AdminLogs')->saveLog('project', Utility::GetColumn($projects, 'title'), 'project_export');
        Excel::createExcel($column_data, "项目", "项目导出-" . date('Y-m-d'), array_values($keynames));
    }


    //输入“项目编号”时是否重复
    public function check_dup_ajax() {
        $identifier = trim($this->_param("identifier"));

        if($identifier){
            $projects_info = D("Projects")->getDuplicateProjectsByColumn($identifier, "identifier", $this->login_user['manage_group_ids']);
        }

        if($projects_info) {
            echo 'true';
        } else {
            echo 'false';
        }
    }

    function ajax_save_content_block_toggle() {
        $id = intval(I("id"));
        $block = I("block");

        $toggle = unserialize(M("Projects")->where('id='.$id)->getField('pm_display_toggle'));

        if($toggle[$block]) {
            $toggle[$block] = 0;
        } else {
            $toggle[$block] = 1;
        }
        $data['id'] = $id;
        $data['pm_display_toggle'] = serialize($toggle);

        M('Projects')->save($data);
    }

    function ajax_add_school() {
        $data['project_id'] = intval($this->_param('pk'));
        $data['school_id'] = intval($this->_param('value'));
        $data['create_user_id'] = $this->login_user['id'];
        $data['create_time'] = date('Y-m-d H:i:s');

        D("SchoolProjectMapping")->add($data);
        return;
    }

    function ajax_edit_mapping_note() {
        $data['id'] = intval($this->_param('pk'));
        $data['note'] = $this->_param('value');

        D("SchoolProjectMapping")->save($data);
        return;
    }

}