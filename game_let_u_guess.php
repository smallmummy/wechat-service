<?php

	require('cls_member.php');
	global $member;
	$member=new cls_member(true);
	//参与活动需要扣除的互动点数,必须为负数
	$act_point=-1;

	//小九;小九将传递的参数放在POST中
	$xml = $_POST;
		
	$request = array_change_key_case($xml, CASE_LOWER);
	// 将数组键名转换为小写，提高健壮性，减少因大小写不同而出现的问题

	//$from = $request['FromUserName'];
	$from = $member->str_post_fakeid;

	//echo "from smallmummy\n";
	
	switch($member->str_post_key)
	{
		case "遊戲讓你猜":
		case "游戏让你猜":
			//显示游戏说明
			echo "游戏说明\n\n     虽然我是一个机器人,但还是有点智商的,你可以在你心中默默想一个关键字,然后我会问你若干个问题,你只需要回答是或者不是,或者不知道,我就会慢慢猜出来你想的那个词的, 输入\"游戏让你猜\" 或者 \"游戏猜人物\" 或者 \"游戏猜影视\" 或者 \"游戏猜生物\" 或者 \"游戏猜食物\" 或者 \"游戏猜东西\" 或者 \"游戏猜文学\" 或者 \"游戏猜音乐\" 或者 \"游戏猜地点\" 或者 \"游戏猜机构\" 或者 \"游戏猜事件\"就可以开始游戏了!\n";
			exit;
			break;
		case "遊戲猜人物"://
		case "游戏猜人物"://
			$key_string="猜人物";
			break;
		case "遊戲猜影視":
		case "游戏猜影视":
			$key_string="猜影视";
			break;
		case "遊戲猜生物":
		case "游戏猜生物":
			$key_string="猜生物";
			break;
		case "遊戲猜食物":
		case "游戏猜食物":
			$key_string="猜食物";
			break;
		case "遊戲猜東西":
		case "游戏猜东西":
			$key_string="猜东西";
			break;
		case "遊戲猜文學":
		case "游戏猜文学":
			$key_string="猜文学";
			break;
		case "遊戲猜音樂":
		case "游戏猜音乐":
			$key_string="猜音乐";
			break;
		case "遊戲猜地點":
		case "游戏猜地点":
			$key_string="猜地点";
			break;
		case "遊戲猜機構":
		case "游戏猜机构":
			$key_string="猜机构";
			break;
		case "遊戲猜事件":
		case "游戏猜事件":
			$key_string="猜事件";
			break;
		default:
			exit('游戏让你猜关键字匹配错误');
	}
	
	
	
	
	
	
	
	//查询是否已经是会员;
	$re_get_member=$member->get_member_info($member->str_post_fakeid);
	if (!isset($re_get_member))
	{
		$member->close_db();
		//不是会员
		exit("您好,您目前还不是我们的会员,不能参加此活动,请输入\"会员绑定   你的昵称\"来手动加入会员,详情可以输入\"会员\"来查询\n\n");
	}
	else//是会员, 需要扣除积分,参加互动
	{
		
		$tmp_return_add_bonus=$member->add_bonus_by_activity(2,0,$act_point,"参加游戏$key_string 活动,扣除互动积分1点");
		//判断结果
		switch($tmp_return_add_bonus[0])
		{
			case 2://2:执行sql错误
				$member->close_db();
				$tmp_re="参与游戏$$key_string 扣除互动积分失败, 请联系老板处理,具体的错误信息为$member->str_sql_errmsg|$member->str_exe_sql";
				exit($tmp_re);
				break;
			case 0://0:更新成功,直接跳出,进行后续处理
				break;
			default:
				exit('daily_sign-tmp_return_add_bonus未知错误');
		}
		
		$member->close_db();
		echo "[系统提示]已经成功扣除会员互动积分1点, 现在开始游戏\n\n";
		$tmp_reply = xiaojo($key_string,$from);
		echo $tmp_reply;
	}
	
	
	
	
	
	
	
	
	
	
	

	
	function xiaojo($key,$from) //小九接口函数，该函数可通用于其他程序
	{
		$yourdb = "smallmummy";//小九账号
		$yourpw = "@163.COMa";//小九密码
		$key=urlencode($key);
		$yourdb=urlencode($yourdb);
		$from=urlencode($from);//粉丝openid
		$post="chat=".$key."&db=".$yourdb."&pw=".$yourpw."&from=".$from;
		$api = "http://www.xiaojo.com/api5.php";
		$replys = curlpost($post,$api);
		$reply = urldecode( $replys);
		return $reply;
	}
	function curlpost($curlPost,$url) //curl post 函数
	{
		$ch = curl_init();//初始化curl
		curl_setopt($ch,CURLOPT_URL,$url);//抓取指定网页
		curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
		curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
		curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
		$data = curl_exec($ch);//运行curl
		curl_close($ch);
		return $data;
	}

	

?>

