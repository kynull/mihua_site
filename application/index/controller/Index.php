<?php
namespace app\index\controller;
use \think\Controller;

class Index extends Controller
{
    public function Index()
    {
        // 渲染模板输出
        return $this->fetch('index',['title'=>'首页']);
    }
}
