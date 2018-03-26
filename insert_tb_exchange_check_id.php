<?php


//echo("开始执行");


//初始化sql
$mysql = new SaeMysql();


//$str_exe_sql

srand(mktime());
//累计码的前两位数字
for($num=11;$num<21;$num++)
{
	
	for($iii=0;$iii<5000;$iii++)
	{
		
		//开始事务
		//$str_exe_sql="begin;\n";
		$str_exe_sql="";
		

		//累计码的前两位数字
		$str_consume_id="{$num}";
		
		//生成一个6位的序列号
		for($i=0;$i<6;$i++)
		{
			$digit=rand()%10;
			$str_consume_id.="{$digit}";
		}
		//echo($str_consume_id);
		//echo("\n");
		
		//扣减码对应的扣减点数
		$deduct_amt=($num-10)*100;
		
		//构造insert语句内容
		
		$str_exe_sql.='insert into tb_exchange_check_id(`check_id`,`exchange_amt`) values("'.$str_consume_id.'",'.$deduct_amt.');';


	
		
		//提交事务
		//$str_exe_sql.="\ncommit;";

		//echo $str_exe_sql;
		//echo "\n";
		
		//执行sql语句
		$mysql->runSql($str_exe_sql);
		
		/*
		if( $mysql->errno() != 0 )
		{
			echo $mysql->errno();
			echo "--\n";
			echo($mysql->errmsg());
		}
		else
		{
			echo("执行成功");
		}
		
		*/

	
	}
	

}




?>