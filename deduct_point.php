<?php


require('cls_member.php');
global $member;
$member=new cls_member(true);
//积分码的前缀, 是用来方便入口,数据库中的数据是没有前缀的, 判断前需要剥离掉
$voucher_prefix="907";


//查询是否已经是会员;
$re_get_member=$member->get_member_info($member->str_post_fakeid);
if (!isset($re_get_member))
{
	$member->close_db();
	//不是会员
	exit("您好,您目前还不是我们的会员,不能参加此活动,请输入\"会员绑定   你的昵称\"来手动加入会员,详情可以输入\"会员\"来查询\n\n");
}

//到此步骤,则判断是会员, 可以进行后续动作


//提取出用户输入的积分扣减码
$deduct_id=$member->str_post_key;
//对用户输入的积分扣减码进行处理, 去掉空格
$deduct_id=str_replace(" ","",$deduct_id);
//去掉前缀，xxx_judge为数据库中的积分扣减码的格式
$deduct_id_judge=substr($deduct_id,3);


//使用正则表达式,初步验证积分码的格式
if (!(preg_match('/^([1-9])[0-9]{7}$/',$deduct_id_judge,$temp)))
{
	//累计码的初步验证失败
	exit("   你好,你输入的积分扣减码错误,请让服务员检查,谢谢\n\n   Maaf, potong nomor yang Anda masukan salah, silahkan cek lagi.\n");
}


//通过初步验证,进入后续流程


//调用cls_member中的函数, 获取累计码的详细信息
$re_check_deduct_id=$member->check_deduct_id($deduct_id_judge);


if (!isset($re_check_deduct_id))
{
	//没有查询到数据,表示在数据库中没有这样的积分码
	exit("   你好,你输入的积分扣减码不存在,请让服务员检查,谢谢\n\n   Maaf, potong nomor yang Anda masukan tidak ada, silakan cek lagi.\n");
}


//数据存在, 提取记录中的字段进行后续的逻辑分析
$re_check_deduct_amt=$re_check_deduct_id['exchange_amt'];
$re_check_already_used=$re_check_deduct_id['already_used'];
$re_check_used_datetime=$re_check_deduct_id['used_datetime'];
$re_check_fake_id=$re_check_deduct_id['fake_id'];
$re_check_member_no=$re_check_deduct_id['member_no'];



if ($re_check_already_used == 1)
{
	//根据member_no信息，反馈积分扣减码已经被别人使用
	exit("   你输入的积分扣减码({$deduct_id})已经被其他的客人(会员号码:{$re_check_member_no})在{$re_check_used_datetime}使用过了,如有需要,请联系老板进一步处理\n\n   Maaf, voucher yang Anda masukan({$deduct_id}) sudah digunakan oleh tamu lain(member nomor:{$re_check_member_no}) di {$re_check_used_datetime}, silahkan hubungi BOSS untuk memproses jika perlu.\n");
	
}




//积分扣减码存在,而且没有被使用过,进入扣减会员积分流程

//判断用户剩余积分是否大于扣减积分， 否则积分不足，就不能进行扣减

if ($member->bonus_after < $re_check_deduct_amt)
{
	//把这张积分扣减码作废
	//修改积分扣减码在数据库中的状态
	$re_update_deduct_id=$member->update_dedcut_id($deduct_id_judge,-9999);
	
	//判断返回结果
	switch ($re_update_deduct_id)
	{
		case 2://更新voucher_id失败
			//记录原因
			$member->insert_err_msg($member->str_post_fakeid,"作废此积分扣减码,但更新积分扣减码状态失败, 积分扣减码为{$deduct_id_judge},"."exe_sql=".$member->str_exe_sql.", err_sql=".$member->str_sql_errmsg);
			break;
	
		case 0:
			//null
			break;
	
	
		default:
			//默认出口退出
			$member->insert_err_msg($member->str_post_fakeid,"作废此积分扣减码，从deduct_point中的switch(re_update_deduct_id)的默认出口中退出!积分扣减码为{$deduct_id_judge},"."exe_sql=".$member->str_exe_sql.", err_sql=".$member->str_sql_errmsg);
			break;
	
	 
	
	}
	
	
	
	
	//小于扣减积分，积分不足，不能进行扣减
	exit("	你好，{$member->str_member_no}号会员，你目前的积分为{$member->bonus_after},小于{$re_check_deduct_amt}(积分扣减码为{$deduct_id})，不能完成本次积分扣减\n\n     Maaf, Member No.{$member->str_member_no}, Anda tetap poin tidak cukup, poin saat ini adalah {$member->bonus_after}, kurang dari {$re_check_deduct_amt}\n");
}

//用户的剩余积分足够，进行扣减


//当前时间, 系统数据库中的时区不对, 不能自动填充
$tmp_current_timestamp=date("Y-m-d H:i:s",time()-3600);
$str_suggest_info="于{$tmp_current_timestamp}参与积分兑换活动,使用积分扣减码(号码:{$deduct_id})扣减了{$re_check_deduct_amt}点积分";
$re_add_bonus_by_activity=$member->add_bonus_by_activity(2,$re_check_deduct_amt*(-1),0,$str_suggest_info);


//根据返回结果,给用户返回消息

switch ($re_add_bonus_by_activity[0])
{
	case 2:
		
		//更新用户积分失败!插入处理错误的历史堆栈表
		$member->insert_err_msg($member->str_post_fakeid,"使用积分扣减码扣减用户积分发生错误,错误信息{$member->str_sql_errmsg}|{$member->str_exe_sql}");
		$member->close_db();
		$tmp_re="使用积分扣减码扣减用户积分发生错误, 请联系老板处理,具体的错误信息为$member->str_sql_errmsg|$member->str_exe_sql\n\n potong kesalahan poin bonus, silahkan hubungi BOSS untuk memproses, info kesalahan detail: $member->str_sql_errmsg|$member->str_exe_sql";
		exit($tmp_re);
		break;
	case 0:
		//更新用户积分成功, 根据结果拼凑提示信息
		
		
		$tmp_re="";
		$tmp_re.="   会员{$member->str_member_no}你好，成功使用扣减码(ID:$deduct_id)扣减了{$re_check_deduct_amt}点积分,目前您的会员积分一共是{$re_add_bonus_by_activity[4]}点积分.\n\n";
		$tmp_re.="   Hi,Memeber{$member->str_member_no}. Anda sudah digunakan voucher potong(ID:$deduct_id),potong {$re_check_deduct_amt} poin bonus,bonus anggota Anda saat ini adalah {$re_add_bonus_by_activity[4]} poin.\n";
		//$tmp_re=$tmp_re.'#description|#pic|#url|';
		
		//更新用户积分成功后,修改积分扣减码在数据库中的状态
		$re_update_deduct_id=$member->update_dedcut_id($deduct_id_judge,$re_add_bonus_by_activity[4]);
		
		
		
		//判断返回结果
		switch ($re_update_deduct_id)
		{
			case 2://更新voucher_id失败
				//因为用户的积分已经更新成功, 但voucher表更新失败, 为了不提醒用户反复使用, 不提醒错误, 只返回成功提示
				$member->insert_err_msg($member->str_post_fakeid,"扣减用户积分成功,但更新积分扣减码状态失败, 积分扣减码为{$deduct_id_judge},"."exe_sql=".$member->str_exe_sql.", err_sql=".$member->str_sql_errmsg);
				break;
				
			case 0:
				//null
				break;
				
				
			default:
				//默认出口退出
				$member->insert_err_msg($member->str_post_fakeid,"从deduct_point中的switch(re_update_deduct_id)的默认出口中退出!积分扣减码为{$deduct_id_judge},"."exe_sql=".$member->str_exe_sql.", err_sql=".$member->str_sql_errmsg);
				break;
				
				
				
		}
		
		//返回用户提示信息
		$member->close_db();
		exit($tmp_re);
		break;

	default:
		//默认出口退出
		$member->insert_err_msg($member->str_post_fakeid,"从deduct_point中的switch(re_add_bonus_by_activity)的默认出口中退出!积分扣减码为{$deduct_id_judge},"."exe_sql=".$member->str_exe_sql.", err_sql=".$member->str_sql_errmsg);
		$member->close_db();
		$tmp_re="扣减用户积分发生未知错误, 请联系老板处理,具体的错误信息为deduct_point->switch(re_add_bonus_by_activity)\n\n potong kesalahan titik bonus, silahkan hubungi BOSS untuk memproses, info kesalahan detail: deduct_point->switch(re_add_bonus_by_activity)";
		exit($tmp_re);
		break;
}







?>

