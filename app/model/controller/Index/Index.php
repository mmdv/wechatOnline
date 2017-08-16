<?php
namespace app\model\controller;

class Index
{

    //回复关注事件
    public function responseSubscribe($postObj,$qrcodeLink){
        $toUser = $postObj->FromUserName;
        //发送者:开发者公众账号
        $fromUser = $postObj->ToUserName;
        $time = time();
        //回复内容
        $msgType = 'text';

        $template = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							</xml>";
        //sprintf 按照模板解析变量
        $info = sprintf($template, $toUser, $fromUser, $time, $msgType, $qrcodeLink);
        echo $info;
    }

    //以下就是微信SDK,思想是:重复方法共用
    public function responseMsgImg($postObj, $arr)
    {
        //单文本回复
        $toUser = $postObj->FromUserName;
        $fromUser = $postObj->ToUserName;

        $template = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[%s]]></MsgType>
						<ArticleCount>" . count($arr) . "</ArticleCount>
						<Articles>";
        foreach ($arr as $k => $v) {
            $template .= "<item>
							<Title><![CDATA[" . $v['title'] . "]]></Title> 
							<Description><![CDATA[" . $v['description'] . "]]></Description>
							<PicUrl><![CDATA[" . $v['picUrl'] . "]]></PicUrl>
							<Url><![CDATA[" . $v['url'] . "]]></Url>
							</item>";
        }

        $template .= "</Articles>
						</xml> ";
        echo sprintf($template, $toUser, $fromUser, time(), 'news');

        //注意：进行多图文发送时，子图文个数不能超过10个
        ///单文本回复
    }

    //回复纯文本
    public function responseMsgText($postObj,$content){
        //纯文本回复
        $template = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        </xml>";

        //注意模板中的中括号 不能少 也不能多
        $fromUser = $postObj->ToUserName;
        $toUser = $postObj->FromUserName;
        $time = time();
        $msgType = 'text';
        echo sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
    }

}
?>

    