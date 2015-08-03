<?php
class AdminVisitsModel extends BaseModel {
    
    //insert update delete
    public function saveVisit($module, $module_id, $extra_data) {

        //暂时去掉  2013年6月18日
        return;

        $insert['user_id'] = $this->getLoginUserId();
        $insert['ipaddress'] = Utility::GetRemoteIp();
        $insert['module'] = $module;           // 分list，detail
        $insert['module_id'] = $module_id;     // 如果是list 标示第几页，如果是detail，是detailid
        $insert['timestamp'] = time();
        $insert['extra_data'] = $extra_data;

        $obj = D('AdminVisits');
        $obj->create($insert);
        return $obj->add();
    }

    public function getVisits($filter, $page, $size) {
        
        return D('AdminVisits')->where($filter)->page($page, $size)->order('timestamp desc')->select();
    }

    public function getUserVisitMap($manage_group_ids, $within) {
        if(!$within) $within = time()-3600*24;

        $sql = 'select user_id, count(user_id) count from visits 
                where user_group_id in ('. implode(',', $manage_group_ids) .') 
                and timestamp>'. $within .' group by user_id';
        $raw = $this->query($sql, true);
        foreach ($raw as $v) {
            $rs[$v['user_id']] = $v['count'];
        }
        return $rs;
    }
}