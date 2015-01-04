<?php
namespace Home\Controller;

class IndexController extends BaseController {

    private $app_id = 'wxc43356a7940e32d4';

    private $app_secret = 'ec234926610a429dfaca36328af9b014';

    public function sendAction() {
        $userID = I('get.uid');
        $actionto = I('get.ac');
        $userobj = M('user');
        $userinfo = $userobj->field('user_id, user_name, user_regdate, user_image')->where('user_id = "'.$userID.'"')->find();
        if ($userinfo) {
            session('userinfo', $userinfo);
        } else {
            session('userinfo', array('user_id' => $userID, 'user_name'=>'访客'));
        }
        $this->redirect('index/'.$actionto);
    }

    public function indexAction(){
        $parent = I('get.parentid');
        $code = I('get.code');
        $url ="https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$this->app_id."&secret=".$this->app_secret."&code=".$code."&grant_type=authorization_code";
        $json_content = file_get_contents($url);
        $json_obj = json_decode($json_content, true);
        $access_token = $json_obj['access_token'];
        $openid = $json_obj['openid'];
        $userinfostr = file_get_contents("https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid."&lang=zh_CN");
        $userinfo = json_decode($userinfostr, true);
        $money = M('money');
        $setting = M("setting");
        $setinfo = $setting->where('set_id = 1')->find();
        $my_money_list = array();
        $totel_money = 0;
        if ($userinfo) {
            $user = M('user');
            $wxuser = $user->where('user_id = "'.$userinfo['openid'].'"')->find();
            if (!$wxuser) {
                $data = array('user_id'=>$userinfo['openid'], 'user_name'=>$userinfo['nickname'], 'user_regdate'=>date('Y-m-d H:i:s'), 'user_image'=>$userinfo['headimgurl'], 'user_status'=>'1');
                $user_result = $user->add($data);
            }
            
            $own_money = $money->where('money_owner = "'.$userinfo['openid'].'" and money_from = "0"')->find();
            if ($own_money) {
                $this->assign('is_get_money', 1);
            } else {
                $data = array('money_owner'=>$userinfo['openid'], 'money_number'=>$setinfo['set_beginmoney'], 'money_from'=>'0', 'money_time'=>date('Y-m-d H:i:s'));
                $own_money_result = $money->add($data);
                $this->assign('is_get_money', 0);
            }
            
            $my_get_money = $money->where('money_owner = "'.$userinfo['openid'].'" and money_from != "0"')->select();
            foreach ($my_get_money as $my_money) {
                $usermoneyinfo = $user->where('user_id = "'.$my_money['money_from'].'"')->find();
                $my_money = array_merge($my_money, $usermoneyinfo);
                $my_money_list[] = $my_money;
            }
            
            $totel_money_list = $money->where('money_owner = "'.$userinfo['openid'].'"')->select();
            foreach ($totel_money_list as $value) {
                $totel_money = $totel_money + $value['money_number'];
            }
        }

        if ($parent && $parent != $userinfo['openid']) {
            $wxmoney = $money->where('money_owner = "'.$parent.'" and money_from = "'.$userinfo['openid'].'"')->find();
            if (!$wxmoney) {
                $data = array('money_owner'=>$parent, 'money_number'=>$setinfo['set_sharemoney'], 'money_from'=>$userinfo['openid'], 'money_time'=>date('Y-m-d H:i:s'));
                $money_result = $money->add($data);
            }
        }
        $this->assign('my_money_list', $my_money_list);
        $this->assign('userinfo', $userinfo);
        $this->assign('setinfo', $setinfo);
        $this->assign('totel_money', $totel_money);
        $this->display();
    }

    public function eventAction() {
        $fromUserName = I('post.fromUserName');
        $nickname = I('post.nickname');
        $headimgurl = I('post.headimgurl');
        $eventType = I('post.eventType');
        $user = M('user');
        if ($eventType == 'subscribe') {
            $status = '1';
        } else {
            $status = '0';
        }
        $userinfo = $user->where('user_id = "'.$fromUserName.'"')->find();
        if ($userinfo) {
            $result = $user->where('user_id = "'.$fromUserName.'"')->setField('user_status', $status);
        } else {
            $data = array('user_id'=>$fromUserName, 'user_name'=>$nickname, 'user_regdate'=>date('Y-m-d H:i:s'), 'user_image'=>$headimgurl, 'user_status'=>$status);
            $result = $user->add($data);
        }
        return '关注成功';
    }
    
    public function tixian() {
        $openid = I('get.id');
        $this->display();
    }
}
