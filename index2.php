<?php
	// 改变应用目录的名称
// define('APP_PATH', getcwd().'/app/');
// 加载框架引导文件
// require './thinkphp/start.php';


// 图灵api测试接口

header('Content-type:text/html;charset=utf-8');
//配置您申请的appkey
$appkey = "fb1e06403dc44c7a9c977ffaafa9a02e";




//************1.问答************
$url = "http://www.tuling123.com/openapi/api";
$params = array(
      "key" => $appkey,//您申请到的本接口专用的APPKEY
      "info" => "不好",//要发送给机器人的内容，不要超过30个字符
      // "dtype" => "xml",//返回的数据的格式，json或xml，默认为json
      // "loc" => "北京中关村",//地点，如北京中关村
      // "lon" => "",//经度，东经116.234632（小数点后保留6位），需要写为116234632
      // "lat" => "",//纬度，北纬40.234632（小数点后保留6位），需要写为40234632
      "userid" => ""//1~32位，此userid针对您自己的每一个用户，用于上下文的关联
);
$paramstring = http_build_query($params);
$content = juhecurl($url,$paramstring);
$result = json_decode($content);
echo $result->text;
// $result = json_decode($content,true);
// print_r($result);
// echo $result['text'];
// if($result){
//     if($result['error_code']=='0'){
//         print_r($result);
//     }else{
//         echo $result['error_code'].":".$result['reason'];
//     }
// }else{
//     echo "请求失败";
// }
//**************************************************




//************2.数据类型************
// $url = "http://www.tuling123.com/openapi/api";
// $params = array(
//       "dtype" => "",//返回的数据格式，json或xml，默认json
//       "key" => $appkey,//您申请本接口的APPKEY，请在应用详细页查询
// );
// $paramstring = http_build_query($params);
// $content = juhecurl($url,$paramstring);
// $result = json_decode($content,true);
// print_r($result);
// if($result){
//     if($result['error_code']=='0'){
//         print_r($result);
//     }else{
//         echo $result['error_code'].":".$result['reason'];
//     }
// }else{
//     echo "请求失败";
// }
//**************************************************





/**
 * 请求接口返回内容
 * @param  string $url [请求的URL地址]
 * @param  string $params [请求的参数]
 * @param  int $ipost [是否采用POST形式]
 * @return  string
 */
function juhecurl($url,$params=false,$ispost=0){
    $httpInfo = array();
    $ch = curl_init();

    curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
    curl_setopt( $ch, CURLOPT_USERAGENT , 'JuheData' );
    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 60 );
    curl_setopt( $ch, CURLOPT_TIMEOUT , 60);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    if( $ispost )
    {
        curl_setopt( $ch , CURLOPT_POST , true );
        curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
        curl_setopt( $ch , CURLOPT_URL , $url );
    }
    else
    {
        if($params){
            curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
        }else{
            curl_setopt( $ch , CURLOPT_URL , $url);
        }
    }
    $response = curl_exec( $ch );
    if ($response === FALSE) {
        //echo "cURL Error: " . curl_error($ch);
        return false;
    }
    $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
    $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
    curl_close( $ch );
    return $response;
}