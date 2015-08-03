<?php
class PartnertagAction extends BaseAction {

    function render_dialog_edit() {
        $partner_id = $this->_param("id");
        if(!$partner_id) return;

        $partner = D('Partners')->getPartnerById($partner_id,0);
        $data['partner'] = $partner;
        $data['all_tags'] = D('PartnerTags')->getTags($this->login_user['team_id']);
        
        $data['tags'] = Utility::AssColumn($partner['tags'],'partner_tag_id');
        $data['title'] = $partner['title'];
// var_dump($data);        
        $data['modal_style'] = 'style="width:780px"';

        $this->assign($data);

        $html = $this->fetch('Partner:tag_editor_dialog');
        $data = array(
            array("data" => $html, "type" => "dialog"),
            array("data" => "dialog_validator()", "type" => "eval")
        );
        json($data, "mix");
    }

    function update_dialog_tags() {
        $last_id = D('PartnerTags')->updateTagsMapping($this->_param('partner_id'), $this->_param('selected_tags'));
        Session::Set("success", "保存成功");

        $this->redirect($_SERVER['HTTP_REFERER'], array('highlight_id' => $last_id));        
    }

    function update_tag() {
        $id = intval($this->_param('tid'));
        $new_name = trim($this->_param('new_name'));
        $res = D("PartnerTags")->where("id=$id")->setField("name",$new_name);
        return json("update_tag_callback($id, $res, '$new_name')",'eval');
    }

    function delete_tag() {
        $id = intval($this->_param('tid'));
        $res =  D("PartnerTags")->delete($id);
        return json("delete_tag_callback($id, $res)",'eval');
    }

    function update_tag_color($id){
        $color = trim($this->_param('color'));
        $data = array('rgb'=>$color, 'id'=>$id);
        $tag = D('PartnerTags');
        $tag->save($data);
    }


}