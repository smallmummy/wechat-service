<?php
/*
    File: index.php
    Author: 管理员
    Date: 2013.04.15
    Usage: 小九机器人微信接口
    论坛微信QQ群: 39161950(已满),39161950
	小九官网： www.xiaojo.com
	微信论坛： www.weixen.com
 */

//小九的接口
//URL: http://1.chongqingweidao.sinaapp.com/index.php
//token: smallmummy_cicilia

//小五的接口
//URL:http://www.v5kf.com/public/weixin?site_id=16326&salt=ondEhM
//token: ondEhM
//原始ID: gh_ef913c5ac779
//小五调用的第三方API
//[vs_api_url charset="utf-8" return="text"]http://1.chongqingweidao.sinaapp.com/input_consume_bonus_id.php?from=[vs_user=wxkey][/vs_api_url]
//[vs_api_url charset="utf-8" return="text"]http://1.chongqingweidao.sinaapp.com/member.php?key=[vs=pa]&from=[vs_user=wxkey][/vs_api_url]




header("content-Type: text/html; charset=utf-8");
require_once(dirname(__FILE__) . "/wechat.php");
define("DEBUG", true);

//这里为你的小九后台账号,不填不能正常回复！！！！
$yourdb="smallmummy";
$yourpw="@163.COMa";

//下面为可选配置的选项
//http://1.chongqingweidao.sinaapp.com/index.php
define("TOKEN", "smallmummy_cicilia");
define("MOREN", "抱歉，我真的不知道肿么回答了，您可以用问*答*来教我");//丢包后默认回复
define("FLAG", "@");//星标标识，默认为 @,用户对话里包含此标识则设置为星标，用于留言
//配置结束

$w = new Wechat(TOKEN, DEBUG);
if (isset($_GET['echostr'])) 
{
    $w->valid();
    exit();
}

//回复用户
$w->reply("reply_cb");
exit();

function reply_cb($request, $w)//消息回复主函数
{
    $to = $request['ToUserName'];
    $from = $request['FromUserName'];
	$time = $w->get_creattime();
    if ($w->get_msg_type() == "location") //发送位置接口
	{
		$lacation = "x@".(string)$request['Location_X']."@".(string)$request['Location_Y'];
		$lacation = urlencode(str_replace('\.','\\\.',$lacation));
		$lacation = urldecode(xiaojo($lacation,$from,$to));
		return  $lacation;
    }
    else if ($w->get_msg_type() == "image")//返回图片地址
	{ 
		$PicUrl = $request['PicUrl'];
		$pic = urldecode(xiaojo("&".$PicUrl,$from,$to));
		//$w->set_funcflag();
		return $pic;
    }
	else if ($w->get_msg_type() == "voice") //用户发语音时回复语音或音乐,请在此配置默认语音回复
	{
    
		return array(
			"title" =>  "你好",
			"description" =>  "亲爱的主人",           
			"murl" =>  "http://weixen-file.stor.sinaapp.com/b/xiaojo.mp3",//语音地址，建议自定义一个语音
			"hqurl" =>  "http://weixen-file.stor.sinaapp.com/b/xiaojo.mp3",
		);
    }
	else if ($w->get_msg_type() == "event")//事件检测
	{ 
		if ($w->get_event_type() == "subscribe")//首次关注回复请在后台设置关键词为 "subscribe" 的图文、文本或语音规则
		{
			return xiaojo("subscribe",$from,$to);		
		}
		elseif($w->get_event_type() == "unsubscribe")
		{
			$unsub = xiaojo("unsubscribe",$from,$to);
			return $unsub;
		}
		elseif($w->get_event_type() == "click")
		{
			$menukey = $w->get_event_key();
			$menu = xiaojo($menukey,$from,$to);
			return $menu;
		}
		else
		{
			$menukey = $w->get_event_key();
			return $menukey;
		}
    }
    $content = trim($request['Content']);
   	$firsttime = $content;
	if ($content !== "") //发纯文本
    {
   
    	//首先判断需要屏蔽的词语
    	switch($content)
    	{
    		case "我想你猜"://
    			return "我猜你是想玩游戏让你猜吧,你可以输入\"游戏让你猜\"试一试";
    			break;
    		case "猜人物"://
    			return "我猜你是想玩游戏猜人物吧,你可以输入\"游戏猜人物\"试一试";
    			break;
    		case "猜影视":
    			return "我猜你是想玩游戏猜影视吧,你可以输入\"游戏猜影视\"试一试";
    			break;
   			case "猜生物":
   				return "我猜你是想玩游戏猜生物吧,你可以输入\"游戏猜生物\"试一试";
    			break;
    		case "猜食物":
    			return "我猜你是想玩游戏猜食物吧,你可以输入\"游戏猜食物\"试一试";
    			break;
    		case "猜东西":
    			return "我猜你是想玩游戏猜东西吧,你可以输入\"游戏猜东西\"试一试";
    			break;
    		case "猜文学":
    			return "我猜你是想玩游戏猜文学吧,你可以输入\"游戏猜文学\"试一试";
    			break;
    		case "猜音乐":
    			return "我猜你是想玩游戏猜音乐吧,你可以输入\"游戏猜音乐\"试一试";
    			break;
    		case "猜地点":
    			return "我猜你是想玩游戏猜地点吧,你可以输入\"游戏猜地点\"试一试";
    			break;
    		case "猜机构":
    			return "我猜你是想玩游戏猜机构吧,你可以输入\"游戏猜机构\"试一试";
    			break;
    		case "猜事件":
    			return "我猜你是想玩游戏猜事件吧,你可以输入\"游戏猜事件\"试一试";
    			break;
    			
    			
    			
    				 
    		default:
    			//不在上述需要屏蔽的词语中,继续处理
    			break;
    		
    	}
    	
    	
    	
    	
    	
        //$w->set_funcflag(); //如果有必要的话，加星标，方便在web处理
		$content = $w->biaoqing($content); //表情处理
		if(strstr($content,FLAG))//如果有星标的标记则设为星标(用于留言)
		{ 
			$w->set_funcflag();
		}
		$content = $content."^".$time;
		$reply = xiaojo($content,$from,$to);		
		if($reply=="")
		{
			$reply = MOREN ;
		}
		return  $reply;

    }
	else
	{
		return  MOREN;
	}
    
}
?>
