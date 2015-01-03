<?php
namespace Admin\Controller;

class IndexController extends BaseController {
    
    public function picAction() {
        $userobj = M("user");
        $count = $userobj->where('user_image != ""')->count();
        $page = new \Think\Page($count, 20);
        $userlist = $userobj->field('user_name, user_image')->where('user_image != ""')->order('user_regdate desc')->limit($page->firstRow.','.$page->listRows)->select();
        $show = $page->show();
        $this->assign('page',$show);
        $this->assign('userlist', $userlist);
        $this->display();
    }

    public function indexAction(){
        $this->display();
    }

    public function loginAction() {
        $this->display();
    }

    public function dologinAction(){
        $userobj = M("user");
        $user_pw = I('post.user_pw');
        $data['user_id'] = I('post.user_id');
        $data['user_pw'] = md5($user_pw);
        $data['user_status'] = 1;
        $userInfo = $userobj->field('user_pw', true)->where($data)->find();
        if(!empty($userInfo)){
            session('userinfo', $userInfo);
            $this->success('登录成功', 'index');
        } else {
            $this->error('登录失败', 'login');
        }
    }

    public function logoutAction() {
        $userInfo = session('userinfo');
        if(!empty($userInfo)){
            session('userinfo', null);
        }
        $this->redirect('Index/login');
    }

    public function jxhdAction() {
        $jxhd = M("jxhd");
        $count = $jxhd->count();
        $page = new \Think\Page($count, 10);
        $jxhdlist = $jxhd->order(array('jxhd_date'=>'desc'))->limit($page->firstRow.','.$page->listRows)->select();
        $show = $page->show();
        $this->assign('page',$show);
        $this->assign('jxhdlist', $jxhdlist);
        $this->display();
    }

    public function addjxhdAction() {
        $this->display();
    }

    public function modjxhdAction() {
        $hdid = I('get.hdid');
        $jxhd = M("jxhd");
        $jxhdinfo = $jxhd->where('jxhd_id="'.$hdid.'"')->find();
        if (!$jxhdinfo) {
            $this->error("活动不存在");
        }
        $this->assign('jxhdinfo', $jxhdinfo);
        $this->display();
    }

    public function deljxhdAction() {
        $hdid = I('get.hdid');
        $jxhd = M("jxhd");
        $jxhdinfo = $jxhd->where('jxhd_id="'.$hdid.'"')->find();
        if ($jxhdinfo) {
            $jxhdnumber = $jxhd->where('jxhd_id="'.$hdid.'"')->delete();
            if ($jxhdnumber) {
                unlink('./upload/'.$jxhdinfo['jxhd_image']);
                $this->success('删除活动成功');
            } else {
                $this->error("删除活动失败");
            }
        } else {
            $this->error("删除活动失败");
        }
    }

    public function savejxhdAction() {
        $isdelimage = I('post.deljxhd_image');
        if ($isdelimage) {
            $_POST['jxhd_image'] = '';
            unlink('./upload/'.$isdelimage);
        }
        if ($_FILES['jxhd_image']['name']) {
            $upload = new \Think\Upload();
            $upload->maxSize = 3145728;//3M
            $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
            $upload->rootPath = './upload/';
            $uploadinfo = $upload->uploadOne($_FILES['jxhd_image']);
            if(!$uploadinfo) {
                $this->error($upload->getError());
            }
            $_POST['jxhd_image'] = $uploadinfo['savepath'].$uploadinfo['savename'];
        }
        $jxhd = M("jxhd");
        $post = $_POST;
        if (isset($post['jxhd_id']) && $post['jxhd_id']) {
            unset($post['deljxhd_image']);
            $foodid = $jxhd->where('jxhd_id="'.$post['jxhd_id'].'"')->save($post);
        } else {
            $post['jxhd_date'] = date('Y-m-d H:i:s');
            $foodid = $jxhd->add($post);
        }
        if ($foodid) {
            $this->success('保存活动成功', 'jxhd');
        } else {
            $this->error("保存活动失败");
        }
    }
    
    public function weixinAction() {
        if (!$_SESSION['access_token']) {
                $appid = 'wx746191c3d2d0ebd7';
                $appsecret = 'a4e835f6b20748fba46a827017cc835a';
                $access_token_url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$appsecret;
                $access_token_result = file_get_contents($access_token_url);
                $access_token_result = json_decode($access_token_result);
                $_SESSION['access_token'] = $access_token_result->access_token;
            }
            
            $underbar_url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$_SESSION['access_token'];
//            $underbar_content = array('button' => array(array('type'=>'click', 'name'=>'最近吃啥', 'key'=>'ws_zjcs'), array('type'=>'click', 'name'=>'节目汇编', 'key'=>'ws_jmhb'), array('name'=>'精选', 'sub_button'=>array(array('type'=>'click', 'name'=>'精选商品', 'key'=>'ws_jxsp'), array('type'=>'click', 'name'=>'精选活动', 'key'=>'ws_jxhd')))));
            $underbar_content = '{
                                                            "button":[
                                                            {	
                                                                 "type":"click",
                                                                 "name":"最近吃啥",
                                                                 "key":"ws_zjcs"
                                                             },
                                                             {	
                                                                 "type":"click",
                                                                 "name":"节目汇编",
                                                                 "key":"ws_jmhb"
                                                             },
                                                             {
                                                                  "name":"精选",
                                                                  "sub_button":[
                                                                  {
                                                                      "type":"click",
                                                                      "name":"精选商品",
                                                                      "key":"ws_jxsp"
                                                                   },
                                                                   {
                                                                      "type":"click",
                                                                      "name":"精选活动",
                                                                      "key":"ws_jxhd"
                                                                   }]
                                                              }]
                                                        }
                                                       ';

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $underbar_url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $underbar_content);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            $item_str = curl_exec($ch);
            curl_close($ch);
            echo $item_str;exit;
    }
    
    public function jxspAction() {
        $jxsp = M("jxsp");
        $count = $jxsp->count();
        $page = new \Think\Page($count, 10);
        $jxsplist = $jxsp->order(array('jxsp_date'=>'desc'))->limit($page->firstRow.','.$page->listRows)->select();
        $show = $page->show();
        $this->assign('page',$show);
        $this->assign('jxsplist', $jxsplist);
        $this->display();
    }

    public function addjxspAction() {
        $this->display();
    }

    public function modjxspAction() {
        $spid = I('get.spid');
        $jxsp = M("jxsp");
        $jxspinfo = $jxsp->where('jxsp_id="'.$spid.'"')->find();
        if (!$jxspinfo) {
            $this->error("精选商品不存在");
        }
        $this->assign('jxspinfo', $jxspinfo);
        $this->display();
    }

    public function deljxspAction() {
        $spid = I('get.spid');
        $jxsp = M("jxsp");
        $jxspinfo = $jxsp->where('jxsp_id="'.$spid.'"')->find();
        if ($jxspinfo) {
            $jxspnumber = $jxsp->where('jxsp_id="'.$spid.'"')->delete();
            if ($jxspnumber) {
                unlink('./upload/'.$jxspinfo['jxsp_image']);
                $jxspcom = M("jxspcom");
                $jxspcom->where('jxspcom_jxsp_id = "'.$spid.'"')->delete();
                $jxspzan = M("jxspzan");
                $jxspzan->where('jxspzan_jxsp_id = "'.$spid.'"')->delete();
                $this->success('删除精选商品成功');
            } else {
                $this->error("删除精选商品失败");
            }
        } else {
            $this->error("删除精选商品失败");
        }
    }

    public function savejxspAction() {
        $isdelimage = I('post.deljxsp_image');
        if ($isdelimage) {
            $_POST['jxsp_image'] = '';
            unlink('./upload/'.$isdelimage);
        }
        if ($_FILES['jxsp_image']['name']) {
            $upload = new \Think\Upload();
            $upload->maxSize = 3145728;//3M
            $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
            $upload->rootPath = './upload/';
            $uploadinfo = $upload->uploadOne($_FILES['jxsp_image']);
            if(!$uploadinfo) {
                $this->error($upload->getError());
            }
            $_POST['jxsp_image'] = $uploadinfo['savepath'].$uploadinfo['savename'];
        }
        $jxsp = M("jxsp");
        $post = $_POST;
        if (isset($post['jxsp_id']) && $post['jxsp_id']) {
            unset($post['deljxsp_image']);
            $foodid = $jxsp->where('jxsp_id="'.$post['jxsp_id'].'"')->save($post);
        } else {
            $post['jxsp_date'] = date('Y-m-d H:i:s');
            $foodid = $jxsp->add($post);
        }
        if ($foodid) {
            $this->success('保存精选商品成功', 'jxsp');
        } else {
            $this->error("保存精选商品失败");
        }
    }
    
}