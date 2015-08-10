<?php

class StatAction extends BaseAction {

    private $data;

    //默认页面
    public function index() {

        $this->recommend();

    }

    //推荐人信息
    public function recommend() {

        //推荐人数量
        $recommend_count = D("UserRecommends")->getRecommedCount();
        $data['recommend_count'] = $recommend_count;
        //候选人数量
        $candidate_count = D("UserRecommends")->getCandidateCount();
        $data['candidate_count'] = $candidate_count;
        //按照性别排名
        $recommend_gender_rank = D("UserRecommends")->getRecommendGenderRank();
        $data['recommend_gender_rank'] = $recommend_gender_rank;
        //按照推荐人推荐的数量排名
        $recommend_num_rank = D("UserRecommends")->getRecommendNumRank();
        $data['recommend_num_rank'] = $recommend_num_rank;
        $this->assign($data);
        $this->display("recommend");

    }

    //候选人信息
    public function candidate() {

        //候选人的数量
        $candidate_count = D("UserRecommends")->getCandidateCount();
        $data['candidate_count'] = $candidate_count;
        //候选人的地址
        $candidate_province_rank = D("UserRecommends")->getCandidateProvinceRank();
        $data['candidate_province_rank'] = $candidate_province_rank;
        //候选人的性别，注意，此处候选人未通过之前没有性别信息，所以不统计
        $candidate_gender_rank = D("UserRecommends")->getCandidateGenderRank();
        $data['candidate_gender_rank'] = $candidate_gender_rank;
        //候选人的年龄
        $candidate_age_rank = D("UserRecommends")->getCandidateAgeRank();
        $data['candidate_age_rank'] = $candidate_age_rank;
        $this->assign($data);
        $this->display("candidate");


    }

    //银杏伙伴
    public function partner() {
        
        //银杏伙伴的数量
        $partner_count = D("UserRecommends")->getPartnerCount();
        $data['partner_count'] = $partner_count;
        //银杏伙伴的地址
        $partner_province_rank = D("UserRecommends")->getPartnerProvinceRank();
        $data['partner_province_rank'] = $partner_province_rank;
        //银杏伙伴的性别
        $partner_gender_rank = D("UserRecommends")->getPartnerGenderRank();
        $data['partner_gender_rank'] = $partner_gender_rank;
        $this->assign($data);
        $this->display("partner");
        
    }

    //统计数据
    public function data() {

        $num_of_status = D("UserRecommends")->getNumOfAllStatus();
        $data['num_of_status'] = $this->_silceByMax($num_of_status, 9);
        $this->assign($data);
        $this->display("data");

    }



    //传入一个数组，以及限制数量，返回截取后的数组
    private function _silceByMax($target, $max = 10) {

        //如果当前数量小于指定的最大数量，那么直接输出，否则切出最大数量个数
        return count($target) <= $max?$target:array_slice($target, 0, $max);

    }



}