<?php
class IndexAction extends BaseAction {
    public function index() {
        
        // sync user to contacts
        $last_sync_time = intval(D('Options')->getOption('contact_last_sync_time'));
        if($last_sync_time < time()-60*60) {    //1个小时更新一次
            
            // D('UserInfo')->syncToContacts($last_sync_time);
            // D('Options')->update('contact_last_sync_time', time());
        }

        $this->redirect('/user/recommend');
    }
}