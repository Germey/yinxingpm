<?php

class ProjectsModel extends BaseModel {

    function _after_select(&$resultSet,$options){
        return $resultSet;
     }

    function  _after_find(&$result,$options){
        return $result;
    }

    // public function 
    private function _buildFilter($filter) {

        if($filter['filter_statuses']) {
            $filter['status'] = array('in', explode(',', $filter['filter_statuses']));
        }

        if($filter['projects.contact_name']) {
            //partner user
            $f = array();
            $f['leader_name'] = $filter['projects.contact_name'];
            $filter['partner_id'] = array('in', M('Partners')->where($f)->getField('id', true));
            unset($filter['projects.contact_name']);
        }

        if($filter['projects.partner_title']) {
            $f = array();
            $f['title'] = $filter['projects.partner_title'];
            $pids = M('Partners')->where($f)->getField('id', true);

            // $ff['partner_id'] = array('in', $pids);
            $filter['partner_id'] = array('in', $pids);

            unset($filter['projects.partner_title']);
        }

        if($filter['grant_time_min'] || $filter['grant_time_max']) {
            if($filter['grant_time_min']) {
                $time_min = intval(trim($filter['grant_time_min']));
                if(strlen($time_min)!==6) {
                    $time_min = 0;
                    Session::Set('error', '搜索中项目开始格式有误，应该为YYYYMM，如201202');
                } else {
                    $filter['contract_time'] = array('egt', substr($time_min, 0,4) . '-' . substr($time_min, 4,2));
                }
            }

            if($filter['grant_time_max']) {
                $time_max = trim($filter['grant_time_max']);
                if(strlen($time_max)!==6) {
                    $time_max = 0;
                    Session::Set('error', '搜索中项目结束格式有误，应该为YYYYMM，如201202');
                } else {
                    $filter['contract_time'] = array('elt', substr($time_max, 0,4) . '-' . substr($time_max, 4,2));
                }
            }

            if($time_min && $time_max) {
                $filter['contract_time'] = array('between', substr($time_min, 0,4) . '-' . substr($time_min, 4,2) . ',' . substr($time_max, 0,4) . '-' . substr($time_max, 4,2));
            }
        }

        if($filter['amount_min'] || $filter['amount_max']) {
            if($filter['amount_min']) {
                $amount_min = intval(trim($filter['amount_min'])) * 10000;
                if($amount_min) {
                    $filter['contract_budget'] = array('egt', $amount_min);
                }
            }
            if($filter['amount_max']) {
                $amount_max = intval(trim($filter['amount_max'])) * 10000;
                if($amount_max) {
                    $filter['contract_budget'] = array('elt', $amount_max);
                }
            }
            if($amount_min && $amount_max) {
                $filter['contract_budget'] = array('between', $amount_min.',' . $amount_max);
            }
        }

        if($filter['contract_year']) {
            $filter['contract_time'] = array('like', $filter['contract_year'].'%');
            unset($filter['contract_year']);
        }

        if($filter['contract_amount_range']) {
            $range = D('Stat')->getProjectAmountRangeReverse($filter['contract_amount_range']);
            $filter['contract_budget'] = array('between', $range[0].',' . $range[1]);
        }

        if($filter['projects.budget_range']) {
            $range = D('Stat')->getProjectAmountRangeReverse($filter['budget_range']);
            $filter['need_budget'] = array('between', $range[0].',' . $range[1]);
            unset($filter['projects.budget_range']);
        }

        //其实是typeids
        if($filter['type_name']) {
            $type = M('CommonTypes')->getFieldByName($filter['type_name'], 'id');           
            $filter['type'] = array('like', '%"'. $type .'"%');
            unset($filter['type_name']);
            unset($filter['projects.type_name']);
        }
        
        if(!$filter['status']) {
            $filter['status'] = array('gt',1);
        }

        if($filter['projects.batch_short_title']) {
            $filter['batch_id'] = str_replace('%', '', $filter['projects.batch_short_title'][1]);
            unset($filter['projects.batch_short_title']);
        }

        return $filter;
    }

    public function getProjectsCount($filter) {
        $filter = $this->_buildFilter($filter);
        $count = M("Projects")->where($filter)->count();

        return $count;
    }

    public function getProjects($filter, $page=0, $size=0, $order = null, $rich=false) {
        $filter = $this->_buildFilter($filter);
        $projectModel = D("Projects");
        if(!$order) {
            $order = 'id desc';
            // $order = array("update_time", "desc");
        }else{
            if(strpos($order[0], '_meta_') !== false){
                // $filter['meta_key'] = $order[0];
                $projectModel->join("left join project_metas cm on projects.id = cm.project_id and meta_key = '" . $order[0] . "'");
                $projectModel->field("projects.*");
                $order = $this->generate_order_str("meta_value", $order[1]);
            }else if($order[0] == 'owned_user_group_name'){
                $projectModel->join("left join user_groups on projects.user_group_id = user_groups.id");
                $projectModel->field("projects.*");
                $order = $this->generate_order_str("user_groups.name", $order[1]);
            }else if($order[0] == 'owned_user_id'){
                $projectModel->join("left join users on projects.owned_user_id = users.id");
                $projectModel->field("projects.*");
                $order = $this->generate_order_str("users.realname", $order[1]);
            }else{
                if(is_array($order)) {
                    $order = $this->generate_order_str($order[0], $order[1], $order[1]);
                } 
            }
        }
        $projects = $projectModel->where($filter)->order($order);
        if($page && $size) {
            $projects->page($page, $size);
        }
        $data = $projects->select();

        return $this->getRichInfo($data, $rich);
    }
    //兼容中文
    public function generate_order_str($order_by, $desc, $as_number){
        if($as_number) {
            return $order_by . '*1 ' . $desc;
        }
        return "convert(" . $order_by . " USING gbk) COLLATE gbk_chinese_ci " . $desc;
    }

    public function getProjectById($project_id, $rich=true) {
        $project = $this->getById(intval($project_id));

        return $this->getRichInfo($project, $rich);
    }

    public function getRichInfo($projects, $rich=false) {
        if(!$projects) return NULL;

        $one = 0;
        if(!$projects[0]) {
            $one = $projects['id'];
            $ps[0] = $projects;
            $projects = $ps;
        }
        $projects = Utility::AssColumn($projects);
        $ids = array_keys($projects);

        // $types = M('CommonTypes')->where("`key`='project_type'")->getField('id, name');
        // $service_objects = M('CommonTypes')->where("`key`='project_service_object'")->getField('id, name');
        // $action_types = M('CommonTypes')->where("`key`='project_action_type'")->getField('id, name');
        $statuses = M('ProjectStatuses')->getField('id, name');

        foreach ($projects as $k => $v) {
            $v['link'] = '/project/detail/' . $v['id'];

            // if($v['address_province']) {
            //     $v['address'] = '【'.$v['address_province'] .'】'. $v['address'];
            // }

            // $v['batch'] = D('ProjectBatches')->getById($v['batch_id']);
            // $v['batch_short_title'] = $v['batch']['short_title'];

            // if($v['judge1_id']) {
            //     $v['judge_names'] = M('Users')->getFieldById($v['judge1_id'], 'realname');
            // }
            // if($v['judge2_id']) {
            //     $v['judge_names'] .=  '，' . M('Users')->getFieldById($v['judge2_id'], 'realname');
            // }

            $v['status_name'] = $statuses[$v['status']];
            
            //
            $v['manager'] = M('Users')->getById($v['manager_id']);
            $v['manager_name'] = $v['manager']['realname'];

            //
            // $v['partner'] = D('Partners')->getPartnerById($v['partner_id']);
            // $v['partner_title'] = $v['partner']['title'];



            //已累计资助额
            $f['project_id'] = $v['id'];
            $f['is_planed'] = 0;
            $v['had_grant_amount'] = M('ProjectGrantRecords')->where($f)->sum('amount');
     

            $v['grant_amount_ratio'] = '0%';
            if($v['contract_budget']) {
                $v['grant_amount_ratio'] = moneyit($v['had_grant_amount']) . '（' .intval($v['had_grant_amount']*100/$v['contract_budget']) . '%' . '）';
            }

            //进度
            $tmpend = strtotime($v['actual_end_time']>'1'?$v['actual_end_time']:$v['end_time']);
            $tmpstart = strtotime($v['start_time']);
            $total = $tmpend - $tmpstart;
            $already = time() - $tmpstart;
            if(intval($already) <= 0) {
                $v['progress'] = 0;
            } else {
                $v['progress'] = intval($already*100 / $total);
                if($v['progress'] > 100) {
                    $v['progress'] = 100;
                }
            }

            $v['period_month'] = intval($total/(30*24*3600));
            $tmpend = $v['actual_end_time']>'1'?$v['actual_end_time']:$v['end_time'];
            $v['period_string'] = str_replace('-', '.', $v['start_time'] . '~' . $tmpend) . '（'. $v['period_month'] .'个月）';
            if($v['actual_end_time'] && $v['actual_end_time']>$v['end_time']) {
                $postpone_days = (strtotime($v['actual_end_time']) - strtotime($v['end_time']))/3600/24;
                $v['period_string'] .= '<a data-toggle="tooltip" title="项目延期'.$postpone_days.'天，合同结束时间为'.$v['end_time'].'" class="mytooltip"><i class="icon-exclamation-sign"></i></a>';
            }

            $v['grant_records'] = D('ProjectGrantRecords')->getGrantRecords($v['id']);

            //detail
            $v = array_merge($v, D('ProjectDetails')->getById($v['id']));                

            $v['pm_display_toggle'] = unserialize($v['pm_display_toggle']);

            $projects[$k] = $v;
        }

        if($one) return $projects[$one];

        return $projects;
    }


    //函数定义不要和parent里面的重复
    public function saveProject($data) {
        // if(!$data['title']) return 0;

        $cm = D('Projects');
        
        //负责人（从partner中按照姓名查找）

        //带钱的需要把 , 去掉
        $project_all_columns = D('CustomColumns')->getProjectModuleColumns(1,1);
        foreach ($project_all_columns as $k => $v) {
            if($data[$k] && $v['extra_type'] == 'money') {
                $data[$k] = str_replace(',', '', $data[$k]);
            }
        }

        if($cm->create($data)) {
            $last_id = $cm->saveOrUpdate();

            //资助计划
            D('ProjectGrantRecords')->update($last_id, 1, $data);
            
            //详细信息
            // $this->saveProjectDetail($last_id, $data);

            return $last_id;
        }
        return 0;
    }

    private function saveProjectDetail($project_id, $data) {
        if(!$project_id) return;

        foreach ($data as $k => $v) {
            if(strpos($k, 'project_detail_')===0) {
                $insert[substr($k, 15)] = $v;
            }
        }
        if(!$insert) return;
        
        $insert['id'] = $project_id;
        // 不能自动根据ID去saveOrUpdate，这个比较特殊，因为这里ID每次都是有的，所以默认就去update了，但是数据库里面可能没有记录
        if(M('ProjectDetails')->getById($project_id)) {
            return M('ProjectDetails')->saveOrUpdate($insert);
        } else {
            return M('ProjectDetails')->add($insert);
        }
    }

    public function updateProjectField($ids = array(), $manage_group_ids, $field, $new_value) {
       // meta or not
        if( strpos($field, '_meta_') === 0 ) {
            $cms = M("ProjectMetas");

            foreach ($ids as $cid) {
                if(!$cid) continue;
                $data = array();
                $data['project_id'] = $cid;
                $data['meta_key'] = $field;

                // delete first
                $cms->where($data)->delete();

                //
                $data['meta_value'] = $new_value;
                $cms->add($data);
            }

            //update project time
            $filter['id'] = array('in', $ids);
            D("Projects")->where($filter)->setField("update_time",date('Y-m-d H:i:s'));
            return ;
        } else {
            $cs = M("Projects");
            $data = array($field=>$new_value, 'update_time' => date('Y-m-d H:i:s'));
            $cond['user_group_id'] = array('in', $manage_group_ids);
            $cond['id'] = array('in', $ids);
            return $cs->where($cond)->setField($data);            
        }
    }

    public function deleteProject($project_id) {
        $id = intval($project_id);
        if(!$id) return;

        // M("ProjectNotes")->where(array("project_id" => $id))->delete();
        // M("ProjectAttachments")->where(array("project_id" => $id))->delete();
        // M("ProjectGrantRecords")->where(array("project_id" => $id))->delete();

        $up['id'] = $id;
        $up['status'] = 0;
        
        $this->save($up);
        return;
    }

    //有联合资助的情况
    public function getByPartnerId($partner_id) {
        $filter['partner_id'] = $partner_id;
        $filter['status'] = array('gt',1);

        return $this->getProjects($filter);
    }


    public function getDuplicateProjectsByColumn($param, $column, $group_ids) {
        $sql = "SELECT * FROM projects p where p.user_group_id in(" . implode(",", $group_ids) . ")  and p.$column = '$param'";
        $result = M()->query($sql);
        return $result;
    }


    public function getStatusCountMap($by='status') {
        $sql = 'SELECT status, count(*) count FROM projects where status>0 group by status ';
        $raw = M()->query($sql);
        foreach ($raw as $v) {
            $res[$v['status']] = $v['count'];
        }

        return $res;
    } 
}
?>