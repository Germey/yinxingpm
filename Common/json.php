<?php
function json($data, $type='eval') {
    $type = strtolower($type);
    $allow = array('eval','alert','updater','dialog','mix', 'refresh', 'replacer');
    if (false==in_array($type, $allow))
        return false;
    Output::Json(array( 'data' => $data, 'type' => $type,));
}