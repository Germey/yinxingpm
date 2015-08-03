<?php
class HelloAction extends Action {
    public function index() {

        $f = fopen ("import.txt", "r");

        while (! feof ($f)) {
            $line= trim(fgets ($f));
            if($line) {
                $all[] = explode(',', trim($line));
            }
        }
        fclose ($f);


        // $project = '民间自行组织保护滇金丝猴和其它野生动植物活动';
        // $partner = '德钦县佛山乡巴美村塔玖野生动植物保护协会';
        // $email = '1156861741@qq.com';

        foreach ($all as $one) {
            $project = $one[0];
            $partner = $one[1];
            $email = $one[2];

            if($u=M("CmsUsers")->getByEmail($email)) {
                $uid = $u['id'];
            } else {
                $user_data = array(
                        'email' => $email,
                        'username' => $email,
                        'password' => 'e7fe8b88db51d86ef2f5e169144b9c1b',
                        'ip' => '127.0.0.1',
                    );
                            
                $uid = D("CmsUsers")->saveOrUpdate($user_data);
            }

            $p['cms_user_id'] = $uid;
            $p['user_group_id'] = 1;
            $p['title'] = $project;
            $p['service_area'] = $partner;

            if(M("Projects")->where($p)->find()) {
                var_dump('exist project: ' . $project);
            } else {
                // var_dump($p);
                var_dump(D("Projects")->saveOrUpdate($p));
            }
        }


        $this->display();

    }

}