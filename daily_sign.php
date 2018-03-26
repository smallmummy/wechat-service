<?php



//奖励给用户的互动点数, 根据连续签到天数而定
$amt_add_act_bonus = array(
		1=>10,
		2=>10,
		3=>12,
		4=>12,
		5=>15,
		6=>15,
		7=>66,
		8=>18,
		9=>20,
		10=>20,
		11=>20,
		12=>20,
		13=>25,
		14=>66,
		15=>30,
		16=>30,
		17=>35,
		18=>35,
		19=>35,
		20=>40,
		21=>40,
		22=>50,
		23=>50,
		24=>50,
		25=>50,
		26=>66,
		27=>66,
		28=>66,
		29=>66,
		30=>66,
		31=>66,
		32=>66,
		33=>66,
		34=>75,
		35=>75,
		36=>75,
		37=>75,
		38=>75,
		39=>88,
		40=>88,
		41=>88,
		42=>88,
		43=>88,
		44=>88,
		45=>99,
		46=>99,
		47=>99,
		48=>99,
		49=>99,
		50=>99,
		51=>99,
		52=>99,
		53=>99,
		54=>99,
		55=>99,
		56=>99,
		57=>99,
		58=>99,
		59=>99,
		60=>99,
		61=>99,
		62=>99,
		63=>166,
		64=>166,
		65=>166,
		66=>166,
		67=>166,
		68=>166,
		69=>188,
		70=>188,
		71=>188,
		72=>188,
		73=>188,
		74=>188,
		75=>188,
		76=>188,
		77=>188,
		78=>188,
		79=>188
);


//根据连续签到天数拼凑的返回信息
$return_info = array(
		1=>"欢迎来签到,很准时的第一天签到,请继续坚持",
		2=>"不错呦,第二天了哦",
		3=>"还不错,你已经连续坚持了3天了,加油",
		4=>"再坚持一天,你会发现奖励的不一样了哦",
		5=>"第五天了, 我兑现了我的承诺,增加了一点奖励哦",
		6=>"马上就连续签到一个星期了呀, 额~~如果明天你还来的话,我想我会多你一点的哦",
		7=>"恭喜你已经连续签到了一个星期, 那就多奖励你一点吧,不过好运不是每天有的哦",
		8=>"你是一个很有毅力的人, 竟然还可以坚持, 不过我估计也坚持不了多久了,呵呵",
		9=>"还在坚持?那就再多给一点互动积分奖励吧",
		10=>"我想这是第十天了吧,真不错,你已经连续签到了10天",
		11=>"我觉得你可以去参加马拉松了,真能坚持",
		12=>"你的名字里面应该有个毅字吧?这么有毅力....",
		13=>"今天是第13天,还有一次就是2个星期了,我在想是不是要再多给你一点..... ",
		14=>"连续签到两个星期整,那就再给你一个六六大顺吧,祝你一切顺顺顺 ",
		15=>"两个星期多一个了, 我不知道应该怎么形容你的毅力了...."
);


require('cls_member.php');

global $member;
$member=new cls_member(true);

$tmp_row=$member->get_member_info($member->str_post_fakeid);
if (!isset($tmp_row))
{
	$member->close_db();
	$tmp_re="description|#title|对不起,您还不是会员,不能参加该活动#pic|#url|@title|      发送\"会员绑定 你的昵称\", 比如发送\"会员绑定 小程\",就可以立刻成为会员.\n     会员将享受消费积分累计,现金返现等优惠!#description|#pic|#url|";
	exit($tmp_re);
}
else
{
	$tmp_member_no=$tmp_row['member_no'];
	$tmp_weixin_id=$tmp_row['weixin_id'];
	$tmp_join_date=$tmp_row['join_date'];
	$tmp_bonus=$tmp_row['bonus'];
	$tmp_act_bonus=$tmp_row['act_bonus'];
	$tmp_fake_id=$tmp_row['fake_id'];
	$tmp_suggest_mem_no=$tmp_row['suggest_mem_no'];
	$tmp_last_sign_date=$tmp_row['last_sign_date'];
	$tmp_continue_sign_days=$tmp_row['continue_sign_days'];	

		
	
	//判断当天是否已经签到
	
	//系统当前日期部分
	$tmp_current_date=date("Y-m-d");
	//系统当前时间戳,系统时区+8, 印尼时间-1个小时
	//$tmp_current_timestamp=date("Y-m-d H:i:s",time());	
	$tmp_current_timestamp=date("Y-m-d H:i:s",time()-3600);

	
	//上一次签到时间戳的日期部分
	$tmp_last_sign=date("Y-m-d",strtotime($tmp_last_sign_date));
	//判断是否一致
	if ($tmp_current_date == $tmp_last_sign)
	{
		//日期一致,当天已经签到完成
		exit("不用重复签到了哦,今天已经签到过了,互动积分已经到账了,赶紧去参加其他活动吧,有机会获得会员消费积分哦");
	}
	else
	{
		//当天还未签到, 进行处理
		//判断是否是连续签到
		//当前时间戳减去一天,是否于上次签到时间相同
		if (date("Y-m-d",time()-3600*24) == $tmp_last_sign)
		{
			//相同, 则为连续签到,累计连续签到次数
			$tmp_continue_sign_days_add_one=$tmp_continue_sign_days+1;
		}
		else 
		{
			//不相同, 则不是连续签到, 连续签到天数为1
			$tmp_continue_sign_days_add_one=1;
		}
		
	
		
		//更改当天时间戳

		$tmp_return=$member->update_sign_info($tmp_current_timestamp,$tmp_continue_sign_days_add_one);
		
		switch($tmp_return[0])
		{
			case 2://2:执行sql错误
				$member->close_db();
				$tmp_re="description|#title|\"每日签到\"功能失败!#pic|#url|@title|      会员推荐人功能错误, 请联系老板处理,具体的错误信息为$member->str_sql_errmsg|$member->str_exe_sql#description|#pic|#url|";
				exit($tmp_re);
				break;
			case 0://0:更新成功
				//更新成功的后续逻辑: 给用户奖励, 拼凑返回信息
				
				$tmp_display="description|#title|每日签到: 第 $tmp_continue_sign_days_add_one 天#pic|#url|@title|";
				echo $amt_add_act_bonus[1];
				echo $amt_add_act_bonus[$tmp_continue_sign_days_add_one];
				//奖励用户的互动积分
				$tmp_return_add_bonus=$member->add_bonus_by_activity(4,0,$amt_add_act_bonus[$tmp_continue_sign_days_add_one],"用户于$tmp_current_date 连续第 $tmp_continue_sign_days_add_one 天签到,上一次签到时间为$tmp_last_sign_date. 奖励用户互动积分$amt_add_act_bonus[$tmp_continue_sign_days_add_one]点");
				//判断结果
				switch($tmp_return_add_bonus[0])
				{
					case 2://2:执行sql错误
						$member->close_db();
						$tmp_re="每日签到时奖励会员积分错误, 请联系老板处理,具体的错误信息为$member->str_sql_errmsg|$member->str_exe_sql";
						exit($tmp_re);
						break;
					case 0://0:更新成功,直接跳出,进行后续处理
						break;
					default:
						exit('daily_sign-tmp_return_add_bonus未知错误');
				}
				
				//奖励互动积分已经成功, 进行后续处理
				//拼凑返回信息
				$tmp_display=$tmp_display."       $return_info[$tmp_continue_sign_days_add_one]\n\n";
				$tmp_display=$tmp_display."       [系统提示]今天是你连续第 $tmp_continue_sign_days_add_one 天签到,上一次签到时间为$tmp_last_sign_date. 本次签到奖励用户互动积分$amt_add_act_bonus[$tmp_continue_sign_days_add_one]点, 用互动积分赶紧去参加我们的其他活动吧";
				$member->close_db();
				
				
				
				
				//判断是否已经绑定了昵称
				if ((!isset($tmp_weixin_id)) || ($tmp_weixin_id==""))
				{//会员的昵称为空
					$tmp_display=$tmp_display."\n\n       [温馨提示]您现在的昵称还为空哦,为了方便我们称呼您,请输入\"会员绑定     你的昵称\"来绑定您的昵称";
				}
					
				
				
				$tmp_display=$tmp_display.'#description|#pic|#url|';
				exit($tmp_display);
				break;
			default:
				exit('daily_sign-tmp_return未知错误');
		}
		
	}
	
	
	
	
		
}




?>