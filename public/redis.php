<?php
function checkrobot($useragent=''){
   static $kw_spiders = array('bot', 'crawl', 'spider' ,'slurp', 'search', 'lycos', 'robozilla');
   static $kw_browsers = array('msie', 'netscape', 'opera', 'konqueror', 'mozilla');
   $useragent = strtolower(empty($useragent) ? $_SERVER['HTTP_USER_AGENT'] : $useragent);

   echo ''. $_SERVER['HTTP_USER_AGENT'] .'<br/>';
   if(strpos($useragent, 'https://') === false && dstrpos($useragent, $kw_browsers)) return false;
   if(dstrpos($useragent, $kw_spiders)) return true;
   return false;
}
function dstrpos($string, $arr, $returnvalue = false) {
   if(empty($string)) return false;
   foreach((array)$arr as $v) {
      if(strpos($string, $v) !== false) {
         $return = $returnvalue ? $v : true;
         return $return;
      }
   }
   return false;
}
if(checkrobot()){
   exit('Are You Ghost?');
}


$onlineip = '';
if(getenv('HTTP_CLIENT_IP')) {
   $onlineip = getenv('HTTP_CLIENT_IP');
} elseif(getenv('HTTP_X_FORWARDED_FOR')) {
   $onlineip = getenv('HTTP_X_FORWARDED_FOR');
} elseif(getenv('REMOTE_ADDR')) {
   $onlineip = getenv('REMOTE_ADDR');
} else {
   $onlineip = $HTTP_SERVER_VARS['REMOTE_ADDR'];
}

   // 连接本地的 Redis 服务
   $redis = new Redis();
   $redis->connect('127.0.0.1', 6379);
   $redis->auth('cjky');
   echo $onlineip.'Connection to server sucessfully<br/>';
         //查看服务是否运行
   echo 'Server is running: ' . $redis->ping() .'<br/>';
?>