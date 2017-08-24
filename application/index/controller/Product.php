<?php
namespace app\index\controller;
use \think\Controller;

class Product extends Controller
{
    public function index()
    {
        // 渲染模板输出
        return $this->fetch('index',['title'=>'主营业务']);
    }
    public function trader()
    {
        // 渲染模板输出
        return $this->fetch('trader',['title'=>'惠商贷']);
    }
    public function farming()
    {
        // 渲染模板输出
        return $this->fetch('farming',['title'=>'惠商贷']);
    }
    public function salary()
    {
        // 渲染模板输出
        return $this->fetch('salary',['title'=>'工薪贷']);
    }
    public function house()
    {
        // 渲染模板输出
        return $this->fetch('house',['title'=>'房易贷']);
    }
    public function car()
    {
        // 渲染模板输出
        return $this->fetch('car',['title'=>'微车贷']);
    }
}