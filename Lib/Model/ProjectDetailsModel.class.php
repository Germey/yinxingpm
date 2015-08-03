<?php

class ProjectDetailsModel extends BaseModel {

    public function getById($id) {
        $pd = M('ProjectDetails')->getById($id);
        if($pd) {
            $pd['summary'] = htmlspecialchars_decode($pd['summary']);
            $pd['feasibility'] = htmlspecialchars_decode($pd['feasibility']);
            // $pd['actioned'] = htmlspecialchars_decode($pd['actioned']);
            // $pd['profit_model'] = htmlspecialchars_decode($pd['profit_model']);
            return $pd;
        }
        return array();
    }

}

?>