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
<div id="restau" data-role="page" data-add-back-btn="true">
	
	<div data-role="header"> 
        <h1></h1>
	</div> 
	
    
    <form action="submit_order.php"  method="post" name="form1" id="form1"  >
    


    
    
    
    
	<div data-role="content">

	
	<div data-role="fieldcontain">
	  
  
	  <label for="textinput">下面是您点的菜:</label>
	</div>
	</p>
	<textarea name="textinput" readonly id="textinput"><?php
	
		$arr_food = array(
				301=>"辣子鸡",
				302=>"干煸肥肠",
				303=>"泡椒猪肝",
				3041=>"香菜牛肉(酸甜)",
				3042=>"香菜牛肉(麻辣)",
				3051=>"红烧肥肠",
				3052=>"泡椒肥肠",
				306=>"水煮鱼",
				3071=>"酸菜鱼(不辣)",
				3072=>"酸菜鱼(麻辣)",
				308=>"牙签肉",
				309=>"回锅肉",
				310=>"麻婆豆腐",
				311=>"酱香排骨",
				312=>"油炸酥肉",
				3131=>"泡菜",
				3142=>"凉拌豆腐皮",
				3152=>"凉拌木耳",
				316=>"卤肉三拼",
				317=>"蒜泥白肉",
				318=>"青椒皮蛋",
				319=>"蒜泥黄瓜",
				320=>"酸甜鱼",
				321=>"香辣虾",
				322=>"韭菜回锅肉",
				323=>"木耳回锅肉",
				324=>"青椒回锅肉",
				325=>"火爆腰花",
				326=>"鱼香肉丝",
				327=>"青椒肉丝",
				328=>"水煮肉片",
				329=>"水煮牛肉",
				3302=>"麻辣香锅",
				3312=>"干锅鸡",
				332=>"酥肉炖粉条",
				333=>"家常豆腐",
				334=>"泡椒牛蛙",
				335=>"三鲜猪肝汤",
				336=>"胡萝卜肉片",
				337=>"肉末豇豆",
				338=>"肉末茄子",
				3391=>"酸豆角鸡胗",
				3392=>"泡椒鸡胗",
				340=>"鱼香茄子",
				341=>"干煸豆角",
				342=>"青椒土豆丝",
				343=>"酸辣土豆丝",
				344=>"韭菜鸡蛋",
				345=>"番茄炒蛋",
				346=>"苦瓜炒蛋",
				347=>"木耳炒蛋",
				348=>"白菜炒蛋",
				349=>"炒豆芽",
				3502=>"手撕包菜",
				3511=>"韭菜鸡蛋饼",
				3512=>"火腿鸡蛋饼",
				3513=>"葱油鸡蛋饼",
				352=>"干煸土豆丝",
				353=>"空心菜",
				354=>"蒜蓉菠菜",
				355=>"炒苦瓜",
				356=>"炒小白菜",
				357=>"炒大白菜",
				358=>"番茄蛋花汤",
				359=>"黄瓜皮蛋汤",
				360=>"菠菜肉片汤",
				361=>"平菇肉片汤",
				79=>"白饭",
				362=>"蛋炒饭",
				363=>"酸豆角肉末炒饭",
				364=>"火腿炒饭",
				365=>"重庆小面",
				366=>"牛肉面",
				367=>"肥肠面",
				368=>"清汤抄手",
				369=>"红汤抄手",
				371=>"爆炒鸡肠",
				372=>"小炒牛肉",
				373=>"爆炒猪头肉",
				377=>"小菜豆腐汤",
				888=>"YoYo鸭一只",
				887=>"YoYo鸭半只",
				889=>"白菜猪肉饺子(10个)",
				8891=>"韭菜猪肉饺子(10个)",
				609=>"卤鸭头(一个)"
				);
		
		$arr_food_price = array(
				301=>55000,
				302=>55000,
				303=>50000,
				3041=>55000,
				3042=>55000,
				3051=>55000,
				3052=>55000,
				306=>98000,
				3071=>98000,
				3072=>98000,
				308=>58000,
				309=>58000,
				310=>35000,
				311=>68000,
				312=>40000,
				3131=>15000,
				3142=>30000,
				3152=>25000,
				316=>58000,
				317=>48000,
				318=>30000,
				319=>25000,
				320=>68000,
				321=>68000,
				322=>55000,
				323=>55000,
				324=>55000,
				325=>48000,
				326=>48000,
				327=>48000,
				328=>58000,
				329=>58000,
				3302=>60000,
				3312=>49000,
				332=>58000,
				333=>38000,
				334=>50000,
				335=>35000,
				336=>55000,
				337=>38000,
				338=>40000,
				3391=>48000,
				3392=>48000,
				340=>30000,
				341=>35000,
				342=>30000,
				343=>30000,
				344=>35000,
				345=>35000,
				346=>35000,
				347=>35000,
				348=>25000,
				349=>25000,
				3502=>25000,
				3511=>25000,
				3512=>25000,
				3513=>25000,
				352=>30000,
				353=>25000,
				354=>30000,
				355=>25000,
				356=>25000,
				357=>20000,
				358=>25000,
				359=>20000,
				360=>35000,
				361=>35000,
				79=>5000,
				362=>30000,
				363=>32000,
				364=>32000,
				365=>25000,
				366=>35000,
				367=>35000,
				368=>35000,
				369=>35000,
				371=>45000,
				372=>69000,
				373=>58000,
				377=>30000,
				887=>68000,
				888=>119000,
				889=>30000,
				8891=>30000,
				609=>6000);
		
		//将数组key转换为小写
       $arr_ordered= array_change_key_case($_POST, CASE_LOWER);
       //点菜的SMS变量初始化
       $str_order_sms="pesan:";
       //点菜的总额变量初始化
       $amt_order_total=0;
       //遍历数组中每一个元素   
       foreach ($arr_ordered as $key=>$value)
       {
       		//判断是否是slider标签
       		if (preg_match('/^slider_/',$key,$temp))
			{
				preg_match('/(?<=slider_).*(?=$)/',$key,$temp);
				//昵称
				$food_id=trim($temp[0]);
				
				$food_qunitity=$value;

				//只打印出不为空的, 也就是客人点的餐
				if ($food_qunitity!=0)
				{
					echo("$arr_food[$food_id]  {$food_qunitity}份  RP".number_format($arr_food_price[$food_id]*$food_qunitity)."\n");
					$str_order_sms.="{$food_id}*{$food_qunitity}  ";
					$amt_order_total+=$arr_food_price[$food_id]*$food_qunitity;
					//$str_order_sms.="$arr_food[$food_id]  {$food_qunitity}份\n";
				}
				
			}

       	
       
       
       }
       
       
       if(isset($_POST["txt_user_fakeid"])&&($_POST["txt_user_fakeid"]!="null"))//判断是否传递了用户的fake_id
       {
	       	//有fake_id,进行后续流程
	       	$exist_fake_id=true;
	       	echo "送餐费 RP 15,000\n共计消费金额: RP ".number_format($amt_order_total+15000);
	       	echo "\n(送餐费用根据路程远近调整,以实际为准)";
			//test code
	       	//echo "\n fakeid={$arr_ordered['txt_user_fakeid']}";
       }
       else
       {
	       	//没有fake_id,提示不同的错误
	       	$exist_fake_id=false;
	       	echo "非法进入,请从手机微信wechat客户端进入进行点餐";
       }
       
     
       
       ?></textarea>
       
       
           <?php 
      //判断是否有fake_id
      if ($exist_fake_id==false)
      {
	      //没有fake_id,保持空白
	      $last_order_hp="";
	      $last_order_add="";
      }
      else
      {
	      //有fake_id,调出上一次订餐记录的信息
	      global $member;
	      $member=new cls_member;
	      //调出上一次订餐记录的信息
	      $last_order_info=$member->get_last_order_info($_POST["txt_user_fakeid"]);
	      //判断是否数据为空
	      if (!isset($last_order_info))
	      {
				//第一次订餐,返回空
				$member->close_db();
		  	    $last_order_hp="";
		   	    $last_order_add="";
	      }
	      else
	      {
	      		//将上一次订餐的信息填充
	      		$last_order_hp=$last_order_info['order_hp'];
	      		$last_order_add=$last_order_info['order_add'];
	   		   	
	      }
        
      }
      
      
   
      
             
      ?> 
       
       
    <p>
      	<div data-role="fieldcontain">
 		<label for="textinput">请输入您的电话号码(手机以0开头,固话以031开头):<br>
 		</label>
        <input name="textinput_hp" type="text" id="textinput_hp" value="">
      </div>
      	<div data-role="fieldcontain">
          <p>
            <label for="textinput2">请输入您的送餐地址<font color="red">（如果是订座的话，请在下面注明你们多少个人，几点到，几点可以出菜）:</font><br>
            </label>
            <textarea name="textinput_add" id="textinput_add"></textarea>
          </p>
          <p>
            <label for="textinput2">请输入您的备注,可不填写(比如:尽快,加辣)<br>
            </label>
            <input name="textinput_note" type="text" id="textinput_note" value="">
          </p>
      </div>

      
      
  
      
<script>

var temp_last_order_hp="<?php echo($last_order_hp);?>";
var temp_last_order_add="<?php echo $last_order_add;?>";
document.getElementById("textinput_hp").value=temp_last_order_hp;
document.getElementById("textinput_add").value=temp_last_order_add;
</script>
      
      
      
      

          <p>
            <label for="textinput2">在线订餐说明(请仔细阅读)<br>
            </label>
            <textarea name="order_readme" readonly id="order_readme"><?php
            
            if ($exist_fake_id==true)
            {
            	//正常流程,有fake_id
			    echo "1.请务必保证上述的电话号码是正确的,可接通的\n";
			    echo "2.十钟内,我们的工作人员会拨打您留的上述电话,进行电话确认,口头确认后,则订单有效,我们会尽快处理\n";
			    echo "3.如果您的电话因为信号问题不能接通,或者无人接听,则订单无效,请恕我们不能处理你的订餐\n";
			    echo "4.如果十分钟内,您没有收到我们的电话,可能是您的电话不能接通,请拨打店内座机进行订餐\n";
			    echo "5.上述的送餐费和消费金额有可能因为实际情况进行调整,具体费用以结账小票为准";
            }
            else
            {
            	//非法进入,没有fake_id,进行不同的提示
            	echo "请用微信wechat客户端进行在线订餐,具体步骤如下:\n";
            	echo "1.使用微信wechat搜索微信号:chongqingweidao,并点击屏幕下方的\"关注\"\n";
            	echo "2.发送\"订餐\",点击收到的消息\n";
            	echo "3.在弹出的网页中,按照提示进行操作即可";
            }
     
     ?></textarea>
          </p>

      	<p>
      	  <textarea name="txt_order_sms" readonly id="txt_order_sms" style="display: none;"></textarea>
   	  </p>
      	  <textarea name="txt_user_fakeid" id="txt_user_fakeid" style="display: none;"><?php 
   if(is_array($_POST)&&count($_POST)>0)//先判断是否通过POST传值了
   {
       if(isset($_POST["txt_user_fakeid"]))//是否存在"id"的参数
       {
           $id=$_POST["txt_user_fakeid"];//存在
           //echo $id;
           echo $arr_ordered['txt_user_fakeid'];
       }
       else//
       {
       	   echo "null";
       }
   }
   else
   {
     echo "null";
   }
?></textarea>
 
 

      	<p>
     	  <input name="fill_form_submit" type="button" class="classement" id="fill_form_submit" onclick="if(InputCheck(this.form1)){do_submit(this.form1)};" value="同意上述说明,提交订单">
   	  </p>
<script>
var temp_temp="<?php echo($exist_fake_id);?>";
if ( temp_temp == false)
{
	document.getElementById("fill_form_submit").disabled=true;
}
</script>


      	<p>&nbsp;</p>
      	<p>&nbsp;</p>
      	<p>&nbsp;</p>
      	<p>&nbsp;</p>

	</div>
    </form>

    
    
    
    <script language=JavaScript>
<!--

function InputCheck(RegForm)
{
  if (form1.textinput_hp.value == "")
  {
    alert("您的电话号码不能为空!");
    form1.textinput_hp.focus();
    return false;
  }
  if (form1.textinput_add.value == "")
  {
    alert("您的送餐地址不能为空!");
    form1.textinput_add.focus();
    return false;
  }

  if(!(/^0(8\d|31)\d{4,13}$/.test(form1.textinput_hp.value)))
  {
	 alert("请输入正确的电话号码,比如08或者031开头!");
	 form1.textinput_hp.focus();
	 return false;	   
  }


  //判断点菜金额是否达到最低门限
  var isBigThan80="<?php
  if ($amt_order_total >= 100000)
  {
  	echo 1;
  }
  else
  {
  	echo 0;
  }
  ?>";

  if(isBigThan80 == 0)
  {
	 alert("点餐消费金额需要大于100,000才可以送餐哦,请再加点儿菜吧~");
	 return false;	 
  }

   //满足所有的条件后, 进入后续处理流程
   var temp="<?php echo $str_order_sms;?>";

   var tmp_record_id="<?php 
   
   //global $member;
   //$member=new cls_member;
   //调出上一次订餐记录的信息
   $max_order_info=$member->get_last_order_max();
   //判断是否数据为空
   if (!isset($max_order_info))
   {
   	//表中为空, 赋初始值1
   	$member->close_db();
   	$tmp_return=1;
   }
   else
   {
   	//取出record_id的最大值, 加1,赋值
   	$tmp_return=$max_order_info['max(record_id)']+1;
   }
   
   
   echo $tmp_return;
   
 
   
   ?>";

   var tmp_current_time="<?php echo date("h:ia",time()-3600);   ?>";
   form1.txt_order_sms.value="No."+tmp_record_id+": "+temp+",note:"+form1.textinput_note.value+",tele:"+form1.textinput_hp.value+",alamat:"+form1.textinput_add.value+";wak:"+tmp_current_time; 
  return true;
}



function do_submit(RegForm)
{

	document.getElementById("fill_form_submit").disabled=true;
	form1.submit(); 
	return true;
}


//-->
</script>
    
    
    
    
    
    
</div><!-- /page -->
</body>
</html>



