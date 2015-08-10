<?php

class StatAction extends BaseAction {

    private $data;

    //默认页面
    public function index() {

        $this->recommend();

    }

    //推荐人信息
    public function recommend() {

        $filter_columns = $this->_getRecommendFilterColumns();
        $filter = array();
        foreach(array_keys($filter_columns) as $v) {
            $value = trim(htmlspecialchars($this->_get($v)));
            if(!$value) continue;
            $filter["user_info." . $v] = array('like', '%'.$value.'%');

        }
        //推荐人数量
        $recommend_count = D("UserRecommends")->getRecommedCount($filter);
        $data['recommend_count'] = $recommend_count;
        //候选人数量,这里指的是满足条件的推荐人所推荐候选人的总数量
        $candidate_count = D("UserRecommends")->getRecommendCandidateCount($filter);
        $data['candidate_count'] = $candidate_count;
        //按照性别排名
        $recommend_gender_rank = D("UserRecommends")->getRecommendGenderRank($filter);
        $data['recommend_gender_rank'] = $recommend_gender_rank;
        //按照推荐人推荐的数量排名
        $recommend_num_rank = D("UserRecommends")->getRecommendNumRank($filter);
        $data['recommend_num_rank'] = $recommend_num_rank;
        $this->assign($data);
        $this->display("recommend");

    }

    //推荐人和候选人字段略有不同，单独列出
    private function _getRecommendFilterColumns() {

         $columns = array(
            "name" => array("display_name" => "姓名" , ),
            "gender" => array("display_name" => "性别" , ),
            "mobile" => array("display_name" => "联系电话" , ),
            "org" => array("display_name" => "工作单位" , ),
            "email" => array("display_name" => "联系邮件", ),
            "address_province" => array("display_name" => "省份", ),
        );

        return $columns;
    }


    //候选人信息
    public function candidate() {

        $filter_columns = $this->_getFilterColumns();
        $filter = array();
        foreach(array_keys($filter_columns) as $v) {
            $value = trim(htmlspecialchars($this->_get($v)));
            if(!$value) continue;
            $filter["user_recommends." . $v] = array('like', '%'.$value.'%');

        }
        //候选人的数量
        $candidate_count = D("UserRecommends")->getCandidateCount($filter);
        $data['candidate_count'] = $candidate_count;
        //候选人的地址
        $candidate_province_rank = D("UserRecommends")->getCandidateProvinceRank($filter);
        $data['candidate_province_rank'] = $candidate_province_rank;
        //候选人的性别，注意，此处候选人未通过之前没有性别信息，所以不统计
        $candidate_gender_rank = D("UserRecommends")->getCandidateGenderRank($filter);
        $data['candidate_gender_rank'] = $candidate_gender_rank;
        //候选人的年龄
        $candidate_age_rank = D("UserRecommends")->getCandidateAgeRank($filter);
        $data['candidate_age_rank'] = $candidate_age_rank;
        $this->assign($data);
        $this->display("candidate");


    }

    private function _getFilterColumns() {

         $columns = array(
            "name" => array("display_name" => "姓名" , ),
            "mobile" => array("display_name" => "联系电话" , ),
            "org" => array("display_name" => "工作单位" , ),
            "email" => array("display_name" => "联系邮件", ),
            "address_province" => array("display_name" => "省份", ),
        );

        return $columns;
    }


    //银杏伙伴
    public function partner() {
        
        $filter_columns = $this->_getFilterColumns();
        $filter = array();
        foreach(array_keys($filter_columns) as $v) {
            $value = trim(htmlspecialchars($this->_get($v)));
            if(!$value) continue;
            $filter["user_recommends." . $v] = array('like', '%'.$value.'%');

        }
        //银杏伙伴的数量
        $partner_count = D("UserRecommends")->getPartnerCount($filter);
        $data['partner_count'] = $partner_count;
        //银杏伙伴的地址
        $partner_province_rank = D("UserRecommends")->getPartnerProvinceRank($filter);
        $data['partner_province_rank'] = $partner_province_rank;
        //银杏伙伴的性别
        $partner_gender_rank = D("UserRecommends")->getPartnerGenderRank($filter);
        $data['partner_gender_rank'] = $partner_gender_rank;
        $this->assign($data);
        $this->display("partner");
        
    }


    //统计数据
    public function data() {

        $num_of_status = D("UserRecommends")->getNumOfAllStatus();
        $data['num_of_status'] = $this->_silceByMax($num_of_status, 9);
        $status_map = D("UserStatuses")->getAuditStatusNameIdMap();
        $data['status_map'] = $status_map;
        $this->assign($data);
        $this->display("data");

    }



    //传入一个数组，以及限制数量，返回截取后的数组
    private function _silceByMax($target, $max = 10) {

        //如果当前数量小于指定的最大数量，那么直接输出，否则切出最大数量个数
        return count($target) <= $max?$target:array_slice($target, 0, $max);

    }



}