<?php

defined('THINK_PATH') or exit();

class EncryptRequestBehavior extends Behavior {

    // 行为扩展的执行入口必须是run
    public function run(&$content) {

        $content = preg_replace_callback('#(project|partner)/detail/(\d+)#i', encrypt_request, $content);
        $content = preg_replace_callback('#(\?|&)id=(\d+)#i', encrypt_request, $content);

        // return preg_replace_callback('#(\?|&)id=(\d+)(\b)#i', obscure_cb, $u);

    }


}
?>