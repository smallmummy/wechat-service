<?php


	require('cls_member.php');
	include_once 'diff_platform.php';
	global $member;
	$member=new cls_member(true);

	
	//$tmp_re="description|#title|微信在线订餐/电话订餐#pic|#url|@title|微信在线订餐\n\n\"点击这里\"进入微信在线订餐.#description|#pic|#url|http://1.chongqingweidao.sinaapp.com/restaurant_picker/index.php?fakeid={$member->str_post_fakeid}|@title|电话订餐\n\n消费金额超过80,000RP即可送餐\n 送餐时间: 11:30(中午)~11:00(晚上)\n 订餐电话: 031-7319094 / 083892585501 / 083856999480#description|#pic|#url|";

	$arr_news_para['ArticleCount']=3;
	$arr_news_para['Description1']="";
	$arr_news_para['Title1']="在线订餐/电话订餐/在线订座";
	$arr_news_para['PicUrl1']="";
	$arr_news_para['Url1']="";
		
	$arr_news_para['Description2']="";
	$arr_news_para['Title2']="在线订餐/在线订桌\n\n\"点击这里\"进入在线订餐/在线订座";
	$arr_news_para['PicUrl2']="";
	$arr_news_para['Url2']="http://1.chongqingweidao.sinaapp.com/restaurant_picker/index.php?fakeid={$member->str_post_fakeid}";
	
	$arr_news_para['Description3']="";
	$arr_news_para['Title3']="电话订餐\n\n消费金额超过100,000RP即可送餐\n 送餐时间: 11:30(中午)~11:00(晚上)\n 订餐电话: 031-7319094 / 083892585501 / 083856999480";
	$arr_news_para['PicUrl3']="";
	$arr_news_para['Url3']="http://1.chongqingweidao.sinaapp.com/restaurant_picker/index.php?fakeid={$member->str_post_fakeid}";
	
		
	$tmp_re=combine_msg_news($arr_news_para);
	exit($tmp_re);
	
	
	//
	//
	
?>
