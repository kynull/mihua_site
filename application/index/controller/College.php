<?php
namespace app\index\controller;
use \think\Controller;

class College extends Controller
{
    public function index()
    {
        // 渲染模板输出
        return $this->fetch('index', ['title'=>'米花学院']);
    }
    public function detail()
    {
        $id = input('param.id');
        // 渲染模板输出
        return $this->fetch('detail'.$id, ['title'=>'米花学院']);
    }
}