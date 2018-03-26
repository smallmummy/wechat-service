<?php
//header("content-Type: text/html; charset=utf-8");
/*
*这个版本是免费预览版
*无法获取用户数据和添加商家活动
*
*需要订制的商家请联系QQ776136718
*定制版本支持结合商家原有磁卡类会员卡，集成会员卡积分功能，获取用户数据和添加商家活动
*/

include_once 'diff_platform.php';


$arr_news_para['ArticleCount']=8;
$arr_news_para['Description1']="";
$arr_news_para['Title1']="重庆味道帮助菜单(点击下面查看)";
$arr_news_para['PicUrl1']="";
$arr_news_para['Url1']="";
	
$arr_news_para['Description2']="";
$arr_news_para['Title2']="1.功能介绍";
$arr_news_para['PicUrl2']="";
$arr_news_para['Url2']="http://1.chongqingweidao.sinaapp.com/material/menu-1.html";

$arr_news_para['Description3']="";
$arr_news_para['Title3']="2.会员功能";
$arr_news_para['PicUrl3']="";
$arr_news_para['Url3']="http://1.chongqingweidao.sinaapp.com/material/menu-2.html";

$arr_news_para['Description4']="";
$arr_news_para['Title4']="3.订餐系统";
$arr_news_para['PicUrl4']="";
$arr_news_para['Url4']="http://1.chongqingweidao.sinaapp.com/material/menu-3.html";

$arr_news_para['Description5']="";
$arr_news_para['Title5']="4.近期优惠";
$arr_news_para['PicUrl5']="";
$arr_news_para['Url5']="http://1.chongqingweidao.sinaapp.com/material/menu-4.html";

$arr_news_para['Description6']="";
$arr_news_para['Title6']="5.会员微信活动";
$arr_news_para['PicUrl6']="";
$arr_news_para['Url6']="http://1.chongqingweidao.sinaapp.com/material/menu-5.html";

$arr_news_para['Description7']="";
$arr_news_para['Title7']="6.生活助手";
$arr_news_para['PicUrl7']="";
$arr_news_para['Url7']="http://1.chongqingweidao.sinaapp.com/material/menu-6.html";

$arr_news_para['Description8']="";
$arr_news_para['Title8']="7.意见反馈";
$arr_news_para['PicUrl8']="";
$arr_news_para['Url8']="http://1.chongqingweidao.sinaapp.com/material/menu-7.html";

$tmp_re=combine_msg_news($arr_news_para);
exit($tmp_re);



/*
$tmp_re='description|这个是描述#title|重庆味道帮助菜单#pic|#url';
$tmp_re.='|@title|1.功能介绍#description|下面的描述#pic|#url|http://1.chongqingweidao.sinaapp.com/material/menu-1.html|';
$tmp_re.='@title|2.会员功能#description|下面的描述#pic|#url|http://1.chongqingweidao.sinaapp.com/material/menu-2.html|';
$tmp_re.='@title|3.订餐系统#description|下面的描述#pic|#url|http://1.chongqingweidao.sinaapp.com/material/menu-3.html|';
$tmp_re.='@title|4.近期优惠#description|下面的描述#pic|#url|http://1.chongqingweidao.sinaapp.com/material/menu-4.html|';
$tmp_re.='@title|5.会员微信活动#description|下面的描述#pic|#url|http://1.chongqingweidao.sinaapp.com/material/menu-5.html|';
$tmp_re.='@title|6.生活助手#description|下面的描述#pic|#url|http://1.chongqingweidao.sinaapp.com/material/menu-6.html|';
$tmp_re.='@title|7.意见反馈#description|下面的描述#pic|#url|http://1.chongqingweidao.sinaapp.com/material/menu-7.html|';


exit($tmp_re);



*/


?>





