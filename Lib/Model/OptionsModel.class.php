<?php
class OptionsModel extends BaseModel {

    public function GetAllOptions() {
        /* load all the system_options from database to construct hashtable*/
        $res = $this->where('autoload="Y"')->select();
        foreach($res as $r) {
            $INI[$r['option_name']] = $r['option_value'];
        }

        return $INI;
    }


    public function getOption($option_key) {
        $value = $this->where("option_name = '$option_key'")->getField("option_value");
        return $value;
    }

    public function update($key, $value) {
        $option = $this->getByOptionName($key);
        if($option) {
            $u['id'] = $option['id'];
        }
        $u['option_name'] = $key;
        $u['option_value'] = $value;

        return $this->saveOrUpdate($u);
    }
}