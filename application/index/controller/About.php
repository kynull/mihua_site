<?php
namespace app\index\controller;
use \think\Controller;

class About extends Controller
{
    public function index()
    {
        // 渲染模板输出
        return $this->fetch('index',['title'=>'关于我们']);
    }
    public function intro()
    {
        // 渲染模板输出
        return $this->fetch('intro',['title'=>'公司介绍']);
    }
    public function culture()
    {
        // 渲染模板输出
        return $this->fetch('culture',['title'=>'企业文化']);
    }
    public function joint()
    {
        // 渲染模板输出
        return $this->fetch('joint',['title'=>'合作伙伴']);
    }
    public function contact()
    {
        // 渲染模板输出
        return $this->fetch('contact',['title'=>'联系我们']);
    }
}
