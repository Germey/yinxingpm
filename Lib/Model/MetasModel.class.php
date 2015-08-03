<?php

class MetasModel extends BaseModel
{
    protected $_table_prefix = null;
    protected $_table_name = null;
    protected $_fetch_id_name = null;

    public function __call($funName,$argu){  
        return $this->__callStatic($funName,$argu);
    }

    public function __callStatic($funName,$argu) {
        if(preg_match('#^([a-zA-Z0-9]*)(Add|Get|Update|Delete|Search)(ById|Value|)$#', $funName, $m)) {
            $this->_table_prefix = strtolower($m[1]);
            $this->_table_name = $this->_table_prefix . '_metas';
            $this->_fetch_id_name = $this->_table_prefix . '_id';

            $action = $m[2] . $m[3];
            return self::$action($argu);
        } else {
                echo 'Error Function:' . $funName . ', the format should be TableName[Get|Update|Delete]';
        }
    }

    /*  Usage:
                Metas::contactGetById(5);
    */
    protected function GetById($args) {
        $ids = $args[0];

        settype($ids, 'array');
        $idstring = join('\',\'', $ids);
        if(preg_match('/[\s]/', $idstring)) return array();

        $q = "SELECT * FROM $this->_table_name WHERE $this->_fetch_id_name IN ('$idstring')";
        $rs = self::query($q);
        return $rs;

        // return 
    }


    /* 
        //从contact来的格式目前是这样的，先这样做
        0 => string '_meta_jin_mi_du_1361613017' (length=26)
        1 => 
        array
          0 => string 'like' (length=4)
          1 => string '%松%' (length=5)*/
    protected function SearchValue($args) {
        $k = $args[0];
        $v = $args[1];
        if(!$k || !$v) return NULL;

        $cond['meta_key'] = $k;
        $cond['meta_value'] = $v;

        return D($this->_table_name)->where($cond)->select();
    }

    /*  Usage: 
            $data = array('partner_id' => 2, 'key'=>'my_meta_key', 'value' => 'my_meta_value');
            Metas::partnerUpdateById(123, $data);
    */
    protected function UpdateById($args) {
            $object_id = $args[0];
            $metas = $args[1];
            $old_data = Utility::AssColumn($this->GetById(array($object_id)), 'meta_key');

            foreach ($metas as $k => $v) {
                if($v && $v !== $old_data[$k]['meta_value']) {
                    $m = array();
                    if($old_data[$k]) {
                        $m['id'] = $old_data[$k]['id'];
                    }
                    $m[$this->_fetch_id_name] = $object_id;
                    $m['meta_key'] = $k;
                    $m['meta_value'] = $v;

                    $new_data[] = $m;
                }
            }

            if(!$new_data) return;

            $mm = D($this->_table_name);     
            foreach ($new_data as $d) {
                $mm->create($d);
                $mm->saveOrUpdate();
            }
            return;
    }


    /*  Usage: 
            Metas::PartnerDeleteById(9)
    */
    protected function DeleteById($table_prefix, $args) {
            $table_name = $table_prefix . '_metas';
            $id = $args[0];
            return Table::Delete($table_name, $id);
    }


    /*  Usage:
            Metas::PartnerUpdate($partner_id, $key, $value);            //will insert if not exist
    */
    // protected function Update($table_prefix, $args) {
    //         $table_name = $table_prefix . '_metas';
    //         $prefix_id_col_name  = $table_prefix . '_id';
    //         $prefix_id = $args[0];
    //         $key = $args[1];
    //         $value = $args[2];

    //         $e = self::Get($table_prefix, array($prefix_id, $key));
    //         if($e) {
    //                 $data[$prefix_id_col_name] = $prefix_id;
    //                 $data['key'] = $key;
    //                 $data['value'] = $value;
    //                 return self::UpdateById($table_prefix, array($e['id'], $data));
    //         }
    //         return self::Add($table_prefix, array($prefix_id, $key, $value));
    // }

    /*  Usage: 
            Metas::PartnerGet($partner_id, $key='optional');
    */
    // protected function Get($table_prefix, $args) {
    //     $table_name = $table_prefix . '_metas';
    //     $table_prefix_id_col_name  = $table_prefix . '_id';
    //     $table_prefix_id = $args[0];
    //     $meta_key = $args[1];

    //     $condition = array( $table_prefix_id_col_name => $table_prefix_id );
    //     if($meta_key) {
    //             $condition['meta_key'] = $meta_key;
    //     }

    //     $res = DB::LimitQuery($table_name, array(
    //         'condition' => $condition, 
    //     ));
    //     if($meta_key && $res) {
    //             return $res[0]; 
    //     }
    //     return $res;
    // } 

}