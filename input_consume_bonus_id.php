<?php


require('cls_member.php');
global $member;
$member=new cls_member(true);
//积分码的前缀, 是用来方便入口,数据库中的数据是没有前缀的, 判断前需要剥离掉
$voucher_prefix="627";


//查询是否已经是会员;
$re_get_member=$member->get_member_info($member->str_post_fakeid);
if (!isset($re_get_member))
{
	$member->close_db();
	//不是会员
	exit("您好,您目前还不是我们的会员,不能参加此活动,请输入\"会员绑定   你的昵称\"来手动加入会员,详情可以输入\"会员\"来查询\n\n");
}

//到此步骤,则判断是会员, 可以进行后续动作


//提取出用户输入的累计码
$consume_bonus_id=$member->str_post_key;
//对用户输入的累计码进行处理, 去掉空格
$consume_bonus_id=str_replace(" ","",$consume_bonus_id);
//去掉前缀
$consume_bonus_id_judge=substr($consume_bonus_id,3);


//使用正则表达式,初步验证积分码的格式
if (!(preg_match('/^([1-9])[0-9]{10,11}$/',$consume_bonus_id_judge,$temp)))
{
	//累计码的初步验证失败
	exit("   你好,你输入的积分码错误,请重新检查小票上的积分码,谢谢\n\n   Maaf, voucher yang Anda masukan salah, silahkan periksa kembali bahwa dalam faktur.\n");
}


//通过初步验证,进入后续流程


//调用cls_member中的函数, 获取累计码的详细信息
$re_check_bonus_id=$member->check_consume_bonus_id($consume_bonus_id_judge);


if (!isset($re_check_bonus_id))
{
	//没有查询到数据,表示在数据库中没有这样的积分码
	exit("   你好,你输入的积分码不存在,请重新检查小票上的积分码,谢谢\n\n   Maaf, voucher yang Anda masukan tidak ada, silakan cek kembali bahwa dalam faktur.\n");
}


//数据存在, 提取记录中的字段进行后续的逻辑分析
$re_check_consume_amt=$re_check_bonus_id['consume_amt'];
$re_check_already_used=$re_check_bonus_id['already_used'];
$re_check_used_datetime=$re_check_bonus_id['used_datetime'];
$re_check_fake_id=$re_check_bonus_id['fake_id'];


if ($re_check_already_used == 1)
{
	//积分码已经被别人使用
	$re_query_member_info=$member->query_member_info_base_fake_id($re_check_fake_id);
	
	
	if (!isset($re_query_member_info))
	{
		//没有查询到数据,表示在数据库中没有fake_id,异常情况, 继续提示用户, 但不用添加被谁使用的具体信息
		exit("   您好,您输入的积分码已经被其他的客人在{$re_check_used_datetime}使用过了,如有需要,请联系老板进一步处理\n\n   Maaf, voucher yang Anda masukan sudah digunakan oleh tamu lain di {$re_check_used_datetime}, silahkan hubungi BOSS untuk memproses jika perlu.\n");
	}
	else
	{
		//根据fake_id找到用户的具体信息, 填充后提示客人, 这张积分券是被谁使用过了
		$re_query_member_info_member_no=$re_query_member_info['member_no'];
		$re_query_member_info_weixin_id=$re_query_member_info['weixin_id'];
		exit("   您好,您输入的积分码已经被其他的客人(昵称:{$re_query_member_info_weixin_id} ; 会员号码:{$re_query_member_info_member_no})在{$re_check_used_datetime}使用过了,如有需要,请联系老板进一步处理\n\n   Maaf, voucher yang Anda masukan sudah digunakan oleh tamu lain(nama:{$re_query_member_info_weixin_id} ; member nomor:{$re_query_member_info_member_no}) di {$re_check_used_datetime}, silahkan hubungi BOSS untuk memproses jika perlu.\n");
	}
	
}




//积分码存在,而且没有被使用过,进入累计会员积分流程


//调用cls_member中的函数, 累计会员的积分


//当前时间, 系统数据库中的时区不对, 不能自动填充
$tmp_current_timestamp=date("Y-m-d H:i:s",time()-3600);
$str_suggest_info="于{$tmp_current_timestamp}在店里消费,使用积分码(号码:{$consume_bonus_id})获取到了{$re_check_consume_amt}点积分";
$re_add_bonus_by_activity=$member->add_bonus_by_activity(1,$re_check_consume_amt,0,$str_suggest_info);


//根据返回结果,给用户返回消息

switch ($re_add_bonus_by_activity[0])
{
	case 2:
		
		//更新用户积分失败!插入处理错误的历史堆栈表
		$member->insert_err_msg($member->str_post_fakeid,"根据积分码增加用户的积分发生错误,错误信息{$member->str_sql_errmsg}|{$member->str_exe_sql}");
		$member->close_db();
		$tmp_re="根据积分码增加用户的积分发生错误, 请联系老板处理,具体的错误信息为$member->str_sql_errmsg|$member->str_exe_sql\n\n meningkatkan kesalahan titik bonus, silahkan hubungi BOSS untuk memproses, info kesalahan detail: $member->str_sql_errmsg|$member->str_exe_sql";
		exit($tmp_re);
		break;
	case 0:
		//更新用户积分成功, 根据结果拼凑提示信息
		
		//$tmp_re="description|#title|累计用户积分成功!#pic|#url|@title|";
		$tmp_re="";
		$tmp_re.="   您已经成功使用了积分券(ID:$consume_bonus_id),成功的获取到了{$re_check_consume_amt}点积分,目前您的会员积分一共是{$re_add_bonus_by_activity[4]}点积分.\n\n";
		$tmp_re.="   Anda sudah digunakan voucher berhasil(ID:$consume_bonus_id),mendapatkan {$re_check_consume_amt} poin bonus,bonus anggota Anda saat ini adalah {$re_add_bonus_by_activity[4]} poin.\n";
		//$tmp_re=$tmp_re.'#description|#pic|#url|';
		
		//更新用户积分成功后,修改voucher在数据库中的状态
		$re_update_consume_bonus_id=$member->update_consume_bonus_id($consume_bonus_id_judge);
		//判断返回结果
		switch ($re_update_consume_bonus_id)
		{
			case 2://更新voucher_id失败
				//因为用户的积分已经更新成功, 但voucher表更新失败, 为了不提醒用户反复使用, 不提醒错误, 只返回成功提示
				$member->insert_err_msg($member->str_post_fakeid,"更新用户积分成功,但更新voucher状态失败, voucherID为{$consume_bonus_id_judge}".",exe_sql=".$member->str_exe_sql.", err_sql=".$member->str_sql_errmsg);
				break;
				
			case 0:
				//null
				break;
				
				
			default:
				//默认出口退出
				$member->insert_err_msg($member->str_post_fakeid,"从input_consume_bonus中的switch(re_update_consume_bonus_id)的默认出口中退出!".",exe_sql=".$member->str_exe_sql.", err_sql=".$member->str_sql_errmsg);
				break;
				
				
				
		}
		
		//返回用户提示信息
		$member->close_db();
		exit($tmp_re);
		break;

	default:
		//默认出口退出
		$member->insert_err_msg($member->str_post_fakeid,"从input_consume_bonus中的switch(re_add_bonus_by_activity)的默认出口中退出!".",exe_sql=".$member->str_exe_sql.", err_sql=".$member->str_sql_errmsg);
		$member->close_db();
		$tmp_re="增加用户的积分发生未知错误, 请联系老板处理,具体的错误信息为input_consume_bonus->switch(re_add_bonus_by_activity)\n\n meningkatkan kesalahan titik bonus, silahkan hubungi BOSS untuk memproses, info kesalahan detail: input_consume_bonus->switch(re_add_bonus_by_activity)";
		exit($tmp_re);
		break;
}







?>

