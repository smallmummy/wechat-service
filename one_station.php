<?php


require('cls_member.php');
global $member;
$member=new cls_member(true);
//参与活动需要扣除的互动点数,必须为负数
global $gl_act_point;
$gl_act_point=-1;
//回答问题的时间限制，超出则为超时
$gl_answer_gap=45;
//用于再次调用"一站到底"的关键字
global $from;
$from = $member->str_post_fakeid;

//查询是否已经是会员;
$re_get_member=$member->get_member_info($member->str_post_fakeid);
if (!isset($re_get_member))
{
	$member->close_db();
	//不是会员
	exit("您好,您目前还不是我们的会员,不能参加此活动,请输入\"会员绑定   你的昵称\"来手动加入会员,详情可以输入\"会员\"来查询\n\n");
}

//到此步骤,则判断是会员, 可以进行后续动作


//判断关键字,是要出题,还是检查答案
switch($member->str_post_key)
{
	case '一站到底':
	case '一站到底':
		throw_question();
		break;
	case 'a':
	case 'A':
	case 'b':
	case 'B':
	case 'c':
	case 'C':
	case 'd':
	case 'D':
	case 'e':
	case 'E':
	case 'f':
	case 'F':
		check_answer();
		break;
	case '一站到底排行榜';
		check_board();
		break;
	default:
		exit("one_station_main_default_error");
								
}


//出题的过程
function throw_question()
{
	global $member;
	global $gl_act_point;
	global $gl_answer_gap;
	global $from;
	
	//随机挑选一道题目
	$tmp_question_row=$member->query_question_bank_random();
	//判断是否数据为空
	if (!isset($tmp_question_row))
	{
		$member->close_db();
		$tmp_re="随机挑选题库数据失败,请联系老板处理";
		exit($tmp_re);
	}
		
	//成功挑选一道题目数据,将题目数据提取处理
	$tmp_question_id=$tmp_question_row['id'];
	$tmp_question_figure=$tmp_question_row['figure'];
	$tmp_question_question_types=$tmp_question_row['question_types'];
	$tmp_question_question=$tmp_question_row['question'];
	$tmp_question_option_num=$tmp_question_row['option_num'];
	$tmp_question_optionA=$tmp_question_row['optionA'];
	$tmp_question_optionB=$tmp_question_row['optionB'];
	$tmp_question_optionC=$tmp_question_row['optionC'];
	$tmp_question_optionD=$tmp_question_row['optionD'];
	$tmp_question_optionE=$tmp_question_row['optionE'];
	$tmp_question_optionF=$tmp_question_row['optionF'];
	$tmp_question_answer=$tmp_question_row['answer'];
	$tmp_question_classify=$tmp_question_row['classify'];	
	
	
	
	//查找用户的答题的历史记录
	$tmp_question_user_row=$member->query_question_user();
	//判断是否数据为空,如果为空,表示用户是第一次参加活动,全部变量初始化
	if (!isset($tmp_question_user_row))
	{
		//插入用户的答题的历史记录数据
		$tmp_question_user_row['openid']=$member->str_post_fakeid;
		$tmp_question_user_row['qid']=$tmp_question_id;
		//用户尝试答题的累计数量
		$tmp_question_user_row['reply_num']=0;
		$tmp_question_user_row['right_num']=0;
		$tmp_question_user_row['score']=0;
		//系统当前时间戳,系统时区+8, 印尼时间-1个小时
		$tmp_current_timestamp=date("Y-m-d H:i:s",time()-3600);
		$tmp_question_user_row['lastdate']=$tmp_current_timestamp;	

		
		//调用过程,将数据插入数据库
		$temp_insert=$member->insert_question_user($tmp_question_user_row);
		//判断插入结果
		if ($temp_insert!=0)
		{
			$member->close_db();
			$tmp_re="插入用户答题历史表失败, 请联系老板处理,具体的错误信息为{$member->str_sql_errmsg}|{$member->str_exe_sql}";
			exit($tmp_re);
		}
		
	}

	


	//判断用户是刚开始参加,还是上一道问题回答正确的继续出题流程	
	switch($tmp_question_user_row['right_num'])
	{
		case -2://上一次用户回答超时
			$tmp_question_user_row['right_num']=0;	
			//重新开始游戏,需要扣除积分
			$need_dedcut=1;		
			break;
		case -1://上一次用户回答错误
			$tmp_question_user_row['right_num']=0;
			//重新开始游戏,需要扣除积分
			$need_dedcut=1;
			break;	
		case 0://用户刚开始参加活动,根据情况提示信息
			//重新开始游戏,需要扣除积分
			$need_dedcut=1;
			break;
		default://用户上一次回答正确,继续答题
			//继续答题,不需要再扣除积分
			$need_dedcut=0;
			break;	
	}
	
	//需要扣除积分
	if ($need_dedcut==1)
	{
		$tmp_return_add_bonus=$member->add_bonus_by_activity(2,0,$gl_act_point,"参加游戏一站到底,扣除互动积分{$gl_act_point}点");
		//判断结果
		switch($tmp_return_add_bonus[0])
		{
			case 2://2:执行sql错误
				$member->close_db();
				$tmp_re="参与游戏游戏一站到底,扣除互动积分失败, 请联系老板处理,具体的错误信息为$member->str_sql_errmsg|$member->str_exe_sql";
				exit($tmp_re);
				break;
			case 0://0:更新成功,直接跳出,进行后续处理
				break;
			default:
				exit('daily_sign-tmp_return_add_bonus未知错误');
		}
		
		echo "[系统提示]已经成功扣除会员互动积分1点, 现在开始游戏\n\n";
	}
	


	
	
	
	
	
	
	//插入用户的答题的历史记录数据
	$tmp_question_user_row['openid']=$member->str_post_fakeid;
	$tmp_question_user_row['qid']=$tmp_question_id;
	
	//用户尝试答题的累计数量增加1
	$tmp_question_user_row['reply_num']+=1;
	//系统当前时间戳,系统时区+8, 印尼时间-1个小时
	$tmp_current_timestamp=date("Y-m-d H:i:s",time()-3600);
	$tmp_question_user_row['lastdate']=$tmp_current_timestamp;
	
	//调用过程,更新用户答题的历史记录
	$temp_update=$member->update_question_user($tmp_question_user_row);
	//判断插入结果
	if ($temp_update!=0)
	{
		$member->close_db();
		$tmp_re="更新用户答题历史表失败, 请联系老板处理,具体的错误信息为{$member->str_sql_errmsg}|{$member->str_exe_sql}";
		exit($tmp_re);
	}
	
	
	//拼凑问题头,第几关
	$stage_num=$tmp_question_user_row['right_num']+1;
	$str="第【'{$stage_num}'】关\n";
	
	//拼凑问题内容以及选项
	switch($tmp_question_option_num)
	{
		case '2':
			$str.="问题：\n{$tmp_question_row['question']}\n选项：\nA、{$tmp_question_row['optionA']} B、{$tmp_question_row['optionB']}";
			break;
		case '3':
			$str.="问题：\n{$tmp_question_row['question']}\n选项：\nA、{$tmp_question_row['optionA']} B、{$tmp_question_row['optionB']} C、{$tmp_question_row['optionC']}";
			break;
		case '4':
			$str.="问题：\n{$tmp_question_row['question']}\n选项：\nA、{$tmp_question_row['optionA']} B、{$tmp_question_row['optionB']} C、{$tmp_question_row['optionC']} D、{$tmp_question_row['optionD']}";
			break;
		case '5':
			$str.="问题：\n{$tmp_question_row['question']}\n选项：\nA、{$tmp_question_row['optionA']} B、{$tmp_question_row['optionB']} C、{$tmp_question_row['optionC']} D、{$tmp_question_row['optionD']} E、{$tmp_question_row['optionE']}";
			break;
		case '6':
			$str.="问题：\n{$tmp_question_row['question']}\n选项：\nA、{$tmp_question_row['optionA']} B、{$tmp_question_row['optionB']} C、{$tmp_question_row['optionC']} D、{$tmp_question_row['optionD']} E、{$tmp_question_row['optionE']} F、{$tmp_question_row['optionF']}";
			break;
		default:
			exit("one_station_tmp_question_option_num_default_error");
			break;
	}
	
	$str=$str."\n———————————-——\n回答请直接输入答案的编号，如A或B，或者ACD（如果是多选题的话），大小写都可以";
	
	$member->close_db();
	exit($str);

	
}


//检查答案的过程
function check_answer()
{
	
	global $member;
	global $gl_act_point;
	global $gl_answer_gap;
	global $from;
	
	//本次闯关数,在回答超时或者回答错误的时候,会更新此变量
	$score_this_time=0;
	
	//查找用户的答题的历史记录
	$tmp_question_user_row=$member->query_question_user();
	//判断是否数据为空,如果为空,表示用户还没有拿到题
	if (!isset($tmp_question_user_row))
	{
		$member->close_db();
		$str="你还没有拿到题目哦, 请输入【一站到底】开始你的挑战";
		exit($str);
	}
		
	
	//每次回答错误的时候，会把这个标志位置为-1，以表明本次回答错误
	if ($tmp_question_user_row['right_num']==-1)
	{
		$member->close_db();
		$str="你还没有拿到题目哦, 请输入【一站到底】开始你的挑战";
		exit($str);
	}
	
	
	//系统当前时间戳,系统时区+8, 印尼时间-1个小时
	$tmp_current_timestamp=date("Y-m-d H:i:s",time()-3600);
	//用现在的时间减去上一次拿到题目的时间，计算之间的间隔
	$answer_gap=strtotime($tmp_current_timestamp)-strtotime($tmp_question_user_row['lastdate']);
	
	//判断回答问题是否为超时
	if ($answer_gap>$gl_answer_gap)
	{
		//大于门限值，则为超时
		
		//存放此次挑战的成绩
		$score_this_time=$tmp_question_user_row['right_num'];
		//超时，将标志位置为-2
		$tmp_question_user_row['right_num']=-2;
		//拼凑返回信息
		$str_reply="回答问题超时，每个问题你只有{$gl_answer_gap}秒的思考时间哦~,本次答题终止,可输入\"一站到底\"再次挑战!";
		
		
		
	}
	else //在时间的限制之内， 判断结果是否正确	
	{
		
		//根据用户的上次拿到的题目，查找题目的具体信息
		$tmp_question_row=$member->query_question_bank_byID($tmp_question_user_row['qid']);
		//判断是否数据为空
		if (!isset($tmp_question_row))
		{
			$member->close_db();
			$tmp_re="根据题目ID查找题库数据失败,请联系老板处理";
			exit($tmp_re);
		}
		
		//成功挑选一道题目数据,将题目数据提取处理
		$tmp_question_id=$tmp_question_row['id'];
		$tmp_question_figure=$tmp_question_row['figure'];
		$tmp_question_question_types=$tmp_question_row['question_types'];
		$tmp_question_question=$tmp_question_row['question'];
		$tmp_question_option_num=$tmp_question_row['option_num'];
		$tmp_question_optionA=$tmp_question_row['optionA'];
		$tmp_question_optionB=$tmp_question_row['optionB'];
		$tmp_question_optionC=$tmp_question_row['optionC'];
		$tmp_question_optionD=$tmp_question_row['optionD'];
		$tmp_question_optionE=$tmp_question_row['optionE'];
		$tmp_question_optionF=$tmp_question_row['optionF'];
		$tmp_question_answer=$tmp_question_row['answer'];
		$tmp_question_classify=$tmp_question_row['classify'];
		
		
		
		//正确答案，将正确答案放入数组中，用于拼接正确答案标志
		$answer_arr=str_split($tmp_question_answer);
		$correct_answer="";
		//拼接正确的答案
		for($i=0;$i<$tmp_question_option_num;$i++)
		{
			if($answer_arr[$i]==1)
			{
				switch($i)	
				{
					case '0':
						$correct_answer.='A';
						break;
					case '1':
						$correct_answer.='B';
						break;
					case '2':
						$correct_answer.='C';
						break;
					case '3':
						$correct_answer.='D';
						break;
					case '4':
						$correct_answer.='E';
						break;
					case '5':
						$correct_answer.='F';
						break;
				}
		
			}
		
		}
		
		//判断答案是否正确
		if(strtolower($member->str_post_key)==strtolower($correct_answer))
		{
			//答案正确的处理流程
			
			
			//用户连续回答问题的数目加1
			$tmp_question_user_row['right_num']+=1;
			$str_reply="恭喜你，回答正确,进入一下关!\n\n";
		}
		else
		{
			//用户回答错误
			
			//存放此次挑战的成绩
			$score_this_time=$tmp_question_user_row['right_num'];
			//将标志位置为-1
			$tmp_question_user_row['right_num']=-1;		
			$str_reply="回答错误哦,本次答题终止,可输入\"一站到底\"再次挑战!";
		}
		
		
	}
		
	
	//判断此次成绩和历史最好成绩, 取较大者,更新入历史最好成绩
	$tmp_question_user_row['score']=max($tmp_question_user_row['score'],$score_this_time);
	
	//调用过程,更新用户答题的历史记录
	$temp_update=$member->update_question_user($tmp_question_user_row);
	//判断插入结果
	if ($temp_update!=0)
	{
		$member->close_db();
		$tmp_re="更新用户答题历史表失败, 请联系老板处理,具体的错误信息为{$member->str_sql_errmsg}|{$member->str_exe_sql}";
		exit($tmp_re);
	}
	
	
	
	//判断用户回答问题正确与否,处理后续流程
	switch($tmp_question_user_row['right_num'])
	{
		case -2://用户回答超时
			$member->close_db();
			exit($str_reply);
			break;
		case -1://上一次用户回答错误
			$member->close_db();
			exit($str_reply);
			break;
		case 0://不可能出现的情况
			exit("错误!right_num为0, 请联系老板处理");
			break;
		default://用户上一次回答正确,继续答题
			$member->close_db();
			echo($str_reply);
			$tmp_reply = xiaojo("一站到底",$from);
			echo $tmp_reply;
			break;
	}

	
}



//查询排行榜
function check_board()
{

	global $member;
	
	
	//查找数据库中的排行信息
	$re_get_board=$member->query_question_user_board();
	if (!isset($re_get_board))
	{
		$member->close_db();
		//不是会员
		exit("貌似目前还没有人玩这个游戏");
	}
	

	/*返回的数据表的字段为:
	 member_no
	weixin_id
	openid
	reply_num
	score
	*/
	
	
	//用于存放第几名
	$i=0;
	//用于判断是否是并且的排行,如果上次的score和本次的score是一样的,则为并列排行
	$last_score=-27;
	//返回信息的头
	$tmp_re="一站到底英雄排行榜\n———————————-——\n";
	foreach ($re_get_board as $tmp_row)
	{
		//和上一条记录中的score不一样
		if ($tmp_row['score']!=$last_score)
		{
			//不是并列排行
			$i+=1;
			$last_score=$tmp_row['score'];
		}
		$tmp_re.="第'{$i}名:'{$tmp_row['weixin_id']}'(会员ID:'{$tmp_row['member_no']}'),连续闯关数:'{$tmp_row['score']}',累计尝试答题次数:'{$tmp_row['reply_num']}'\n";
		
		//判断用户处于第几位
		if ($tmp_row['openid']==$member->str_post_fakeid)
		{
			//用户榜上有名
			$tmp_re_post="———————————-——\n恭喜你,你榜上有名哦,你目前的排名为:第【'{$i}'】名";
		}
		

	}
	
	$member->close_db();
	exit($tmp_re.$tmp_re_post);
	
	
	

}




function xiaojo($key,$from) //小九接口函数，该函数可通用于其他程序
{
	$yourdb = "xxxxxx";//小九账号
	$yourpw = "xxxxx";//小九密码
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