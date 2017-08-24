<?php
namespace app\index\controller;
use \think\Controller;

class Keeper extends Controller
{
    public function index()
    {
        // 渲染模板输出
        return $this->fetch('index',['title'=>'主营业务']);

    }
}