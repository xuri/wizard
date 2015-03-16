<?php

/*
|--------------------------------------------------------------------------
| 复写官方函数
|--------------------------------------------------------------------------
|
| 官方函数库路径
| Illuminate/Support/helpers.php
|
*/

/**
 * Generate a URL to a named route.
 *
 * @param  string  $route
 * @param  string  $parameters
 * @return string
 */
function route($route, $parameters = array())
{
	if (Route::getRoutes()->hasNamedRoute($route))
		return app('url')->route($route, $parameters);
	else
		return 'javascript:void(0)';
}

/**
 * Generate a HTML link to a named route.
 *
 * @param  string  $name
 * @param  string  $title
 * @param  array   $parameters
 * @param  array   $attributes
 * @return string
 */
function link_to_route($name, $title = null, $parameters = array(), $attributes = array())
{
	if (Route::getRoutes()->hasNamedRoute($name))
		return app('html')->linkRoute($name, $title, $parameters, $attributes);
	else
		return '<a href="javascript:void(0)"'.HTML::attributes($attributes).'>'.$name.'</a>';
}


/*
|--------------------------------------------------------------------------
| 延伸自拓展配置文件
|--------------------------------------------------------------------------
|
*/

/**
 * 样式别名加载（支持批量加载）
 * @param  string|array $aliases    配置文件中的别名
 * @param  array        $attributes 标签中需要加入的其它参数的数组
 * @return string
 */
function style($aliases, $attributes = array(), $interim = '')
{
	if (is_array($aliases)) {
		foreach ($aliases as $key => $value) {
			$interim .= (is_int($key)) ? style($value, $attributes, $interim) : style($key, $value, $interim);
		}
		return $interim;
	}
	$cssAliases = Config::get('extend.webAssets.cssAliases');
	$url        = isset($cssAliases[$aliases]) ? $cssAliases[$aliases] : $aliases;
	return HTML::style($url, $attributes);
}

/**
 * 脚本别名加载（支持批量加载）
 * @param  string|array $aliases    配置文件中的别名
 * @param  array        $attributes 标签中需要加入的其它参数的数组
 * @return string
 */
function script($aliases, $attributes = array(), $interim = '')
{
	if (is_array($aliases)) {
		foreach ($aliases as $key => $value) {
			$interim .= (is_int($key)) ? script($value, $attributes, $interim) : script($key, $value, $interim);
		}
		return $interim;
	}
	$jsAliases = Config::get('extend.webAssets.jsAliases');
	$url       = isset($jsAliases[$aliases]) ? $jsAliases[$aliases] : $aliases;
	return HTML::script($url, $attributes);
}

/**
 * 脚本别名加载（补充）用于 js 的 document.write(）中
 * @param  string $aliases    配置文件中的别名
 * @param  array  $attributes 标签中需要加入的其它参数的数组
 * @return string
 */
function or_script($aliases, $attributes = array())
{
	$jsAliases         = Config::get('extend.webAssets.jsAliases');
	$url               = isset($jsAliases[$aliases]) ? $jsAliases[$aliases] : $aliases;
	$attributes['src'] = URL::asset($url);
	return "'<script".HTML::attributes($attributes).">'+'<'+'/script>'";
}

/*
|--------------------------------------------------------------------------
| 自定义核心函数
|--------------------------------------------------------------------------
|
*/

/**
 * 批量定义常量
 * @param  array  $define 常量和值的数组
 * @return void
 */
function define_array($define = array())
{
	foreach ($define as $key => $value)
		defined($key) OR define($key, $value);
}

/**
 * 友好的日期输出
 * @param  string|\Carbon\Carbon $theDate 待处理的时间字符串 | \Carbon\Carbon 实例
 * @return string                         友好的时间字符串
 */
function friendly_date($theDate)
{
	// 获取待处理的日期对象
	if (! $theDate instanceof \Carbon\Carbon)
		$theDate = \Carbon\Carbon::createFromTimestamp(strtotime($theDate));
	// 取得英文日期描述
	$friendlyDateString = $theDate->diffForHumans(\Carbon\Carbon::now());
	// 本地化
	$friendlyDateArray  = explode(' ', $friendlyDateString);
	$friendlyDateString = $friendlyDateArray[0]
		.Lang::get('friendlyDate.'.$friendlyDateArray[1])
		.Lang::get('friendlyDate.'.$friendlyDateArray[2]);
	// 数据返回
	return $friendlyDateString;
}

/**
 * 拓展分页输出，支持临时指定分页模板
 * @param  Illuminate\Pagination\Paginator $paginator 分页查询结果的最终实例
 * @param  string                          $viewName  分页视图名称
 * @return \Illuminate\View\View
 */
function pagination(Illuminate\Pagination\Paginator $paginator, $viewName = null)
{
	$viewName = $viewName ?: Config::get('view.pagination');
	$paginator->getFactory()->setViewName($viewName);
	return $paginator->links();
}

/**
 * 反引用一个经过 e（htmlentities）和 addslashes 处理的字符串
 * @param  string $string 待处理的字符串
 * @return 转义后的字符串
 */
function strip($string)
{
	return stripslashes(HTML::decode($string));
}


/*
|--------------------------------------------------------------------------
| Public Function Library
|--------------------------------------------------------------------------
|
*/

/**
 * Closing HTML tag (this function is still flawed, unable to process incomplete labels, there is no better plan, caution)
 * @param  string $html HTML String
 * @return string
 */
function close_tags($html)
{
	// Labels needn't to complete
	$arr_single_tags = array('meta', 'img', 'br', 'link', 'area');
	// Match the start tag
	preg_match_all('#<([a-z1-6]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
	$openedtags = $result[1];
	// Match the close tag
	preg_match_all('#</([a-z]+)>#iU', $html, $result);
	$closedtags = $result[1];
	// Close opened tab quantity is calculated, and if the same return HTML data
	if (count($closedtags) === count($openedtags)) return $html;
	// Reverse sort the array, the last open tab at the top
	$openedtags = array_reverse($openedtags);
	// Traversing the open tags array
	foreach ($openedtags as $key => $value) {
		// Skip without closing tags
		if (in_array($value, $arr_single_tags)) continue;
		// Started complete
		if (in_array($value, $closedtags)) {
			unset($closedtags[array_search($value, $closedtags)]);
		} else {
			$html .= '</'.$value.'>';
		}
	}
	return $html;
}

/**
 * Resources list sort
 * @param  string $columnName Column name
 * @param  string $default    If the default sort column，up Default Ascending, down Default descending
 * @return string             a Tag sort icon
 */
function order_by($columnName = '', $default = null)
{
	$sortColumnName = Input::get('sort_up', Input::get('sort_down', false));
	if (Input::get('sort_up')) {
		$except = 'sort_up'; $orderType = 'sort_down';
	} else {
		$except = 'sort_down' ; $orderType = 'sort_up';
	}
	if ($sortColumnName == $columnName) {
		$parameters = array_merge(Input::except($except), array($orderType => $columnName));
		$icon       = Input::get('sort_up') ? 'chevron-up' : 'chevron-down' ;
	} elseif ($sortColumnName === false && $default == 'asc') {
		$parameters = array_merge(Input::all(), array('sort_down' => $columnName));
		$icon       = 'chevron-up';
	} elseif ($sortColumnName === false && $default == 'desc') {
		$parameters = array_merge(Input::all(), array('sort_up' => $columnName));
		$icon       = 'chevron-down';
	} else {
		$parameters = array_merge(Input::except($except), array('sort_up' => $columnName));
		$icon       = 'random';
	}
	$a  = '<a href="';
	$a .= action(Route::current()->getActionName(), $parameters);
	$a .= '" class="glyphicon glyphicon-'.$icon.'"></a>';
	return $a;
}

/**
 * Get user constellation
 * @param  init $constellation User constellation code
 * @return array               User constellation
 */
function getConstellation($constellation)
{
	if($constellation == NULL)
	{
		$constellationInfo = array(
			'icon' => 'default.png',
			'name' => '未选择星座'
		);
		return $constellationInfo;
	} else {
		switch ($constellation)
		{
			case "1":
				$constellationIcon = 'shuipin.png';
				$constellationName = '水瓶座';
			break;
			case "2":
				$constellationIcon = 'shuangyu.png';
				$constellationName = '双鱼座';
			break;
			case "3":
				$constellationIcon = 'baiyang.png';
				$constellationName = '白羊座';
			break;
			case "4":
				$constellationIcon = 'jinniu.png';
				$constellationName = '金牛座';
			break;
			case "5":
				$constellationIcon = 'shuangzi.png';
				$constellationName = '双子座';
			break;
			case "6":
				$constellationIcon = 'juxie.png';
				$constellationName = '巨蟹座';
			break;
			case "7":
				$constellationIcon = 'shizi.png';
				$constellationName = '狮子座';
			break;
			case "8":
				$constellationIcon = 'chunv.png';
				$constellationName = '处女座';
			break;
			case "9":
				$constellationIcon = 'tiancheng.png';
				$constellationName = '天秤座';
			break;
			case "10":
				$constellationIcon = 'tianxie.png';
				$constellationName = '天蝎座';
			break;
			case "11":
				$constellationIcon = 'sheshou.png';
				$constellationName = '射手座';
			break;
			case "12":
				$constellationIcon = 'mojie.png';
				$constellationName = '摩羯座';
			break;
			default:
				$constellationIcon = 'default.png';
				$constellationName = '未选择星座';
		}

		$constellationInfo = array(
			'icon' => $constellationIcon,
			'name' => $constellationName
		);
		return $constellationInfo;
	}
}

/**
 * Get user tag
 * @param  string $tag User tag in database
 * @return string      Tag name
 */
function getTagName($tag)
{
	switch ($tag)
	{
		case "1":
			$tagName = '高冷';
		break;
		case "2":
			$tagName = '颜控';
		break;
		case "3":
			$tagName = '女神';
		break;
		case "4":
			$tagName = '萌萌哒';
		break;
		case "5":
			$tagName = '治愈系';
		break;
		case "6":
			$tagName = '小清新';
		break;
		case "7":
			$tagName = '女王范';
		break;
		case "8":
			$tagName = '天然呆';
		break;
		case "9":
			$tagName = '萝莉';
		break;
		case "10":
			$tagName = '静待缘分';
		break;
		case "11":
			$tagName = '减肥ing';
		break;
		case "12":
			$tagName = '戒烟ing';
		break;
		case "13":
			$tagName = '缺爱ing';
		break;
		case "14":
			$tagName = '暖男';
		break;
		case "15":
			$tagName = '创业者';
		break;
		case "16":
			$tagName = '直率';
		break;
		case "17":
			$tagName = '懒';
		break;
		case "18":
			$tagName = '感性';
		break;
		case "19":
			$tagName = '理性';
		break;
		case "20":
			$tagName = '温柔细心';
		break;
		case "21":
			$tagName = '暴脾气';
		break;
		case "22":
			$tagName = '技术宅';
		break;
		case "23":
			$tagName = '文艺病';
		break;
		case "24":
			$tagName = '旅行爱好者';
		break;
		case "25":
			$tagName = '健身狂魔';
		break;
		case "26":
			$tagName = '考研ing';
		break;
		case "27":
			$tagName = '吃货';
		break;
		case "28":
			$tagName = '长腿欧巴';
		break;
		case "29":
			$tagName = '街舞solo';
		break;
		case "30":
			$tagName = '爱音乐';
		break;
		case "31":
			$tagName = '幽默';
		break;
		case "32":
			$tagName = '乐观';
		break;
		case "33":
			$tagName = '事业型';
		break;
		case "34":
			$tagName = '完美主义';
		break;
		case "35":
			$tagName = '情商略高';
		break;
		case "36":
			$tagName = '阳光';
		break;
		case "37":
			$tagName = '学霸';
		break;
		case "38":
			$tagName = '执着';
		break;
		case "39":
			$tagName = '自信';
		break;
		case "40":
			$tagName = '独立型';
		break;
		default:
			$tagName = '无标签';
		break;
	}
	return $tagName;
}

/**
 * Easemob Web IM API
 * @return object easemob configure
 */
function getEasemob()
{
	$easemob			= System::where('name', 'easemob')->first(); // Get easemod API config
	$nowTime			= new DateTime(); // Now time
	$easemobUpdated		= $nowTime->getTimestamp() - strtotime($easemob->updated_at); // Calculate last update timestamp
	// Get token
	if($easemob->token == NULL) // First get token
	{
		$accessToken 	= cURL::newJsonRequest('post', 'https://a1.easemob.com/jinglingkj/pinai/token', ['grant_type' => 'client_credentials','client_id' => $easemob->sid, 'client_secret' => $easemob->secret])->setHeader('content-type', 'application/json')->send(); // Send cURL
		$accessToken	= json_decode($accessToken->body, true); // Json decode
		$easemob->token	= $accessToken['access_token'];
		$easemob->save(); // Save access token
	} elseif($easemobUpdated < 172800) // Last update timestamp 2 Days (201600 - 3 days)
	{
		$accessToken 	= cURL::newJsonRequest('post', 'https://a1.easemob.com/jinglingkj/pinai/token', ['grant_type' => 'client_credentials','client_id' => $easemob->sid, 'client_secret' => $easemob->secret])->setHeader('content-type', 'application/json')->send(); // Send cURL
		$accessToken	= json_decode($accessToken->body, true); // Json decode
		$easemob->token	= $accessToken['access_token'];
		$easemob->save(); // Save access token
	}
	return $easemob;
}

/**
 * Create Notification
 * @param Integer $category  	Category code
 * @param Integer $receiverId 	Receiver ID
 * @param Integer $senderId  	Sender ID
 */
function Notification($category, $senderId, $receiverId)
{
	$notification				= new Notification;
	$notification->sender_id	= $senderId;
	$notification->receiver_id	= $receiverId;
	$notification->category 	= $category;
	$notification->save();

	return $notification;
}

/**
 * Create Notifications
 * @param Integer $category  	Category code
 * @param Integer $receiverId 	Receiver ID
 * @param Integer $senderId  	Sender ID
 */
function Notifications($category, $senderId, $receiverId, $category_id, $post_id, $comment_id, $reply_id)
{
	$notification				= new Notification;
	$notification->sender_id	= $senderId;
	$notification->receiver_id	= $receiverId;
	$notification->category 	= $category;
	if($category_id){
		$notification->category_id 	= $category_id;
	}
	if($post_id){
		$notification->post_id 		= $post_id;
	}
	if($comment_id){
		$notification->comment_id 	= $comment_id;
	}
	if($reply_id){
		$notification->reply_id 	= $reply_id;
	}
	$notification->save();

	return $notification;
}

/**
 * Get Notification Content
 * @param Integer $catrgory  	Category code
 * @param Integer $sender_id 	Sender ID
 * @return Array            	Notification title and content
 */
function getNotification($category, $sender_id)
{
	$sender = User::where('id', $sender_id)->first();
	switch ($category) {
		case '1':
			$notificationTitle 		= '好友请求消息';
			$notificationContent	= $sender->nickname.'追你';
			break;
		case '2':
			$notificationTitle 		= '好友请求消息';
			$notificationContent 	= $sender->nickname.'再次追你';
			break;
		case '3':
			$notificationTitle 		= '好友请求消息';
			$notificationContent	= $sender->nickname.'接受了你的邀请';
			break;
		case '4':
			$notificationTitle 		= '好友请求消息';
			$notificationContent 	= $sender->nickname.'拒绝了你的邀请';
			break;
		case '5':
			$notificationTitle 		= '好友关系提醒';
			$notificationContent 	= $sender->nickname.'将你加入了黑名单';
			break;
		case '6':
			$notificationTitle 		= '论坛消息';
			$notificationContent 	= $sender->nickname.'评论了你发布帖子，快去看看吧';
			break;
		case '7':
			$notificationTitle 		= '论坛消息';
			$notificationContent 	= $sender->nickname.'评回复了你的评论，快去看看吧';
			break;
		case '8':
			$notificationTitle 		= '系统消息';
			$notificationContent 	= '系统消息';
			break;
		case '9':
			$notificationTitle 		= '系统消息';
			$notificationContent 	= '系统消息';
			break;
		case '10':
			$notificationTitle 		= '好友关系提醒';
			$notificationContent 	= $sender->nickname.'解除了对你的拉黑';
			break;
	}
	$notification = array(
		'title'		=> $notificationTitle,
		'content'	=> $notificationContent
	);
	return $notification;
}

/**
 * Calculate diff between two days
 * @param  date $day1 format:Y-m-d
 * @param  date $day2 format:Y-m-d
 * @return int
 */
function diffBetweenTwoDays ($day1, $day2)
{
	$second1 = strtotime($day1);
	$second2 = strtotime($day2);

	if ($second1 < $second2) {
		$tmp = $second2;
		$second2 = $second1;
		$second1 = $tmp;
	}

	return ($second1 - $second2) / 86400;
}


/**
 * Get plain text intro from html
 * @param  string $html     HTML code
 * @param  int $numchars 	Abstract of the number of characters
 * @return string          	Pain text intro from html
 */
function getplaintextintrofromhtml($html, $numchars) {
	// Remove the HTML tags
	$html = strip_tags($html);

	// Convert HTML entities to single characters
	//
	$html = html_entity_decode($html, ENT_QUOTES, 'UTF-8');

	// Make the string the desired number of characters
	// Note that substr is not good as it counts by bytes and not characters

	$html = mb_substr($html, 0, $numchars, 'UTF-8');

	// Add an elipsis
	$html .= "…";
	return $html;
}

/**
 * Convert br
 * @param  string $string Before convert string
 * @return string         After convert string
 */
function convertBr($string) {
	$breaks	= array("<br />","<br>","<br/>");
	$string	= str_ireplace($breaks, "\n", $string);
	return $string;
}

/**
 * String to array
 * @param  string &$str         badwords string
 * @param  string &$replace_arr replace array
 * @return string               result
 */
function strtr_array(&$str,&$replace_arr) {
    $maxlen = 0;$minlen = 1024*128;
    if (empty($replace_arr)) return $str;
    foreach($replace_arr as $k => $v) {
        $len = strlen($k);
        if ($len < 1) continue;
        if ($len > $maxlen) $maxlen = $len;
        if ($len < $minlen) $minlen = $len;
    }
    $len = strlen($str);
    $pos = 0;$result = '';
    while ($pos < $len) {
        if ($pos + $maxlen > $len) $maxlen = $len - $pos;
        $found = false;$key = '';
        for($i = 0;$i<$maxlen;++$i) $key .= $str[$i+$pos]; //原文：memcpy(key,str+$pos,$maxlen)
        for($i = $maxlen;$i >= $minlen;--$i) {
            $key1 = substr($key, 0, $i); //原文：key[$i] = '\0'
            if (isset($replace_arr[$key1])) {
                $result .= $replace_arr[$key1];
                $pos += $i;
                $found = true;
                break;
            }
        }
        if(!$found) $result .= $str[$pos++];
    }
    return $result;
}

/**
 * Bad words filter
 * @param  string $text Before filter bad words
 * @return string       After filter bad words
 */
function badWordsFilter($text) {
	require __DIR__.'/api/wordfilter/badword.src.php';
	$text = strtr($text, array_combine($badword,array_fill(0,count($badword),'*')));
	return $text;
}