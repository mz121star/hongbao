<?php
namespace Admin\Controller;

class FoodController extends BaseController {

    public function listAction(){
        $food = M("Food");
        $userid = $this->userInfo['user_id'];
        $count = $food->count();
        $page = new \Think\Page($count, 10);
        $foodlist = $food->field('food_id,food_name,food_adddate,food_difficulty')->order(array('food_id'=>'desc'))->limit($page->firstRow.','.$page->listRows)->select();
        $show = $page->show();
        $this->assign('page',$show);
        $this->assign('foodlist', $foodlist);
        $this->display();
    }

    public function addAction(){
        $this->display();
    }
    
    public function modfoodAction() {
        $foodid = I('get.foodid');
        $food = M("Food");
        $foodinfo = $food->where('food_id="'.$foodid.'"')->find();
        if (!$foodinfo) {
            $this->error("菜肴不存在");
        }
        $this->assign('foodinfo', $foodinfo);
        $this->display();
    }
    
    public function delfoodAction(){
        $foodid = I('get.foodid');
        $food = M("Food");
        $foodinfo = $food->where('food_id="'.$foodid.'"')->find();
        if ($foodinfo) {
            $foodnumber = $food->where('food_id="'.$foodid.'"')->delete();
            if ($foodnumber) {
                unlink('./upload/'.$foodinfo['food_image']);
                $fav = M("fav");
                $fav->where('favfood_id="'.$foodid.'"')->delete();
                $this->success('删除菜肴成功');
            } else {
                $this->error("删除菜肴失败");
            }
        } else {
            $this->error("删除菜肴失败");
        }
    }

    public function saveAction(){
        $isdelimage = I('post.delfood_image');
        if ($isdelimage) {
            $_POST['food_image'] = '';
            unlink('./upload/'.$isdelimage);
        }
        if ($_FILES['food_image']['name']) {
            $upload = new \Think\Upload();
            $upload->maxSize = 3145728;//3M
            $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
            $upload->rootPath = './upload/';
            $uploadinfo = $upload->uploadOne($_FILES['food_image']);
            if(!$uploadinfo) {
                $this->error($upload->getError());
            }
            $_POST['food_image'] = $uploadinfo['savepath'].$uploadinfo['savename'];
        }
        $food = M("Food");
        $post = $_POST;
        if (isset($post['food_id']) && $post['food_id']) {
            unset($post['delfood_image']);
            $foodid = $food->where('food_id="'.$post['food_id'].'"')->save($post);
        } else {
            $post['food_adddate'] = date('Y-m-d H:i:s');
            $foodid = $food->add($post);
        }
        if ($foodid) {
            $this->success('保存菜肴成功', 'list');
        } else {
            $this->error("保存菜肴失败");
        }
    }
}