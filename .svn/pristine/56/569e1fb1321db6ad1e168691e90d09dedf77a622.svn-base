<?php

class UserInfoModel extends BaseModel {

    function _after_select(&$resultSet,$options){
        foreach ($resultSet as $k => $v) {
            $resultSet[$k] = $this->_after_find($v);
        }
        return $resultSet;
     }

    function  _after_find(&$result,$options){
        if($result) {
            $result['address'] = trim($result['address_province'] . '&nbsp;' . $result['address_city'] . '&nbsp;' . $result['address_area'] . '&nbsp;' . $result['address'], '&nbsp;');
        }
        return $result;
    }

    private function _buildFilter($filter) {

        if(!$filter['status']) {
            $filter['status'] = array('gt', 1);
        }

        return $filter;
    }

    public function getUserInfoCount($filter) {
        $filter = $this->_buildFilter($filter);
        $count = M("UserInfo")->where($filter)->count();

        return $count;
    }

    public function getUserInfos($filter, $page=0, $size=0, $order = null) {

        $filter = $this->_buildFilter($filter);
        
        $user_obj = D("UserInfo");
        if(!$order) {
            $order = 'update_time desc';
        }

        $users = $user_obj->where($filter)->order($order);
        if($page && $size) {
            $users->page($page, $size);
        }
        $result = $users->select();

        return $result;

        // return $this->_rich($data, $rich);
    }

    //兼容中文
    public function generate_order_str($order_by, $desc){
        return "convert(" . $order_by . " USING gbk) COLLATE gbk_chinese_ci " . $desc;
    }

    //通过ID返回用户数据
    public function getUserInfo($user_id) {
        $u = $this->find($user_id);
        if(!$u) return null;

        return $this->_rich($u);
    }

    public function _rich($one) {
        if(!$one) return NULL;

        if($one['invite_code']) {
            $one['recommend_info'] = M("UserRecommends")->getByInviteCode($one['invite_code']);
            $one['recommender'] = M("UserInfo")->getById($one['recommend_info']['recommend_user_id']);

            $no_fields = array('name','org','mobile','email','address','address_province','address_city','address_area');
            foreach ($one['recommend_info'] as $k => $v) {
                if(in_array($k, $no_fields)) continue;
                $one[$k] = $v;
            }
        }
        $one['status_name'] = M("UserStatuses")->where('id=%d',$one['status'])->getField('name');

        if($one['editable']) {
            $one['status_name'] .= '（需完善修改）';
        }

        return $one;
    }

    public function syncToContacts($last_sync_time) {
        $date_time = date('Y-m-d H:i:s', $last_sync_time);
        $partners = $this->where("update_time>'$date_time' or leader_remote_contact_id<1")->select();

        foreach ($partners as $p) {
            $this->syncToContact($p);
        }

        return;
    }

    public function getStatusCountMap() {
        $filter['status'] = array('gt',1);
        // $filter['apply_type_id'] = $apply_type_id;
        return $this->where($filter)->group('status')->getField('status,count(*) count',true);
    } 

    public function autoGenerateIdentifier($user_type_id) {
        $prefix = $user_type_id==1?'V':'E';
        $year = date('Y');

        $f['identifier'] = array('like', $prefix.$year.'%');
        $max = intval(str_replace($prefix.$year, '', $this->where($f)->max('identifier')));

        return $prefix . $year . str_pad($max+1, 3, "0", STR_PAD_LEFT);
    }


    //返回ID序列数组
    public function getIdMap() {
        //第二个参数为True返回所有值
        return $this->getField("id", true);
    }

    //通过姓名获取ID
    public function getIdByName($name) {
        $where['name'] = array("like",'%'.$name.'%');
        $id = $this->where($where)->getField("id");
        return $id;
    }


}
?>