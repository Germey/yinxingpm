<?php

class ProjectNotesModel extends NotesModel {

    public function getByProjectId($project_id) {
        return $this->getNotesByModuleId('project', $project_id);
    }

    public function save($project_id, $title, $content) {
        return $this->saveNote('project', $project_id, $title, $content);
    }

}

?>