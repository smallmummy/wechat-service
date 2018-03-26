<?php

//本文件是用来适配不同平台接口的

//$diff_platform_ID---不同平台的标示
//1: 小九
//2: V5KF

global $diff_platform_ID;
$diff_platform_ID=2;



//用来拼接图文消息的函数
//$arr_para['ArticleCount']	:	有多少个子图文消息
//$arr_para['Description1']	:	第一个子消息的描述
//$arr_para['Title1']		:	第一个子消息的title
//$arr_para['PicUrl1']		:	第一个子消息的图片url
//$arr_para['Url1']			:	第一个子消息的内容url
//$arr_para['Description2']	:	第二个子消息的描述
//$arr_para['Title2']		:	第二个子消息的title
//$arr_para['PicUrl2']		:	第二个子消息的图片url
//$arr_para['Url2']			:	第二个子消息的内容url
//.......
//.......
function combine_msg_news($arr_para)
{
	global $diff_platform_ID;
	
	//根据不同的平台接口,确定不同的拼接方式
	switch($diff_platform_ID)
	{
		case 1://小九
			
			$after_combine="description|".$arr_para['Description1']."#title|".$arr_para['Title1']."#pic|".$arr_para['PicUrl1']."#url|".$arr_para['Url1'];

			for ($i=1;$i<$arr_para['ArticleCount'];$i++)
			{
				$j=$i+1;
				$after_combine.="@title|".$arr_para['Title'.$j]."#description|".$arr_para['Description'.$j]."#pic|".$arr_para['PicUrl'.$j]."#url|".$arr_para['Url'.$j]."|";
			}
			
			
			break;
			 
		case 2://V5KF
			$after_combine="<xml>\n<MsgType>news</MsgType>\n<ArticleCount>".$arr_para['ArticleCount']."</ArticleCount>\n<Articles>\n";
			
			for ($i=0;$i<$arr_para['ArticleCount'];$i++)
			{
				$j=$i+1;
				$after_combine.="<item>\n<Title>".$arr_para['Title'.$j]."</Title>\n<Description>".$arr_para['Description'.$j]."</Description>\n<PicUrl>".$arr_para['PicUrl'.$j]."</PicUrl>\n<Url>".$arr_para['Url'.$j]."</Url>\n</item>\n";
			}
	
			$after_combine.="</Articles>\n</xml>";
			break;
			 
		default://没有匹配到, 应该是coding错误
			exit("diff_platform--swtich--diff_platform_ID error!");
			break;
	}
	
	
	return $after_combine;
	

	
}





?>
