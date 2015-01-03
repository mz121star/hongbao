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
       /* $order = I('get.order');
        $orderby = 'food_adddate desc';
        if ($order == 'favmost') {
            $orderby = 'food_favcount desc';
        } elseif ($order == 'dif') {
            $orderby = 'food_difficulty desc';
        }
        $search_name = I('get.search_name');
        $where = array();
        if ($search_name) {
            $where['food_name'] = array('like', '%'.$search_name.'%');
        }
        $food = M('food');
        $fav = M('fav');
        $foodresult = $food->where($where)->field('food_id,food_name,food_adddate,food_qishu,food_image')->order($orderby)->select();
        $foodlist = array();
        foreach ($foodresult as $value) {
            $favcount = $fav->where('favfood_id = "'.$value['food_id'].'"')->count();
            $value['favcount'] = $favcount;
            $foodlist[] = $value;
        }
        $this->assign('foodlist', $foodlist);
        $this->assign('order', $order);
        $this->assign('search_name', $search_name);*/
        $parent=I('get.parentid');
        $code=I('get.code');
        $url=" https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$this->app_id."&secret=".$this->app_secret."&code=".$code."&grant_type=authorization_code";
       $json_content= file_get_contents($url);
        $json_obj=json_decode($json_content);
        $access_token=$json_obj['access_token'];
        $openid=$json_obj['openid'];
        $userinfostr=file_get_contents("https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid."&lang=zh_CN");
        $userinfo=json_decode($userinfostr);
        $this->assign('userinfo', $userinfo);
        $this->display();
    }

    public function getjxhdAction() {
        $jxhd = M("jxhd");
        $count = $jxhd->count();
        $page = new \Think\Page($count, 1);
        $jxhdlist = $jxhd->order(array('jxhd_date'=>'desc'))->limit($page->firstRow.','.$page->listRows)->select();
        $jxhdinfo = array();
        if (isset($jxhdlist[0])) {
            $jxhdinfo = $jxhdlist[0];
        }
        echo json_encode($jxhdinfo);
        exit;
    }

    public function jxhdAction() {
        $jxhd = M("jxhd");
        $count = $jxhd->count();
        $page = new \Think\Page($count, 1);
        $jxhdlist = $jxhd->order(array('jxhd_date'=>'desc'))->limit($page->firstRow.','.$page->listRows)->select();
        $jxhdinfo = array();
        if (isset($jxhdlist[0])) {
            $jxhdinfo = $jxhdlist[0];
        }
        $this->assign('jxhdinfo', $jxhdinfo);
        $this->display();
    }
    
    public function jxspAction() {
        $jxsp = M('jxsp');
        $jxspcom = M('jxspcom');
        $foodlist = $jxsp->order('jxsp_date desc')->select();
        $this->assign('foodlist', $foodlist);
        $this->display();
    }
    
    public function jxdetailAction() {
        $jxid = I('get.jxid');
        $jxsp = M('jxsp');
        $jxspinfo = $jxsp->where('jxsp_id = "'.$jxid.'"')->find();
        if (!$jxspinfo) {
            $this->error("商品不存在");
        }
        $this->assign('jxspinfo', $jxspinfo);
        $jxspcom = M('jxspcom');
        $commentcount = $jxspcom->where('jxspcom_jxsp_id = "'.$jxid.'"')->count();
        $commentlist = $jxspcom->where('jxspcom_jxsp_id = "'.$jxid.'"')->select();
        $this->assign('commentlist', $commentlist);
        $this->assign('commentcount', $commentcount);
        $this->display();
    }
    
    public function jxcommentAction() {
        $this->checklogin();
        $post = filterAllParam('post');
        $post['jxspcom_user_id'] = $this->userInfo['user_id'];
        $post['jxspcom_user_name'] = $this->userInfo['user_name'];
        $post['jxspcom_date'] = date('Y-m-d H:i:s');
        $jxspcom = M('jxspcom');
        $commentid = $jxspcom->add($post);
        if ($commentid) {
            $jxsp = M('jxsp');
            $jxsp->where('jxsp_id = "'.$post['jxspcom_jxsp_id'].'"')->setInc('jxsp_comcount');
            $this->success('评论成功');
        } else {
            $this->error("评论失败");
        }
    }
    
    public function jxzanAction() {
        $this->checklogin();
        $jxid = I('get.jxid');
        $jxsp = M('jxsp');
        $jxspinfo = $jxsp->where('jxsp_id = "'.$jxid.'"')->find();
        if (!$jxspinfo) {
            echo '精选商品不存在';exit;
        }
        $jxspzan = M('jxspzan');
        $data['jxspzan_jxsp_id'] = $jxid;
        $data['jxspzan_user_id'] = $this->userInfo['user_id'];
        $iszan = $jxspzan->where($data)->count();
        if ($iszan) {
            echo '已经赞过了';exit;
        }
        $data['jxspzan_date'] = date('Y-m-d H:i:s');
        $zanid = $jxspzan->add($data);
        if ($zanid) {
            $jxsp->where('jxsp_id = "'.$jxid.'"')->setInc('jxsp_zancount');;
            echo '成功点赞';exit;
        } else {
            echo '点赞失败';exit;
        }
    }

    public function zjcsAction() {
        $this->checklogin();
        $toupiao = M("toupiao");
        $count = $toupiao->count();
        $page = new \Think\Page($count, 1);
        $votelist = $toupiao->order(array('tp_adddate'=>'desc'))->limit($page->firstRow.','.$page->listRows)->select();
        $voteinfo = array();
        $votefood = array();
        $tpfood = M("tpfood");
        if (isset($votelist[0])) {
            $voteinfo = $votelist[0];
            $votefood = $tpfood->where('tpfood_tpid ="'.$voteinfo['tp_id'].'"')->select();
        }
        $tpuser = M("tpuser");
        $user = $this->userInfo['user_id'];
        $isvote = $tpuser->where('tpuser_tp_id = "'.$voteinfo['tp_id'].'" and tpuser_user_id = "'.$user.'"')->count();
        if ($isvote) {
            $foodresult = $tpfood->where('tpfood_tpid = "'.$voteinfo['tp_id'].'"')->select();
            $foodtotalvote = $tpuser->where('tpuser_tp_id = "'.$voteinfo['tp_id'].'"')->count();
            $foodlist = array();
            foreach ($foodresult as $value) {
                $foodvote = $tpuser->where('tpuser_food_id = "'.$value['tpfood_id'].'"')->count();
                $value['foodvote'] = $foodvote;
                $value['foodvoteperset'] = intval($foodvote/$foodtotalvote*100);
                $foodlist[] = $value;
            }
            $this->assign('foodlist', $foodlist);
            $this->display('tpjg');
        } else {
            $user = $this->userInfo['user_id'];
            $this->assign('voteinfo', $voteinfo);
            $this->assign('votefood', $votefood);
            $this->display();
        }
    }

    public function savetpAction() {
        $this->checklogin();
        $post = I('post.');
        if (!count($post['tpuser_food_id'])) {
            $this->error("请选择投票选项");
        }
        $user = $this->userInfo['user_id'];
        $tpuser = M("tpuser");
        foreach ($post['tpuser_food_id'] as $value) {
            $insert = array('tpuser_food_id'=>$value, 'tpuser_user_id'=>$user, 'tpuser_tp_id'=>$post['tpuser_tp_id'], 'tpuser_date'=>date('Y-m-d H:i:s'));
            $tpuser->add($insert);
        }
        
        $tpfood = M("tpfood");
        $foodresult = $tpfood->where('tpfood_tpid = "'.$post['tpuser_tp_id'].'"')->select();
        $foodtotalvote = $tpuser->where('tpuser_tp_id = "'.$post['tpuser_tp_id'].'"')->count();
        $foodlist = array();
        foreach ($foodresult as $value) {
            $foodvote = $tpuser->where('tpuser_food_id = "'.$value['tpfood_id'].'"')->count();
            $value['foodvote'] = $foodvote;
            $value['foodvoteperset'] = intval($foodvote/$foodtotalvote*100);
            $foodlist[] = $value;
        }
        $this->assign('foodlist', $foodlist);
        $this->display('tpjg');
    }

    public function detailAction() {
        $foodid = I('get.foodid');
        $food = M('food');
        $foodinfo = $food->where('food_id = "'.$foodid.'"')->find();
        if (!$foodinfo) {
            $this->error("菜肴不存在");
        }
        $this->assign('foodinfo', $foodinfo);
        $comment = M('comment');
        $commentcount = $comment->where('commentfood_id = "'.$foodid.'"')->count();
        $commentlist = $comment->where('commentfood_id = "'.$foodid.'"')->select();
        $this->assign('commentlist', $commentlist);
        $this->assign('commentcount', $commentcount);
        $this->display();
    }

    public function favfoodAction() {
        $this->checklogin();
        $foodid = I('get.foodid');
        $food = M('food');
        $foodinfo = $food->where('food_id = "'.$foodid.'"')->find();
        if (!$foodinfo) {
            echo '菜肴不存在';exit;
        }
        $favobj = M('fav');
        $data['favfood_id'] = $foodid;
        $data['favuser_id'] = $this->userInfo['user_id'];
        $isfav = $favobj->where($data)->count();
        if ($isfav) {
            echo '已经收藏过了';exit;
        }
        $data['fav_date'] = date('Y-m-d H:i:s');
        $favid = $favobj->add($data);
        if ($favid) {
            $food->where('food_id = "'.$foodid.'"')->setInc('food_favcount');;
            echo '收藏成功';exit;
        } else {
            echo '收藏失败';exit;
        }
    }

    public function myfavAction() {
        $this->checklogin();
        $favobj = M('fav');
        $food = M('food');
        $data['favuser_id'] = $this->userInfo['user_id'];
        $resultlist = $favobj->where($data)->select();
        $favlist = array();
        foreach ($resultlist as $value) {
            $foodinfo = $food->where('food_id = "'.$value['favfood_id'].'"')->find();
            $favlist[] = $foodinfo;
        }
        $this->assign('favlist', $favlist);
        $this->display();
    }

    public function commentAction() {
        $this->checklogin();
        $post = filterAllParam('post');
        $post['commentuser_id'] = $this->userInfo['user_id'];
        $post['commentuser_name'] = $this->userInfo['user_name'];
        $post['comment_date'] = date('Y-m-d H:i:s');
        $comment = M('comment');
        $commentid = $comment->add($post);
        if ($commentid) {
            $this->success('评论成功');
        } else {
            $this->error("评论失败");
        }
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
}
