<?php




header("content-Type: text/html; charset=utf-8");
//中文对照数组
 $wtocn=array(
'AM Clouds / PM Sun'=>'上午有云/下午后晴 ',
'AM Showers'=>'上午阵雨 ',
'AM Snow Showers'=>'上午阵雪 ',
'AM Thunderstorms'=>'上午雷暴雨 ',
'Clear'=>'晴朗 ',
'Cloudy'=>'多云 ',
'Cloudy / Wind'=>'阴时有风 ',
'Clouds Early / Clearing Late'=>'早多云/晚转晴 ',
'Drifting Snow'=>'飘雪 ',
'Drizzle'=>'毛毛雨 ',
'Dust'=>'灰尘 ',
'Fair'=>'晴 ',
'Few Showers'=>'短暂阵雨 ',
'Few Snow Showers'=>'短暂阵雪 ',
'Few Snow Showers / Wind'=>'短暂阵雪时有风 ',
'Fog'=>'雾 ',
'Haze'=>'薄雾 ',
'Hail'=>'冰雹 ',
'Heavy Rain'=>'大雨 ',
'Heavy Rain Icy'=>'大冰雨 ',
'Heavy Snow'=>'大雪 ',
'Heavy Thunderstorms'=>'强烈雷雨 ',
'Isolated Thunderstorms'=>'局部雷雨 ',
'Light Drizzle'=>'微雨 ',
'Light Rain'=>'小雨 ',
'Light Rain Shower'=>'小阵雨 ',
'Light Rain Shower and Windy'=>'小阵雨带风 ',
'Light Rain with Thunder'=>'小雨有雷声 ',
'Light Snow'=>'小雪 ',
'Light Snow Fall'=>'小降雪 ',
'Light Snow Grains'=>'小粒雪 ',
'Light Snow Shower'=>'小阵雪 ',
'Lightening'=>'雷电 ',
'Mist'=>'薄雾 ',
'Mostly Clear'=>'大部晴朗 ',
'Mostly Cloudy'=>'大部多云 ',
'Mostly Cloudy/ Windy'=>'多云时阴有风 ',
'Mostly Sunny'=>'晴时多云 ',
'Partly Cloudy'=>'局部多云 ',
'Partly Cloudy/ Windy'=>'多云时有风 ',
'PM Rain / Wind'=>'下午小雨时有风 ',
'PM Light Rain'=>'下午小雨 ',
'PM Showers'=>'下午阵雨 ',
'PM Snow Showers'=>'下午阵雪 ',
'PM Thunderstorms'=>'下午雷雨 ',
'Rain'=>'雨 ',
'Rain Shower'=>'阵雨 ',
'Rain Shower/ Windy'=>'阵雨/有风 ',
'Rain / Snow Showers'=>'雨或阵雪 ',
'Rain / Snow Showers Early'=>'下雨/早间阵雪 ',
'Rain / Wind'=>'雨时有风 ',
'Rain and Snow'=>'雨夹雪 ',
'Scattered Showers'=>'零星阵雨 ',
'Scattered Showers / Wind'=>'零星阵雨时有风 ',
'Scattered Snow Showers'=>'零星阵雪 ',
'Scattered Snow Showers / Wind'=>'零星阵雪时有风 ',
'Scattered Strong Storms'=>'零星强烈暴风雨 ',
'Scattered Thunderstorms'=>'零星雷雨 ',
'Showers'=>'阵雨 ',
'Showers Early'=>'早有阵雨 ',
'Showers Late'=>'晚有阵雨 ',
'Showers / Wind'=>'阵雨时有风 ',
'Showers in the Vicinity'=>'周围有阵雨 ',
'Smoke'=>'烟雾 ',
'Snow'=>'雪 ',
'Snow / Rain Icy Mix'=>'冰雨夹雪 ',
'Snow and Fog'=>'雾夹雪 ',
'Snow Shower'=>'阵雪 ',
'Snowflakes'=>'雪花 ',
'Sunny'=>'晴朗 ',
'Sunny / Wind'=>'晴时有风 ',
'Sunny Day'=>'晴天 ',
'Thunder'=>'雷鸣 ',
'Thunder in the Vicinity'=>'周围有雷雨 ',
'Thunderstorms'=>'雷雨 ',
'Thunderstorms Early'=>'早有持续雷雨 ',
'Thunderstorms Late'=>'晚有持续雷雨 ',
'Windy'=>'有风 ',
'Windy / Snowy'=>'有风/有雪 ',
'Windy Rain'=>'刮风下雨 ',
'Wintry Mix'=>'雨雪混合'
);
//$wtime=date("m-d",strtotime($weather_chile->fore_day1_date));
 


//错误处理, 如果出错,echo返回
function errorHandler($level, $msg, $file, $line) {

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
 
 	echo(sprintf($template,$error_type[$level],$msg,$file,$line));
 }
 
 

set_error_handler('errorHandler');



#http://weather.yahooapis.com/forecastrss?w=2151330&u=c#

$post="";
//yahoo天气的API,其中1044316是泗水的ID
$api = "http://weather.yahooapis.com/forecastrss?w=1044316&u=c";
//发送给yahoo,返回RSS和XML
$replys=file_get_contents($api);
//获取XML中的channel->item->description数据
$a_replys=(array)simplexml_load_string($replys, 'SimpleXMLElement');
$a_channel=(array)$a_replys['channel'];
$a_item=(array)$a_channel['item'];
$str_des=$a_item['description']->asXML();
//过滤,得到天气的数据
preg_match("/Current Conditions.*?(?=<a href)/s",$str_des,$a_weather_temp);
//去掉多余的字符
$a_weather_temp[0]=preg_replace("/<b>|<\/b>|<br \/>|<br>/i","",$a_weather_temp[0]);
//获取目前的天气状况, 英文
preg_match("/(?<=Current Conditions\:\\n).*(?=,)/s",$a_weather_temp[0],$a_today_wea);
$a_today_wea[0]=trim($a_today_wea[0]);
//获取2天后的天气状况,英文
preg_match_all("/(?<=-).*(?=\.)/",$a_weather_temp[0],$a_trm_wea);
$a_trm_wea[0][0]=trim($a_trm_wea[0][0]);
$a_trm_wea[0][1]=trim($a_trm_wea[0][1]);
//将目前和2天后的天气状况替换成中文
$a_weather_temp[0]=preg_replace("/".$a_today_wea[0]."/i",$wtocn[$a_today_wea[0]],$a_weather_temp[0]);
$a_weather_temp[0]=preg_replace("/".$a_trm_wea[0][0]."/i",$wtocn[$a_trm_wea[0][0]],$a_weather_temp[0]);
$a_weather_temp[0]=preg_replace("/".$a_trm_wea[0][1]."/i",$wtocn[$a_trm_wea[0][1]],$a_weather_temp[0]);
//将英文的星期替换成中文
$a_weather_temp[0]=preg_replace("/sun|sunday/i","星期天",preg_replace("/sat|saturday/i","星期六",preg_replace("/fri|friday/i","星期五",preg_replace("/thu|thursday/i","星期四",preg_replace("/wed|wednesday/i","星期三",preg_replace("/tue|tuesday/i","星期二",preg_replace("/mon|monday/i","星期一",$a_weather_temp[0])))))));
//替换其他固定英文字符到中文
$a_weather_temp[0]=preg_replace("/Current Conditions/i","目前天气",$a_weather_temp[0]);
$a_weather_temp[0]=preg_replace("/Forecast/i","天气预报",$a_weather_temp[0]);
$a_weather_temp[0]=preg_replace("/High/i","最高温度",$a_weather_temp[0]);
$a_weather_temp[0]=preg_replace("/Low/i","最低温度",$a_weather_temp[0]);
//返回翻译后的天气状况
echo $a_weather_temp[0];


?>
