<!DOCTYPE html> 
<html> 
	<head>
  <meta charset="UTF-8">	
	<title>印尼泗水中餐馆"重庆味道"</title> 
	
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<link rel="stylesheet" href="jquery.mobile.structure-1.0.1.css" />
	<link rel="apple-touch-icon" href="images/launch_icon_57.png" />
	<link rel="apple-touch-icon" sizes="72x72" href="images/launch_icon_72.png" />
	<link rel="apple-touch-icon" sizes="114x114" href="images/launch_icon_114.png" />
	<link rel="stylesheet" href="jquery.mobile-1.0.1.css" />
	<link rel="stylesheet" href="custom.css" />
	<style type="text/css">
	body,td,th {
}
    </style>
	<script src="js/jquery-1.7.1.min.js"></script>
	<script src="js/jquery.mobile-1.0.1.min.js"></script>
</head> 

<body> 
<div data-role="page" id="home" data-theme="c">

	<div data-role="content">
	
	<div id="branding">
		<h1>印尼泗水中餐馆"重庆味道" </h1>
	</div>
	
   <form name="form2" method="post" action="restaurant.php">

     <h1>点餐、订座说明:</h1>
     <ul>
       <li>在你要点的食物右边,选择你要点的数量;</li>
       <li>每种食物可订餐的份数最多为3份(白饭除外), 如有特殊要求,请打电话订餐;</li>
       <li><font color="red">如果订座，请先点一部分菜，我们会先和你打电话确认，之后我们即可提前准备；</font></li>
     </ul>

     <p>
    <input name="txt_user_fakeid" type="text" id="txt_user_fakeid" value="123" style="display: none;"/>
	
     </p>
     <h2>特色菜</h2>
	<script>
	var ds="<?php 
   if(is_array($_GET)&&count($_GET)>0)//先判断是否通过get传值了
   {
       if(isset($_GET["fakeid"]))//是否存在"id"的参数
       {
           $id=$_GET["fakeid"];//存在
           echo $id;
       }
       else
       {
       	   echo "null";
       }
   }
   else
   {
   	  echo "null";
   }
?>";
document.getElementById("txt_user_fakeid").value=ds;
	</script>
    
	<ul data-role="listview" data-inset="true" >
	<li> <img src="menu_pic/yoyo鸭_888.png" width="75" height="75"/>
	  <div data-role="fieldcontain">
	    <label for="selectmenu" class="select">   数量:</label>
	    <select name="slider_887" id="slider_887">
	      <option value="0" selected="selected">0</option>
	      <option value="1">1</option>
	      <option value="2">2</option>
          <option value="3">3</option>
	      </select>
	    </div>
	  <h2>887. yoyo鸭(半只) 68rb</h2>
        </li>
          <li> <img src="menu_pic/yoyo鸭_888.png" width="75" height="75"/>
	  <div data-role="fieldcontain">
	    <label for="selectmenu" class="select">   数量:</label>
	    <select name="slider_888" id="slider_888">
	      <option value="0" selected="selected">0</option>
	      <option value="1">1</option>
	      <option value="2">2</option>
          <option value="3">3</option>
	      </select>
	    </div>
	  <h2>888. yoyo鸭(一只) 119rb </h2>
          </li>
      <li> <img src="menu_pic/饺子_889.png" width="75" height="75"/>
	  <div data-role="fieldcontain">
	    <label for="selectmenu" class="select">   数量:</label>
	    <select name="slider_889" id="slider_889">
	      <option value="0" selected="selected">0</option>
	      <option value="1">1</option>
	      <option value="2">2</option>
          <option value="3">3</option>
	      </select>
	    </div>
	  <h2>889. 白菜猪肉饺 30rb/10bj </h2>
          </li>
          <li> <img src="menu_pic/饺子_889.png" width="75" height="75"/>
	  <div data-role="fieldcontain">
	    <label for="selectmenu" class="select">   数量:</label>
	    <select name="slider_8891" id="slider_8891">
	      <option value="0" selected="selected">0</option>
	      <option value="1">1</option>
	      <option value="2">2</option>
          <option value="3">3</option>
	      </select>
	    </div>
	  <h2>8891. 韭菜猪肉饺 30rb/10bj </h2>
          </li>
           <li> <img src="menu_pic/卤鸭头_609.png" width="75" height="75"/>
	  <div data-role="fieldcontain">
	    <label for="selectmenu" class="select">   数量:</label>
	    <select name="slider_609" id="slider_609">
	      <option value="0" selected="selected">0</option>
	      <option value="5">5</option>
	      <option value="10">10</option>
          <option value="15">15</option>
	      </select>
	    </div>
	  <h2>609. 卤鸭头 6rb/bj </h2>
          </li>
          <li> <img src="menu_pic/辣子鸡_301.png" width="75" height="75"/>
	  <div data-role="fieldcontain">
	    <label for="selectmenu" class="select">   数量:</label>
	    <select name="slider_301" id="slider_301">
	      <option value="0" selected="selected">0</option>
	      <option value="1">1</option>
	      <option value="2">2</option>
          <option value="3">3</option>
	      </select>
	    </div>
	  <h2>301. 辣子鸡 55rb </h2>
          </li>
    <li> <img src="menu_pic/干煸肥肠_302.png" alt="111" width="100" height="100"/>
	  <div data-role="fieldcontain">
	    <label for="selectmenu" class="select">   数量:</label>
	    <select name="slider_302" id="slider_302">
	      <option value="0" selected="selected">0</option>
	      <option value="1">1</option>
	      <option value="2">2</option>
          <option value="3">3</option>
	      </select>
	    </div>
      <h1></h1>
      <h2>302. 干煸肥肠 55rb</h2>
    </li>
    <li><img src="menu_pic/泡椒猪肝_303.png" alt="22" width="100" height="100"/>
  <div data-role="fieldcontain">
	    <label for="selectmenu" class="select">   数量:</label>
	    <select name="slider_303" id="slider_303">
	      <option value="0" selected="selected">0</option>
	      <option value="1">1</option>
	      <option value="2">2</option>
          <option value="3">3</option>
	      </select>
	    </div>
      <h1></h1>
      <h2>303. 泡椒猪肝 50rb </h2>
    </li>
    <li> <img src="menu_pic/香菜牛肉(麻辣)_3042.png" alt="222" width="100" height="100"/>
  <div data-role="fieldcontain">
	    <label for="selectmenu" class="select">   数量:</label>
	    <select name="slider_3042" id="slider_3042">
	      <option value="0" selected="selected">0</option>
	      <option value="1">1</option>
	      <option value="2">2</option>
          <option value="3">3</option>
	      </select>
	    </div>
      <h1></h1>
      <h2>3042. 香菜牛肉(麻辣) 55rb</h2>
    </li>
    <li> <img src="menu_pic/红烧肥肠_3051.png" alt="111" width="100" height="100"/>
  <div data-role="fieldcontain">
	    <label for="selectmenu" class="select">   数量:</label>
	    <select name="slider_3051" id="slider_3051">
	      <option value="0" selected="selected">0</option>
	      <option value="1">1</option>
	      <option value="2">2</option>
          <option value="3">3</option>
	      </select>
	    </div>
      <h1></h1>
      <h2>3051. 红烧肥肠 55rb</h2>
    </li>
    <li><img src="menu_pic/泡椒肥肠_3052.png" alt="22" width="100" height="100"/>
  <div data-role="fieldcontain">
	    <label for="selectmenu" class="select">   数量:</label>
	    <select name="slider_3052" id="slider_3052">
	      <option value="0" selected="selected">0</option>
	      <option value="1">1</option>
	      <option value="2">2</option>
          <option value="3">3</option>
	      </select>
	    </div>
      <h1></h1>
      <h2>3052. 泡椒肥肠 55rb</h2>
    </li>
    <li> <img src="menu_pic/水煮鱼_306.png" alt="111" width="100" height="100"/>
  <div data-role="fieldcontain">
	    <label for="selectmenu" class="select">   数量:</label>
	    <select name="slider_306" id="slider_306">
	      <option value="0" selected="selected">0</option>
	      <option value="1">1</option>
	      <option value="2">2</option>
          <option value="3">3</option>
	      </select>
	    </div>
      <h1></h1>
      <h2>306. 水煮鱼 98rb</h2>
    </li>
    <li> <img src="menu_pic/酸菜鱼(不辣)_3071.png" alt="22222" width="100" height="100"/>
  <div data-role="fieldcontain">
	    <label for="selectmenu" class="select">   数量:</label>
	    <select name="slider_3071" id="slider_3071">
	      <option value="0" selected="selected">0</option>
	      <option value="1">1</option>
	      <option value="2">2</option>
          <option value="3">3</option>
	      </select>
	    </div>
      <h1></h1>
      <h2>3071. 酸菜鱼(不辣) 98rb</h2>
    </li>
    <li> <img src="menu_pic/酸菜鱼(麻辣)_3072.png" alt="111" width="100" height="100"/>
      <div data-role="fieldcontain">
	    <label for="selectmenu" class="select">   数量:</label>
	    <select name="slider_3072" id="slider_3072">
	      <option value="0" selected="selected">0</option>
	      <option value="1">1</option>
	      <option value="2">2</option>
          <option value="3">3</option>
	      </select>
	    </div>
      <h1></h1>
      <h2>3072. 酸菜鱼(麻辣) 98rb</h2>
    </li>
    <li><img src="menu_pic/牙签肉_308.png" alt="22" width="75" height="75"/>
  <div data-role="fieldcontain">
	    <label for="selectmenu" class="select">   数量:</label>
	    <select name="slider_308" id="slider_308">
	      <option value="0" selected="selected">0</option>
	      <option value="1">1</option>
	      <option value="2">2</option>
          <option value="3">3</option>
	      </select>
	    </div>
      <h1></h1>
      <h2>308. 牙签肉 58rb</h2>
    </li>
    <li> <img src="menu_pic/回锅肉_309.png" alt="111" width="100" height="100"/>
  <div data-role="fieldcontain">
	    <label for="selectmenu" class="select">   数量:</label>
	    <select name="slider_309" id="slider_309">
	      <option value="0" selected="selected">0</option>
	      <option value="1">1</option>
	      <option value="2">2</option>
          <option value="3">3</option>
	      </select>
	    </div>
      <h1></h1>
      <h2>309.  回锅肉 58rb</h2>
    </li>
    <li> <img src="menu_pic/麻婆豆腐_310.png" alt="222" width="100" height="100"/>
  <div data-role="fieldcontain">
	    <label for="selectmenu" class="select">   数量:</label>
	    <select name="slider_310" id="slider_310">
	      <option value="0" selected="selected">0</option>
	      <option value="1">1</option>
	      <option value="2">2</option>
          <option value="3">3</option>
	      </select>
	    </div>
      <h1></h1>
      <h2>310.  麻婆豆腐 35rb</h2>
    </li>
    <li><img src="menu_pic/酱香排骨_311.png" alt="222" width="100" height="100"/>
  <div data-role="fieldcontain">
	    <label for="selectmenu" class="select">   数量:</label>
	    <select name="slider_311" id="slider_311">
	      <option value="0" selected="selected">0</option>
	      <option value="1">1</option>
	      <option value="2">2</option>
          <option value="3">3</option>
	      </select>
	    </div>
      <h1></h1>
      <h2>311. 酱香排骨 68rb</h2>
    </li>
	</ul>
	
    
    <h2>凉菜      </h2>
    <ul data-role="listview" data-inset="true" >
    <li><img src="menu_pic/凉拌豆腐皮_3142.png" alt="22" width="100" height="100"/>
  <div data-role="fieldcontain">
	    <label for="selectmenu" class="select">   数量:</label>
	    <select name="slider_3142" id="slider_3142">
	      <option value="0" selected="selected">0</option>
	      <option value="1">1</option>
	      <option value="2">2</option>
          <option value="3">3</option>
	      </select>
	    </div>
      <h1></h1>
      <h2>3142. 凉拌豆腐皮 30rb</h2>
    </li>
    <li><img src="menu_pic/蒜泥黄瓜_319.png" alt="22" width="100" height="100"/>
  <div data-role="fieldcontain">
	    <label for="selectmenu" class="select">   数量:</label>
	    <select name="slider_319" id="slider_319">
	      <option value="0" selected="selected">0</option>
	      <option value="1">1</option>
	      <option value="2">2</option>
          <option value="3">3</option>
	      </select>
	    </div>
      <h1></h1>
      <h2>319. 蒜泥黄瓜 25rb</h2>
    </li>
	</ul>
    
    
    
    
    	

    
     <h2>重庆小炒</h2>
		<ul data-role="listview" data-inset="true" >
<li><img src="menu_pic/香辣虾_321.png" alt="111" width="100" height="100"/>
            <div data-role="fieldcontain">
              <label for="selectmenu" class="select">   数量:</label>
              <select name="slider_321" id="slider_321">
                <option value="0" selected="selected">0</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
              </select>
            </div>
            <h1></h1>
            <h2>321. 香辣虾 68rb</h2>
    </li>  
    <li><img src="menu_pic/火爆腰花_325.png" alt="22" width="100" height="100"/>
  <div data-role="fieldcontain">
	    <label for="selectmenu" class="select">   数量:</label>
	    <select name="slider_325" id="slider_325">
	      <option value="0" selected="selected">0</option>
	      <option value="1">1</option>
	      <option value="2">2</option>
          <option value="3">3</option>
	      </select>
	    </div>
      <h1></h1>
      <h2>325. 火爆腰花 48rb </h2>
    </li>
        <li> <img src="menu_pic/鱼香肉丝_326.png" alt="111" width="100" height="100"/>
  <div data-role="fieldcontain">
	    <label for="selectmenu" class="select">   数量:</label>
	    <select name="slider_326" id="slider_326">
	      <option value="0" selected="selected">0</option>
	      <option value="1">1</option>
	      <option value="2">2</option>
          <option value="3">3</option>
	      </select>
	    </div>
      <h1></h1>
      <h2>326. 鱼香肉丝 48rb</h2>
    </li>
    <li> <img src="menu_pic/青椒肉丝_327.png" alt="22222" width="100" height="100"/>
  <div data-role="fieldcontain">
	    <label for="selectmenu" class="select">   数量:</label>
	    <select name="slider_327" id="slider_327">
	      <option value="0" selected="selected">0</option>
	      <option value="1">1</option>
	      <option value="2">2</option>
          <option value="3">3</option>
	      </select>
	    </div>
      <h1></h1>
      <h2>327. 青椒肉丝 48rb</h2>
    </li>
<li><img src="menu_pic/爆炒鸡肠_371.png" alt="111" width="75" height="75"/>
            <div data-role="fieldcontain">
              <label for="selectmenu" class="select">   数量:</label>
              <select name="slider_371" id="slider_371">
                <option value="0" selected="selected">0</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
              </select>
            </div>
            <h1></h1>
            <h2>371. 爆炒鸡肠 45rb</h2>
    </li>
    

 	</ul>

     <h2>家常素炒</h2>
		<ul data-role="listview" data-inset="true" >

    <li> <img src="menu_pic/番茄炒蛋_345.png" alt="22222" width="100" height="100"/>
  <div data-role="fieldcontain">
	    <label for="selectmenu" class="select">   数量:</label>
	    <select name="slider_345" id="slider_345">
	      <option value="0" selected="selected">0</option>
	      <option value="1">1</option>
	      <option value="2">2</option>
          <option value="3">3</option>
	      </select>
	    </div>
      <h1></h1>
      <h2>345. 番茄炒蛋 35rb</h2>
    </li>
        <li> <img src="menu_pic/韭菜鸡蛋_344.png" alt="22222" width="100" height="100"/>
  <div data-role="fieldcontain">
	    <label for="selectmenu" class="select">   数量:</label>
	    <select name="slider_344" id="slider_344">
	      <option value="0" selected="selected">0</option>
	      <option value="1">1</option>
	      <option value="2">2</option>
          <option value="3">3</option>
	      </select>
	    </div>
      <h1></h1>
      <h2>344. 韭菜鸡蛋 35rb</h2>
    </li>
        <li> <img src="menu_pic/木耳炒蛋_347.png" alt="22222" width="100" height="100"/>
  <div data-role="fieldcontain">
	    <label for="selectmenu" class="select">   数量:</label>
	    <select name="slider_347" id="slider_347">
	      <option value="0" selected="selected">0</option>
	      <option value="1">1</option>
	      <option value="2">2</option>
          <option value="3">3</option>
	      </select>
	    </div>
      <h1></h1>
      <h2>347. 木耳炒蛋 35rb</h2>
    </li>  
        <li> <img src="menu_pic/苦瓜炒蛋_346.png" alt="22222" width="100" height="100"/>
  <div data-role="fieldcontain">
	    <label for="selectmenu" class="select">   数量:</label>
	    <select name="slider_346" id="slider_346">
	      <option value="0" selected="selected">0</option>
	      <option value="1">1</option>
	      <option value="2">2</option>
          <option value="3">3</option>
	      </select>
	    </div>
      <h1></h1>
      <h2>346. 苦瓜炒蛋 35rb</h2>
    </li>  
    <li> <img src="menu_pic/干煸豆角_341.png" alt="22222" width="100" height="100"/>
  <div data-role="fieldcontain">
	    <label for="selectmenu" class="select">   数量:</label>
	    <select name="slider_341" id="slider_341">
	      <option value="0" selected="selected">0</option>
	      <option value="1">1</option>
	      <option value="2">2</option>
          <option value="3">3</option>
	      </select>
	    </div>
      <h1></h1>
      <h2>341. 干煸豆角 35rb</h2>
    </li>
        <li> <img src="menu_pic/蒜蓉菠菜_354.png" alt="22222" width="100" height="100"/>
  <div data-role="fieldcontain">
	    <label for="selectmenu" class="select">   数量:</label>
	    <select name="slider_354" id="slider_354">
	      <option value="0" selected="selected">0</option>
	      <option value="1">1</option>
	      <option value="2">2</option>
          <option value="3">3</option>
	      </select>
	    </div>
      <h1></h1>
      <h2>354. 蒜蓉菠菜 30rb</h2>
    </li>       
    <li> <img src="menu_pic/青椒土豆丝_342.png" alt="22222" width="100" height="100"/>
  <div data-role="fieldcontain">
	    <label for="selectmenu" class="select">   数量:</label>
	    <select name="slider_342" id="slider_342">
	      <option value="0" selected="selected">0</option>
	      <option value="1">1</option>
	      <option value="2">2</option>
          <option value="3">3</option>
	      </select>
	    </div>
      <h1></h1>
      <h2>342. 青椒土豆丝 30rb</h2>
    </li>        
    <li> <img src="menu_pic/酸辣土豆丝_343.png" alt="22222" width="100" height="100"/>
  <div data-role="fieldcontain">
	    <label for="selectmenu" class="select">   数量:</label>
	    <select name="slider_343" id="slider_343">
	      <option value="0" selected="selected">0</option>
	      <option value="1">1</option>
	      <option value="2">2</option>
          <option value="3">3</option>
	      </select>
	    </div>
      <h1></h1>
      <h2>343. 酸辣土豆丝 30rb</h2>
    </li>   
    <li> <img src="menu_pic/空心菜_353.png" alt="22222" width="100" height="100"/>
  <div data-role="fieldcontain">
	    <label for="selectmenu" class="select">   数量:</label>
	    <select name="slider_353" id="slider_353">
	      <option value="0" selected="selected">0</option>
	      <option value="1">1</option>
	      <option value="2">2</option>
          <option value="3">3</option>
	      </select>
	    </div>
      <h1></h1>
      <h2>353. 空心菜 25rb </h2>
    </li>     
    <li> <img src="menu_pic/炒苦瓜_355.png" alt="22222" width="100" height="100"/>
  <div data-role="fieldcontain">
	    <label for="selectmenu" class="select">   数量:</label>
	    <select name="slider_355" id="slider_355">
	      <option value="0" selected="selected">0</option>
	      <option value="1">1</option>
	      <option value="2">2</option>
          <option value="3">3</option>
	      </select>
	    </div>
      <h1></h1>
      <h2>355. 炒苦瓜 25rb</h2>
    </li>       
    <li> <img src="menu_pic/炒小白菜_356.png" alt="22222" width="100" height="100"/>
  <div data-role="fieldcontain">
	    <label for="selectmenu" class="select">   数量:</label>
	    <select name="slider_356" id="slider_356">
	      <option value="0" selected="selected">0</option>
	      <option value="1">1</option>
	      <option value="2">2</option>
          <option value="3">3</option>
	      </select>
	    </div>
      <h1></h1>
      <h2>356. 炒小白菜 25rb</h2>
    </li>      
   	</ul>



     <h2>汤</h2>
		<ul data-role="listview" data-inset="true" >
    <li> <img src="menu_pic/三鲜猪肝汤_335.png" alt="22222" width="75" height="75"/>
  <div data-role="fieldcontain">
	    <label for="selectmenu" class="select">   数量:</label>
	    <select name="slider_335" id="slider_335">
	      <option value="0" selected="selected">0</option>
	      <option value="1">1</option>
	      <option value="2">2</option>
          <option value="3">3</option>
	      </select>
	    </div>
      <h1></h1>
      <h2>335. 三鲜猪肝汤 35rb</h2>
    </li>
    <li> <img src="menu_pic/番茄蛋花汤_358.png" alt="22222" width="100" height="100"/>
  <div data-role="fieldcontain">
	    <label for="selectmenu" class="select">   数量:</label>
	    <select name="slider_358" id="slider_358">
	      <option value="0" selected="selected">0</option>
	      <option value="1">1</option>
	      <option value="2">2</option>
          <option value="3">3</option>
	      </select>
	    </div>
      <h1></h1>
      <h2>358. 番茄蛋花汤 25rb</h2>
    </li>          
 	</ul>



     <h2>主食</h2>
	<ul data-role="listview" data-inset="true" >    
    
    <li> <img src="menu_pic/白饭_79.png" alt="22222" width="100" height="100"/>
  <div data-role="fieldcontain">
	    <label for="selectmenu" class="select">   数量:</label>
	    <select name="slider_79" id="slider_79">
	      <option value="0" selected="selected">0</option>
	      <option value="1">1</option>
	      <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
          <option value="6">6</option>
          <option value="7">7</option>
          <option value="8">8</option>
          <option value="9">9</option>
	      </select>
	    </div>
      <h1></h1>
      <h2>79. 白饭 5rb</h2>
    </li> 
        
    <li> <img src="menu_pic/蛋炒饭_362.png" alt="22222" width="100" height="100"/>
  <div data-role="fieldcontain">
	    <label for="selectmenu" class="select">   数量:</label>
	    <select name="slider_362" id="slider_362">
	      <option value="0" selected="selected">0</option>
	      <option value="1">1</option>
	      <option value="2">2</option>
          <option value="3">3</option>
	      </select>
	    </div>
      <h1></h1>
      <h2>362. 蛋炒饭 30rb</h2>
    </li>         
    <li> <img src="menu_pic/重庆小面_365.png" alt="22222" width="100" height="100"/>
  <div data-role="fieldcontain">
	    <label for="selectmenu" class="select">   数量:</label>
	    <select name="slider_365" id="slider_365">
	      <option value="0" selected="selected">0</option>
	      <option value="1">1</option>
	      <option value="2">2</option>
          <option value="3">3</option>
	      </select>
	    </div>
      <h1></h1>
      <h2>365. 重庆小面 25rb</h2>
    </li>      
    <li> <img src="menu_pic/肥肠面_367.png" alt="22222" width="100" height="100"/>
  <div data-role="fieldcontain">
	    <label for="selectmenu" class="select">   数量:</label>
	    <select name="slider_367" id="slider_367">
	      <option value="0" selected="selected">0</option>
	      <option value="1">1</option>
	      <option value="2">2</option>
          <option value="3">3</option>
	      </select>
	    </div>
      <h1></h1>
      <h2>367. 肥肠面 35rb</h2>
    </li>      
    <li> <img src="menu_pic/清汤抄手_368.png" alt="22222" width="100" height="100"/>
  <div data-role="fieldcontain">
	    <label for="selectmenu" class="select">   数量:</label>
	    <select name="slider_368" id="slider_368">
	      <option value="0" selected="selected">0</option>
	      <option value="1">1</option>
	      <option value="2">2</option>
          <option value="3">3</option>
	      </select>
	    </div>
      <h1></h1>
      <h2>368. 清汤抄手 35rb</h2>
    </li>      
    <li> <img src="menu_pic/红汤抄手_369.png" alt="22222" width="100" height="100"/>
  <div data-role="fieldcontain">
	    <label for="selectmenu" class="select">   数量:</label>
	    <select name="slider_369" id="slider_369">
	      <option value="0" selected="selected">0</option>
	      <option value="1">1</option>
	      <option value="2">2</option>
          <option value="3">3</option>
	      </select>
	    </div>
      <h1></h1>
      <h2>369. 红汤抄手 35rb</h2>
    </li>      
   
 	</ul>
    
    
    
    
    


	  <input type="submit" name="1111" id="1111" value="好了, 点我进行下一步">
	  </form>
	<p>&nbsp; </p>
	<p>&nbsp;</p>	
	
	</div>
	</div>

</div>
<!-- /page -->
</body>
</html>
