<?php

// TODO 待完善：Validation等
function display_edit_columns_list($all_columns, $data) {
    //text，file，按照通栏的来，别的走一半
    $full_li_class_types = array('file', 'text', 'split');
    $str = '';
    foreach ($all_columns as $k => $v) {
        $liclass = '';
        if(in_array($v['type'], $full_li_class_types)) {
            $liclass = 'clear full';
        }
        if(isset($v['hidden']) && ($v['hidden']=='edit' || $v['hidden']=='both' || $v['hidden']=='1')) {
            $liclass .= ' hidden';
        }

        $str .= "<li class='$liclass'>";
        if(!$v['display_name']) {
            $str .= '';
            continue;
        }
        if(!$v['type'] || $v['type'] == 'string' || $v['type'] == 'number' ) {
            $str .= display_tag_input($v['display_name'], $k, $data[$k], $v['require'], $v['type'], $v['class'], $v['tip'], $v['extra_type']);
        } else if ($v['type'] == 'select') {
            $str .= display_tag_select($v['display_name'], $k, $data[$k], $v['options'], $v['require'], $v['class'], $v['extra_type']);
        } else if($v['type'] == 'file') {
            $str .= display_tag_file($v['display_name'], $k, $data[$k], $v['require'], $v['file_type'], $v['tip']);
        } else if($v['type'] == 'text') {
            $str .= display_tag_text($v['display_name'], $k, $data[$k], $v['extra_type']);
        } else if($v['type'] == 'date') {
            $str .= display_tag_date($v['display_name'], $k, $data[$k], $v['options'], $v['require'], $v['class']);
        } else if($v['type'] == 'split') {
            $str .= display_split($v['display_name'], $v['class'], $k);
        }
        $str .= '</li>';
    }
    return $str;
}

function display_tag_input($label_name, $name, $value, $require=false, $type="text", $class, $tip,  $extra_type) {
    if(!$type) $type = 'text';
    if(!$class) $class = 'span4';
    if($extra_type=='money') {
        $other_attr = 'step="0.01"';  
        $suffix = '<span class="muted">元</span>';
        $class .= " money";
        $type = 'text';             // 用了formatCurrency的函数，这里不能number了
    } 
    $str = '<div class="control-group">';
    $str .= '<label class="control-label">' . $label_name . '</label>';
    $str .= '<div class="controls">';
    $str .= '<input id="'. $name .'" class="'.$class.'" type="'.$type.'" name="'.$name.'" value="'.htmlspecialchars($value).'" placeholder="'.$tip.'" '. $other_attr .'/>' . $suffix;
    $str .= '</div></div>';
    return $str;
}

function display_tag_select($label_name, $name, $value, $options, $require=false, $class, $extra_type=NULL) {
    if(!$class) $class = 'span4';
    
    if($extra_type=='multiple') {
        $extra_type = 'multiple';
        $tag_name = $name . '[]';
    } else {
        $extra_type = '';
        $tag_name = $name;
    }

    $str = '<div class="control-group">';
    $str .= '<label class="control-label">' . $label_name . '</label>';
    $str .= '<div class="controls">';
    $str .= '<select id="'.$name.'" '.$extra_type.' class="'.$class.' select2" name="'.$tag_name.'"><option value="0">-</option>'. Utility::Option($options, $value) . '</select>';
    $str .= '</div></div>';
    return $str;
}


function display_tag_file($label_name, $name, $value, $require=false, $file_type, $tip) {

    $str = '<div class="control-group">';
    $str .= '<label class="control-label">' . $label_name . '</label>';
    $str .= '<div class="controls">';
    $str .= '<input type="file" name="'.$name.'">';
    $str .= '<span class="muted">'.$tip.'</span>';    
    if($value && $file_type) {
        $str .= '<input type="hidden" name="old_'.$name.'" value="'.$value.'">';
        if($file_type == 'image') {
            $str .= '<div><img src="'. image_path($value) .'"/></div>';
        } else if ($file_type == 'file') {
            $str .= '<div>'. $value .'</div>';
        }
    }
    $str .= '</div></div>';
    return $str;
} 


function display_tag_text($label_name, $name, $value, $text_type) {
    $str = '<div class="control-group">';
    $str .= '<label class="control-label">' . $label_name . '</label>';
    $str .= '<div class="controls">';
    $class = ($text_type=='rich')?"editor":"";
    $str .= '<textarea id="' . $name . '" class="' . $class . '" name="' .$name. '" >' . $value . '</textarea>';
    $str .= '</div></div>';
    return $str;
}

function display_tag_date($label_name, $name, $value, $options, $require, $class=""){
    $str = '<div class="control-group">';
    $str .= '<label class="control-label">' . $label_name . '</label>';
    $str .= '<div class="controls">';
    $str .= "<input type='text' class='datepicker {$class}' name='$name' id='$name' value='" . substr($value,0,10) . "'/>";
    $str .= '</div></div>';
    return $str;
}

function display_split($label_name, $class,$key) {
    $str = '<a name="'.$key.'"></a><h5 class="'.$class.'">'.$label_name.'</h5>';
    return $str;
}

function display_columns_list($all_columns, $data) {
    //text，file，按照通栏的来，别的走一半
    $full_li_class_types = array('text', 'split', 'group');
    $str = '';
    foreach ($all_columns as $name => $v) {
        $liclass = '';
        if(in_array($v['type'], $full_li_class_types)) {
            $liclass = 'clear full';
        }

        if(isset($v['hidden']) && ($v['hidden'] == 'detail' || $v['hidden'] == 'both' || $v['hidden'] == '1')) {
            $liclass .= ' hidden';
        }
        $str .= "<li class='column_display_li $liclass'>";
        if(!$v['type'] || $v['type'] == 'string' || $v['type'] == 'number' || $v['type'] == 'select' || $v['type'] == 'text' || $v['type'] == 'date') {
            $str .= display_text($v['display_name'], $name, $data[$name], $v['options'], $v['class'], $v['link'], $data, $all_columns, $liclass, $v['length'], $v['extra_type']);
        } else if($v['type'] == 'split') {
            $str .= display_split($v['display_name']);
        } else if($v['type'] == 'file') {
            $str .= display_file($v['display_name'], $name, $data[$name], $v['param'], $v['class'], $v['link'], $data, $all_columns);
        }  else if($v['type'] == 'group') {
            $str .= '<span class="column_title">'.$v['display_name'].'</span><div class="span11 column_content">';
            $str .= display_group($data[$name], $v['param']['options']);
            $str .= '</div></li>';
        } 


        $str .= '</li>';
    }
    return $str;
}

function display_column_li($label_name, $value, $colspan=1) {
    if($label_name) $label_name = $label_name . ': ';
    $li_class = 'column_display_li';
    if($colspan==2) {
        $li_class .= ' twice';
    }
    if($colspan==3) {
        $li_class .= ' three';
    }

    $str = '<li class="'.$li_class.'"><span class="column_title">'.$label_name.'</span><div class="column_content">'.$value.'</div></li>';
    return $str;
}


function display_text($label_name, $name, $value, $options, $class="",$link, $data, $all_columns, $liclass="", $length=0, $extra_type=NULL){
    if(!$class) $class = 'span5';
    if($liclass == "clear full") $class = "span11";
    if($label_name) {
        // $label_name .=  ':';
    }
    $str .= '<span class="column_title">' . $label_name . '</span>';
    $value = column_item_value($name, $data, $all_columns, $length);
    if($extra_type=='money' && is_numeric($value)) {
        $value = moneyit($value);
    } else {
        $value = nl2br($value);
    }
    $str .= '<div class="'.$class.' column_content"  name="'.$name.'"  id="'.$name.'">'.$value.'</div>';
    return $str;
}

function display_file($label_name, $name, $value, $options, $require, $class="") {
    // var_dump($label_name,$name, $value, $options);
    if(!$class) $class = 'span4';
    if($liclass == "clear full") $class = "span11";

    $str .= '<span class="column_title">' . $label_name . '</span>';
    if($options['file_type']=='image') {
        $value = '<a target="_blank" title="点击查看全图" href="/uploads/'.$value.'"><img src="/uploads/'.$value.'" style="max-width:400px" /></a>';
    }


    $str .= '<div class="'.$class.' column_content"  name="'.$name.'"  id="'.$name.'">'.$value.'</div>';
    return $str;
}


function column_item_value($key, &$data, $column_info, $length) {    
    $display_value = $data[$key];
    if($length != 0){
        $display_value = substr($display_value, 0, $length);
    }
    if($column_info[$key]['type'] == 'select') {
        $options = $column_info[$key]['options'];        
        if(is_list($options)) {
            $options = array_to_map($options);
        }
        if($options[$display_value]) {
            $display_value = $options[$display_value];
        }
    } 

    if($column_info[$key]['link']) {
        $link = $column_info[$key]['link'];
        $link_type = $link['link_type'];
        $column = $link['column'];
        if(empty($column)) $column = $key;
        if($link_type == 'school' || $link_type == 'project' || $link_type == 'user') {
            $html = "<a target='_blank' href='/$link_type/detail/" . $data[$column] . "'>" . $display_value . "</a>";
        }
        if($link_type == 'mail'){
            $html = "<a target='_blank' href='mailto:" . $display_value . "'>" . $display_value . "</a>";
        };
        if($link_type == 'website'){
            $html = "<a target='_blank' href='" . $data[$column] . "'>" . $display_value . "</a>";
        };
        if($link_type == 'weibo'){
            $weibo_link = $data['weibo_link'];
            $weibo_name = $data['weibo_name'];
            if($weibo_link && $weibo_name){
                $html = "<a target='_blank' href='" . $weibo_link . "'>" . $weibo_name . "</a>";
            }
        }
        if($link_type == 'tag') {
            $html = display_tag_string($data['id'], $data['tags']);
        }
        return $html;
    }else{
        if($column_info[$key]['extra_type']=='money') {
            return moneyit($display_value);
        }
        return $display_value;
    }
}

function display_tag_string($obj_id, $tags) {
    $html = '';
    foreach ($tags as $t) {
        $html .= '<a href="' . $t['link'] . '" class="label label-info" style="background:'. $t['rgb'] .'">'.$t['name'].'</a>&nbsp';
    }
    $html .= '<a class="ajaxlink" href="/partnertag/render_dialog_edit?id='.$obj_id.'"><i id="icon-edit-'.$obj_id.'" class="icon-edit-small"></i></a><span id="icon-edit-holder-'.$obj_id.'">&nbsp;&nbsp;&nbsp;</span>';
    return $html;
}

function column_item_key($key, $custom_columns, $pre_link=null) {


    if($custom_columns[$key]['display_name']) {
        if($pre_link){
            $order_link_info = generate_order_link($key, $custom_columns[$key]['type']);
            $a_str = "<a href='/" . $pre_link . "?" . $order_link_info[0] . "'>";
            $a_str .= $custom_columns[$key]['display_name'];
            if($order_link_info[1] == 1){
                $a_str .= "<i class='icon-chevron-down'>";
            }else if($order_link_info[1] == 2){
                $a_str .= "<i class='icon-chevron-up'>";
            }
            $a_str .= "</a>";
            return $a_str;
        }else{
            return $custom_columns[$key]['display_name'];
        }
    }
    return L($key);
}



//table 上方的搜索过滤
function display_filter_item($key, $value, $all_columns) {
    // var_dump($key, $value, $all_columns);
    if($all_columns[$key]['hidden'] !== 'filter') {
        if($all_columns[$key]['type'] == 'select') {
            return '<select data-placeholder="'.$all_columns[$key]['display_name'].'" class="span2 select2" name="'.$key.'"><option></option>'. Utility::Option($all_columns[$key]['options'], $_GET[$key]) . '</select>';
        }  else {
            return '<input name="'.$key.'" value="'.$_GET[$key].'" class="span2" type="text" placeholder="'.column_item_key($key, $all_columns).'" />';
        }
    }
}


//tag
function display_tag_filter($tagid_str=NULL) {
    if(!$tagid_str) return;
    $ids = array_unique(explode('-', $tagid_str));
    $tags = Utility::AssColumn(D('PartnerTags')->select(implode(',', $ids)));
    foreach ($ids as $tid) {
        $others = array_diff($ids, array($tid));
        $map[$tags[$tid]['name']]['other_ids'] = implode('-', $others);
        $map[$tags[$tid]['name']]['rgb'] = $tags[$tid]['rgb'];
    }    
    if(!$map) return;
    $str = '<p class="tag-filter">';
    foreach ($map as $name => $tag) {
        $str .= '<a href="/partner?tagid='.$tag['other_ids'].'" ><span style="background-color:' . $tag['rgb'] . '" class="label label-info"><span>'.$name.'&nbsp;|&nbsp;X</span></span></a>&nbsp;&nbsp;';
    }
    $str .= '</p>';

    return $str;
}


function dashboard_group_edit($key,$label_name, $options, $value, $placeholder) {

    $table = '<table class="table table-noborder" id="group_new_table_'.$key.'"><thead><tr>';
    foreach ($options as $k => $v) {
        $table .= '<th>'.$v['name'].'</th>';       
    }
    $table .= '</thead></tr>';

    $name_prefix = $key . '_group_';

    $vs = unserialize($value);
    foreach ($vs as $v) {
        $table .= '<tr>';
        foreach ($options as $ok => $ov) {
            if($ov['type']=='select') {
                $table .= '<td>'. group_edit_select($name_prefix . $ok, $v[$ok], $ov['class'], $ov['options']) .'</td>';
            } else if($ov['type'] == 'textarea') {
                $table .= '<td>'. group_edit_textarea($name_prefix . $ok, $v[$ok], $ov['class']) .'</td>';
            } else {
                $table .= '<td>'. group_edit_input($name_prefix . $ok, $v[$ok], $ov['class'], $ov['extra_attr']) .'</td>';
            }
        }
        $table .= '</tr>';
    }

    $table .= '<tr id="group_new_line_'. $key .'">';
    foreach ($options as $k => $v) {
        if($v['type']=='select') {
            $table .= '<td>'. group_edit_select($name_prefix . $k, '', $v['class'], $v['options']) .'</td>';
        }  else if($v['type'] == 'textarea') {
            $table .= '<td>'. group_edit_textarea($name_prefix . $k,'', $v['class']) .'</td>';
        } else {
            $table .= '<td>'. group_edit_input($name_prefix . $k,'', $v['class'], $v['extra_attr']) .'</td>';
        }
    }

    $table .= '</tr></table>';
    $table .= '<p><a href="javascript:void(0);" onclick="add_group_line(\''.$key.'\')">+增加新记录</a>&nbsp;&nbsp;<span class="muted">（若要删除某行，将该行的每个输入框清空即可）</span></p>';
    
    $str .= '<div class="muted">'. $placeholder .'</div>';
    if($label_name) {    
        $str .= '<div class="control-group">';
        $str .= '<label class="control-label">' . $label_name . '</label>';
        $str .= '<div class="controls">';
        $str .= $table;
        $str .= '</div></div>';
    } else {
        $str .= $table;
    }

    return $str;
}

function group_edit_input($name, $value, $class, $extra_attr) {
    $class .= " group_span";
    return '<input type="text" class="'.$class.'" name="'. $name .'[]" value="'.$value.'" '.$extra_attr.'/>';
}

function group_edit_select($name, $value, $class, $options) {
    $class .= " group_span";
    return '<select type="text" class="'.$class.'" name="'. $name .'[]" />'.Utility::Option($options,$value).'</select>';   
}

function group_edit_textarea($name, $value, $class) {
    return '<textarea type="text" class="'.$class.'" name="'. $name .'[]" >'. $value .'</textarea>';
}

function display_group($value, $options, $label_name) {

    $value = unserialize($value);
    $table = '<table class="table table-bordered"><thead><tr>';
    foreach ($options as $ok => $ov) {
        $table .= '<th>'.$ov['name'].'</th>';
    }
    $table .= '</thead></tr>';

    foreach ($value as $v) {
        $table .= '<tr>';
        foreach ($options as $ok => $ov) {
            $wrap="";
            if(strlen($v[$ok]) < 20) {
                $wrap = 'nowrap';
            }
            $table .= '<td '. $wrap .'>'.nl2br($v[$ok]).'</td>';
        }
        $table .= '</tr>';
    }
    $table .= '</table>';

    if($label_name) {
        $table = '<li class="column_display_li three"><span class="column_title">'.$label_name.'：</span><div class="column_content">'.$table.'</div></li>';
    }

    return $table;
}

function createJSONRank($num = 4) {
    $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $result = array();
    for ($i = 0; $i < $num; $i ++) {
        array_push($result, array("value" => substr($str, $i, 1),"text" => substr($str, $i, 1)));
    }
    return json_encode($result);
}
