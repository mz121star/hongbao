<?php
namespace Home\Controller;

class VoteController extends BaseController {

    private $app_id = 'wxc43356a7940e32d4';

    private $app_secret = 'ec234926610a429dfaca36328af9b014';

    public function indexAction() {
        $this->display();
    }
    
    public function saveVoteAction() {
        echo '<pre>';
        print_r($_FILES);exit;
    }
}
