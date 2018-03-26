<?php

	require('cls_member.php');
	global $member;
	$member=new cls_member(true);

	
	$temp=$member->check_is_admin();
	
	/*
	//判断结果
	switch($temp)
	{
		case 2://2:不在管理员组中,没有权限
			$member->close_db();
			$tmp_re="朋友进错地方了吧,呵呵~去别处逛逛吧";
			exit($tmp_re);
			break;
		case 0:
			break;
		default:
			exit('test未知错误');
	}*/
	
	
	//继续test逻辑
	
	
	//$tmp_re='[vs_function]CreateNews(V5KF微信接口, http://static.v5kf.com/images/banner_weixin.png, http://www.v5kf.com/public/weixin/page?site_id=10000&id=1, V5KF微信接口图文介绍)[/vs_function]';
	
	

	//$tmp_re='description|#title|格式错误#pic|#url|@title|%t%t%t%t缺少你的昵称,不能绑定.正确的格式是"会员绑定 你的昵称", 比如发送"会员绑定 小程"#description|#pic|#url|';
	
	$tmp_re = <<<eot
	<xml>
 <MsgType>news</MsgType>
 <ArticleCount>2</ArticleCount>
 <Articles>
 <item>
 <Title></Title> 
 <Description></Description>
 <PicUrl></PicUrl>
 <Url></Url>
 </item>
 <item>
 <Title>缺少你的昵称,不能绑定.正确的格式是"会员绑定 你的昵称", 比如发送"会员绑定 小程</Title> 
 <Description></Description>
 <PicUrl></PicUrl>
 <Url></Url>
 </item>
 </Articles>
 </xml> 
eot;
	
	
	/*
	<xml>
 <MsgType>news</MsgType>
 <ArticleCount>2</ArticleCount>
 <Articles>
 <item>
 <Title>格式错误</Title> 
 <Description></Description>
 <PicUrl></PicUrl>
 <Url></Url>
 </item>
 <item>
 <Title>缺少你的昵称,不能绑定.正确的格式是"会员绑定 你的昵称", 比如发送"会员绑定 小程</Title> 
 <Description></Description>
 <PicUrl></PicUrl>
 <Url></Url>
 </item>
 </Articles>
 </xml> 
	 */
	
	
	
	exit($tmp_re);
	

?>

