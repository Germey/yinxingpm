<?php

class UserQuestionAnswersModel extends BaseModel {

    public function getByUserId($cms_user_id=0, $type_id, $display_key_prefix='') {
        $f['cms_user_id'] = $cms_user_id;
        $f['type_id'] = $type_id;
        $raw = $this->where($f)->select();
        foreach ($raw as $k => $v) {
            $res[$display_key_prefix.$v['question_id']] = $v['answer'];
        }

        return $res;
    }

    public function getQuestionAnswers($cms_user_id=0, $type_id) {
        $f['cms_user_id'] = $cms_user_id;
        $raw = Utility::AssColumn($this->where($f)->select(), 'question_id');

        $questions = D("UserTypeQuestions")->gets($type_id);

        foreach ($questions as $q) {
            $res[$q['question']] = $raw[$q['id']]['answer'];
        }

        return $res;
    }    
}
?>