<?php

class UserNotesModel extends NotesModel {

    public function getByUserId($id) {
        return $this->getNotesByModuleId('user', $id);
    }

    public function save($id, $title, $content) {
        return $this->saveNote('user', $id, $title, $content);
    }
}

?>