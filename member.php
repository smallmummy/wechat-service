<?php

	//会员说明字符串
	global $str_err_readme;
	$str_err_readme=<<<TEXT
正确格式说明:
>>输入"会员绑定  你的名字",即可加入会员或者修改你的会员名字,比如"会员绑定 小程";
			
>>输入"会员查询",可以查询你的会员信息， 记住你的会员ID， 推荐朋友加入，你将获得会员积分哦；
			
>>输入"会员记录",可以查询你最后10次的累计消费记录;
			
TEXT;
	include_once 'diff_platform.php';
	require('cls_member.php');

	global $member;
	$member=new cls_member(true);

	///////////会员绑定
	if ((preg_match('/^会员绑定/',$member->str_post_key,$temp)) or (preg_match('/^會員綁定/',$member->str_post_key,$temp)))
	{
		//关键字只有'会员绑定',缺少名字
		if ((preg_match('/^会员绑定$/',$member->str_post_key,$temp)) or (preg_match('/^會員綁定$/',$member->str_post_key,$temp)))
		{
			
			$arr_news_para['ArticleCount']=2;
			$arr_news_para['Description1']="";
			$arr_news_para['Title1']="格式错误";
			$arr_news_para['PicUrl1']="";
			$arr_news_para['Url1']="";
			
			$arr_news_para['Description2']="";
			$arr_news_para['Title2']="     缺少你的名字,不能绑定.正确的格式是\"会员绑定 你的名字\", 比如发送\"会员绑定 小程\"";
			$arr_news_para['PicUrl2']="";
			$arr_news_para['Url2']="";
				
			$tmp_re=combine_msg_news($arr_news_para);
			exit($tmp_re);
		}
		//正确的格式: 会员绑定[名字]
		elseif ((preg_match('/^会员绑定[\s]*\S+$/',$member->str_post_key,$temp)) or (preg_match('/^會員綁定[\s]*\S+$/',$member->str_post_key,$temp)))
		{
			/***********************************条件校验后进入会员绑定的流程****************************************/
			bind_member();
			/***********************************会员绑定的结束****************************************/
		}
		else
		{
			$arr_news_para['ArticleCount']=2;
			$arr_news_para['Description1']="";
			$arr_news_para['Title1']="格式错误";
			$arr_news_para['PicUrl1']="";
			$arr_news_para['Url1']="";
			
			$arr_news_para['Description2']="";
			$arr_news_para['Title2']="     缺少你的名字,不能绑定.正确的格式是\"会员绑定 你的名字\", 比如发送\"会员绑定 小程\"";
			$arr_news_para['PicUrl2']="";
			$arr_news_para['Url2']="";
				
			$tmp_re=combine_msg_news($arr_news_para);
			exit($tmp_re);
		}
	}
	///////////会员查询
	elseif ((preg_match('/^会员查询/',$member->str_post_key,$temp)) or (preg_match('/^會員查詢/',$member->str_post_key,$temp)) or (preg_match('/^memberinfo/',$member->str_post_key,$temp)) or (preg_match('/^info/',$member->str_post_key,$temp)))
	{	
		/***********************************条件校验后进入会员查询的流程****************************************/
		member_info();
		/***********************************会员查询的结束****************************************/
	}
	/*
	///////////会员消费-----此功能暂时废弃
	elseif ((preg_match('/^会员消费/',$member->str_post_key,$temp)) or (preg_match('/^會員消費/',$member->str_post_key,$temp)))
	{
		//正确的关键词格式
		if ((preg_match('/^会员消费.*\s.*\s.*$/',$member->str_post_key,$temp)) or (preg_match('/^會員消費.*\s.*\s.*$/',$member->str_post_key,$temp)))
		{
			//此为旧的关键词, 取消此功能
			
			
			//字符串分割
			//$arr_temp=preg_split("/ +/",$member->str_post_key);
			//小票单号
			$str_bill_no=strtolower($arr_temp[1]);
			//消费金额
			$str_amt=$arr_temp[2];
			
			if (!(preg_match('/^(zd|ZD|zD|Zd)[0-9]{12}$/',$str_bill_no,$temp)))
			{
				$tmp_re='description|#title|格式错误#pic|#url|@title|%t%t%t%t小票的单号格式错误,应该是zd开头,后面有12位数字,比如zd201306050004.#description|#pic|#url|';
				exit($tmp_re);
			}
			if (!preg_match('/^[0-9]+$/',$str_amt,$temp))
			{
				$tmp_re='description|#title|格式错误#pic|#url|@title|%t%t%t%t消费金额格式错误,应该全部是数字,没有小数点.#description|#pic|#url|';
				exit($tmp_re);
			}
			
			
			
			//函数中的功能和参数已经修改, 不能再被旧的关键词使用
			// ***********************************条件校验后进入会员消费的流程****************************************
			//member_consume($str_bill_no,$str_amt);
			// ***********************************会员消费的结束****************************************
			
			exit("抱歉,旧的会员消费累计功能现在暂停使用, 新的替代功能马上退出, 敬请期待, 谢谢!");
			
			
		}
		else
		{
			$tmp_re='description|#title|格式错误#pic|#url|@title|%t%t%t%t正确的格式是"会员消费  小票单号  消费金额", 比如发送"会员消费  ZD2013061213  120000", 中间有空格的.%n%t%t%t%t小票单号和消费金额可以在结账单上找到.#description|#pic|#url|';
			exit($tmp_re);
		}
	}*/
	///////////会员记录
	elseif ((preg_match('/^会员记录$/',$member->str_post_key,$temp)) or (preg_match('/^會員記錄$/',$member->str_post_key,$temp)))
	{
		/***********************************条件校验后进入会员记录的流程****************************************/
		member_his_query();
		/***********************************会员记录的结束*****************************************/
		//exit('你现在正在使用会员记录功能');
	}
	///////////会员
	elseif ((preg_match('/^会员$/',$member->str_post_key,$temp)) or (preg_match('/^會員$/',$member->str_post_key,$temp)))
	{
		//汇总的会员信息
		/***********************************用户输入"会员"的流程****************************************/
		member_readme();
		/***********************************流程结束****************************************/
	}
	/*
	///////////会员推荐人
	elseif ((preg_match('/^会员推荐人/',$member->str_post_key,$temp)) or (preg_match('/^會員推薦人/',$member->str_post_key,$temp)))
	{
		//关键字只有'会员推荐人',缺少推荐人的会员号码
		if ((preg_match('/^会员推荐人$/',$member->str_post_key,$temp) or preg_match('/^会员推荐人\S+$/',$member->str_post_key,$temp)) or (preg_match('/^會員推薦人$/',$member->str_post_key,$temp) or preg_match('/^會員推薦人\S+$/',$member->str_post_key,$temp)))
		{
			$tmp_re='description|#title|格式错误#pic|#url|@title|%t%t%t%t缺少你的推荐人的会员号码,比如输入"会员推荐人 101"(中间是有空格的哦),其中101是你的推荐人的会员号码,每个人的会员号码可以在"会员查询"中找到.#description|#pic|#url|';
			exit($tmp_re);
		}
		//正确的格式: 会员推荐人 推荐人的会员ID
		elseif((preg_match('/^会员推荐人[\s]*\S+$/',$member->str_post_key,$temp)) or (preg_match('/^會員推薦人[\s]*\S+$/',$member->str_post_key,$temp)))
		{
			// ***********************************条件校验后进入会员推荐人的流程****************************************

			
			member_suggest();
			
			// ***********************************会员推荐人的结束****************************************
		}
		else
		{
			$member->close_db();
			$tmp_re='description|#title|格式错误#pic|#url|@title|%t%t%t%t缺少你的推荐人的会员号码,比如输入"会员推荐人 101"(中间是有空格的哦),其中101是你的推荐人的会员号码,每个人的会员号码可以在"会员查询"中找到.#description|#pic|#url|';
			exit($tmp_re);
		}
		
	}*/
	///////////会员管理,只有管理员组的人才可以进入
	elseif ((preg_match('/^会员管理$/',$member->str_post_key,$temp)) or (preg_match('/^會員管理$/',$member->str_post_key,$temp)))
	{
		
		$temp=$member->check_is_admin();
		
		//判断结果
		switch($temp)
		{
			case 2://2:不在管理员组中,没有权限
				$member->close_db();
				
				$arr_news_para['ArticleCount']=2;
				$arr_news_para['Description1']="";
				$arr_news_para['Title1']="去别处逛逛吧";
				$arr_news_para['PicUrl1']="";
				$arr_news_para['Url1']="";
					
				$arr_news_para['Description2']="";
				$arr_news_para['Title2']="     朋友, 地球太危险了, 还是去别的地方逛逛吧~";
				$arr_news_para['PicUrl2']="";
				$arr_news_para['Url2']="";
				
				$tmp_re=combine_msg_news($arr_news_para);
				exit($tmp_re);
				
				break;
			case 0://0:属于管理员组,有权限, 进入后续处理
				
				
				$tmp_data=$member->query_member_info();
				if (!isset($tmp_data))
				{
					//没有查询到数据,没有用户记录
					$member->close_db();
					$arr_news_para['ArticleCount']=2;
					$arr_news_para['Description1']="";
					$arr_news_para['Title1']="会员记录";
					$arr_news_para['PicUrl1']="";
					$arr_news_para['Url1']="";
						
					$arr_news_para['Description2']="";
					$arr_news_para['Title2']="     目前暂无任何用户";
					$arr_news_para['PicUrl2']="";
					$arr_news_para['Url2']="";
					
					$tmp_re=combine_msg_news($arr_news_para);
					exit($tmp_re);
					break;
					
				}
				else
				{
					//有很多的记录, 进行返回信息的拼接
					$arr_news_para['ArticleCount']=2;
					$arr_news_para['Description1']="";
					$arr_news_para['Title1']="会员记录";
					$arr_news_para['PicUrl1']="";
					$arr_news_para['Url1']="";
					
					$arr_news_para['Description2']="";
					$arr_news_para['Title2']="";
					$arr_news_para['PicUrl2']="";
					$arr_news_para['Url2']="";

					$i=0;
					foreach ($tmp_data as $tmp_row)
					{
						$i=$i+1;
		
						//$tmp_re=$tmp_re.'      '.$tmp_row['member_no'].".     ".$tmp_row['join_date']."    , 会员名称:".$tmp_row['weixin_id'].", 消费积分: ".$tmp_row['bonus'].", 互动积分: ".$tmp_row['act_bonus'].", 推荐人: ".$tmp_row['suggest_mem_no']."\n\n";
						//暂时取消了推荐人和互动积分的功能以及相关提示
						//由于长度限制,只返回最近加入的10个会员的信息
						if ($i<=10)
						{
							$arr_news_para['Title2'].='      '.$tmp_row['member_no'].".     ".$tmp_row['join_date']."    , 会员名称:".$tmp_row['weixin_id'].", 会员积分: ".$tmp_row['bonus']."\n";
						}
		
					}
					$arr_news_para['Title2'].="截止目前,共计{$i}名会员";

					$tmp_re=combine_msg_news($arr_news_para);
					exit($tmp_re);
					break;
				}
				
				break;
			default://默认出口出来, 返回错误

				//插入错误历史记录表
				$member->insert_err_msg($member->str_post_fakeid,"member.php中main()中的swtich(temp)中的default".",exe_sql=".$member->str_exe_sql.", err_sql=".$member->str_sql_errmsg);
				
				//拼接错误信息
				$arr_news_para['ArticleCount']=2;
				$arr_news_para['Description1']="";
				$arr_news_para['Title1']="未知错误";
				$arr_news_para['PicUrl1']="";
				$arr_news_para['Url1']="";
				
				$arr_news_para['Description2']="";
				$arr_news_para['Title2']="member_check_admin未知错误";
				$arr_news_para['PicUrl2']="";
				$arr_news_para['Url2']="";
					
				$tmp_re=combine_msg_news($arr_news_para);
				exit($tmp_re);
				break;

		}
		
	}
	///////////会员记录管理,只有管理员组的人才可以进入
	elseif ((preg_match('/^会员记录管理$/',$member->str_post_key,$temp)) or (preg_match('/^會員記錄管理$/',$member->str_post_key,$temp)))
	{
		$temp=$member->check_is_admin();
	
		//判断结果
		switch($temp)
		{
			case 2://2:不在管理员组中,没有权限
				$member->close_db();
				
				$arr_news_para['ArticleCount']=2;
				$arr_news_para['Description1']="";
				$arr_news_para['Title1']="去别处逛逛吧";
				$arr_news_para['PicUrl1']="";
				$arr_news_para['Url1']="";
					
				$arr_news_para['Description2']="";
				$arr_news_para['Title2']="     朋友, 地球太危险了, 还是去别的地方逛逛吧~";
				$arr_news_para['PicUrl2']="";
				$arr_news_para['Url2']="";
				
				$tmp_re=combine_msg_news($arr_news_para);
				exit($tmp_re);

				break;
			case 0://0:属于管理员组,有权限, 进入后续处理
	
				$tmp_data=$member->query_member_his();
				if (!isset($tmp_data))
				{
					//没有查询到数据,没有用户留言
					$member->close_db();
					
					$arr_news_para['ArticleCount']=2;
					$arr_news_para['Description1']="";
					$arr_news_para['Title1']="会员历史记录";
					$arr_news_para['PicUrl1']="";
					$arr_news_para['Url1']="";
					
					$arr_news_para['Description2']="";
					$arr_news_para['Title2']="     目前暂无任何用户的历史记录";
					$arr_news_para['PicUrl2']="";
					$arr_news_para['Url2']="";
						
					$tmp_re=combine_msg_news($arr_news_para);
					exit($tmp_re);
					break;

				}
				else
				{
					
					//有很多的记录, 进行返回信息的拼接
					$arr_news_para['ArticleCount']=2;
					$arr_news_para['Description1']="";
					$arr_news_para['Title1']="会员历史记录";
					$arr_news_para['PicUrl1']="";
					$arr_news_para['Url1']="";
						
					$arr_news_para['Description2']="";
					$arr_news_para['Title2']="";
					$arr_news_para['PicUrl2']="";
					$arr_news_para['Url2']="";
					
					
					$i=0;
					foreach ($tmp_data as $tmp_row)
					{
						$i=$i+1;
						//暂停使用互动积分的功能
						//$arr_news_para['Title2'].="      会员ID:".$tmp_row['member_no']."  ".$tmp_row['exe_date']."    , 消费前:".$tmp_row['bonus_before'].", 消费后: ".$tmp_row['bonus_after'].", 互动前: ".$tmp_row['act_bonus_before'].", 互动后: ".$tmp_row['act_bonus_after'].", 理由: ".$tmp_row['suggest_info'].", 账单: ".$tmp_row['bill_no']."\n\n";
						$arr_news_para['Title2'].="      会员ID:".$tmp_row['member_no']."  ".$tmp_row['exe_date']."    , 消费前:".$tmp_row['bonus_before'].", 消费后: ".$tmp_row['bonus_after']." 理由: ".$tmp_row['suggest_info']."\n\n";
	
					}
					$tmp_re=combine_msg_news($arr_news_para);
					exit($tmp_re);
					break;
				}
	
			default:
				
				//插入错误历史记录表
				$member->insert_err_msg($member->str_post_fakeid,"member.php中main()中的member_check_admin中的default".",exe_sql=".$member->str_exe_sql.", err_sql=".$member->str_sql_errmsg);
				
				//拼接错误信息
				$arr_news_para['ArticleCount']=2;
				$arr_news_para['Description1']="";
				$arr_news_para['Title1']="未知错误";
				$arr_news_para['PicUrl1']="";
				$arr_news_para['Url1']="";
				
				$arr_news_para['Description2']="";
				$arr_news_para['Title2']="member_check_admin未知错误";
				$arr_news_para['PicUrl2']="";
				$arr_news_para['Url2']="";
					
				$tmp_re=combine_msg_news($arr_news_para);
				exit($tmp_re);
				break;

		}
	
	}
	/////////会员XXXX 错误的关键字
	else
	{
		member_readme();
	}
	
	
	
	$member->close_db();


/******************************************主程序结束*****************************************/
	

	//会员绑定
	function bind_member()
	{		
		global $member;//用正则取出名字
		preg_match('/(?<=会员绑定).*(?=$)/',$member->str_post_key,$temp);
		//名字
		$str_weixin_no=trim($temp[0]);
		//判断如果简体匹配失败, 也就是说用户输入的是繁体, 则再用繁体匹配一遍
		if ((!isset($str_weixin_no)) || ($str_weixin_no==""))
		{
			preg_match('/(?<=會員綁定).*(?=$)/',$member->str_post_key,$temp);
			$str_weixin_no=trim($temp[0]);
		}
		
		$bind_result=$member->bind_member($str_weixin_no);
		//exit('你现在正在使用会员绑定功能,名字为'.$str_weixin_no);
		
	
		switch($bind_result)
		{
			case -1://-1:没有输入名字
				$member->close_db();
				
				$arr_news_para['ArticleCount']=2;
				$arr_news_para['Description1']="";
				$arr_news_para['Title1']="格式错误";
				$arr_news_para['PicUrl1']="";
				$arr_news_para['Url1']="";
					
				$arr_news_para['Description2']="";
				$arr_news_para['Title2']="     缺少你的名字,不能绑定.正确的格式是\"会员绑定 你的名字\", 比如发送\"会员绑定 小程\"";
				$arr_news_para['PicUrl2']="";
				$arr_news_para['Url2']="";
				
				$tmp_re=combine_msg_news($arr_news_para);
				exit($tmp_re);

				break;
			case 1: //已经有记录,更新成功
				$member->close_db();
				
				$arr_news_para['ArticleCount']=2;
				$arr_news_para['Description1']="";
				$arr_news_para['Title1']="更新名字成功!";
				$arr_news_para['PicUrl1']="";
				$arr_news_para['Url1']="";
					
				$arr_news_para['Description2']="";
				$arr_news_para['Title2']="     你的新名字是\"{$str_weixin_no}\",你可以发送\"会员查询\"来查询你的会员信息";
				$arr_news_para['PicUrl2']="";
				$arr_news_para['Url2']="";
				
				$tmp_re=combine_msg_news($arr_news_para);
				exit($tmp_re);
				break;
			case 2://2:执行sql错误
				$member->close_db();
				
				$arr_news_para['ArticleCount']=2;
				$arr_news_para['Description1']="";
				$arr_news_para['Title1']="绑定会员失败";
				$arr_news_para['PicUrl1']="";
				$arr_news_para['Url1']="";
					
				$arr_news_para['Description2']="";
				$arr_news_para['Title2']="     请联系老板处理,具体的错误信息为{$member->str_sql_errmsg} | {$member->str_exe_sql} ";
				$arr_news_para['PicUrl2']="";
				$arr_news_para['Url2']="";
				
				$tmp_re=combine_msg_news($arr_news_para);
				exit($tmp_re);
				
				break;
			case 0://0:没有数据,插入成功,新的名字
				$member->close_db();
				
				$arr_news_para['ArticleCount']=2;
				$arr_news_para['Description1']="";
				$arr_news_para['Title1']="欢迎您成为重庆味道的会员!";
				$arr_news_para['PicUrl1']="";
				$arr_news_para['Url1']="";
					
				$arr_news_para['Description2']="";
				$arr_news_para['Title2']="     你好,{$member->str_weixin_id},你可以发送\"会员查询\"来查询你的会员信息;\n\n      会员积分可用来消费现金折现等优惠!";
				$arr_news_para['PicUrl2']="";
				$arr_news_para['Url2']="";
				
				$tmp_re=combine_msg_news($arr_news_para);
				exit($tmp_re);

				break;
			default://未知错误
				
				//插入错误历史记录表
				$member->insert_err_msg($member->str_post_fakeid,"member.php中的bind_member()中的swtich(bind_result)中的default".",exe_sql=".$member->str_exe_sql.", err_sql=".$member->str_sql_errmsg);
				//返回错误信息
				$arr_news_para['ArticleCount']=2;
				$arr_news_para['Description1']="";
				$arr_news_para['Title1']="绑定会员名字错误";
				$arr_news_para['PicUrl1']="";
				$arr_news_para['Url1']="";
					
				$arr_news_para['Description2']="";
				$arr_news_para['Title2']="     未知错误";
				$arr_news_para['PicUrl2']="";
				$arr_news_para['Url2']="";
				
				$tmp_re=combine_msg_news($arr_news_para);
				exit($tmp_re);

					
		}
	
	
	
	
	}
	
	//会员查询
	function member_info()
	{
		include_once 'diff_platform.php';
		global $member;
		$tmp_row=$member->get_member_info($member->str_post_fakeid);
		if (!isset($tmp_row))
		{
			//不是会员
			$member->close_db();
			
			$arr_news_para['ArticleCount']=2;
			$arr_news_para['Description1']="";
			$arr_news_para['Title1']="对不起,您还不是会员";
			$arr_news_para['PicUrl1']="";
			$arr_news_para['Url1']="";
				
			$arr_news_para['Description2']="";
			$arr_news_para['Title2']="     发送\"会员绑定 你的名字\", 比如发送\"会员绑定 小程\",就可以立刻成为会员.\n     会员将享受会员积分累计,现金返现等优惠!";
			$arr_news_para['PicUrl2']="";
			$arr_news_para['Url2']="";
			
			
			
			$tmp_re=combine_msg_news($arr_news_para);
			exit($tmp_re);
		}
		else
		{
			//是会员, 返回会员信息
			$tmp_member_no=$tmp_row['member_no'];
			$tmp_weixin_id=$tmp_row['weixin_id'];
			$tmp_join_date=$tmp_row['join_date'];
			$tmp_bonus=$tmp_row['bonus'];
			$tmp_act_bonus=$tmp_row['act_bonus'];
			$tmp_fake_id=$tmp_row['fake_id'];
			$tmp_suggest_mem_no=$tmp_row['suggest_mem_no'];
			$member->close_db();
			
			
			$arr_news_para['ArticleCount']=2;
			$arr_news_para['Description1']="";
			$arr_news_para['Title1']="您好,$tmp_weixin_id";
			$arr_news_para['PicUrl1']="";
			$arr_news_para['Url1']="";
			
			$arr_news_para['Description2']="";
			//$arr_news_para['Title2']="     您的会员号码是{$tmp_member_no}, 于{$tmp_join_date}成为重庆味道的会员,目前的消费积分为{$tmp_bonus},互动积分为{$tmp_act_bonus}";
			//暂时取消互动积分的功能和相关的提示
			$arr_news_para['Title2']="     您的会员号码是{$tmp_member_no}, 于{$tmp_join_date}成为重庆味道的会员,目前的会员积分为{$tmp_bonus}";
			$arr_news_para['PicUrl2']="";
			$arr_news_para['Url2']="";

			
			//判断是否已经绑定了名字
			if ((!isset($tmp_weixin_id)) || ($tmp_weixin_id==""))
			{//会员的名字为空
			$arr_news_para['Title2']=$arr_news_para['Title2']."\n\n     您现在的名字还为空哦,为了方便我们称呼您,请输入\"会员绑定     你的名字\"来绑定您的名字";
			}
			
			
			/*------推荐人功能暂停使用
			//判断是否有推荐人信息
			if ($tmp_suggest_mem_no!=0)
			{
				$arr_news_para['Title2']=$arr_news_para['Title2']."\n\n    你是由$tmp_suggest_mem_no 号会员推荐加入的.";
			}
			else
			{
				$arr_news_para['Title2']=$arr_news_para['Title2']."\n\n    你还没有绑定你的推荐人,输入\"会员推荐人   你的推荐人的会员号码\",比如\"会员推荐人 101\",推荐你加入会员的人将可以得到50000点的消费积分和50点的互动积分奖励;";
			}
			*/

			$tmp_re=combine_msg_news($arr_news_para);
			exit($tmp_re);
			
			
			
		}
	
	
	}
	
	//会员消费---此功能已经废弃
	function member_consume($str_bill_no,$str_amt)
	{
		global $member;
		/***********************************条件校验后进入会员消费的流程****************************************/
		//开始正经插入消费记录
		//exit('你现在正在使用会员消费功能,小票单号为'.$str_bill_no.',消费金额为'.$str_amt);
	
		
		//如果还不是会员
		$tmp_row=$member->get_member_info($member->str_post_fakeid);
		if (!isset($tmp_row))
		{
			$member->close_db();
			$tmp_re='description|#title|对不起,您还不是会员#pic|#url|@title|%t%t%t%t非会员不能累计消费,发送"会员绑定 你的名字", 比如发送"会员绑定 小程",就可以立刻成为会员.#description|#pic|#url|';
			exit($tmp_re);
		}
				
		$tmp_insert_re=$member->add_consumption($str_bill_no,$str_amt);
	
		//样例,为了方便代码编写参考,无意义
		//$arr_return[1]=$tmp_bill_no;
		//$arr_return[2]=$tmp_amt;
		//$arr_return[3]=$this->bonus_before;
		//$arr_return[4]=$this->bonus_after;
		//$arr_return[5]=$tmp_row_bill['exe_date'];
		//$arr_return[6]=$tmp_row_bill['member_no'];
		//$arr_return[7]=$tmp_row_ID['weixin_id'];
		//$arr_return[8]=$this->act_bonus_before;
		//$arr_return[9]=$this->act_bonus_after;
	
		switch($tmp_insert_re[0])
		{
			case 1://1:bill_no重复, 已经有人使用这个账单
				$member->close_db();
				$tmp_re='description|#title|消费累计失败#pic|#url|@title|%t%t%t%t抱歉,您不能使用这张小票,因为这张小票已经被'.$tmp_insert_re[7].'于'.$tmp_insert_re[5].'使用过了.#description|#pic|#url|';
				exit($tmp_re);
				break;
			case 2://2:执行sql错误
				$member->close_db();
				$tmp_re='description|#title|消费累计失败#pic|#url|@title|%t%t%t%t插入会员消费信息错误, 请联系老板处理,具体的错误信息为'.$member->str_sql_errmsg."|".$member->str_exe_sql.'#description|#pic|#url|';
				exit($tmp_re);
				break;
			case 0://0:插入成功
				$member->close_db();
				$tmp_bonus=$tmp_insert_re[4]-$tmp_insert_re[3];
				$tmp_act_bonus=$tmp_insert_re[9]-$tmp_insert_re[8];
				$tmp_re="description|#title|消费累计成功!#pic|#url|@title|       本次累计的消费积分为$tmp_bonus , 累计的互动积分为$tmp_act_bonus , 目前的消费积分为$tmp_insert_re[4] , 目前的互动积分为$tmp_insert_re[9]#description|#pic|#url|";
				exit($tmp_re);
				break;
			default:
				exit('未知错误!');
	
		}
		/***********************************会员消费的结束****************************************/
	
	
	
	}
	
	
	
	//会员历史记录查询
	function member_his_query()
	{
		include_once 'diff_platform.php';
		global $member;
		$tmp_data=$member->query_his();
		if (!isset($tmp_data))
		{
			//没有查询到数据,没有消费记录
			$member->close_db();
			
			$arr_news_para['ArticleCount']=2;
			$arr_news_para['Description1']="";
			$arr_news_para['Title1']="暂无消费记录!";
			$arr_news_para['PicUrl1']="";
			$arr_news_para['Url1']="";
			
			$arr_news_para['Description2']="";
			$arr_news_para['Title2']="     你现在还没有任何消费记录哦~在这里你可以查询到你的消费记录和参与一些活动的记录";
			$arr_news_para['PicUrl2']="";
			$arr_news_para['Url2']="";
				
							
			$tmp_re=combine_msg_news($arr_news_para);
			exit($tmp_re);

		}
		else
		{
			
			
			$arr_news_para['ArticleCount']=2;
			$arr_news_para['Description1']="";
			$arr_news_para['Title1']="您的最近10次消费记录";
			$arr_news_para['PicUrl1']="";
			$arr_news_para['Url1']="";
				
			$arr_news_para['Description2']="";
			$arr_news_para['Title2']="";
			$arr_news_para['PicUrl2']="";
			$arr_news_para['Url2']="";

			$i=0;
			foreach ($tmp_data as $tmp_row)
			{
				$i=$i+1;

				//$tmp_re=$tmp_re.'      '.$i.".     ".$tmp_row['exe_date']."      消费积分为".$tmp_row['bonus_after'].", 会员活动积分为".$tmp_row['act_bonus_after'].", 积分变动理由: ".$tmp_row['suggest_info']."\n\n";		
				//$arr_news_para['Title2'].='      '.$i.".     ".$tmp_row['exe_date']."      消费积分为".$tmp_row['bonus_after'].", 会员活动积分为".$tmp_row['act_bonus_after'].", 积分变动理由: ".$tmp_row['suggest_info']."\n\n";
				//暂时取消互动积分的功能和相关的提示
				$arr_news_para['Title2'].='      '.$i.".     ".$tmp_row['exe_date']."      会员积分为".$tmp_row['bonus_after'].", 积分变动说明: ".$tmp_row['suggest_info']."\n\n";
			}

			
			
			$tmp_re=combine_msg_news($arr_news_para);
			exit($tmp_re);
			
		}
	
	}
	
	
	//输入"会员"的说明
	function member_readme()
	{
		include_once 'diff_platform.php';
		global $member;
		global $str_err_readme;
		$tmp_row=$member->get_member_info($member->str_post_fakeid);
		if (!isset($tmp_row))
		{
			//不是会员的人来查询"会员"关键字
			
			$arr_news_para['ArticleCount']=2;
			$arr_news_para['Description1']="";
			$arr_news_para['Title1']="对不起,您还不是会员";
			$arr_news_para['PicUrl1']="";
			$arr_news_para['Url1']="";
			
			$arr_news_para['Description2']="";
			$arr_news_para['Title2']="     发送\"会员绑定 你的名字\", 比如发送\"会员绑定 小程\",就可以立刻成为会员.\n\n     会员将享受会员积分累计,现金返现等优惠!\n\n";
			$arr_news_para['PicUrl2']="";
			$arr_news_para['Url2']="";
			
		}
		else//会员
		{
			//提取数据
			$tmp_member_no=$tmp_row['member_no'];
			$tmp_weixin_id=$tmp_row['weixin_id'];
			$tmp_join_date=$tmp_row['join_date'];
			$tmp_bonus=$tmp_row['bonus'];
			$tmp_fake_id=$tmp_row['fake_id'];

			if ((!isset($tmp_weixin_id)) || ($tmp_weixin_id==""))
			{//会员的名字为空
				
				$arr_news_para['ArticleCount']=2;
				$arr_news_para['Description1']="";
				$arr_news_para['Title1']="会员功能 格式说明(您的名字还为空)";
				$arr_news_para['PicUrl1']="";
				$arr_news_para['Url1']="";
				
				$arr_news_para['Description2']="";
				$arr_news_para['Title2']="     请发送发送\"会员绑定 你的名字\", 比如发送\"会员绑定 小程\",来绑定您的名字\n\n";
				$arr_news_para['PicUrl2']="";
				$arr_news_para['Url2']="";
		
			}
			else
			{//会员的名字不为空
				
				$arr_news_para['ArticleCount']=2;
				$arr_news_para['Description1']="";
				$arr_news_para['Title1']="会员功能 格式说明";
				$arr_news_para['PicUrl1']="";
				$arr_news_para['Url1']="";
				
				$arr_news_para['Description2']="";
				$arr_news_para['Title2']="";
				$arr_news_para['PicUrl2']="";
				$arr_news_para['Url2']="";
			}
		}
		$member->close_db();
		
		
		//拼接功能说明的内容
		$arr_news_para['Title2'].=$str_err_readme;
		$tmp_re=combine_msg_news($arr_news_para);
		
		exit($tmp_re);
		
		
		
	}
	
	
	//会员推荐人----此功能已经废弃
	function member_suggest()
	{
		global $member;

		//字符串分割
		$arr_temp=preg_split("/ +/",$member->str_post_key);
		//推荐人的会员号码
		$str_suggest_mem_no=strtolower($arr_temp[1]);
			
		$tmp_return=$member->member_suggest($str_suggest_mem_no);
		
		switch($tmp_return)
		{
			case 1://1:使用人还不是会员,不能使用会员推荐人功能
				$member->close_db();
				$tmp_re='description|#title|您还是不是我们的会员#pic|#url|@title|%t%t%t%t非会员不能使用会员推荐人功能,请发送"会员绑定 你的名字"来加入会员.#description|#pic|#url|';
				exit($tmp_re);
				break;
			case 3://3:推荐人不存在,没有根据推荐人的会员号码找到会员信息
				$member->close_db();
				$tmp_re='description|#title|"会员推荐人"功能失败!#pic|#url|@title|%t%t%t%t抱歉,您所输入的推荐人的会员号码不存在,请与你的推荐人确认.#description|#pic|#url|';
				exit($tmp_re);
				break;
			case 2://2:执行sql错误
				$member->close_db();
				$tmp_re='description|#title|"会员推荐人"功能失败!#pic|#url|@title|%t%t%t%t会员推荐人功能错误, 请联系老板处理,具体的错误信息为'.$member->str_sql_errmsg."|".$member->str_exe_sql.'#description|#pic|#url|';
				exit($tmp_re);
				break;
			case 0://0:插入成功
				$member->close_db();
				$tmp_re='description|#title|奖励成功!#pic|#url|@title|%t%t%t%t处理成功,已经将会员积分累计到你的推荐人上(它的会员号码为'.$str_suggest_mem_no.').#description|#pic|#url|';
				exit($tmp_re);
				break;
			case 4://4:已经有推荐人了(是本人),本人不能推荐本人的
				$member->close_db();
				$tmp_re='description|#title|"会员推荐人"功能失败!#pic|#url|@title|%t%t%t%t你填写的推荐人会员号码好像是你自己的会员号码,自己是不能推荐自己的哦,推荐人必须是别人,会员号码可以通过发送"会员查询"来查看.#description|#pic|#url|';
				exit($tmp_re);
				break;
			case 5://5:已经有推荐人了(非本人),不能重复使用此功能
				$member->close_db();
				$tmp_re='description|#title|"会员推荐人"功能失败!#pic|#url|@title|%t%t%t%t抱歉,您已经绑定了您的推荐人,并且他已经获得了会员积分奖励,您不能重复使用此功能,具体信息可以通过发送"会员查询"来查看.#description|#pic|#url|';
				exit($tmp_re);
				break;
			default:
				exit('未知错误');
		
		}
		
	}
	
	

?>




