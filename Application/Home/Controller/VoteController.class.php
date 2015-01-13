<?php
namespace Home\Controller;

class VoteController extends BaseController {

    private $app_id = 'wxc43356a7940e32d4';

    private $app_secret = 'ec234926610a429dfaca36328af9b014';

    public function indexAction() {
        $this->display();
    }
    
    public function showVoteAction() {
        $vote = M("Vote");
        $voteid = I('get.voteid');
        $voteinfo = $vote->where('vote_id = "'.$voteid.'"')->find();
        if (!$voteinfo) {
            $this->error("无此投票", U('vote/index'));
        }
        $this->assign('voteinfo', $voteinfo);
        $this->display();
    }
    
    public function saveVoteAction() {
        $isdelimage = I('post.delweibo_send');
        if ($isdelimage) {
            $_POST['weibo_send'] = '';
            unlink('./upload/'.$isdelimage);
        }
        if ($_FILES['weibo_send']['name']) {
            $upload = new \Think\Upload();
            $upload->maxSize = 3145728;//3M
            $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
            $upload->rootPath = './upload/';
            $uploadinfo = $upload->uploadOne($_FILES['weibo_send']);
            if(!$uploadinfo) {
                $this->error($upload->getError());
            }
            $_POST['weibo_send'] = $uploadinfo['savepath'].$uploadinfo['savename'];
        }
        $vote = M("Vote");
        $post = filterAllParam('post');
        if (isset($post['vote_id']) && $post['vote_id']) {
            unset($post['delweibo_send']);
            $voteid = $vote->where('vote_id="'.$post['vote_id'].'"')->save($post);
        } else {
            $voteid = $vote->add($post);
        }
        if ($voteid) {
            $this->success('保存成功', U('vote/showVote', array('voteid'=>$voteid)));
        } else {
            $this->error("保存失败");
        }
    }
}
