<?php




  class cls_member {

    private $debug;

    /**
     * 以数组的形式保存微信服务器每次发来的请求
     *
     * @var array
     */
    private $request;
    
    //游泳的POST参数的内容
    public $str_post_key;//post过来的关键字信息
    public $str_post_fakeid;//微信接口中的fakeid
    public $str_weixin_id;//用户昵称,用于会员绑定后,主程序提取
    public $str_member_no;//会员ID,用于累计消费的时候,反馈主程序;新加入会员的时候,不能反馈,因为刚刚插入,查不出来
    public $str_sql_errmsg;//执行SQL语句的错误信息
    public $str_exe_sql;//执行SQL的内容,用于执行错误的时候,主程序可以提取分析
    public $str_join_date;//会员第一次加入会员的日期
    public $bonus_before=0;//会员的积分,没有累计前的
    public $bonus_after=0;//会员的积分,累计后的   //用于存放会员目前的消费积分
    public $act_bonus_before=0;//会员的互动积分,没有累计前的
    public $act_bonus_after=0;//会员的互动积分,累计后的    //用于存放会员目前的互动积分
    public $suggest_mem_no;//会员的推荐人的ID
    public $last_sign_date;//会员的上一次签到的日期
    public $continue_sign_days;//会员的连续签到的天数
    
    
    private $mysql;//用户操作SAE数据库的全局变量

    private $admin_gp = array ("oCtCnjjiQJYGnFvrVtrVDWnRTnr0");
    
    
    public function __construct($debug = FALSE) {

    include_once 'diff_platform.php';
      $this->debug = $debug;
      set_error_handler(array(&$this, 'errorHandler'));
      // 设置错误处理函数，将错误通过文本消息回复显示

      
      //根据不同的平台接口,确定不同的参数提取方式
      
      global $diff_platform_ID;
      switch($diff_platform_ID)
      {
      	case 1://小九
      		$xml = $_POST;
      		break;
      	
      	case 2://V5KF
      		$xml = $_GET;
      		
      		break;
      	
      	default://没有匹配到, 应该是coding错误
      		exit("cls_member--swtich--diff_platform_ID error!");
      		break;
      }
      

      


      $this->request = array_change_key_case($xml, CASE_LOWER);
      // 将数组键名转换为小写，提高健壮性，减少因大小写不同而出现的问题
      
      
      
      //初始化sql
      $this->mysql = new SaeMysql();
    
      //获取所有的参数
      $this->str_post_key=trim($this->getRequest('key'));
      $this->str_post_fakeid=trim($this->getRequest('from'));
    
    }




    /**
     * 获取本次请求中的参数，不区分大小
     *
     * @param  string $param 参数名，默认为无参
     * @return mixed
     */
    protected function getRequest($param = FALSE) {
      if ($param === FALSE) {
        return $this->request;
      }

      $param = strtolower($param);

      if (isset($this->request[$param])) {
        return $this->request[$param];
      }

      return NULL;
    }

    
    /**
     * 关闭数据库链接
     *
     * @param  NULL
     *
     */
    public function close_db() {
    	$this->mysql->closeDb();
    }
    

    /**
     * 会员信息查询, 根据fakeid, 查询到数据后,会绑定this中的变量
     * 
     * @param  string $str_fake_id 用户的fakeid
     * @return 如果不是会员,则返回NULL; 否则返回数组,会员信息
     */
    public function get_member_info($str_fake_id) 
    {
    	if (!isset($str_fake_id)) {
    		$return_msg="没有用户fakeid,不能查询用户信息";
    		return $return_msg;
    	}
    
    	$this->str_exe_sql = "select * from tb_member where fake_id='".$str_fake_id."'";
    	$data = $this->mysql->getData($this->str_exe_sql);
    	
    	if (!isset($data))
    	{
    		//没有查询到数据
    		return NULL;
    	}
    	else//已经绑定,有数据
    	{
    		$row=$data[0];

    		$this->str_member_no=$row['member_no'];
    		$this->str_weixin_id=$row['weixin_id'];
    		$this->str_join_date=$row['join_date'];
    		$this->bonus_after=$row['bonus'];
    		$this->act_bonus_after=$row['act_bonus'];
    		$this->suggest_mem_no=$row['suggest_mem_no'];
    		$this->last_sign_date=$row['last_sign_date'];
    		$this->continue_sign_days=$row['continue_sign_days'];
    		
    		
    		 
    		return $row;
    	}
    	
    }
    

    /**
     * 会员信息查询, 根据fakeid,只返回数据
     *
     * @param  string $str_fake_id 用户的fakeid
     * @return 如果不是会员,则返回NULL; 否则返回数组,会员信息
     */
    public function query_member_info_base_fake_id($str_fake_id)
    {
    	
    	//根据用户的fake_id,查找到表中记录
    	$this->str_exe_sql = "select * from tb_member where fake_id='".$str_fake_id."'";
    	$data = $this->mysql->getData($this->str_exe_sql);
    	
    	if (!isset($data))
    	{
    		//没有查询到数据
    		return null;
    	}
    	else//有记录
    	{
    		//只返回第一条记录
    		return $data[0];
    	}
    	
    	
    	
    	
    	 
    }
    
    
    
    /**
     * 会员信息查询, 根据member_no
     *
     * @param  string $tmp_member_no 用户内部的ID
     * @return 如果不是会员,则返回NULL; 否则返回数组,会员信息
     */
    public function get_member_info_via_ID($tmp_member_no)
    {
    
    	$this->str_exe_sql = "select * from tb_member where member_no='".$tmp_member_no."'";
    	$data = $this->mysql->getData($this->str_exe_sql);
    	 
    	if (!isset($data))
    	{
    		//没有查询到数据
    		return NULL;
    	}
    	else//已经绑定,有数据
    	{
    		$row=$data[0];
    		return $row;
    	}
    	 
    }
    
    
    
    /**
     * 根据bill_no查询用户消费记录
     *
     * @param  string $tmp_bill_no; 小票的单号
     * @return NULL 没有查询到数据,证明这个小票还没有被别人使用, 可以使用
     * 		   array 有记录, 已经有人使用过这个小票,数组的结构与表结果相同
     */
    public function get_bill_no($tmp_bill_no)
    {

    	$this->str_exe_sql = "select * from tb_his_consume where bill_no='".$tmp_bill_no."'";
    	$data = $this->mysql->getData($this->str_exe_sql);
    	 
    	if (!isset($data))
    	{
    		//没有查询到数据,证明这个小票还没有被别人使用, 可以使用
    		return NULL;
    	}
    	else//有记录, 已经有人使用过这个小票
    	{
    		
    		$row=$data[0];
    		return $row;
    	}
    	 
    }   
    
    
    
    
    /**
     * 会员绑定
     *
     * @param  string $str_weixin_id 用户的微信号
     * @return string 0:插入成功
     * 				  1:有fake_id的记录,重新更新用户的昵称
     * 				  2:执行sql错误
     * 					
     */
    public function bind_member($str_weixin_id="")
    {

    	$tmp_test=$this->get_member_info($this->str_post_fakeid);
    	
    	if (isset($tmp_test))
    	{
    		//已经有用户的记录,重新更新用户的昵称
    		
    		
    		$this->str_member_no=$tmp_test['member_no'];//会员内部ID
    		$this->str_weixin_id=$str_weixin_id;//要更新的会员昵称
    		$this->str_join_date=$tmp_test['join_date'];//会员加入的日期
    		$this->bonus_before=$tmp_test['bonus'];//会员目前的积分
			$this->str_post_fakeid=$this->str_post_fakeid;//会员的fake_id
    		
    		$this->str_exe_sql = 'UPDATE tb_member SET weixin_id="' . $this->str_weixin_id . '" WHERE fake_id="' . $this->str_post_fakeid . '"';
    		
    		//return $sql;
    		$this->mysql->runSql($this->str_exe_sql);
    		
    		if( $this->mysql->errno() != 0 )
    		{
    			$this->str_sql_errmsg=$this->mysql->errmsg();
    			return 2;
    		}
    		//更新用户昵称成功
    		return 1;
    	}
    	else
    	{
    		$tmp_member_no=null;
    		$tmp_weixin_id=$str_weixin_id;
    		$tmp_join_date=null;
    		$tmp_bonus=0;
    		$tmp_fake_id=$this->str_post_fakeid; 

    		$this->str_exe_sql = 'INSERT INTO tb_member(`weixin_id`,`fake_id`) VALUES ("' . $tmp_weixin_id . '","' . $tmp_fake_id . '")';

    		//return $sql;
    		$this->mysql->runSql($this->str_exe_sql);

    		if( $this->mysql->errno() != 0 )
    		{
    			$this->str_sql_errmsg=$this->mysql->errmsg();
    			return 2;
    		}

    		$this->str_weixin_id=$tmp_weixin_id;
    		//加入会员成功
    		return 0;
    	}
    		
    	
    }
    
    
    
    
    
    
    /**
     * 会员消费
     *
     * @param  string $str_bill_no 小单的单号
     * @param  string $str_amt 小单的消费金额
     * @return array [0]	0:插入成功; 
     *	    	          	1:bill_no重复, 已经有人使用这个账单
     * 				  		2:执行sql错误
     *               [1]	小单的单号
     *               [2]	小票的金额
     *               [3]	会员累计前的积分
     *               [4]	会员累计后的积分;若[0]<>0的时候,字段无意义
     *               [5]	[0]=0,NULL ; [0]=1,为别人累计成功的时间
     *               [6]	[0]=0,NULL ; [0]=1,为别人(累计人)的内部会员ID
     *               [7]    [0]=0,NULL ; [0]=1,为被人(累计人)的昵称
     *               [8]    会员累计前的互动积分
     *               [9]	会员累计后的互动积分;若[0]<>0的时候,字段无意义
     *               
     */
    public function add_consumption($tmp_bill_no,$tmp_amt)
    {
    	//获取用户的信息,查tb_member表,得出所有字段
    	$tmp_row=$this->get_member_info($this->str_post_fakeid);
    	$this->str_member_no=$tmp_row['member_no'];//会员内部ID
    	$this->str_weixin_id=$tmp_row['weixin_id'];//会员的昵称
    	$this->str_join_date=$tmp_row['join_date'];//会员加入的日期
    	$this->bonus_before=$tmp_row['bonus'];//会员目前的积分
    	$this->act_bonus_before=$tmp_row['act_bonus'];//会员目前的互动积分
    	
    	//根据0.03的比率来折算bonus
    	$tmp_added_bonus=round($tmp_amt*0.03);
    	//取整千分位
    	$tmp_added_bonus=round($tmp_added_bonus/1000)*1000;
    	
    	
    	//根据0.0001的比率来折算act_bonus
    	$tmp_added_act_bonus=round($tmp_amt*0.0001);

    	
    	
    	$this->bonus_after=$this->bonus_before + $tmp_added_bonus;//会员累计成功后的应有积分
    	$this->act_bonus_after=$this->act_bonus_before + $tmp_added_act_bonus;//会员累计成功后的应有的互动积分
    	
    	
    	//返回的数组
    	$arr_return[1]=$tmp_bill_no;
    	$arr_return[2]=$tmp_amt;
    	$arr_return[3]=$this->bonus_before;
    	$arr_return[4]=$this->bonus_after;
    	$arr_return[8]=$this->act_bonus_before;
    	$arr_return[9]=$this->act_bonus_after;
    	 
    	
    	
    	//在历史表中查找小票的单号
    	$tmp_row_bill=$this->get_bill_no($tmp_bill_no);
    	//如果数组有值,表明有人已经使用过这个小票
    	if (isset($tmp_row_bill))
    	{
    		//别人使用的时间
    		$arr_return[5]=$tmp_row_bill['exe_date'];
    		//被什么人使用过的
    		$arr_return[6]=$tmp_row_bill['member_no'];
    		$arr_return[0]=1;
    		//根据用户内部的ID,查找用户的昵称
    		$tmp_row_ID=$this->get_member_info_via_ID($arr_return[6]);
    		$arr_return[7]=$tmp_row_ID['weixin_id'];
    		
    		return $arr_return;
    	}
    	else//NULL,没有查询到数据,证明这个小票还没有被别人使用, 可以使用
    	{ 
    		
    		$tmp_suggest_info="店里消费,消费金额RP".$tmp_amt." , 累计消费积分".$tmp_added_bonus." , 累计互动积分".$tmp_added_act_bonus;
    		
    		//当前时间, 系统数据库中的时区不对, 不能自动填充
    		$tmp_current_timestamp=date("Y-m-d H:i:s",time()-3600);
    		
    		//插入的用户消费记录的SQL
    		//$this->str_exe_sql = 'INSERT INTO tb_his_consume(`bill_no`,`amt`,`bonus_before`,`bonus_after`,`member_no`,`fake_id`,`suggest_info`) VALUES ("' . $tmp_bill_no . '",' . $tmp_amt . ','. $this->bonus_before . ',' . $this->bonus_after . ',"' . $this->str_member_no .'","'.$this->str_post_fakeid.'",'.$tmp_suggest_info.'");';
    		//$this->str_exe_sql = "INSERT INTO tb_his_consume(`bill_no`,`amt`,`bonus_before`,`bonus_after`,`act_bonus_before`,`act_bonus_after`,`member_no`,`fake_id`,`suggest_info`) VALUES (\"$tmp_bill_no\",$tmp_amt,$this->bonus_before,$this->bonus_after,$this->act_bonus_before,$this->act_bonus_after,\"$this->str_member_no\",\"$this->str_post_fakeid\",\"$tmp_suggest_info\");";
    		$this->str_exe_sql = "INSERT INTO tb_his_consume(`bill_no`,`amt`,`bonus_before`,`bonus_after`,`act_bonus_before`,`act_bonus_after`,`member_no`,`fake_id`,`suggest_info`,`exe_date`) VALUES (\"$tmp_bill_no\",$tmp_amt,$this->bonus_before,$this->bonus_after,$this->act_bonus_before,$this->act_bonus_after,\"$this->str_member_no\",\"$this->str_post_fakeid\",\"$tmp_suggest_info\",\"$tmp_current_timestamp\");";
    		
    		//执行SQL
    		$this->mysql->runSql($this->str_exe_sql);
    		//执行SQL出错
    		if( $this->mysql->errno() != 0 )
    		{
    			//具体的出错原因
    			$this->str_sql_errmsg=$this->mysql->errmsg();
    			$arr_return[0]=2;
    			return $arr_return;
    		}
    		
    		//更改用户积分的SQL
    		$this->str_exe_sql ="UPDATE tb_member SET bonus=$this->bonus_after,act_bonus=$this->act_bonus_after WHERE fake_id=\"$this->str_post_fakeid\";";
    		
    		//执行SQL
    		$this->mysql->runSql($this->str_exe_sql);
    		//执行SQL出错
    		if( $this->mysql->errno() != 0 )
    		{
    			//具体的出错原因
    			$this->str_sql_errmsg=$this->mysql->errmsg();
    			$arr_return[0]=2;
    			return $arr_return;
    		}
    		
    		//插入SQL成功
    		$arr_return[0]=0;
    		return $arr_return;
    	}
    	
    }
    
    
    
    
    
    /**
     * 更新会员的消费积分和互动积分
     * @param  int		$bill_no			1: 积分增加； 2：积分扣减
     * @param  int		$amt_add_bonus		需要增加的消费积分
     * @param  int		$amt_add_act_bonus	需要增加的互动积分
     * @param  string	$str_suggest_info	填充suggest_info字段, 用于说明积分变动理由
     * @return array [0]	0:插入成功;
     *	    	          	1:保留
     * 				  		2:执行sql错误
     *               [1]	小单的单号
     *               [2]	小票的金额
     *               [3]	会员累计前的积分
     *               [4]	会员累计后的积分;若[0]<>0的时候,字段无意义
     *               [5]	[0]=0,NULL ; [0]=1,为别人累计成功的时间
     *               [6]	[0]=0,NULL ; [0]=1,为别人(累计人)的内部会员ID
     *               [7]    [0]=0,NULL ; [0]=1,为被人(累计人)的昵称
     *               [8]    会员累计前的互动积分
     *               [9]	会员累计后的互动积分;若[0]<>0的时候,字段无意义
     *
     */
    public function add_bonus_by_activity($bill_no,$amt_add_bonus,$amt_add_act_bonus,$str_suggest_info)
    {
    	
    	//获取用户的信息,查tb_member表,得出所有字段
    	$tmp_row=$this->get_member_info($this->str_post_fakeid);
    	
    	if (!isset($tmp_row))
    	{
    		//没有查询到数据,没有记录
    		$arr_return[0]=2;
    		$this->str_sql_errmsg="系统忙,会稍后处理";
    		$this->str_exe_sql="$this->str_post_fakeid";
    		return $arr_return;
    	}
    	
    	
    	$this->str_member_no=$tmp_row['member_no'];//会员内部ID
    	$this->str_weixin_id=$tmp_row['weixin_id'];//会员的昵称
    	$this->str_join_date=$tmp_row['join_date'];//会员加入的日期
    	$this->bonus_before=$tmp_row['bonus'];//会员目前的积分
    	$this->act_bonus_before=$tmp_row['act_bonus'];//会员目前的互动积分
    	     	 
    	$this->bonus_after=$this->bonus_before + $amt_add_bonus;//会员累计成功后的应有积分
    	$this->act_bonus_after=$this->act_bonus_before + $amt_add_act_bonus;//会员累计成功后的应有的互动积分
    	 
    	 
    	//返回的数组
    	$arr_return[1]="";
    	$arr_return[2]=0;
    	$arr_return[3]=$this->bonus_before;
    	$arr_return[4]=$this->bonus_after;
    	$arr_return[8]=$this->act_bonus_before;
    	$arr_return[9]=$this->act_bonus_after;
    	
    	//当前时间, 系统数据库中的时区不对, 不能自动填充
    	$tmp_current_timestamp=date("Y-m-d H:i:s",time()-3600);
    	
    	//插入的用户消费记录的SQL
    	//$this->str_exe_sql = "INSERT INTO tb_his_consume(`bonus_before`,`bonus_after`,`act_bonus_before`,`act_bonus_after`,`member_no`,`fake_id`,`suggest_info`) VALUES ($this->bonus_before,$this->bonus_after,$this->act_bonus_before,$this->act_bonus_after,\"$this->str_member_no\",\"$this->str_post_fakeid\",\"$str_suggest_info\");";
    	$this->str_exe_sql = "INSERT INTO tb_his_consume(`bill_no`,`bonus_before`,`bonus_after`,`act_bonus_before`,`act_bonus_after`,`member_no`,`fake_id`,`suggest_info`,`exe_date`) VALUES ($bill_no,$this->bonus_before,$this->bonus_after,$this->act_bonus_before,$this->act_bonus_after,\"$this->str_member_no\",\"$this->str_post_fakeid\",\"$str_suggest_info\",\"$tmp_current_timestamp\");";
    	
    	//执行SQL
    	$this->mysql->runSql($this->str_exe_sql);
    	//执行SQL出错
    	if( $this->mysql->errno() != 0 )
    	{
    		//具体的出错原因
    		$this->str_sql_errmsg=$this->mysql->errmsg();
    		$arr_return[0]=2;
    		return $arr_return;
    	}
    	
    	//更改用户积分的SQL
    	$this->str_exe_sql ="UPDATE tb_member SET bonus=$this->bonus_after,act_bonus=$this->act_bonus_after WHERE fake_id=\"$this->str_post_fakeid\";";
    	
    	//执行SQL
    	$this->mysql->runSql($this->str_exe_sql);
    	//执行SQL出错
    	if( $this->mysql->errno() != 0 )
    	{
    		//具体的出错原因
    		$this->str_sql_errmsg=$this->mysql->errmsg();
    		$arr_return[0]=2;
    		return $arr_return;
    	}
    	
    	//插入SQL成功
    	$arr_return[0]=0;
    	return $arr_return;
    	
    
    }
    
    
    
    /**
     * 更新会员的上次签到时间戳和连续签到天数
     *
     * @param  int		$tmp_cur_timestamp	当前的时间戳
     * @param  int		$tmp_con_sign_days	连续签到的天数
     * @return array [0]	0:插入成功;
     *	    	          	1:保留
     * 				  		2:执行sql错误
     *               [1]	小单的单号
     *               [2]	小票的金额
     *               [3]	会员累计前的积分
     *               [4]	会员累计后的积分;若[0]<>0的时候,字段无意义
     *               [5]	[0]=0,NULL ; [0]=1,为别人累计成功的时间
     *               [6]	[0]=0,NULL ; [0]=1,为别人(累计人)的内部会员ID
     *               [7]    [0]=0,NULL ; [0]=1,为被人(累计人)的昵称
     *               [8]    会员累计前的互动积分
     *               [9]	会员累计后的互动积分;若[0]<>0的时候,字段无意义
     *
     */
    public function update_sign_info($tmp_cur_timestamp,$tmp_con_sign_days)
    {

    	
    	//更改更新会员的上次签到时间戳和连续签到天数
    	$this->str_exe_sql ="UPDATE tb_member SET last_sign_date=\"$tmp_cur_timestamp\",continue_sign_days=$tmp_con_sign_days WHERE fake_id=\"$this->str_post_fakeid\";";
    	 
    	//执行SQL
    	$this->mysql->runSql($this->str_exe_sql);
    	//执行SQL出错
    	if( $this->mysql->errno() != 0 )
    	{
    		//具体的出错原因
    		$this->str_sql_errmsg=$this->mysql->errmsg();
    		$arr_return[0]=2;
    		return $arr_return;
    	}
    	 
    	//插入SQL成功
    	$arr_return[0]=0;
    	return $arr_return;
    	 
    	
    
    }
    	 
    
    
    
    
    /**
     * 会员消费历史记录查询, 管理员专用
     *
     * @param  NULL
     * @return string NULL		: 没有查询到数据
     * 				  $data		: 所有的会员消费历史记录查询, 有多条记录
     *
     */
    public function query_his()
    {
    	
    	
    	
    	
    	$this->str_exe_sql = 'SELECT * FROM  `tb_his_consume` WHERE fake_id = "' . $this->str_post_fakeid .'"ORDER BY exe_date DESC LIMIT 10';
    	$data = $this->mysql->getData($this->str_exe_sql);
    	
    	if (!isset($data))
    	{
    		//没有查询到数据,没有消费记录
    		return NULL;
    	}
    	else//有记录, 返回消费记录
    	{
    		return $data;
    	}
    	
    	
    }
    
    
    
    
    /**
     * 所有的留言的临时记录查询, 管理员专用
     *
     * @param  NULL
     * @return string NULL		: 没有查询到数据
     * 				  $data		: 所有的留言信息, 有多条记录
     *
     */
    public function query_note_his()
    {
    	 
    	 
    	$this->str_exe_sql = 'SELECT * FROM  `tb_customer_note` ORDER BY exe_date desc';
    	$data = $this->mysql->getData($this->str_exe_sql);
    	 
    	if (!isset($data))
    	{
    		//没有查询到数据,没有消费记录
    		return NULL;
    	}
    	else//有记录, 返回消费记录
    	{
    		return $data;
    	}
    	 
    	 
    }
    
    
    /**
     * 查询所有的会员信息管理, 管理员专用
     *
     * @param  NULL
     * @return string NULL		: 没有查询到数据
     * 				  $data		: 所有的会员信息, 有多条记录
     *
     */
    public function query_member_info()
    {
    
    
    	$this->str_exe_sql = 'SELECT * FROM  `tb_member` ORDER BY join_date desc';
    	$data = $this->mysql->getData($this->str_exe_sql);
    
    	if (!isset($data))
    	{
    		//没有查询到数据,没有消费记录
    		return NULL;
    	}
    	else//有记录, 返回消费记录
    	{
    		return $data;
    	}
    
    
    }
     
    
    /**
     * 查询会员的历史记录
     *
     * @param  NULL
     * @return string NULL		: 没有查询到数据
     * 				  $data		: 会员的历史记录, 有多条记录
     *
     */
    public function query_member_his()
    {
    
    
    	$this->str_exe_sql = 'SELECT * FROM  `tb_his_consume` where DateDiff(exe_date,CURdate())>=-1 ORDER BY exe_date desc';
    	$data = $this->mysql->getData($this->str_exe_sql);
    
    	if (!isset($data))
    	{
    		//没有查询到数据,没有消费记录
    		return NULL;
    	}
    	else//有记录, 返回消费记录
    	{
    		return $data;
    	}
    
    
    }
    
    
    
    /**
     * 一站到底数据库操作-------查找题库中的随机一道题目
     *
     * @param  NULL
     * @return string NULL		: 没有查询到数据
     * 				  $data[0]	: 题目的信息,有且只有一条记录
     *
     */
    public function query_question_bank_random()
    {
    
    	$this->str_exe_sql = 'SELECT * FROM `question_bank` ORDER BY RAND() LIMIT 1';
    	$data = $this->mysql->getData($this->str_exe_sql);
    
    	if (!isset($data))
    	{
    		//没有查询到数据
    		return NULL;
    	}
    	else//有记录, 题目数据
    	{
    		//只返回第一条记录
    		return $data[0];
    	}
    
    
    }

    
    
    /**
     * 一站到底数据库操作-------根据题目的ID查找题目的具体信息
     *
     * @param  $tmp_question_id   题目的ID
     * @return string NULL		: 没有查询到数据
     * 				  $data[0]	: 题目的信息,有且只有一条记录
     *
     */
    public function query_question_bank_byID($tmp_question_id)
    {
    
    	$this->str_exe_sql = "SELECT * FROM `question_bank` where `id`='{$tmp_question_id}';";
    	$data = $this->mysql->getData($this->str_exe_sql);
    
    	if (!isset($data))
    	{
    		//没有查询到数据
    		return NULL;
    	}
    	else//有记录, 题目数据
    	{
    		//只返回第一条记录
    		return $data[0];
    	}
    
    
    }
    
    
    
    /**
     * 一站到底数据库操作-------查找用户答题的历史记录
     *
     * @param  NULL
     * @return string NULL		: 没有查询到数据
     * 				  $data[0]	: 用户的答题记录,有且只有一条记录
     *
     */
    public function query_question_user()
    {
    
    	$this->str_exe_sql = "SELECT * FROM `question_user` WHERE `openid`='{$this->str_post_fakeid}'";
    	$data = $this->mysql->getData($this->str_exe_sql);
    
    	if (!isset($data))
    	{
    		//没有查询到数据
    		return NULL;
    	}
    	else//有记录, 题目数据
    	{
    		//只返回第一条记录
    		return $data[0];
    	}
    
    
    }
    

    
    
    /**
     * 一站到底数据库操作-------查找用户答题的排行榜
     *
     * @param  NULL
     * @return string NULL		: 没有查询到数据
     * 				  $data		: 返回所有的数据
     *
     */
    public function query_question_user_board()
    {
    
    	/*返回的数据表的字段为:
		member_no
		weixin_id
		openid
		reply_num
		score
		*/
    	
    	
    	//$this->str_exe_sql = "SELECT * FROM `question_user` ORDER BY `score` desc";
    	$this->str_exe_sql = "SELECT tb_member.member_no as member_no,tb_member.weixin_id as weixin_id,question_user.openid as openid,question_user.reply_num as reply_num,question_user.score as score FROM  `question_user`,`tb_member` where question_user.openid=tb_member.fake_id  ORDER BY  question_user.score DESC";
    	$data = $this->mysql->getData($this->str_exe_sql);
    
    	if (!isset($data))
    	{
    		//没有查询到数据
    		return NULL;
    	}
    	else//有记录, 题目数据
    	{
    		//只返回第一条记录
    		return $data;
    	}
    
    
    }
    
    
    
    /**
     * 插入用户答题历史记录表(用户第一次参加活动)
     *
     * @param  string	$tmp_question_user_row['openid']	用户的fake_id
     * @param  int		$tmp_question_user_row['qid']	用户答题的问题ID(正在进行ing)
     * @param  int		$tmp_question_user_row['reply_num']	用户尝试答题的累计数量
     * @param  int		$tmp_question_user_row['right_num']	用户连续回答正确的数量
     * @param  int		$tmp_question_user_row['score']	用户的累计分值,暂无用途
     * @param  datetime	$tmp_question_user_row['lastdate']	用户选中题目的时间
     * @return string 0:插入数据成功
     * 				  1:保留
     * 				  2:执行sql错误
     *
     */
    public function insert_question_user($tmp_question_user_row)
    {
    
    	//构造插入SQL
    	$this->str_exe_sql ="INSERT INTO `question_user`(`id`, `openid`, `qid`, `reply_num`, `right_num`, `score`, `lastdate`) VALUES (null,'{$tmp_question_user_row['openid']}','{$tmp_question_user_row['qid']}','{$tmp_question_user_row['reply_num']}','{$tmp_question_user_row['right_num']}','{$tmp_question_user_row['score']}','{$tmp_question_user_row['lastdate']}')";
    
    	//执行SQL
    	$this->mysql->runSql($this->str_exe_sql);
    	//执行SQL出错
    	if( $this->mysql->errno() != 0 )
    	{
    		//具体的出错原因
    		$this->str_sql_errmsg=$this->mysql->errmsg();
    		return 2;
    	}
    
    	//插入SQL成功
    	return 0;
    
    	 
    
    }
    
 
    
    

    /**
     * 更新用户答题历史记录表(用户每次回答问题后，都需要调用此过程)
     *
     * @param  string	$tmp_question_user_row['openid']	用户的fake_id
     * @param  int		$tmp_question_user_row['qid']	用户答题的问题ID(正在进行ing)
     * @param  int		$tmp_question_user_row['reply_num']	用户尝试答题的累计数量
     * @param  int		$tmp_question_user_row['right_num']	用户连续回答正确的数量
     * @param  int		$tmp_question_user_row['score']	用户的累计分值,暂无用途
     * @param  datetime	$tmp_question_user_row['lastdate']	用户选中题目的时间
     * @return string 0:更新数据成功
     * 				  1:保留
     * 				  2:执行sql错误
     *
     */
    public function update_question_user($tmp_question_user_row)
    {
    
    	//构造插入SQL
    	$this->str_exe_sql ="UPDATE `question_user` SET `qid`='{$tmp_question_user_row['qid']}',`reply_num`='{$tmp_question_user_row['reply_num']}',`right_num`='{$tmp_question_user_row['right_num']}',`score`='{$tmp_question_user_row['score']}',`lastdate`='{$tmp_question_user_row['lastdate']}' WHERE `openid`='{$tmp_question_user_row['openid']}'";
    
    	//执行SQL
    	$this->mysql->runSql($this->str_exe_sql);
    	//执行SQL出错
    	if( $this->mysql->errno() != 0 )
    	{
    		//具体的出错原因
    		$this->str_sql_errmsg=$this->mysql->errmsg();
    		return 2;
    	}
    
    	//更新SQL成功
    	return 0;
    
    
    
    }    
    
    
    
    
    
    
    /**
     * 会员推荐人功能
     * @param  $str_suggest_mem_no 推荐人的会员号码
     * @return string 0:处理成功
     * 				  1:使用人还不是会员,不能使用会员推荐人功能
     * 				  3:推荐人不存在,没有根据推荐人的会员号码找到会员信息
     * 				  2:执行sql错误
     * 				  4:已经有推荐人了(是本人),本人不能推荐本人的
     *                5:已经有推荐人了(非本人),不能重复使用此功能
     * 
     *
     */
    //被推荐人A  推荐人B    A由B推荐,而加入会员   B推荐了--->A
    public function member_suggest($str_suggest_mem_no)
    {
    	$tmp_row=$this->get_member_info($this->str_post_fakeid);
    	if (!isset($tmp_row))
    	{//使用人A还不是会员,不能使用会员推荐人功能
    		return 1;
    	}
    	else//是会员,进行后续流程
    	{
    		//提取会员A的信息----被推荐人A
    		$tmp_member_no_a=$tmp_row['member_no'];
    		$tmp_weixin_id_a=$tmp_row['weixin_id'];
    		$tmp_join_date_a=$tmp_row['join_date'];
    		$tmp_bonus_a=$tmp_row['bonus'];
    		$tmp_fake_id_a=$tmp_row['fake_id'];
    		$tmp_suggest_mem_no_a=$tmp_row['suggest_mem_no'];
    		
    		//已经有推荐人,不能重复使用这个功能推荐
    		if ($tmp_suggest_mem_no_a!=0)
    		{
    				return 5;//已经有推荐人了(非本人),不能重复使用此功能	
    		}
    		
    		//已经有推荐人了(是本人),本人不能推荐本人的
    		if ($str_suggest_mem_no==$tmp_member_no_a)
    		{
    			return 4;//已经有推荐人了(是本人),本人不能推荐本人的
    		}
    	
    		
    		//校验推荐人的会员号码是否正确
    		$tmp_row_ID=$this->get_member_info_via_ID($str_suggest_mem_no);
    		
    		if (!isset($tmp_row_ID))
    		{//没有根据推荐人的会员号码找到会员信息
    			return 3;
    		}
    		//找到了推荐人的会员信息,进行后续流程
    		
    		//提取会员B的信息-----推荐人B
    		$tmp_member_no_b=$tmp_row_ID['member_no'];
    		$tmp_weixin_id_b=$tmp_row_ID['weixin_id'];
    		$tmp_join_date_b=$tmp_row_ID['join_date'];
    		$tmp_bonus_b=$tmp_row_ID['bonus'];
    		$tmp_act_bonus_b=$tmp_row_ID['act_bonus'];
    		$tmp_fake_id_b=$tmp_row_ID['fake_id'];
    		
    		//更新被推荐人的信息,suggest_mem_no字段------被推荐人A
    		$this->str_exe_sql = 'update tb_member set suggest_mem_no='.$str_suggest_mem_no.' where fake_id="'.$this->str_post_fakeid.'"';
    		//执行SQL
    		$this->mysql->runSql($this->str_exe_sql);
    		//执行SQL出错
    		if( $this->mysql->errno() != 0 )
    		{
    			//具体的出错原因
    			$this->str_sql_errmsg=$this->mysql->errmsg();
    			return 2;
    		}
    		
    		
    		//更新推荐人的会员信息,增加积分------推荐人B
    		//推荐人B获得会员消费积分50,000, 互动积分50
    		$this->str_exe_sql = 'update tb_member set bonus=bonus+50000,act_bonus=act_bonus+50 where member_no='.$str_suggest_mem_no;
    		//执行SQL
    		$this->mysql->runSql($this->str_exe_sql);
    		//执行SQL出错
    		if( $this->mysql->errno() != 0 )
    		{
    			//具体的出错原因
    			$this->str_sql_errmsg=$this->mysql->errmsg();
    			return 2;
    		}
    		
    		
    		//更新用户消费记录表,增加记录------推荐人B的消费记录
    		$tmp_tmp=$tmp_bonus_b+50000;
    		$tmp_tmp_act=$tmp_act_bonus_b+50;
    		$tmp_suggest_info="介绍新会员(会员ID为$tmp_weixin_id_a),增加消费积分50000, 互动积分50";
    		
    		
    		//当前时间, 系统数据库中的时区不对, 不能自动填充
    		$tmp_current_timestamp=date("Y-m-d H:i:s",time()-3600);
    		
    		//$this->str_exe_sql = 'INSERT INTO tb_his_consume(`suggest_info`,`amt`,`bonus_before`,`bonus_after`,`member_no`,`fake_id`) VALUES ("' . $tmp_weixin_id_a. '(会员号码为' .$tmp_member_no_a . ')",' . '50000' . ','. $tmp_bonus_b . ',' . $tmp_tmp . ',"' . $tmp_member_no_b .'","'.$tmp_fake_id_b.'");';
    		//$this->str_exe_sql = "INSERT INTO tb_his_consume(`suggest_info`,`amt`,`bonus_before`,`bonus_after`,`act_bonus_before`,`act_bonus_after`,`member_no`,`fake_id`) VALUES (\"$tmp_suggest_info\",50000,$tmp_bonus_b,$tmp_tmp,$tmp_act_bonus_b,$tmp_tmp_act,\"$tmp_member_no_b\",\"$tmp_fake_id_b\");";
    		$this->str_exe_sql = "INSERT INTO tb_his_consume(`suggest_info`,`amt`,`bonus_before`,`bonus_after`,`act_bonus_before`,`act_bonus_after`,`member_no`,`fake_id`,`exe_date`) VALUES (\"$tmp_suggest_info\",50000,$tmp_bonus_b,$tmp_tmp,$tmp_act_bonus_b,$tmp_tmp_act,\"$tmp_member_no_b\",\"$tmp_fake_id_b\",\"$tmp_current_timestamp\");";
    		//执行SQL
    		$this->mysql->runSql($this->str_exe_sql);
    		//执行SQL出错
    		if( $this->mysql->errno() != 0 )
    		{
    			//具体的出错原因
    			$this->str_sql_errmsg=$this->mysql->errmsg();
    			return 2;
    		}
    		
    		return 0;
    		
    	}
    
    }
    
    
    
    /**
     * 用户发送'留言 XXX', 将留言存入数据库
     *
     * @param  string $str_note 用户的留言
     * @return string 0:插入成功
     * 				  1:保留
     * 				  2:执行sql错误
     *
     */
    public function insert_customer_note($str_note)
    {
    
    	//当前时间, 系统数据库中的时区不对, 不能自动填充
    	$tmp_current_timestamp=date("Y-m-d H:i:s",time()-3600);
    	
    	//进入这个函数之前,需要判断是否是会员, 是会员的话,才可以进入此函数
    	$this->str_exe_sql = "INSERT INTO tb_customer_note(`fake_id`,`member_no`,`weixin_id`,`exe_date`,`note_content`) VALUES (\"$this->str_post_fakeid\",\"$this->str_member_no\",\"$this->str_weixin_id\",\"$tmp_current_timestamp\",\"$str_note\");";
    	//执行SQL
    	$this->mysql->runSql($this->str_exe_sql);
    	//执行SQL出错
    	if( $this->mysql->errno() != 0 )
    	{
    		//具体的出错原因
    		$this->str_sql_errmsg=$this->mysql->errmsg();
    		return 2;
    	}
    	
    	//echo $this->str_exe_sql;
    	return 0;
    	
    	
    }
    

    
    /**
     * 插入用户订餐历史记录表
     *
     * @param  $fake_id 用户的fake_id	
     * @param  $order_content 点餐内容(以匹配名称)	
     * @param  $order_hp 用户点餐联系电话	
     * @param  $order_add 用户点餐地址	
     * @param  $order_note 用户点餐备注
     * @param  $order_sms 点餐短信(已拼凑)	
     * @param  $suceed_sms 是否给店里发送短信成功
     * @param  $sms_return_content 错误短信的返回信息
     * @return string 0:插入成功
     * 				  1:保留
     * 				  2:执行sql错误
     *
     */
    
    public function insert_order_history($fake_id,$order_content,$order_hp,$order_add,$order_note,$order_sms,$suceed_sms,$sms_return_content)
    {
    
    	//当前时间, 系统数据库中的时区不对, 不能自动填充
    	$tmp_current_timestamp=date("Y-m-d H:i:s",time()-3600);
    	 
    	//进入这个函数之前,需要判断是否是会员, 是会员的话,才可以进入此函数
    	$this->str_exe_sql = "INSERT INTO order_history(`fake_id`,`order_content`,`order_hp`,`order_add`,`order_note`,`order_sms`,`suceed_sms`,`sms_return_content`,`order_exe_date`) VALUES (\"$fake_id\",\"$order_content\",\"$order_hp\",\"$order_add\",\"$order_note\",\"$order_sms\",\"$suceed_sms\",\"$sms_return_content\",\"$tmp_current_timestamp\");";
    	//执行SQL
    	$this->mysql->runSql($this->str_exe_sql);
    	//执行SQL出错
    	if( $this->mysql->errno() != 0 )
    	{
    		//具体的出错原因
    		$this->str_sql_errmsg=$this->mysql->errmsg();
    		return 2;
    	}
    	 
    	//echo $this->str_exe_sql;
    	return 0;
    	 
    	 
    }
    
    
    
    /**
     * 调出最后一条用的户订餐历史记录表
     *
     * @param  $fake_id 用户的fake_id
     * @return string NULL		: 没有查询到数据
     * 				  $data[0]	: 最后的订餐记录,有且只有一条记录
     *
     */
    
    public function get_last_order_info($fake_id)
    {
    
        $this->str_exe_sql = "SELECT * FROM `order_history` where fake_id=\"{$fake_id}\" ORDER BY order_exe_date desc limit 1;";
    	$data = $this->mysql->getData($this->str_exe_sql);
    
    	if (!isset($data))
    	{
    		//没有查询到数据
    		return NULL;
    	}
    	else//有记录, 题目数据
    	{
    		//只返回第一条记录
    		return $data[0];
    	}
    
    
    }
    
    
    
    
    /**
     * 查找订餐历史记录表中的最大序号
     *
     * @param  NULL
     * @return string NULL		: 没有查询到数据
     * 				  $data[0]	: 最大的record_id
     *
     */
    
    public function get_last_order_max()
    {
    
    	$this->str_exe_sql = "SELECT max(record_id) FROM `order_history`;";
    	$data = $this->mysql->getData($this->str_exe_sql);
    
    	if (!isset($data))
    	{
    		//没有查询到数据
    		return NULL;
    	}
    	else//有记录, 题目数据
    	{
    		//只返回第一条记录
    		return $data[0];
    	}
    
    
    }
    
    
    
    
    
    
    /**
     * 判断用户是否在管理员组中, 多用于某些函数处理前的鉴权
     *
     * @param  null
     * @return string 0:属于管理员组
     * 				  1:保留
     * 				  2:不属于管理员组
     *
     */
    public function check_is_admin()
    {
    
    	if (in_array ($this->str_post_fakeid, $this->admin_gp))
    	{
    		return 0;
    	}
    	else
    	{
    		return 2;
    	}

    	 
    }
    
    
    
    
    
    
    
    
    /**
     * 判断用户是否已经参加过"2014年新加入"活动(表tb_2014_new_member)
     *
     * @param  $fake_id 用户的fake_id
     * @return string null:用户没有参加过活动(表中没有数据)
     * 				  date[0]:第一条数据,用户已经参加过活动(表中有数据)
     *
     */
    
    public function check_join_2014_new_member($fake_id)
    {
    
		//查询表数据, 有数据,则已经参加过此活动;如果没有数据,则没有参加过此活动
    	$this->str_exe_sql = "SELECT * FROM `tb_2014_new_member` WHERE `fake_id`='{$fake_id}'";
    	$data = $this->mysql->getData($this->str_exe_sql);
    	
    	if (!isset($data))
    	{
    		//没有查询到数据
    		return null;
    	}
    	else//有记录
    	{
    		//只返回第一条记录
    		return $data[0];
    	}    

    
    }    
    
    
    
    
    
    
    /**
     * 检查用户消费后输入的消费累积码
     *
     * @param  $bonus_id 用户消费后的消费累计码
     * @return string null:没有这个累计码
     * 				  date[0]:第一条数据,用户输入的累计码的详细数据
     *
     */
    
    public function check_consume_bonus_id($bonus_id)
    {
    
    	//查询表数据, 有数据,则已经参加过此活动;如果没有数据,则没有参加过此活动
    	$this->str_exe_sql = "SELECT * FROM `tb_consume_bonus_id` WHERE `consume_bonus_id`='{$bonus_id}'";
    	$data = $this->mysql->getData($this->str_exe_sql);
    	 
    	if (!isset($data))
    	{
    		//没有查询到数据
    		return null;
    	}
    	else//有记录
    	{
    		//只返回第一条记录
    		return $data[0];
    	}
    
    
    }
    
    

    /**
     * 检查输入的扣减积分码
     *
     * @param  $deduct_id 用户输入的积分扣减码
     * @return string null:没有这个扣减码
     * 				  date[0]:第一条数据,用户输入的扣减码的详细数据
     *
     */
    
    public function check_deduct_id($deduct_id)
    {
    
    	//查询表数据, 有数据,则已经参加过此活动;如果没有数据,则没有参加过此活动
    	$this->str_exe_sql = "SELECT * FROM `tb_exchange_check_id` WHERE `check_id`='{$deduct_id}'";
    	$data = $this->mysql->getData($this->str_exe_sql);
    
    	if (!isset($data))
    	{
    		//没有查询到数据
    		return null;
    	}
    	else//有记录
    	{
    		//只返回第一条记录
    		return $data[0];
    	}
    
    
    }
    
    
    /**
     * 更新voucher的状态(已经成功被使用)
     *
     * @param  $consume_bonus_id 已经成功被使用的voucher ID
     * @return string 0:更新数据成功
     * 				  1:保留
     * 				  2:执行sql错误
     *
     */
    
    public function update_consume_bonus_id($consume_bonus_id)
    {
    
    	//当前时间, 系统数据库中的时区不对, 不能自动填充
    	$tmp_current_timestamp=date("Y-m-d H:i:s",time()-3600);
    	
    	//构造插入SQL
    	$this->str_exe_sql ="UPDATE `tb_consume_bonus_id` SET `already_used`=1,`used_datetime`='{$tmp_current_timestamp}',`fake_id`='{$this->str_post_fakeid}' WHERE `consume_bonus_id`='{$consume_bonus_id}'";
    	
    	//执行SQL
    	$this->mysql->runSql($this->str_exe_sql);
    	//执行SQL出错
    	if( $this->mysql->errno() != 0 )
    	{
    		//具体的出错原因
    		$this->str_sql_errmsg=$this->mysql->errmsg();
    		return 2;
    	}
    	
    	//更新SQL成功
    	return 0;
    	
    	
    	
    
    }
    
    
    
    
    /**
     * 更新积分扣减码的状态(已经成功被使用)
     *
     * @param  $deduct_id_judge 已经成功被使用的积分扣减码
     * @param  $bonus_amt_after 扣减后，用户剩余的积分
     * 
     * @return string 0:更新数据成功
     * 				  1:保留
     * 				  2:执行sql错误
     *
     */
    
    public function update_dedcut_id($deduct_id_judge,$bonus_amt_after)
    {
    
    	//当前时间, 系统数据库中的时区不对, 不能自动填充
    	$tmp_current_timestamp=date("Y-m-d H:i:s",time()-3600);
    	 
    	//构造插入SQL
    	$this->str_exe_sql ="UPDATE `tb_exchange_check_id` SET `already_used`=1,`used_datetime`='{$tmp_current_timestamp}',`fake_id`='{$this->str_post_fakeid}',`member_no`={$this->str_member_no},`point_left`='{$bonus_amt_after}' WHERE `check_id`='{$deduct_id_judge}'";
    	 
    	//执行SQL
    	$this->mysql->runSql($this->str_exe_sql);
    	//执行SQL出错
    	if( $this->mysql->errno() != 0 )
    	{
    		//具体的出错原因
    		$this->str_sql_errmsg=$this->mysql->errmsg();
    		return 2;
    	}
    	 
    	//echo $this->str_exe_sql;
    	
    	//更新SQL成功
    	return 0;
    	 
    	 
    	 
    
    }
    
    
    

    /**
     * 插入2014新加入用户表
     *
     * @param  $fake_id 用户的fake_id
     * @param  $activity_id 用户的活动码
     * @return string 0:插入成功
     * 				  1:保留
     * 				  2:执行sql错误
     *
     */
    public function insert_2014_new_join($fake_id,$activity_id)
    {
    
    	//当前时间, 系统数据库中的时区不对, 不能自动填充
    	$tmp_current_timestamp=date("Y-m-d H:i:s",time()-3600);
    
    	//构造插入SQL
    	$this->str_exe_sql = "INSERT INTO tb_2014_new_member(`fake_id`,`activity_id`,`exe_date`) VALUES (\"$fake_id\",\"$activity_id\",\"$tmp_current_timestamp\");";
    	//执行SQL
    	$this->mysql->runSql($this->str_exe_sql);
    	//执行SQL出错
    	if( $this->mysql->errno() != 0 )
    	{
    		//具体的出错原因
    		$this->str_sql_errmsg=$this->mysql->errmsg();
    		return 2;
    	}
    
    	//echo $this->str_exe_sql;
    	return 0;
     
    }
    
    
    
    
    
    
    
    
    /**
     * 插入错误处理消息表
     *
     * @param  $fake_id 用户的fake_id
     * @param  $err_msg 错误消息的说明
     * @return string 0:插入成功
     * 				  1:保留
     * 				  2:执行sql错误
     *
     */
    public function insert_err_msg($fake_id,$err_msg)
    {
    
    	//当前时间, 系统数据库中的时区不对, 不能自动填充
    	$tmp_current_timestamp=date("Y-m-d H:i:s",time()-3600);
    
    	//构造插入SQL
    	$this->str_exe_sql = "INSERT INTO tb_err_msg(`fake_id`,`err_msg`,`exe_date`) VALUES (\"$fake_id\",\"$err_msg\",\"$tmp_current_timestamp\");";
    	//执行SQL
    	$this->mysql->runSql($this->str_exe_sql);
    	//执行SQL出错
    	if( $this->mysql->errno() != 0 )
    	{
    		//具体的出错原因
    		$this->str_sql_errmsg=$this->mysql->errmsg();
    		return 2;
    	}
    
    	//echo $this->str_exe_sql;
    	return 0;
    	 
    }
    
    
    
    
    
    
    
    
    
    

    /**
     * 自定义的错误处理函数，将 PHP 错误通过文本消息回复显示
     * @param  int $level   错误代码
     * @param  string $msg  错误内容
     * @param  string $file 产生错误的文件
     * @param  int $line    产生错误的行数
     * @return void
     */
    public function errorHandler($level, $msg, $file, $line) {
      if ( ! $this->debug) {
        return;
      }

      $error_type = array(
        // E_ERROR             => 'Error',
        E_WARNING           => 'Warning',
        // E_PARSE             => 'Parse Error',
        E_NOTICE            => 'Notice',
        // E_CORE_ERROR        => 'Core Error',
        // E_CORE_WARNING      => 'Core Warning',
        // E_COMPILE_ERROR     => 'Compile Error',
        // E_COMPILE_WARNING   => 'Compile Warning',
        E_USER_ERROR        => 'User Error',
        E_USER_WARNING      => 'User Warning',
        E_USER_NOTICE       => 'User Notice',
        E_STRICT            => 'Strict',
        E_RECOVERABLE_ERROR => 'Recoverable Error',
        E_DEPRECATED        => 'Deprecated',
        E_USER_DEPRECATED   => 'User Deprecated',
      );

      $template = <<<ERR
PHP 报错啦！

%s: %s
File: %s
Line: %s
ERR;

      $str_err_content=sprintf($template,$error_type[$level],$msg,$file,$line);
      //exit($str_err_content);
      echo $str_err_content;
    }

  }


  
  
 
  
  
  
  

  
?>
  