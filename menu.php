<?php


include_once 'diff_platform.php';


$arr_news_para['ArticleCount']=2;
$arr_news_para['Description1']="";
$arr_news_para['Title1']="菜单/Menu";
$arr_news_para['PicUrl1']="";
$arr_news_para['Url1']="";

$arr_news_para['Description2']="";
$arr_news_para['Title2']="\"点击这里\"查看具体的菜单\nklik di sini untuk memeriksa menu detil";
$arr_news_para['PicUrl2']="";
$arr_news_para['Url2']="http://1.chongqingweidao.sinaapp.com/restaurant_picker/index.php?fakeid={$member->str_post_fakeid}";


$tmp_re=combine_msg_news($arr_news_para);
exit($tmp_re);



?>