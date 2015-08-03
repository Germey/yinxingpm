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

}
?>