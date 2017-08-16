<?php

    namespace app\index\controller;
    use think\vendor;
    use think;
    class Qrcode
    {
        public function qrcode(){
            $openId = $_GET['open'];
            $nickName = $_GET['nickName'];

            echo $openId.'<BR>';
            echo $nickName.'<BR>';
//            echo $nickName.'<BR>';
//            无logo二维码
//            vendor('phpqrcode.phpqrcode');
//            //生成二维码图片
//            $object = new \QRcode();
//            $data='http://www.shouce.ren/';//网址或者是文本内容
//            $level=3;
//            $size=4;
//            echo getcwd();
//            echo "<br>".__DIR__;
//            echo " <br>".dirname(__FILE__);
////            E:\wamp\www\app\index
//            $ad = dirname(dirname(__FILE__)).$users_id.'.jpg';
//            $errorCorrectionLevel =intval($level) ;//容错级别
//            $matrixPointSize = intval($size);//生成图片大小
//            $object->png($data,  $ad, $errorCorrectionLevel, $matrixPointSize, 2);
////            参数:1:二维码信息,2:保存路径,3容错,4大小.


            //带LOGO             $url = 'http://mydd.0317cn.net/index.php/Home/Logo/res/users_id/'.$users_id;
            $data = $openId.$nickName;
            //二维码内容
            $errorCorrectionLevel = 'M';
            if (isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L','M','Q','H')))
                $errorCorrectionLevel = $_REQUEST['level'];

            $matrixPointSize = 10;
            if (isset($_REQUEST['size']))
                $matrixPointSize = min(max((int)$_REQUEST['size'], 1), 10);

            //生成图片大小
            //生成二维码图片
            Vendor('phpqrcode.phpqrcode');
            $object = new \QRcode();
//            ....................................................
            $path = 'public'.DIRECTORY_SEPARATOR.'qrcode'.DIRECTORY_SEPARATOR;
//            ...................................................
            $filename = $path.'test.png';
            echo 'hello'.$filename.'<br>';
            $object->png($data, $filename, $errorCorrectionLevel, $matrixPointSize, 2);

            $logo = $path.'00.jpg';
//            //准备好的logo图片
//            $logo = '../../../'.'00.jpg';
            $QR = $path.'test.png';
            if ($logo !== FALSE) {

                $QR = imagecreatefromstring(file_get_contents($QR));
                echo '7777';
                $logo = imagecreatefromstring(file_get_contents($logo));
                echo '888';
                $QR_width = imagesx($QR);
                //二维码图片宽度
                $QR_height = imagesy($QR);
                //二维码图片高度
                $logo_width = imagesx($logo);
                //logo图片宽度
                $logo_height = imagesy($logo);
                //logo图片高度
                $logo_qr_width = $QR_width / 5;
                $scale = $logo_width/$logo_qr_width;
                $logo_qr_height = $logo_height/$scale;
                $from_width = ($QR_width - $logo_qr_width) / 2;
                //重新组合图片并调整大小
                imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
            }
            //输出图片 带logo图片
            $outputPath = DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'qrcode'.DIRECTORY_SEPARATOR;
            imagepng($QR, $path.'test.png');
            echo '<img src='.$outputPath.'test.png>';
        }
    }