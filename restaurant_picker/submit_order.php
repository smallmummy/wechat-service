<!DOCTYPE html> 
<html> 
<head> 
<meta charset="UTF-8">
<title>印尼泗水中餐馆"重庆味道"</title> 
<meta name="viewport" content="width=device-width, initial-scale=1"> 
<link rel="stylesheet" href="jquery.mobile.structure-1.0.1.css" />
<link rel="apple-touch-icon" href="images/launch_icon_57.png" />
<link rel="apple-touch-icon" sizes="72x72" href="images/launch_icon_72.png" />
<link rel="apple-touch-icon" sizes="114x114" href="images/launch_icon_114.png" />
<link rel="stylesheet" href="jquery.mobile-1.0.1.css" />
<link rel="stylesheet" href="custom.css" />
<script src="js/jquery-1.7.1.min.js"></script>
<script src="js/jquery.mobile-1.0.1.min.js"></script>
<?php include("../cls_member.php");?>
</head> 








<body> 
<div id="restau" data-role="page" data-add-back-btn="false">
<div data-role="header"> 
        <h1></h1>
</div> 
    
    <form  name="form1" id="form1"  >
 
<div data-role="content">
<div data-role="fieldcontain">
 
  
  <label for="textinput">系统提示:</label>
</div>
</p>

<p>
  <textarea name="text_result" readonly id="text_result">123</textarea>
  </p>

<p>&nbsp;</p>
<p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>


<?php 

//include("../cls_member.php");
global $member;
$member=new cls_member;


//clicktell的API需要的信息
$clicktell_username='xxx';
$clicktell_pwd='xxx';
$clicktell_api_id='xxxx';

//被叫号码在后面的逻辑调用过程中赋值
//$clicktell_to_number='6283879836699';
$valid_enter=true;
if(is_array($_POST)&&count($_POST)>0)//先判断是否通过POST传值了
{
	//判断传递过来的参数是否健全
	if(!isset($_POST["txt_user_fakeid"]))
	{
		//传递的参数不全,非法进入
		$valid_enter=false;
	}
	elseif(!isset($_POST["textinput"]))
	{
		//传递的参数不全,非法进入
		$valid_enter=false;
	}
	elseif(!isset($_POST["textinput_hp"]))
	{
		//传递的参数不全,非法进入
		$valid_enter=false;
	}
	elseif(!isset($_POST["textinput_add"]))
	{
		//传递的参数不全,非法进入
		$valid_enter=false;
	}
	elseif(!isset($_POST["textinput_note"]))
	{
		//传递的参数不全,非法进入
		$valid_enter=false;
	}
	elseif(!isset($_POST["txt_order_sms"]))
	{
		//传递的参数不全,非法进入
		$valid_enter=false;
	}
}
else
{
	$valid_enter=false;
}

//参数齐全的情况下,进入逻辑处理
if ($valid_enter==true)
{
	//提取所有参数
	$str_post_fakeid=$_POST["txt_user_fakeid"];
	$str_textinput=$_POST["textinput"];
	$str_textinput_hp=$_POST["textinput_hp"];
	$str_textinput_add=$_POST["textinput_add"];
	$str_textinput_note=$_POST["textinput_note"];
	$str_txt_order_sms=$_POST["txt_order_sms"];
	
	$str_txt_order_sms=unicode_encode($str_txt_order_sms);
	
	
	//向店里发送短信
	//发送是否成功, 存在多个号码,分单次发送,如果全部失败,则判定发送失败,反之则发送成功
	$send_all_sms_success=false;
	//发送短信的返回信息(多条返回结果的拼接后)
	$sms_return_content="";
	
	//************************************第一条短信发送中********************************
	//被叫号码1,店里手机号, 6283857581267
	//$clicktell_to_number='6287702427771';
	
	$clicktell_to_number='6282139749191';
	
	//拼接历史记录表中的错误短信的返回信息
	$sms_return_content.="to:".$clicktell_to_number;
	$sms_return_content.=",text:".$_POST["txt_order_sms"];
	
	$arr_temp_return=send_sms_to_resto($str_txt_order_sms,$clicktell_username,$clicktell_pwd,$clicktell_api_id,$clicktell_to_number);
	
	
	//test code
	//$arr_temp_return[0]=true;
	//test code
	//$arr_temp_return[1]="true for test";
	
	
	//被叫号码1发送结果判断
	if ($arr_temp_return[0])
	{
		//本次发送短信成功
		$send_all_sms_success=true;
	}
	$sms_return_content.=";re:".$arr_temp_return[1];
	//************************************第一条短信发送完毕********************************
	
	
	
	//************************************第二条短信发送中********************************	
	//被叫号码2,老板手机号, 087702427771
	$clicktell_to_number='6287702427771';

	
	//拼接历史记录表中的错误短信的返回信息
	$sms_return_content.="to:".$clicktell_to_number;
	$sms_return_content.=",text:".$_POST["txt_order_sms"];
	
	$arr_temp_return=send_sms_to_resto($str_txt_order_sms,$clicktell_username,$clicktell_pwd,$clicktell_api_id,$clicktell_to_number);
	
	
	//test code
	//$arr_temp_return[0]=true;
	//test code
	//$arr_temp_return[1]="true for test";
	
	
	//被叫号码2发送结果判断
	if ($arr_temp_return[0])
	{
		//本次发送短信成功
		$send_all_sms_success=true;
	}
	$sms_return_content.=";re:".$arr_temp_return[1];
	
	//************************************第二条短信发送完毕********************************
	
	//test code
	//$send_all_sms_success=true;
	
	//************************************多条短信发送完毕********************************
	
	
	
	
	//不管发送短信是否成功,将记录插入到历史表中
	$tmp_return=insert_order_history($str_post_fakeid,$str_textinput,$str_textinput_hp,$str_textinput_add,$str_textinput_note,$str_txt_order_sms,$send_all_sms_success,$sms_return_content);
	switch($tmp_return)
	{
		case 1:
		case 0:
			//插入数据库正确
			$insert_DB=true;
			break;
		case 2:
			//插入数据库失败
			$insert_DB=false;
			$insert_fail_reason=$member->str_sql_errmsg;
			break;
		default:
			$insert_DB=false;
			$insert_fail_reason="submit_order的inser_order_history的switch默认错误";
	}

}

/**
* 插入用户点餐历史记录表
* @param  $fake_id 用户的fake_id
* @param  $order_content 点餐内容(以匹配名称)
* @param  $order_hp 用户点餐联系电话
* @param  $order_add 用户点餐地址
* @param  $order_note 用户点餐备注
* @param  $order_sms 点餐短信(已拼凑)
* @param  $suceed_sms 是否给店里发送短信成功
* @param  $sms_return_content 错误短信的返回信息
* @return 0:插入成功
*         1:保留
*         2:执行sql错误
*/
function insert_order_history($fake_id,$order_content,$order_hp,$order_add,$order_note,$order_sms,$suceed_sms,$sms_return_content)
{
	global $member;
	$tmp_return=$member->insert_order_history($fake_id,$order_content,$order_hp,$order_add,$order_note,$order_sms,$suceed_sms,$sms_return_content);
	return $tmp_return;
}
/**
* 发送短信函数,调用clicktell接口
* @param  $sms_content  短信内容
* @param  $username  接口的用户名
* @param  $pwd 接口的密码
* @param  $api_id    接口的api ID
* @param  $to_number   要发送的手机号码
* @return $arr_return[0] false:发送短信出现错误; true:发送短信成功
* 	   $arr_return[1] clicktell返回的信息
*/
function send_sms_to_resto($sms_content,$username,$pwd,$api_id,$to_number)
{
	//拼装API
	$api = "http://api.clickatell.com/http/sendmsg?user=".$username."&password=".$pwd."&api_id=".$api_id."&to=".$to_number."&text=".$sms_content."&unicode=1&concat=3";
	//发送到API接口
	$replys=file_get_contents($api);
	//判断API的返回结果
	if (preg_match('/ID:/',$replys,$temp))
	{
		//有ID字样,发送短信成功
		$arr_return[0]=true;
		$arr_return[1]=$replys;
	}
	elseif (preg_match('/ERR/',$replys,$temp))
	{
		//有ERR字样,发送短息失败
		$arr_return[0]=false;
		$arr_return[1]=$api."--->".$replys;
	}
		return $arr_return;
	}

	
	
	
	function unicode_encode($name)
	{
		$name = iconv('UTF-8', 'UCS-2//IGNORE', $name);
		//$name = iconv('GBK', 'UCS-2', $name);
		$len = strlen($name);
		$str = '';
		for ($i = 0; $i < $len - 1; $i = $i + 2)
		{
			$c = $name[$i];
			$c2 = $name[$i + 1];
			/*
			 if (ord($c) > 0)
			 {    // 两个字节的文字
			//$str .= '\u'.base_convert(ord($c), 10, 16).base_convert(ord($c2), 10, 16);
			//$str .= '\u'.base_convert(ord($c2), 10, 16).base_convert(ord($c), 10, 16);
			$str .= str_pad(base_convert(ord($c2), 10, 16),2,'0',STR_PAD_LEFT).str_pad(base_convert(ord($c), 10, 16),2,'0',STR_PAD_LEFT);
			}
			else
			{
			//$str .= $c2;
			$str= $str.'$'.$c2;
			}
			*/
			$str .= str_pad(base_convert(ord($c2), 10, 16),2,'0',STR_PAD_LEFT).str_pad(base_convert(ord($c), 10, 16),2,'0',STR_PAD_LEFT);
	
	
		}
		return $str;
	}
	
	
?>



      
      
      
      
      
      
      
      
<script>
var ds="<?php 

if ($valid_enter==false)
{
	echo "非法进入,参数不全,不能处理;\\n返回请点击左上角的返回按钮";
}
elseif ($send_all_sms_success==true)
{
	//发送短信成功
	echo "已经成功收到你的订餐,请耐心等候;\\n如果10分钟内没有收到我们的电话,代表我们不能处理你的订餐;\\n请拨打电话订餐031-7317074/083892585501;\\n返回请点击左上角的返回按钮";
}
else
{
	//发送短信失败
	echo "对不起,可能现在系统忙,不能处理您的订餐信息;\\n请拨打电话订餐:031-7317074/083892585501;\\n返回请点击左上角的返回按钮";
}

?>";
document.getElementById("text_result").value=ds;
</script>  
 
 

      

      
      
      
</div>
    </form>

    

    
    
    
</div><!-- /page -->
</body>
</html>


