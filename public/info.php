<?php

/**
 * 作用取得客户端的ip、地理信息、浏览器、本地真实IP
 */
class get_client_info {

    /**
     * 获取客户端浏览器类型
     * @param  string $glue 浏览器类型和版本号之间的连接符
     * @return string|array 传递连接符则连接浏览器类型和版本号返回字符串否则直接返回数组 false为未知浏览器类型
     */
    function GetBrowser($glue = null){
        $browser = array();
        /* 定义浏览器特性正则表达式 */
        $regex = array(
            'ie'      => '/(MSIE) (\d+\.\d)/',
            'chrome'  => '/(Chrome)\/(\d+\.\d+)/',
            'firefox' => '/(Firefox)\/(\d+\.\d+)/',
            'opera'   => '/(Opera)\/(\d+\.\d+)/',
            'safari'  => '/Version\/(\d+\.\d+\.\d) (Safari)/',
        );

        if(!empty($_SERVER['HTTP_USER_AGENT'])){
            $agent = $_SERVER['HTTP_USER_AGENT']; //获取客户端信息

            foreach($regex as $type => $reg) {
                preg_match($reg, $agent, $data);
                if(!empty($data) && is_array($data)){
                    $browser = $type === 'safari' ? array($data[2], $data[1]) : array($data[1], $data[2]);
                    break;
                }
            }
            return empty($browser) ? false : (is_null($glue) ? $browser : implode($glue, $browser));
        }else{
            return "获取浏览器信息失败！";
        }
    }

    ////获得访客浏览器语言
    function GetLang(){
        if(!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
            $lang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
            $lang = substr($lang,0,5);
            if(preg_match("/zh-cn/i",$lang)){
                $lang = "简体中文";
            }elseif(preg_match("/zh/i",$lang)){
                $lang = "繁体中文";
            }else{
                $lang = "English";
            }
            return $lang;

        }else{return "获取浏览器语言失败！";}
    }

    ////获取访客操作系统
    function GetOs(){
        if(!empty($_SERVER['HTTP_USER_AGENT'])){
            $OS = $_SERVER['HTTP_USER_AGENT'];
            if (preg_match('/win/i',$OS)) {
                $OS = 'Windows';
            }elseif (preg_match('/mac/i',$OS)) {
                $OS = 'MAC';
            }elseif (preg_match('/linux/i',$OS)) {
                $OS = 'Linux';
            }elseif (preg_match('/unix/i',$OS)) {
                $OS = 'Unix';
            }elseif (preg_match('/bsd/i',$OS)) {
                $OS = 'BSD';
            }else {
                $OS = 'Other';
            }
            return $OS;
        }else{return "获取访客操作系统信息失败！";}
    }
    // 获取访问者ip
    function Get_IP(){
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            $cip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $cip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }elseif(!empty($_SERVER['REMOTE_ADDR'])){
            $cip = $_SERVER['REMOTE_ADDR'];
        }else{
            $cip = '';
        }
        return $cip;
    }
    /**
     * 获得访客真实ip
     * @return mixed|string
     */
    function Getip(){
        echo '__-> '. $_SERVER["HTTP_CLIENT_IP"] .'<br/>';
        if(!empty($_SERVER["HTTP_CLIENT_IP"])){
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        }
        if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){ //获取代理ip
            $ips = explode(',',$_SERVER['HTTP_X_FORWARDED_FOR']);
        }
        if($ip){
            $ips = array_unshift($ips,$ip);
        }

        $count = count($ips);
        for($i=0;$i<$count;$i++){
            if(!preg_match("/^(10|172\.16|192\.168)\./i",$ips[$i])){//排除局域网ip
                $ip = $ips[$i];
                break;
            }
        }
        $tip = empty($_SERVER['REMOTE_ADDR']) ? $ip : $_SERVER['REMOTE_ADDR'];
        if($tip=="127.0.0.1"){ // 获得本地真实IP
            return $this->get_onlineip();
        }else{
            return $tip;
        }
    }

    /**
     * 获得本地真实IP
     * @return mixed|string
     */
    function get_onlineip() {
        $mip = file_get_contents("http://city.ip138.com/city0.asp");
        $sip = array();
        if($mip){
            preg_match("/\[.*\]/",$mip,$sip);
            $p = array("/\[/","/\]/");
            return preg_replace($p,"",$sip[0]);
        } else {
            return "获取本地IP失败！";
        }
    }

    /**
     * 根据ip获得访客所在地地名
     * @param string $ip
     * @return string
     */
    function Getaddress($ip=''){
        if(empty($ip)){
            $ip = $this->Getip();
        }
        $ipadd = file_get_contents("http://int.dpool.sina.com.cn/iplookup/iplookup.php?ip=".$ip);//根据新浪api接口获取
        if($ipadd){
            $charset = iconv("gbk","utf-8",$ipadd);
            preg_match_all("/[\x{4e00}-\x{9fa5}]+/u",$charset,$ipadds);

            return $ipadds;   //返回一个二维数组
        }else{
            return "addree is none";
        }
    }
}

$gifo = new get_client_info();
/**

    echo '<br/>所在地：';
    $ipadds = $gifo->Getaddress();
    foreach($ipadds[0] as $value){
        echo "\r\n    ".iconv("utf-8","gbk",$value);
    }

 **/
    echo "你的ip:".$gifo->Getip() .'<br/>';
    echo "你的ip:".$gifo->Get_IP() .'<br/>';
    echo "<br/>浏览器：".$gifo->GetBrowser('_');
    echo "<br/>浏览器语言：".$gifo->GetLang();
    echo "<br/>操作系统：".$gifo->GetOs();


echo "<br>1.".$_SERVER['PHP_SELF']; #当前正在执行脚本的文件名，与 document root相关
echo "<br>2.".$_SERVER['argv'];     #传递给该脚本的参数。
echo "<br>3.".$_SERVER['argc'];     #包含传递给程序的命令行参数的个数（如果运行在命令行模式）。
echo "<br>4.".$_SERVER['GATEWAY_INTERFACE'];  #服务器使用的 CGI 规范的版本。例如，“CGI/1.1”。
echo "<br>5.".$_SERVER['SERVER_NAME'];        #当前运行脚本所在服务器主机的名称。
echo "<br>6.".$_SERVER['SERVER_SOFTWARE'];    #服务器标识的字串，在响应请求时的头部中给出。
echo "<br>7.".$_SERVER['SERVER_PROTOCOL'];    #请求页面时通信协议的名称和版本。例如，“HTTP/1.0”。
echo "<br>8.".$_SERVER['REQUEST_METHOD'];     #访问页面时的请求方法。例如：“GET”、“HEAD”，“POST”，“PUT”。
echo "<br>9.".$_SERVER['QUERY_STRING'];       #查询(query)的字符串。
echo "<br>0.".$_SERVER['DOCUMENT_ROOT'];      #当前运行脚本所在的文档根目录。在服务器配置文件中定义。
echo "<br>1.".$_SERVER['HTTP_ACCEPT'];        #当前请求的 Accept: 头部的内容。
echo "<br>2.".$_SERVER['HTTP_ACCEPT_CHARSET'];    #当前请求的 Accept-Charset: 头部的内容。例如：“iso-8859-1,*,utf-8”。
echo "<br>3.".$_SERVER['HTTP_ACCEPT_ENCODING'];   #当前请求的 Accept-Encoding: 头部的内容。例如：“gzip”。
echo "<br>4.".$_SERVER['HTTP_ACCEPT_LANGUAGE'];   #当前请求的 Accept-Language: 头部的内容。例如：“en”。
echo "<br>5.".$_SERVER['HTTP_CONNECTION'];        #当前请求的 Connection: 头部的内容。例如：“Keep-Alive”。
echo "<br>6.".$_SERVER['HTTP_HOST'];              #当前请求的 Host: 头部的内容。
echo "<br>7.".$_SERVER['HTTP_REFERER'];           #链接到当前页面的前一页面的 URL 地址。
echo "<br>8.".$_SERVER['HTTP_USER_AGENT'];        #当前请求的 User_Agent: 头部的内容。
echo "<br>9.".$_SERVER['HTTPS'];                  #如果通过https访问,则被设为一个非空的值(on)，否则返回off
echo "<br>0.".$_SERVER['REMOTE_ADDR'];            #正在浏览当前页面用户的 IP 地址。
echo "<br>1.".$_SERVER['REMOTE_HOST'];            #正在浏览当前页面用户的主机名。
echo "<br>2.".$_SERVER['REMOTE_PORT'];            #用户连接到服务器时所使用的端口。
echo "<br>3.".$_SERVER['SCRIPT_FILENAME'];        #当前执行脚本的绝对路径名。
echo "<br>4.".$_SERVER['SERVER_ADMIN'];           #管理员信息
echo "<br>5.".$_SERVER['SERVER_PORT'];            #服务器所使用的端口
echo "<br>6.".$_SERVER['SERVER_SIGNATURE'];       #包含服务器版本和虚拟主机名的字符串。
echo "<br>7.".$_SERVER['PATH_TRANSLATED'];        #当前脚本所在文件系统（不是文档根目录）的基本路径。
echo "<br>8.".$_SERVER['SCRIPT_NAME'];            #包含当前脚本的路径。这在页面需要指向自己时非常有用。
echo "<br>9.".$_SERVER['REQUEST_URI'];            #访问此页面所需的 URI。例如，“/index.html”。
echo "<br>0.".$_SERVER['PHP_AUTH_USER'];          #当 PHP 运行在 Apache 模块方式下，并且正在使用 HTTP 认证功能，这个变量便是用户输入的用户名。
echo "<br>1.".$_SERVER['PHP_AUTH_PW'];            #当 PHP 运行在 Apache 模块方式下，并且正在使用 HTTP 认证功能，这个变量便是用户输入的密码。
echo "<br>2.".$_SERVER['AUTH_TYPE'];              #当 PHP 运行在 Apache 模块方式下，并且正在使用 HTTP 认证功能，这个变量便是认证的类型。
