
<?php

class UserAttachmentsModel extends AttachmentsModel {

    public function getByUserId($id) {
        return $this->getAttachmentsByModuleId('user', $id);
    }

    public function save($id, $title, $content) {
        return $this->saveAttachment('user', $id, $title, $content);
    }

}

?>