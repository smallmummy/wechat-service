<?php


require('cls_member.php');
global $member;
$member=new cls_member(true);


//查询是否已经是会员;
$re_get_member=$member->get_member_info($member->str_post_fakeid);
if (!isset($re_get_member))
{
	$member->close_db();
	//不是会员
	exit("您好,您目前还不是我们的会员,不能参加此活动,请输入\"会员绑定   你的昵称\"来手动加入会员,详情可以输入\"会员\"来查询\n\n");
}

//到此步骤,则判断是会员, 可以进行后续动作


//判断该用户是否已经参加过此活动
$re_check_join_2014=$member->check_join_2014_new_member($member->str_post_fakeid);
if (isset($re_check_join_2014))
{
	//有数据, 已经参加过活动,根据返回的数据给用户提示
	$member->close_db();
	//提取数据
	$re_record_id=$re_check_join_2014['record_id'];
	$re_activity_id=$re_check_join_2014['activity_id'];
	$re_exe_date=$re_check_join_2014['exe_date'];
	//返回用户的提示
	exit("抱歉,你已经于{$re_exe_date}参加过这个活动了,不能再参加了");
}
else
{
	//没有参加过这个活动, 可以参加
	
	
	//生成会员专用的活动码
	$activity_id=generate_activity_id();
	
	//插入用户参加活动表
	$re_insert_2014_new_join=$member->insert_2014_new_join($member->str_post_fakeid,$activity_id);
	//插入处理错误的历史堆栈表
	if ($re_insert_2014_new_join != 0)
	{
		$member->insert_err_msg($member->str_post_fakeid,"插入2014用户参加活动表错误,错误信息{$this->str_sql_errmsg},参加时间为{$tmp_current_timestamp},活动码为{$activity_id}".",exe_sql=".$member->str_exe_sql.", err_sql=".$member->str_sql_errmsg);
	}
	
	
	
	//构造用户消费历史记录说明
	//当前时间, 系统数据库中的时区不对
	$tmp_current_timestamp=date("Y-m-d H:i:s",time()-3600);
	$str_suggest_info="首次参加2014年微信新会员活动,参加时间为{$tmp_current_timestamp},活动码为{$activity_id}";
	
	//插入用户消费记录
	$re_add_bonus_by_activity=$member->add_bonus_by_activity(3,0,0,$str_suggest_info);
	//插入处理错误的历史堆栈表
	if ($re_add_bonus_by_activity != 0)
	{
		$member->insert_err_msg($member->str_post_fakeid,"2014新用户活动表插入用户消费记录错误,错误信息"."exe_sql=".$member->str_exe_sql.", err_sql=".$member->str_sql_errmsg);
	}
	
	
	
	
	exit("欢迎参加2014新用户活动!\n活动码为:\n{$activity_id}\n请将此活动码给服务员，以记录并参加活动(每个会员仅限一次)");

	
	
	
	
}





//生成用户的活动码的函数
//第一位: 时间戳的月份(arr_atoz映射得到)
//第二位: 时间戳的日期(arr_atoz映射得到)
//第三位: 时间戳的小时(arr_atoz映射得到)
//第四五位: 时间戳的分钟(不用映射)
//第六七位: 时间戳的秒数(不用映射)
//第八九十位: 用户fakeid的最后三位(不用映射)
function generate_activity_id()
{
	global $member;
	
	$arr_atoz = array(
			"01"=>"1",
			"02"=>"2",
			"03"=>"3",
			"04"=>"4",
			"05"=>"5",
			"06"=>"6",
			"07"=>"7",
			"08"=>"8",
			"09"=>"9",
			"10"=>"A",
			"11"=>"B",
			"12"=>"C",
			"13"=>"D",
			"14"=>"E",
			"15"=>"F",
			"16"=>"G",
			"17"=>"H",
			"18"=>"J",
			"19"=>"K",
			"20"=>"L",
			"21"=>"M",
			"22"=>"N",
			"23"=>"P",
			"24"=>"Q",
			"25"=>"R",
			"26"=>"S",
			"27"=>"T",
			"28"=>"U",
			"29"=>"W",
			"30"=>"X",
			"31"=>"Y"); 
	
	
	//当前时间, 格式"20140321163159" 
	$tmp_current_timestamp=date("YmdHis",time()-3600);
	
	//获取明文部分
	$tmp_org_mon=substr($tmp_current_timestamp,4,2);
	$tmp_org_day=substr($tmp_current_timestamp,6,2);
	$tmp_org_hour=substr($tmp_current_timestamp,8,2);
	$tmp_org_min=substr($tmp_current_timestamp,10,2);
	$tmp_org_second=substr($tmp_current_timestamp,12,2);
	$tmp_last_3_fakeid=substr($member->str_post_fakeid,-3);
	
	//加密
	$tmp_encode_mon=$arr_atoz[$tmp_org_mon];
	$tmp_encode_day=$arr_atoz[$tmp_org_day];
	$tmp_encode_hour=$arr_atoz[$tmp_org_hour];
	$tmp_encode_min=$tmp_org_min;
	$tmp_encode_second=$tmp_org_second;
	$tmp_encode_last_3_fakeid=$tmp_last_3_fakeid;
	
	//拼凑密文串
	$re_encode=$tmp_encode_mon.$tmp_encode_day.$tmp_encode_hour.$tmp_encode_min.$tmp_encode_second.$tmp_encode_last_3_fakeid;
	
	return $re_encode;
	
	
	
	
}












?>
