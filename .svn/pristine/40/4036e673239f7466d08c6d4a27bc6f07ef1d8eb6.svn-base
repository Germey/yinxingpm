<?php
class AdminLogsModel extends BaseModel {
    
    //insert update delete
    public function saveLog($module, $data, $operation, $module_id, $module_name) {

        $insert['user_id'] = $this->getLoginUserId();
        $insert['module'] = $module;
        $insert['operation'] = $operation;
        $insert['module_id'] = $module_id;
        $insert['module_name'] = $module_name;
        $insert['timestamp'] = time();

        if($operation== 'insert' || $operation== 'update') {        
            if(!$insert['module_id']) $insert['module_id'] = $data['id'];
            if(!$insert['module_name']) $insert['module_name'] = $data['name'] ? $data['name'] : $data['title'];
        }
        $insert['data'] = serialize($data);

        $obj = D('AdminLogs');
        $obj->create($insert);

        return $obj->add();
    }


    public function getAdminLogs($filter, $page, $size) {
        
        return D('AdminLogs')->where($filter)->page($page, $size)->order('timestamp desc')->select();
    }


    public function getUserLogMap($manage_group_ids, $within) {
        if(!$within) $within = time()-3600*24;

        $sql = 'select user_id, count(user_id) count from admin_logs 
                where user_group_id in ('. implode(',', $manage_group_ids) .') 
                and timestamp>'. $within .' group by user_id';
        $raw = $this->query($sql, true);
        foreach ($raw as $v) {
            $rs[$v['user_id']] = $v['count'];
        }
        return $rs;
    }

}