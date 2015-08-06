<?php

class UserRecommendsModel extends BaseModel {

    function _after_select(&$resultSet,$options){
        foreach ($resultSet as $k => $v) {
            $resultSet[$k] = $this->_after_find($v);
        }
        return $resultSet;
     }

    function  _after_find(&$result,$options){
        if($result) {
            $result['pm_display_toggle'] = unserialize($result['pm_display_toggle']);            
            $result['status_name'] = M("UserStatuses")->where('id=%d',$result['status'])->getField('name');
            $result['address'] = $result['address_province'] . '&nbsp;' . $result['address_city'] . '&nbsp;' . $result['address_area'] . '&nbsp;' . $result['address'];
            $result['recommend_user'] = $ru = D("UserInfo")->getById($result['recommend_user_id']);

            // 更新推荐人信息
            $columns = D("CustomColumns")->getUserRecommendModuleColumns();
            foreach($columns as $k=>$v) {
                if(strpos($k,'recommender_')===0) {
                    $result[$k] = $ru[substr($k,12)];
                }
            }
        }
        return $result;
    }

    private function _buildFilter($filter) {

        if(!$filter['status'] || $filter['status']==='all') {
            $filter['status'] = array('gt', 1);
        }

        return $filter;
    }

    public function getCount($filter) {
        $filter = $this->_buildFilter($filter);
        $count = $this->where($filter)->count();

        return $count;
    }

    public function gets($filter, $page=0, $size=0, $order = null) {

        $filter = $this->_buildFilter($filter);
        
        $user_obj = D("UserRecommends");
        if(!$order) {
            $order = 'update_time desc';
        }

        $users = $user_obj->where($filter)->order($order);
        if($page && $size) {
            $users->page($page, $size);
        }
        $result = $users->select();

        // var_dump($users->getLastSql());
        // return $result;

        foreach($result as $k => $v) {
            $result[$k] = $this->_rich($v);
        }

        return $result;
    }


    public function getRecommend($id) {
        $u = $this->find($id);
        if(!$u) return null;

        return $this->_rich($u);
    }

    private function _rich($one) {
        if(!$one) return NULL;

        if(has_submit_apply($one['status'])) {
            $one['userinfo'] = D("UserInfo")->getByInviteCode($one['invite_code']);
        }

        return $one;
    }

    public function getStatusCountMap() {
        $filter['status'] = array('gt',1);
        // $filter['apply_type_id'] = $apply_type_id;
        return $this->where($filter)->group('status')->getField('status,count(*) count',true);
    } 



    //传入一个数组，返回按照出现数量排序的二维数组
    private function _getSortRank($target) {

        //统计数组元素出现的次数
        $count_res = array_count_values($target);     
        //按数组的值降序排列
        arsort($count_res);    
        return $count_res;

    }

    //得到被推荐人表中ID与推荐人ID对应关系，参数为是否包括银杏伙伴，默认不包括
    private function _getRecommendIdMap($include_partner = false) {

        //如果包括银杏伙伴
        if ($include_partner) {
            return $this->getField('id,recommend_user_id');
        } else {
            return $this->where("status < 99")->getField('id,recommend_user_id');
        }

    }

    //得到推荐人的所有去除重复后的ID数组
    private function _getAllRecommedID() {

        //初始化空数组，用来保存不重复的推荐人ID
        $recommend_id = array();
        //查找包括成为银杏伙伴的被推荐人
        $id_map = $this->_getRecommendIdMap(true);
        //得到UserInfo的ID,需要判定id_map中的ID是否有效
        $user_info_id = D("UserInfo")->getIdMap();
        foreach ($id_map as $key => $val) {
            //$val代表推荐人的ID，如果该ID存在有效而且没有加入到recommend_id数组中,加之
            if (in_array($val, $user_info_id) && !in_array($val, $recommend_id)) {
                array_push($recommend_id, $val);
            }
        }
        return $recommend_id;

    }

     //传入一个数组，以及限制数量，返回截取后的数组
    private function _silceByMax($target, $max = 10) {

        //如果当前数量小于指定的最大数量，那么直接输出，否则切出最大数量个数
        return count($target) <= $max?$target:array_slice($target, 0, $max);

    }

    //得到推荐人的数量，被推荐人成为银杏伙伴也要统计
    public function getRecommedCount() {

        $recommend_id = $this->_getAllRecommedID();
        return count($recommend_id);

    }


    //推荐人按照性别排名
    public function getRecommendGenderRank() {

        //去除重复后的推荐人ID
        $recommend_id = $this->_getAllRecommedID();
        //要统计的数组
        $target = $this->table("user_recommends,user_info")->distinct(true)->where("user_recommends.recommend_user_id = 
        user_info.id")->getField("user_info.id,gender",true);
        $rank_result = $this->_getSortRank($target);
        //如果不存在男或者女，则增补数量0
        if (!$rank_result['男']) {
            $rank_result['男'] = 0;
        } 
        if (!$rank_result['女']) {
            $rank_result['女'] = 0;
        }
        return $rank_result;

    }


    //按照推荐人推荐的候选人数量排名
    public function getRecommendNumRank() {

        $recommed_name = array();
        //要统计的数组
        //$target = $this->table("user_recommends,user_info")->where("user_recommends.recommend_user_id = 
        //user_info.id")->getField("user_info.name as name",true);
        $target = $this->query("SELECT user_info.name
                                FROM  `user_recommends` ,  `user_info` 
                                WHERE user_recommends.recommend_user_id = user_info.id");
        //取出name字段，然后变为一维数组
        foreach ($target as $val) {
            array_push($recommed_name, $val['name']);
        }
        //按照出现的个数统计
        $rank_result = $this->_getSortRank($recommed_name);
        return $this->_silceByMax($rank_result);

    }


    //得到候选人的数量，不包括银杏伙伴,不管成功失败均统计
    public function getCandidateCount() {

        $result = $this->where('status<99')->select();
        return count($result);

    }


    //候选人按照省份排名
    public function getCandidateProvinceRank() {

        //要统计的数组
        $target = $this->where("status<99")->getField("address_province",true);
        $rank_result = $this->_getSortRank($target);
        return $this->_silceByMax($rank_result);

    }


    //返回候选人的性别统计，没有成为合作伙伴
    public function getCandidateGenderRank() {

        //通过Invite_code来获得所有的候选人,两个表中invite_code一致且不为空，status小于99，即没有成为合作伙伴
        $target = $this->table("user_recommends,user_info")->where("user_recommends.invite_code = 
            user_info.invite_code and user_recommends.invite_code !='' and user_recommends.status < 99")->getField("gender",true);
        $rank_result = $this->_getSortRank($target);
        //如果不存在男或者女，则增补数量0
        if (!$rank_result['男']) {
            $rank_result['男'] = 0;
        } 
        if (!$rank_result['女']) {
            $rank_result['女'] = 0;
        }
        return $rank_result;

    }


    //返回候选人的年龄统计，没有成为合作伙伴
    public function getCandidateAgeRank() {

        //通过Invite_code来获得所有的候选人,两个表中invite_code一致且不为空，status小于99，即没有成为合作伙伴
        $target = $this->table("user_recommends,user_info")->where("user_recommends.invite_code = 
            user_info.invite_code and user_recommends.invite_code !='' and user_recommends.status < 99")->getField("birthday",true);
        $ages = array();
        foreach ($target as $val) {
            //echo intval(date("Y",time())) ."-" . intval(explode("-", $val)[0])."<br>";
            array_push($ages, intval(date("Y",time())) - intval(explode("-", $val)[0]));
        }
        $rank_result = $this->_getSortRank($ages);
        return $this->_silceByMax($rank_result);

    }

    //得到银杏伙伴的数量
    public function getPartnerCount() {

        $result = $this->where('status=99')->select();
        return count($result);

    }


    //银杏伙伴按照省份排名
    public function getPartnerProvinceRank() {

        //要统计的数组
        $target = $this->where("status=99")->getField("address_province",true);
        $rank_result = $this->_getSortRank($target);
        return $this->_silceByMax($rank_result);

    }


    //银杏伙伴按照性别排名
    public function getPartnerGenderRank() {

        //通过Invite_code来获得所有的银杏伙伴其他信息
        $target = $this->table("user_recommends,user_info")->where("user_recommends.invite_code = 
            user_info.invite_code and user_recommends.invite_code !='' and user_recommends.status = 99")->getField("gender",true);
        $rank_result = $this->_getSortRank($target);
        //如果不存在男或者女，则增补数量0
        if (!$rank_result['男']) {
            $rank_result['男'] = 0;
        } 
        if (!$rank_result['女']) {
            $rank_result['女'] = 0;
        }
        return $rank_result;

    }

    //统计数据通用方法，传入status获得人数
    private function _getNumByStatus($status) {

        return $this->where("status=".$status)->getField("count(*)");
        
    }

    //统计所有类别的数据
    public function getNumOfAllStatus() {

        //保存状态和数量对应关系的数组
        $num_of_status = array();
        //获得各个状态及代号
        $user_statuses = D('UserStatuses')->getStatusIdNameMap();
        $status_count = count($user_statuses);
        foreach ($user_statuses as $key => $value) {
           array_push($num_of_status,array($value => intval($this->_getNumByStatus($key))));
        }
        return $num_of_status;

        /*
        结果类似如下内容：
        array (size=11)
          0 => 
            array (size=1)
              '推荐表审核' => int 1
          1 => 
            array (size=1)
              '待背景调查' => int 2

        */

    }






}
?>