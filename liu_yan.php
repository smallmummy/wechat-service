<?php

	require('cls_member.php');
	global $member;
	$member=new cls_member(true);


	
	//发送过来的消息仅为"留言"两个字,不处理,直接返回
	if (preg_match('/^留言$/',$member->str_post_key,$temp))
	{
		exit("留言不能为空哦, 你输入你要留言的内容, 比如说\"留言 老板要出新菜啦!\"\n");
	}

	
	
	//进入留言查询, 只有管理员才可以进入
	if (preg_match('/^留言管理$/',$member->str_post_key,$temp))
	{
		$temp=$member->check_is_admin();
		
		//判断结果
		switch($temp)
		{
			case 2://2:不在管理员组中,没有权限
				$member->close_db();
				$tmp_re="朋友进错地方了吧,呵呵~去别处逛逛吧";
				exit($tmp_re);
				break;
			case 0://0:属于管理员组,有权限, 进入后续处理
				
				$tmp_data=$member->query_note_his();
				if (!isset($tmp_data))
				{
					//没有查询到数据,没有用户留言
					$member->close_db();
					$tmp_re="description|#title|暂无消费记录!#pic|#url|@title|    目前暂无用户留言.#description|#pic|#url|";
					exit($tmp_re);
				}
				else
				{
					$tmp_re='description|#title|会员留言#pic|#url|@title|';
					$i=0;
					foreach ($tmp_data as $tmp_row)
					{
						$i=$i+1;
				
						$tmp_re=$tmp_re.'      '.$tmp_row['note_id'].".     ".$tmp_row['exe_date']."    会员ID:".$tmp_row['member_no'].", 会员名称:".$tmp_row['weixin_id'].", 留言内容: ".$tmp_row['note_content']."\n\n";
				
					}
					$tmp_re=$tmp_re.'#description|#pic|#url|';
					exit($tmp_re);
				}
				
			default:
				exit('liu_yan_check_admin未知错误');
		}
		
	
	}
	
	

	
	
	
	
	//进入用户留言处理, 数据库操作
	if (preg_match('/^留言.*/',$member->str_post_key,$temp))
	{
		//有留言内容,进行数据库操作
	
		//查询是否已经是会员;
		$re_get_member=$member->get_member_info($member->str_post_fakeid);
		if (!isset($re_get_member))
		{
			$member->close_db();
			//不是会员
			exit("对不起,您还不是会员,不能进行留言, 发送\"会员绑定 你的昵称\", 比如发送\"会员绑定 小程\",就可以立刻成为会员,享受我们的优惠活动");
		}
		else//是会员, 在数据库中插入留言信息
		{
			//调用函数
			$tmp_return_liu_yan=$member->insert_customer_note($member->str_post_key);
	
			//判断结果
			switch($tmp_return_liu_yan)
			{
				case 2://2:执行sql错误
					$member->close_db();
					$tmp_re="留言失败, 请联系老板处理,具体的错误信息为$member->str_sql_errmsg|$member->str_exe_sql";
					exit($tmp_re);
					break;
				case 0://0:更新成功,直接跳出,进行后续处理
					break;
				default:
					exit('liuyan-tmp_return_liu_yan未知错误');
			}
	
			$member->close_db();
	
	
			echo "[系统提示]谢谢你的留言, 我们已经收到, 会尽快处理,并给与反馈的,谢谢!\n";
			exit();
	
		}
	
	
	}
	
	
	
	
	
	
	
	
	

	

?>

