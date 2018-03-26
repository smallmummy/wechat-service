<?php


//echo("开始执行");


//初始化sql
$mysql = new SaeMysql();


//$str_exe_sql

srand(mktime());
//累计码的前两位数字
for($num=81;$num<90;$num++)
{
	
	for($iii=0;$iii<15000;$iii++)
	{
		
		//开始事务
		//$str_exe_sql="begin;\n";
		$str_exe_sql="";
		

		//累计码的前两位数字
		$str_consume_id="{$num}";
		
		//生成一个10位的序列号
		for($i=0;$i<10;$i++)
		{
			$digit=rand()%10;
			$str_consume_id.="{$digit}";
		}
		//echo($str_consume_id);
		//echo("\n");
		
		//累计码对应的累计点数
		$consume_amt=($num-11)*50+100;
		
		//构造insert语句内容
		
		$str_exe_sql.='insert into tb_consume_bonus_id(`consume_bonus_id`,`consume_amt`) values("'.$str_consume_id.'",'.$consume_amt.');';
		
		
	
		
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