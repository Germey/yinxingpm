<?php
class CaptchaAction extends Action{

    function index() {
        ob_get_clean();
        Utility::CaptchaCreate(4);        
    }
}
