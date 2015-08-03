<?php

class SearchModel extends BaseModel {

    function GetMatchContent($content, $keyword,$headlen=60,$keyfront=20,$keybehind=120) {

        $content = trim(strip_tags($content));
              
        $head = mb_substr($content, 0,$headlen);
        $keycount = strlen($keyword);
        $pos = mb_stripos($content, $keyword);
        if($pos>$headlen){
            $start = $pos-$keyfront;
            $end = $keyfront+$keycount+$keybehind;
            $str = mb_substr($content,$start,$end,"utf-8");
            $str = $head."...".$str."...";
        }else{
            $start = 0;
            $end = $headlen+$keycount+$keybehind;
            $str = mb_substr($content, 0,$end);
            $str = $str ."...";
        }

        return $str;
    }

    function SearchContent($keyword,$type,$range="shallow") {
        if(!$keyword) return NULL;

        $search_map = array(
          "author"=>array("classname"   => "AuthorsModel",
                         "method"  => "GetAuthors",
                         "shallow" => array("full_name like '%".$keyword."%' or description like '%".$keyword."%'"),
                         ),
          "page"=>array("classname"   => "PagesModel",
                         "method"  => "GetPages",
                         "shallow" =>  array("(title like '%".$keyword."%' or content like '%".$keyword."%') and type_id>1"),
                         ),
        );


        $refChildClass =  $search_map[$type]['classname'];
        $reflectionClass = new ReflectionClass($refChildClass);
        $method = $reflectionClass->getMethod($search_map[$type]['method']);
        $refClass = $reflectionClass->newInstance();
        $res = call_user_func_array(array($refClass, $method->name), $search_map[$type][$range]);
        return $res;
    }

}
?>