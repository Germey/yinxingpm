<?php

class StatAction extends BaseAction {

    public function index() {
        // $data['sbatchs'] = $sbatchs = $this->_get('sbatchs');
        // $data['batch_range']  = $batchs = M('ProjectBatches')->getField('id,short_title',true);

        $filter['status'] = array('gt', 1);
        if($sbatchs) {
            $filter['batch_id'] = array('in', $sbatchs);
        }
        $all_projects = Utility::AssColumn(M('Projects')->where($filter)->select());

        $project_steps = D('ProjectStatuses')->getStatusStepIds();

        $small_budget = 100000000;
        $short_period_time = 900000000;      //月
        $time = time();

        foreach ($all_projects as $k => $v) {
            $project_count++;
            $all_need_budget += $v['need_budget'];

            if(in_array($v['status'], $project_steps['ing_status_ids'])) {
                $ing_project_count++;
            }

            if(in_array($v['status'], $project_steps['init_status_ids'])) {
                $init_project_count++;
            }
            if(in_array($v['status'], $project_steps['end_status_ids'])) {
                $end_project_count++;
            }
            if(in_array($v['status'], $project_steps['fail_status_ids'])) {
                $fail_project_count++;
                // 下面的统计就不要失败项目的了
                continue;
            }

            $contract_budget = $v['contract_budget'];
            if(!intval($contract_budget)) continue;

            $good_project_count++;
            $total_contract_budget += $contract_budget;
            if($contract_budget && $contract_budget > $big_budget) {
                $big_budget_id = $k;
                $big_budget = $contract_budget;
            }
            if($contract_budget && $contract_budget < $small_budget) {
                $small_budget_id = $k;
                $small_budget = $contract_budget;
            }

            $tmpend = strtotime($v['actual_end_time']>'1'?$v['actual_end_time']:$v['end_time']);
            $tmpstart = strtotime($v['start_time']);
            $period_time = $tmpend - $tmpstart;

            $total_period_time += $period_time;
            if($period_time && $period_time > $long_period_time) {
                $long_period_time = $period_time;
                $long_period_id = $k;
            }
            if($period_time && $period_time < $short_period_time) {
                $short_period_time = $period_time;
                $short_period_id = $k;
            }
        }

        $data['project_count'] = intval($project_count);
        $data['all_need_budget'] = intval($all_need_budget);
        $data['ing_project_count'] = intval($ing_project_count);
        $data['init_project_count'] = intval($init_project_count);
        $data['fail_project_count'] = intval($fail_project_count);
        $data['end_project_count'] = intval($end_project_count);

        $data['total_contract_budget'] = $total_contract_budget;
        if($big_budget) {
            $data['big_budget'] = $big_budget;
        }
        $data['big_budget_id'] = $big_budget_id;
        if($small_budget && $small_budget !== 100000000) {
            $data['small_budget'] = $small_budget;
        }
        $data['small_budget_id'] = $small_budget_id;
        $data['average_budget'] = intval($total_contract_budget/$good_project_count);

        $month_time = 30*24*3600;
        if($long_period_time) {
            $data['long_period'] = round($long_period_time/$month_time, 1);
        }
        if($short_period_time && $short_period_time !== 900000000) {
            $data['short_period'] = round($short_period_time/$month_time, 1);
        }
        $data['long_period_id'] = $long_period_id;
        $data['short_period_id'] = $short_period_id;
        $data['average_period'] = round($total_period_time/$good_project_count/$month_time, 1);

        $data['had_grant_amount'] = M('ProjectGrantRecords')->where('is_planed=0')->sum('amount');

        $partner_ids = M('Partners')->getField('id', true);
        $data['partner_count'] = count($partner_ids);
        $data['partner_average_project_count'] = round($good_project_count/$data['partner_count'], 1);
        
        // $ppm = D('Partners')->getPartnerProjectCount($partner_ids);

        // $partner_min_project_count = 10000;

        // foreach ($ppm as $k => $v) {
        //     if($v > $partner_max_project_count) {
        //         $partner_max_project_count = $v;
        //         $partner_max_project_id = $k;
        //     }

        //     if($v < $partner_min_project_count) {
        //         $partner_min_project_count = $v;
        //     }
        // }
        // $data['partner_max_project_count'] = $partner_max_project_count;
        // $data['partner_max_project_id'] = $partner_max_project_id;

        // $data['partner_min_project_count'] = $partner_min_project_count;

        $this->assign($data);
        D('AdminVisits')->saveVisit('home', 0);
        $this->display();
    }

    public function project() {

        // $data['sbatchs'] = $sbatchs = $this->_get('sbatchs');
        $data['samounts'] = $samounts = $this->_get('samounts');
        $data['stypes'] = $stypes = $this->_get('stypes');
        $data['sprovinces'] = $sprovinces = $this->_get('sprovinces');
        $data['sareas'] = $sareas = $this->_get('sareas');

        // 项目期
        // $data['batch_range']  = $batchs = M('ProjectBatches')->getField('id,short_title',true);

        $data['amount_range'] =  $amount_range = array('10万以下', '10-20万', '20-30万', '30以上');

        $data['type_range'] = $types = M('CommonTypes')->where("`key`='project_type'")->getField('id,name',true);
        $type_ids = array_keys($types);

        $data['province_range'] = $province_map = M('AreaProvinceMapping')->getField('province',true);
        $province_area_map = M('AreaProvinceMapping')->getField('province,area',true);
        $data['area_range'] = array_values(array_unique(array_values($province_area_map)));

        // get all stat projects
        $project_steps = D('ProjectStatuses')->getStatusStepIds();
        $stat_status_ids = array_merge($project_steps['ing_status_ids'], $project_steps['end_status_ids']);
        $f['status'] = array('in', $stat_status_ids);
        // 从列表页来的统计
        if($ids = trim($this->_get('ids'),',')) {
            $ids = explode(',', $ids);
            $f['id'] = array('in', $ids);
        } 
        $ps = D('Projects')->getProjects($f,0,0,'contract_time');

        foreach ($ps as $k => $v) {
            if($ids) {
                $show_project_titles[] = $v['title'];
            }

            $amount = D('Stat')->getProjectAmountRange($v['contract_budget']);
            $add = $v['address_city'];
            if(!$add) $add = '未知';

            //搜索过滤
            // if($sbatchs && !in_array($v['batch_id'], $sbatchs)) continue;
            if($samounts && !in_array($amount, $samounts)) continue;
            if($stypes && !array_intersect($stypes, $v['type'])) continue;

            if($sprovinces && !in_array($v['address_city'], $sprovinces)) continue;
            if($sareas && !in_array($province_area_map[$add], $sareas)) continue;

            // batch
            // $batch_data[$batchs[$v['batch_id']]]['need_budget'] += round($v['need_budget']/10000,1);
            // if($v['contract_time']>1) {
            //     $batch_pcount_map[$batch]++;
            //     // $batch_amount_map[$batch] += round($v['contract_budget']/10000,1);
            //     $batch_data[$batchs[$v['batch_id']]]['pcount']++;
            //     $batch_data[$batchs[$v['batch_id']]]['contract_budget'] += round($v['contract_budget']/10000,1);
            //     $batch_data[$batchs[$v['batch_id']]]['had_grant_amount'] += round($v['had_grant_amount']/10000,1);
            // }

            //amount
            $amount_data[$amount]['need_budget'] += round($v['need_budget']/10000,1);
            if($v['contract_budget']>0) {
                $amount_pcount_map[$amount]++;
                $amount_amount_map[$amount] += round($v['contract_budget']/10000,1);
                $amount_data[$amount]['pcount']++;
                $amount_data[$amount]['contract_budget'] += round($v['contract_budget']/10000,1);
                $amount_data[$amount]['had_grant_amount'] += round($v['had_grant_amount']/10000,1);
            }


            foreach ($v['type'] as $iid) {
                // $ptypes[] = $types[$iid];
                $type_pcount_map[$types[$iid]]++;
                $type_amount_map[$types[$iid]] += round($v['contract_budget']/10000,1);
                $type_data[$types[$iid]]['pcount']++;
                $type_data[$types[$iid]]['contract_budget'] += round($v['contract_budget']/10000,1);
                $type_data[$types[$iid]]['had_grant_amount'] += round($v['had_grant_amount']/10000,1);
                $type_data[$types[$iid]]['need_budget'] += round($v['need_budget']/10000,1);
            }

            // 省级统计
            $add_pcount_map[$add]++;
            $add_amount_map[$add] += round($v['contract_budget']/10000,1);
            $add_data[$add]['pcount']++;
            $add_data[$add]['contract_budget'] += round($v['contract_budget']/10000,1);
            $add_data[$add]['had_grant_amount'] += round($v['had_grant_amount']/10000,1);
            $add_data[$add]['need_budget'] += round($v['need_budget']/10000,1);
        }

        $data['add_pcount_map'] = $add_pcount_map;
        $data['add_amount_map'] = $add_amount_map;
        $data['add_data']       = $add_data;
        $data['show_project_titles']       = $show_project_titles;

        foreach ($add_pcount_map as $k => $v) {
            $area = $province_area_map[$k];
            $data['area_pcount_map'][$area] += $v;
            $data['area_amount_map'][$area] += $add_amount_map[$k];

            $data['area_data'][$area]['pcount'] += $add_data[$k]['pcount'];
            $data['area_data'][$area]['contract_budget'] += $add_data[$k]['contract_budget'];
            $data['area_data'][$area]['had_grant_amount'] += $add_data[$k]['had_grant_amount'];
            $data['area_data'][$area]['need_budget'] += $add_data[$k]['need_budget'];
        }

        $data['amount_pcount_map'] = $amount_pcount_map;
        $data['amount_amount_map'] = $amount_amount_map;
        $data['amount_data']       = $amount_data;

        // 量化统计项        
        $this->result_codes = $result_codes = D("ResultCodes")->order('sequence')->getField('id,name',true);

        $fr['project_id'] = array('in', Utility::GetColumn($ps));
        $quarter_results = D("ProjectQuarterResults")->where($fr)->order('quarter')->select();

        foreach ($quarter_results as $v) {
            $detail = unserialize($v['detail']);
            $result_ranges[$v['quarter']] = 1;
            foreach($result_codes as $k => $c) {
                $code_data[$c][$v['quarter']] += intval($detail[$k]);
                $result_data_by_quarter[$v['quarter']][$k] += intval($detail[$k]);
            }
        }

        $this->result_ranges = array_keys($result_ranges);
        $this->result_data_by_quarter = $result_data_by_quarter;

        $visible = true;
        foreach ($code_data as $k => $v) {
            $code_data_tmp[] = array('name'=>$k, 'data' => array_values($v), "visible" => $visible);
            if($visible) {
                $visible = false;
            }
        }

        $this->code_data  = $code_data_tmp;

        $this->assign($data);
        $this->display('project');
    }



    public function partner() {

        $data['sgrantcounts'] = $sgrantcounts = $this->_get('sgrantcounts');
        $data['syears'] = $syears = $this->_get('syears');
        $data['sstatus'] = $sstatus = $this->_get('sstatus');
        $data['showtoknows'] = $showtoknows = $this->_get('showtoknows');
        $data['sprovinces'] = $sprovinces = $this->_get('sprovinces');
        $data['sareas'] = $sareas = $this->_get('sareas');

        $data['year_range'] =  $year_range = array('1年以下', '1-2年', '2-4年', '4年以上');
        $data['statues_range'] = $statuses = M('CommonTypes')->where("`key`='partner_status'")->getField('id,name',true);
        $data['howtoknow_range'] = $howtoknows =  M('CommonTypes')->where("`key`='partner_howto_know'")->getField('id,name',true);

        $data['province_range'] = $province_map = M('AreaProvinceMapping')->getField('province',true);
        $province_area_map = M('AreaProvinceMapping')->getField('province,area',true);
        $data['area_range'] = array_values(array_unique(array_values($province_area_map)));

        $ps = D('Partners')->getPartners();
        $ppbm = D('Partners')->getPartnerProjectBudgetMap(Utility::GetColumn($ps));

        // 资助次数
        $data['grant_count_range'] = Utility::GetColumn($ppbm, 'project_count');
        if($sgrantcounts) {        
            foreach ($ppbm as $k => $v) {
                if(!in_array($v['project_count'], $sgrantcounts)) {
                    unset($ps[$k]);
                    unset($ppbm[$k]);
                }
            }
        }

        foreach ($ps as $k => $v) {
            $year =  substr($v['found_time'], 0, 4);

            $add = $v['address_city'];
            if(!$add) $add = '未知';

            //搜索过滤
            if($syears && !in_array(D('Stat')->getYearRange($year), $syears)) continue;
            if($showtoknows && !array_intersect($showtoknows, $v['howto_know'])) continue;
            if($sstatus && !in_array($v['status_id'], $sstatus)) continue;
            if($sprovinces && !in_array($v['address_city'], $sprovinces)) continue;
            if($sareas && !in_array($province_area_map[$add], $sareas)) continue;

            // year
            if($year) {
                $year_range = D('Stat')->getYearRange($year);
                $year_pcount_map[$year_range]++;
                $year_amount_map[$year_range] += round($ppbm[$v['id']]['contract_budget']/10000,1);
                $year_data[$year_range]['pcount']++;
                $year_data[$year_range]['project_count'] += $ppbm[$v['id']]['project_count'];
                $year_data[$year_range]['need_budget'] += round($ppbm[$v['id']]['need_budget']/10000,1);
                $year_data[$year_range]['contract_budget'] += round($ppbm[$v['id']]['contract_budget']/10000,1);
                $year_data[$year_range]['had_grant_amount'] += round($ppbm[$v['id']]['had_grant_amount']/10000,1);
            }

            //amount
            if($v['status_id']>0) {
                $status_name = $statuses[$v['status_id']];
                $status_pcount_map[$status_name]++;
                $status_amount_map[$status_name] += round($ppbm[$v['id']]['contract_budget']/10000,1);
                $status_data[$status_name]['pcount']++;
                $status_data[$status_name]['project_count'] += $ppbm[$v['id']]['project_count'];
                $status_data[$status_name]['need_budget'] += round($ppbm[$v['id']]['need_budget']/10000,1);
                $status_data[$status_name]['contract_budget'] += round($ppbm[$v['id']]['contract_budget']/10000,1);
                $status_data[$status_name]['had_grant_amount'] += round($ppbm[$v['id']]['had_grant_amount']/10000,1);
            }

            foreach ($v['howto_know'] as $iid) {
                // $ptypes[] = $types[$iid];
                $howtoknow_pcount_map[$howtoknows[$iid]]++;
                $howtoknow_amount_map[$howtoknows[$iid]] += round($ppbm[$v['id']]['contract_budget']/10000,1);
                $howtoknow_data[$howtoknows[$iid]]['pcount']++;
                $howtoknow_data[$howtoknows[$iid]]['project_count'] += $ppbm[$v['id']]['project_count'];
                $howtoknow_data[$howtoknows[$iid]]['need_budget'] += round($ppbm[$v['id']]['need_budget']/10000,1);
                $howtoknow_data[$howtoknows[$iid]]['contract_budget'] += round($ppbm[$v['id']]['contract_budget']/10000,1);
                $howtoknow_data[$howtoknows[$iid]]['had_grant_amount'] += round($ppbm[$v['id']]['had_grant_amount']/10000,1);
            }


            // address
            $add_pcount_map[$add]++;
            $add_amount_map[$add] += round($ppbm[$v['id']]['contract_budget']/10000,1);
            $add_data[$add]['pcount']++;
            $add_data[$add]['project_count'] += $ppbm[$v['id']]['project_count'];
            $add_data[$add]['need_budget'] += round($ppbm[$v['id']]['need_budget']/10000,1);
            $add_data[$add]['contract_budget'] += round($ppbm[$v['id']]['contract_budget']/10000,1);
            $add_data[$add]['had_grant_amount'] += round($ppbm[$v['id']]['had_grant_amount']/10000,1);
        }


        $data['year_pcount_map'] = $year_pcount_map;
        $data['year_amount_map'] = $year_amount_map;
        $data['year_data']       = $year_data;

        $data['howtoknow_pcount_map'] = $howtoknow_pcount_map;
        $data['howtoknow_amount_map'] = $howtoknow_amount_map;
        $data['howtoknow_data']       = $howtoknow_data;

        $data['status_pcount_map'] = $status_pcount_map;
        $data['status_amount_map'] = $status_amount_map;
        $data['status_data']       = $status_data;

        $data['add_pcount_map'] = $add_pcount_map;
        $data['add_amount_map'] = $add_amount_map;
        $data['add_data']       = $add_data;

        foreach ($add_pcount_map as $k => $v) {
            $area = $province_area_map[$k];
            $data['area_pcount_map'][$area] += $v;
            $data['area_amount_map'][$area] += $add_amount_map[$k];

            $data['area_data'][$area]['pcount'] += $add_data[$k]['pcount'];
            $data['area_data'][$area]['project_count'] += $add_data[$k]['project_count'];
            $data['area_data'][$area]['need_budget'] += $add_data[$k]['need_budget'];
            $data['area_data'][$area]['contract_budget'] += $add_data[$k]['contract_budget'];
            $data['area_data'][$area]['had_grant_amount'] += $add_data[$k]['had_grant_amount'];
        }

        foreach ($ppbm as $k => $v) {
            $data['project_count_pcount_map'][$v['project_count']]++;
            $data['project_count_data'][$v['project_count']]['pcount']++;
            $data['project_count_data'][$v['project_count']]['project_count'] += $v['project_count'];
            $data['project_count_data'][$v['project_count']]['need_budget'] += round($v['need_budget']/10000,1);
            $data['project_count_data'][$v['project_count']]['contract_budget'] += round($v['contract_budget']/10000,1);
            $data['project_count_data'][$v['project_count']]['had_grant_amount'] += round($v['had_grant_amount']/10000,1);
        }

        $this->assign($data);
        $this->display('partner');
    }

}