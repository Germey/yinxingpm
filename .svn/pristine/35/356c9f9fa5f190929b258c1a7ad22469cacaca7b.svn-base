<?php

class ProjectAttachmentsModel extends AttachmentsModel {

    public function getByProjectId($event_id) {
        return $this->getAttachmentsByModuleId('project', $event_id);
    }

    public function save($event_id, $title, $content) {
        return $this->saveAttachment('project', $event_id, $title, $content);
    }

}

?>