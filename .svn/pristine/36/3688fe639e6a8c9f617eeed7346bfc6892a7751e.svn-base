<?php

class BackgroundSurveyModel extends BaseModel{

    public function getAnswers($recommend_id) {
        $where['recommend_id'] = $recommend_id;
        $result = $this->where($where)->select();
        foreach ($result as $key => $value) {
            $answers[$value['question_id']] = $value['answer'];
        }
        return $answers;
    }
}
