<?php
namespace Admin\Controller;

class VoteController extends BaseController {

    public function listAction(){
        $toupiao = M("toupiao");
        $count = $toupiao->count();
        $page = new \Think\Page($count, 10);
        $votelist = $toupiao->order(array('tp_adddate'=>'desc'))->limit($page->firstRow.','.$page->listRows)->select();
        $show = $page->show();
        $this->assign('page',$show);
        $this->assign('votelist', $votelist);
        $this->display();
    }
    
    public function viewAction() {
        $voteid = I('get.voteid');
        $toupiao = M("toupiao");
        $toupiaoinfo = $toupiao->where('tp_id = "'.$voteid.'"')->find();
        if (!$toupiaoinfo) {
            $this->error("投票不存在");
        }
        $this->assign('toupiaoinfo', $toupiaoinfo);
        
        $tpuser = M("tpuser");
        $tpfood = M("tpfood");
        $foodresult = $tpfood->where('tpfood_tpid = "'.$voteid.'"')->select();
        $foodtotalvote = $tpuser->where('tpuser_tp_id = "'.$voteid.'"')->count();
        $foodlist = array();
        foreach ($foodresult as $value) {
            $foodvote = $tpuser->where('tpuser_food_id = "'.$value['tpfood_id'].'"')->count();
            $value['foodvote'] = $foodvote;
            $value['foodvoteperset'] = intval($foodvote/$foodtotalvote*100);
            $foodlist[] = $value;
        }
        $this->assign('foodlist', $foodlist);
        $this->display();
    }

    public function addAction(){
        $this->display();
    }
    
    public function modvoteAction() {
        $voteid = I('get.voteid');
        $toupiao = M("toupiao");
        $voteinfo = $toupiao->where('tp_id="'.$voteid.'"')->find();
        if (!$voteinfo) {
            $this->error("投票不存在");
        }
        $this->assign('voteinfo', $voteinfo);
        $this->display();
    }
    
    public function delvoteAction(){
        $voteid = I('get.voteid');
        $toupiao = M("toupiao");
        $voteinfo = $toupiao->where('tp_id="'.$voteid.'"')->find();
        if ($voteinfo) {
            $votenumber = $toupiao->where('tp_id="'.$voteid.'"')->delete();
            if ($votenumber) {
                unlink('./upload/'.$voteinfo['tp_image']);
                $tpfood = M("tpfood");
                $tpfood->where('tpfood_tpid="'.$voteid.'"')->delete();
                $tpuser = M("tpuser");
                $tpuser->where('tpuser_tp_id="'.$voteid.'"')->delete();
                $this->success('删除投票成功');
            } else {
                $this->error("删除投票失败");
            }
        } else {
            $this->error("删除投票失败");
        }
    }

    public function saveAction(){
        $isdelimage = I('post.deltp_image');
        if ($isdelimage) {
            $_POST['tp_image'] = '';
            unlink('./upload/'.$isdelimage);
        }
        if ($_FILES['tp_image']['name']) {
            $upload = new \Think\Upload();
            $upload->maxSize = 3145728;//3M
            $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
            $upload->rootPath = './upload/';
            $uploadinfo = $upload->uploadOne($_FILES['tp_image']);
            if(!$uploadinfo) {
                $this->error($upload->getError());
            }
            $_POST['tp_image'] = $uploadinfo['savepath'].$uploadinfo['savename'];
        }
        $toupiao = M("toupiao");
        $post = filterAllParam('post');
        $vote_choose = explode(' ', $post['vote_choose']);
        unset($post['vote_choose']);
        if (isset($post['tp_id']) && $post['tp_id']) {
            unset($post['deltp_image']);
            $foodid = $toupiao->where('tp_id="'.$post['tp_id'].'"')->save($post);
        } else {
            $post['tp_adddate'] = date('Y-m-d H:i:s');
            $foodid = $toupiao->add($post);
            $tpfood = M("tpfood");
            if ($foodid) {
                foreach ($vote_choose as $value) {
                    $tpfood->add(array('tpfood_name'=>$value, 'tpfood_tpid'=>$foodid));
                }
            }
        }
        if ($foodid) {
            $this->success('保存投票成功', 'list');
        } else {
            $this->error("保存投票失败");
        }
    }
}