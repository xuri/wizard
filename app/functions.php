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