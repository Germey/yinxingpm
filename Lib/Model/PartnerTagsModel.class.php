<?php

class PartnerTagsModel extends BaseModel
{
    public function getByPartnerId($ids = array()) {
        $one = is_array($ids) ? false : true;
        settype($ids, 'array');
        $idstring = join(',', $ids);
        if(preg_match('/[\s]/', $idstring)) return array();

        $tags = M()
           ->table("partner_tags ct, partner_tag_mapping ctm")
           ->field("ct.*, ctm.*")
           ->where("ct.id = ctm.partner_tag_id and ctm.partner_id in ($idstring)")
           ->query("select %FIELD% from %TABLE% %WHERE%", true);

        if(!$tags) return array();

        //hack
        //-作为 '与' 查询条件
        if($_GET['tagid']) {
            $current_tag = $_GET['tagid'];
        }
        foreach ($tags as $t) {
            if($current_tag) {
                $t['link'] = '/partner?tagid=' . $current_tag . '-' . $t['partner_tag_id'];
            } else {
                $t['link'] = '/partner?tagid=' . $t['partner_tag_id'];
            }
            $rs[$t['partner_id']][] = $t;
        }

        return $rs;
    }

    public function getTags($team_id,$count) {
        if(!$count) $count = 10000;

        $cond = array('');
        $cond['team_id'] = $team_id;

        $result = $this->where($cond)->order("convert(name USING gbk) COLLATE gbk_chinese_ci")->limit($count)->select();
        return Utility::AssColumn($result);
    }


    public function updateTagsMapping($partner_id, $tags) {
        
        foreach ($tags as $t) {
            if(is_numeric($t)) {
                $tids[] = intval($t);
            }
            if(strpos($t, '#newtag#') !== FALSE) {
                $tids[] = $this->addTag(str_replace('#newtag#', '', $t));
            }
        }

        return $this->updateMapping($partner_id, $tids);
    }

    public function addTag($tag_name, $weight, $color) {
        if(!$tag_name) {
            //log error
            return 0;
        }

        $data['name'] = $tag_name;
        $data['weight'] = 0;
        $data['rgb_color'] = '';

        $obj = D("PartnerTags");
        if($obj->create($data)) {
            return $this->saveOrUpdate();
        }
        return 0;
    }

    public function updateMapping($partner_id, $newTagIds) {
        $partner_id = intval($partner_id);
        if(!$partner_id) return NULL;

        M("PartnerTagMapping")->where("partner_id = $partner_id")->delete();

        foreach ($newTagIds as $tid) {
            $data = array('partner_id' => $partner_id , 'partner_tag_id' => $tid);
            M("PartnerTagMapping")->add($data);
        }

        //also update Partner Update Time
        D("Partners")->where("id=$partner_id")->setField("update_time",date('Y-m-d H:i:s'));
        return $partner_id;
    }

    public function GetTagsWeight($team_id) {
        $sql = 'SELECT partner_tag_id tag_id, name, COUNT(*) count 
                FROM partner_tags t, partner_tag_mapping tm 
                WHERE t.id = tm.partner_tag_id and t.team_id = ' . $team_id . ' GROUP BY partner_tag_id';
        $rs = Utility::AssColumn($this->query($sql, true), 'tag_id');
        return $rs;
  }

}