<?php
/**
  * wechat php test
  */

//define your token
define("TOKEN", "weixin");
$wechatObj = new wechatCallbackapiTest();
$wechatObj->responseMsg();

class wechatCallbackapiTest
{
	public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }

    public function responseMsg()
    {
		//get post data, May be due to the different environments
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

      	//extract post data
		if (!empty($postStr)){
                
              	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
                $keyword = trim($postObj->Content);
                $time = time();
				$openid=$fromUsername;
				$date=date('Y-m-d');
				$time=date('H:i:s');
                $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";             
				if(!empty( $keyword ))
                {
					include_once('conn.php');
                  		$str1 = $keyword;
						$arr = array();
						for($i = 0, $len = strlen($str1); $i < $len; ++$i) {
					  if(!count($arr) || $arr[count($arr) - 1] !== ' ' || $str1{$i} !== ' ') {
						$arr[] = $str1{$i};
						 }    
						}   
						$keyword= implode('', $arr);
					  
					  $myarr_str=explode(" ",$keyword);//关键词的分割，以空格分割出来
					  $keyword=$myarr_str[0];//得到分割后的第一个数组
					  //==============================================================================
						switch($keyword){
						case '一站到底':
                         $sql2="select `name` from `user` where `openid`='{$openid}'" ;
							$query2=mysql_query($sql2);
							$rs= mysql_num_rows($query2);
							$rs_name= mysql_fetch_array($query2);
							if(!empty($rs)&&$rs==1){
								$sql_q="SELECT * FROM `question_bank` ORDER BY RAND() LIMIT 1";
								$query_q=mysql_query($sql_q);
								$rs_q=mysql_fetch_array($query_q);
								$sql_u="SELECT * FROM `question_user` WHERE `openid`='{$openid}'";
								$query_u=mysql_query($sql_u);
								$rs_num_u=mysql_num_rows($query_u);
								if($rs_num_u==0||empty($rs_num_u)){
									$sql_into_u="INSERT INTO `question_user`(`id`, `openid`, `qid`, `reply_num`, `right_num`, `score`, `lastdate`) VALUES (null,'{$openid}','{$rs_q[id]}','1','0','0',now())";
									$query_into_u=mysql_query($sql_into_u);
								
								}else{
                                  $rs_u=mysql_fetch_array($query_u);
                                  
									if($rs_u[lastdate]<$date){
									$date=date('Y-m-d H:i:s',time());
										$sql_up_u="UPDATE `question_user` SET `qid`='{$rs_q[id]}',`reply_num`='0',`right_num`='0',`lastdate`='{$date}' WHERE `openid`='{$openid}'";
										$query_up_u=mysql_query($sql_up_u);
									
									}else{
                                      if($rs_u[reply_num]>=10){
                                      	$str="你今天已经回答了10道题了，请明天再来哦！";
                                        break;
                                      }else{
                                       $reply_num=$rs_u[reply_num]+1; 
									   $date=date('Y-m-d H:i:s',time());
										$sql_up_u="UPDATE `question_user` SET `qid`='{$rs_q[id]}',`reply_num`='{$reply_num}',`lastdate`='{$date}' WHERE `openid`='{$openid}'";
										$query_up_u=mysql_query($sql_up_u);
                                      }
									}
								
								}
								switch($rs_q[option_num]){
									case '2':
										$str="问题：\n{$rs_q[question]}\n选项：\nA、{$rs_q[optionA]} B、{$rs_q[optionB]}";
									break;
									case '3':
									$str="问题：\n{$rs_q[question]}\n选项：\nA、{$rs_q[optionA]} B、{$rs_q[optionB]} C、{$rs_q[optionC]}";
									break;
									case '4':
									$str="问题：\n{$rs_q[question]}\n选项：\nA、{$rs_q[optionA]} B、{$rs_q[optionB]} C、{$rs_q[optionC]} D、{$rs_q[optionD]}";
									break;
									case '5':
									$str="问题：\n{$rs_q[question]}\n选项：\nA、{$rs_q[optionA]} B、{$rs_q[optionB]} C、{$rs_q[optionC]} D、{$rs_q[optionD]} E、{$rs_q[optionE]}";
									break;
									case '6':
									$str="问题：\n{$rs_q[question]}\n选项：\nA、{$rs_q[optionA]} B、{$rs_q[optionB]} C、{$rs_q[optionC]} D、{$rs_q[optionD]} E、{$rs_q[optionE]} F、{$rs_q[optionF]}";
									break;
								
								}
                         
                          $str="分值为：".$rs_q[figure]."分的".$str."\n———————————-——\n回答请输入【答案 X】如【答案 A】或【答案 ACD】！跳过请再输入【一站到底】";
								}else{
									$str="请输入【绑定 姓名】进行绑定，如：【绑定 张三】。一定要在中间加空格哦！绑定能参与积分排名哦！";
								
								}
						break;
						case '答案':
							$num_a=count($myarr_str);
							if($num_a==2){
							$sql_u="SELECT * FROM `question_user` WHERE `openid`='{$openid}'";
							$query_u=mysql_query($sql_u);
							$rs_num_u=mysql_num_rows($query_u);
                               $rs_u=mysql_fetch_array($query_u);
							if($rs_num_u==0||empty($rs_num_u)||$rs_u[qid]==0){
								$str="亲，你还没有拿到题目哦！请先输入【一站到底】";
							}else{
							$preg = '[AaBbCcDdEeFf]+';
							 if(preg_match("/$preg/",$myarr_str[1])){
							$myarr_str[1]=strtoupper($myarr_str[1]);
                            $sql="SELECT * FROM `question_user` WHERE `openid`='{$openid}'";
								  $sql="SELECT * FROM `question_user` WHERE `openid`='{$openid}'";
								  $query=mysql_query($sql);
								  $rs=mysql_fetch_array($query);
								  $starr=strtotime($rs[lastdate]);
								  $etarr=time();
								  $t_num=$etarr-$starr;
								  if($t_num<=60){
								$sql_a="SELECT * FROM `question_bank` WHERE `id`='{$rs_u[qid]}'";
								$query_a=mysql_query($sql_a);
								$rs_a=mysql_fetch_array($query_a);
								$an_arr=str_split($rs_a[answer]);
								for($i=0;$i<$rs_a[option_num];$i++){
									if($an_arr[$i]==1){
										switch($i){
											case '0':
											$an.=A;
											break;
											case '1':
											$an.=B;
											break;
											case '2':
											$an.=C;
											break;
											case '3':
											$an.=D;
											break;
											case '4':
											$an.=E;
											break;
											case '5':
											$an.=F;
											break;
											
										}
										
									}
								
								}
								
								if($myarr_str[1]==$an){
									
									$right_num=$rs_u[right_num]+1;
									$score=$rs_u[score]+$rs_a[figure];
									$sql_up_u="UPDATE `question_user` SET `qid`='',`right_num`='{$right_num}',`score`='{$score}',`lastdate`=now() WHERE `openid`='{$openid}'";
									$query_up_u=mysql_query($sql_up_u);
									$sql_sco="SELECT * FROM `user` WHERE `openid`='{$openid}'";
									$query_sco=mysql_query($sql_sco);
									$rs_sco=mysql_fetch_array($query_sco);
									$score=$rs_a[figure]+$rs_sco[integral];
									$sql_up_tu="UPDATE `user` SET `integral`='{$score}' WHERE `openid`='{$openid}'";
									$query_up_tu=mysql_query($sql_up_tu);
                                  $str_r="恭喜你！猜对了！加".$rs_a[figure]."分，你现在的分数为：".$score;
								   //==================
								$sql_q="SELECT * FROM `question_bank` ORDER BY RAND() LIMIT 1";
								$query_q=mysql_query($sql_q);
								$rs_q=mysql_fetch_array($query_q);
								$sql_u="SELECT * FROM `question_user` WHERE `openid`='{$openid}'";
								$query_u=mysql_query($sql_u);
								$rs_u=mysql_fetch_array($query_u);
								 if($rs_u[reply_num]>=10){
                                      	$str=$str_r."你今天已经回答了10道题了，请明天再来哦！你目前的得分是{$rs_sco[integral]}分!";
                                        break;
                                      }else{
									   $reply_num=$rs_u[reply_num]+1; 
									   $date=date('Y-m-d H:i:s',time());
										$sql_up_u="UPDATE `question_user` SET `qid`='{$rs_q[id]}',`reply_num`='{$reply_num}',`lastdate`='{$date}' WHERE `openid`='{$openid}'";
										$query_up_u=mysql_query($sql_up_u);
										switch($rs_q[option_num]){
									case '2':
										$str="问题：\n{$rs_q[question]}\n选项：\nA、{$rs_q[optionA]} B、{$rs_q[optionB]}";
									break;
									case '3':
									$str="问题：\n{$rs_q[question]}\n选项：\nA、{$rs_q[optionA]} B、{$rs_q[optionB]} C、{$rs_q[optionC]}";
									break;
									case '4':
									$str="问题：\n{$rs_q[question]}\n选项：\nA、{$rs_q[optionA]} B、{$rs_q[optionB]} C、{$rs_q[optionC]} D、{$rs_q[optionD]}";
									break;
									case '5':
									$str="问题：\n{$rs_q[question]}\n选项：\nA、{$rs_q[optionA]} B、{$rs_q[optionB]} C、{$rs_q[optionC]} D、{$rs_q[optionD]} E、{$rs_q[optionE]}";
									break;
									case '6':
									$str="问题：\n{$rs_q[question]}\n选项：\nA、{$rs_q[optionA]} B、{$rs_q[optionB]} C、{$rs_q[optionC]} D、{$rs_q[optionD]} E、{$rs_q[optionE]} F、{$rs_q[optionF]}";
									break;
								
								}
								   if($rs_u[reply_num]==10){
                                   $str=$str_r;
                                  }else{
								$str=$str_r."\n———————————-——\n"."分值为：".$rs_q[figure]."分的".$str."\n———————————-——\n回答请输入【答案 X】如【答案 A】或【答案 ACD】！请在一分钟内做答，跳过请再输入【一战到底】";
									  
                                   }
									  
									  }
									  
									  
								//=================	  
								
								}else{
								$reply_num=$rs_u[reply_num]+1;
								$sql_up_u="UPDATE `question_user` SET `qid`='',`lastdate`=now() WHERE `openid`='{$openid}'";
									$query_up_u=mysql_query($sql_up_u);
									$sql_sco="SELECT * FROM `user` WHERE `openid`='{$openid}'";
									$query_sco=mysql_query($sql_sco);
									$rs_sco=mysql_fetch_array($query_sco);
                                  $str_r="这题你答错了！正确答案是:{$an}\n你现在的分数为：{$rs_sco[integral]}";
								  	  //==================
								$sql_q="SELECT * FROM `question_bank` ORDER BY RAND() LIMIT 1";
								$query_q=mysql_query($sql_q);
								$rs_q=mysql_fetch_array($query_q);
								$sql_u="SELECT * FROM `question_user` WHERE `openid`='{$openid}'";
								$query_u=mysql_query($sql_u);
								$rs_u=mysql_fetch_array($query_u);
								
								 if($rs_u[reply_num]>=10){
								
                                      	$str=$str_r."你今天已经回答了10道题了，请明天再来哦！你目前的得分是{$rs_sco[integral]}分!";
                                        break;
                                      }else{
									   $reply_num=$rs_u[reply_num]+1; 
									   $date=date('Y-m-d H:i:s',time());
										$sql_up_u="UPDATE `question_user` SET `qid`='{$rs_q[id]}',`reply_num`='{$reply_num}',`lastdate`='{$date}' WHERE `openid`='{$openid}'";
										$query_up_u=mysql_query($sql_up_u);
										switch($rs_q[option_num]){
									case '2':
										$str="问题：\n{$rs_q[question]}\n选项：\nA、{$rs_q[optionA]} B、{$rs_q[optionB]}";
									break;
									case '3':
									$str="问题：\n{$rs_q[question]}\n选项：\nA、{$rs_q[optionA]} B、{$rs_q[optionB]} C、{$rs_q[optionC]}";
									break;
									case '4':
									$str="问题：\n{$rs_q[question]}\n选项：\nA、{$rs_q[optionA]} B、{$rs_q[optionB]} C、{$rs_q[optionC]} D、{$rs_q[optionD]}";
									break;
									case '5':
									$str="问题：\n{$rs_q[question]}\n选项：\nA、{$rs_q[optionA]} B、{$rs_q[optionB]} C、{$rs_q[optionC]} D、{$rs_q[optionD]} E、{$rs_q[optionE]}";
									break;
									case '6':
									$str="问题：\n{$rs_q[question]}\n选项：\nA、{$rs_q[optionA]} B、{$rs_q[optionB]} C、{$rs_q[optionC]} D、{$rs_q[optionD]} E、{$rs_q[optionE]} F、{$rs_q[optionF]}";
									break;
								
								}
                                   if($rs_u[reply_num]==10){
                                   $str=$str_r;
                                   }else{
								$str=$str_r."\n———————————-——\n"."分值为：".$rs_q[figure]."分的".$str."\n———————————-——\n回答请输入【答案 X】如【答案 A】或【答案 ACD】！请在一分钟内做答，跳过请再输入【一战到底】";
									  
                                   }
									  }
									  
									  
								//=================	
								}
								 }else{
								  $reply_num=$rs_u[reply_num]+1;
								$sql_up_u="UPDATE `question_user` SET `qid`='',`lastdate`=now() WHERE `openid`='{$openid}'";
									$query_up_u=mysql_query($sql_up_u);
									$query_up_u=mysql_query($sql_up_u);
									$sql_sco="SELECT * FROM `user` WHERE `openid`='{$openid}'";
									$query_sco=mysql_query($sql_sco);
									$rs_sco=mysql_fetch_array($query_sco);
								  $str_r="你已经超时，进入下一题";
								   	  //==================
								$sql_q="SELECT * FROM `question_bank` ORDER BY RAND() LIMIT 1";
								$query_q=mysql_query($sql_q);
								$rs_q=mysql_fetch_array($query_q);
								$sql_u="SELECT * FROM `question_user` WHERE `openid`='{$openid}'";
								$query_u=mysql_query($sql_u);
								$rs_u=mysql_fetch_array($query_u);
								 if($rs_u[reply_num]>=10){
                                      	$str="你今天已经回答了10道题了，请明天再来哦！你目前的得分是{$rs_sco[integral]}分!";
                                        break;
                                      }else{
									   $reply_num=$rs_u[reply_num]+1; 
									   $date=date('Y-m-d H:i:s',time());
										$sql_up_u="UPDATE `question_user` SET `qid`='{$rs_q[id]}',`reply_num`='{$reply_num}',`lastdate`='{$date}' WHERE `openid`='{$openid}'";
										$query_up_u=mysql_query($sql_up_u);
										switch($rs_q[option_num]){
									case '2':
										$str="问题：\n{$rs_q[question]}\n选项：\nA、{$rs_q[optionA]} B、{$rs_q[optionB]}";
									break;
									case '3':
									$str="问题：\n{$rs_q[question]}\n选项：\nA、{$rs_q[optionA]} B、{$rs_q[optionB]} C、{$rs_q[optionC]}";
									break;
									case '4':
									$str="问题：\n{$rs_q[question]}\n选项：\nA、{$rs_q[optionA]} B、{$rs_q[optionB]} C、{$rs_q[optionC]} D、{$rs_q[optionD]}";
									break;
									case '5':
									$str="问题：\n{$rs_q[question]}\n选项：\nA、{$rs_q[optionA]} B、{$rs_q[optionB]} C、{$rs_q[optionC]} D、{$rs_q[optionD]} E、{$rs_q[optionE]}";
									break;
									case '6':
									$str="问题：\n{$rs_q[question]}\n选项：\nA、{$rs_q[optionA]} B、{$rs_q[optionB]} C、{$rs_q[optionC]} D、{$rs_q[optionD]} E、{$rs_q[optionE]} F、{$rs_q[optionF]}";
									break;
								
								}
								
								$str=$str_r."\n———————————-——\n"."分值为：".$rs_q[figure]."分的".$str."\n———————————-——\n回答请输入【答案 X】如【答案 A】或【答案 ACD】！请在一分钟内做答，跳过请再输入【一战到底】";
									  
									  
									  }
									  
									  
								//=================	
								  }
							}else{
								  $str="你输入的是非法字符，请重新输入";
						   }
							
							}//===
							
							
							}else{
								$str="你的输入有误，请重新输入，格式【答案 A】或【答案 ACD】";
							
							}
						
						break;
					
						
						
						}
					  				  
					  //==============================================================================
              		$msgType = "text";
                	$contentStr =$str;
                	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                	echo $resultStr;
                }else{
                	echo "Input something...";
                }

        }else {
        	echo "";
        	exit;
        }
    }
		
	private function checkSignature()
	{
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];	
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
}

?>