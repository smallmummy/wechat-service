<?php

	require('cls_member.php');

	global $member;
	$member=new cls_member(true);
	//查询是否已经是会员;如果会员退出的话,是不删除会员的记录的
	$re_get_member=$member->get_member_info($member->str_post_fakeid);
	if (isset($re_get_member))
	{
		//是会员,之前有退出的记录,不需要重新加入会员,只需提示用户
		$head_string='你好,'.$re_get_member['weixin_id'].',欢迎你回来,你的会员信息依然保留%n';
	}
	else//之前重来没有加入过,需要自动加入会员
	{
		//加入会员
		$bind_result=$member->bind_member();
		
		switch($bind_result)
		{
			case 1://1:已经有会员记录,重新更新会员资料,理论上这个逻辑分支走不到
				$head_string='你好,欢迎你回来,你的会员信息依然保留%n';
				break;
			case 2://2:执行sql错误
				$head_string='您好,谢谢您关注重庆味道,自动加入会员失败,请输入"会员绑定%t你的昵称"来手动加入会员,详情可以输入"会员"来查询%n';
				break;
			case 0://0:插入成功					
				$head_string='您好,谢谢您关注重庆味道,已成功自动成为我们的会员,请输入"会员绑定%t你的昵称"来修改你的昵称,详情可以输入"会员"来查询%n';
				break;
			default:
				$head_string='您好,谢谢您关注重庆味道,自动加入会员失败,请输入"会员绑定%t你的昵称"来手动加入会员,详情可以输入"会员"来查询%n';
				break;
					
		}
	

		

	}
	
	$post_string=<<<TEXT
我们这里的功能有:
>>输入"<font color="#0101DF">电话</font>",或者"地址",得到餐厅的地址和联系电话;
>>输入"菜单",得到重庆味道的菜单;
>>输入"订餐",或者"外卖",得到订餐的具体说明;
>>输入"帮助",可以进入自助式帮助说明菜单;
更多功能正在开发中,敬请期待.
TEXT;
	$member->close_db();
	exit($head_string.$post_string);

	
	
	

?>

