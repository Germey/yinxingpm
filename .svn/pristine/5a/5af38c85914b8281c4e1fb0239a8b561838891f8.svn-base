<?php
class SchoolAction extends BaseAction {

    protected $data = array();

    public function __construct() {
        parent::__construct();
        
        $this->data['all_columns'] = D('CustomColumns')->getSchoolModuleColumns($this->login_user['team_id'],$this->login_user['manage_group_ids']);
        $this->data['list_views'] = D('CustomListViews')->getListView('Schools');

        $this->data['selector'] = 'school/index';
    }

    //home page
    public function index() {
        $filter = null;
        $order = $this->_param("order");
        if($order){
            $order = str_replace("|", ' ', $order);
        }
        foreach (array_keys($this->data['all_columns']) as $v) {
            $value = trim(htmlspecialchars($this->_get($v)));
            if(!$value) continue;
            $filter[$v] = array('like', '%'.$value.'%');
        }

        $count = D('Schools')->getSchoolsCount($filter);
        list($pagesize, $page_num, $pagestring) = pagestring($count, 20);
        $schools = D('Schools')->getSchools($filter, $page_num, $pagesize, $order);

        $this->data['schools'] = $schools;
        $this->data['pagestring'] = $pagestring;

        $this->data['school_user_count'] = D('Schools')->getSchoolUserCount(Utility::GetColumn($schools,'id'));

        $this->assign($this->data);

        D('AdminVisits')->saveVisit('school_list',$page_num);
        $this->display();
    }

    public function render_user_list() {
        $school_id = intval($this->_param("id"));

        $this->title = '志愿者列表';
        $data['users'] = D('Schools')->getSchoolUserInfoBySchoolId($school_id);

        $this->assign($data);
        $html = $this->fetch('user_list_dialog');
        $j = array(
            array("data" => $html, "type" => "dialog"),
            array("data" => "dialog_validator()", "type" => "eval")
        );
        json($j, "mix");
    }

    public function detail() {
        $id = $this->_get('id');
        $school = D('Schools')->getById($id);

        if(!$school){
            Session::set("Wrong School");
            redirect("/school/index");
        }

        $this->data['school'] = $school;

        $this->users = D('Schools')->getSchoolUserInfoBySchoolId($id);
        $this->projects = D('Schools')->getSchoolProjectsBySchoolId($id);

        $this->map_teach_detail_group_option = D("Schools")->get_map_detail_group_info();

        // $this->data['notes'] = D('SchoolNotes')->getBySchoolId($id);
        // $this->data['attachments'] = D('SchoolAttachments')->getBySchoolId($id);

        $this->assign($this->data);
        
        D('AdminVisits')->saveVisit('school_detail',$id, $school['name']);

        /**************************************/
        // $attachmentDir = D("AttachmentDir")->getDirectories('school',$id);
        // $this->diretories = $attachmentDir['diretories'];
        // $this->dir_data = $attachmentDir['dir_data'];
        // $this->nodir = $attachmentDir['nodir'];
        /**************************************/
                
        $this->display();
    }

    public function edit() {
        $id = intval($this->_get('id'));

        $school = D('Schools')->getById($id);
        $this->data['school'] = $school;

        $this->assign($this->data);
        $this->display();
    }

    public function submit() {
        $id = D("Schools")->saveOrUpdate($_POST);

        if($id) {
            Session::Set("success", "保存成功");
        } else {
            Session::Set("error", "保存失败");
        }
        
        D('AdminLogs')->saveLog('school', $_POST, intval($_POST['id'])?'update':'insert', $id);
        redirect('/school/detail/' . $id);
    }

    public function delete() {
        $id = intval($this->_get('id'));
        
        D('Schools')->delete($id);
        Session::Set("success", L('success'));

        //log
        D('AdminLogs')->saveLog('school', $id, 'delete');
        json(null, "refresh");
    }
}