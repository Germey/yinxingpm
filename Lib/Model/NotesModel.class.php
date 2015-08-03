<?php

class NotesModel extends BaseModel {

    public function getNotesByModuleId($module_name, $module_id) {
        if(!$module_name || !$module_id) {
            //log
            return NULL;
        }

        $table_name = ucfirst($module_name) . 'Notes';
        $module_id_name = strtolower($module_name) . '_id';

        $notes = D($table_name)->where($module_id_name . '='. $module_id)->order('id desc')->select();   //
        $user_ids = Utility::GetColumn($notes, 'update_user_id');
        $users = Utility::Asscolumn(D('Users')->select(implode(',', $user_ids)));

        foreach ($notes as $k => $n) {
            $notes[$k]['update_user'] = $users[$n['update_user_id']];
        }

        return $notes;

    }

    public function saveNote($module_name, $module_id, $title, $content) {
        if(!$module_name || !$module_id) {
            //log
            return 0;
        }

        $table_name = ucfirst($module_name) . 'Notes';
        $module_id_name = strtolower($module_name) . '_id';
        
        $note = D($table_name);

        $data[$module_id_name] = $module_id;
        $data['title'] = $title;
        $data['content'] = $content;

        $note->create($data);
        return $note->saveOrUpdate();

    }
}

?>