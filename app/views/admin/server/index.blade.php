<?php
	// Suppress All Error Messages
	error_reporting(0);

	// Language compulsory
	@header("content-Type: text/html; charset=utf-8");
	ob_start();

	// This sentence is used to eliminate the time difference
	date_default_timezone_set('Asia/Shanghai');
	define('HTTP_HOST', preg_replace('~^www\.~i', '', $_SERVER['HTTP_HOST']));
	$time_start = microtime_float();

	function GetCoreInformation() {
		$data	= file('/proc/stat');
		$cores	= array();
		foreach( $data as $line ) {
			if( preg_match('/^cpu[0-9]/', $line) ) {
				$info = explode(' ', $line);
				$cores[]= array(
					'user'		=> $info[1],
					'nice'		=> $info[2],
					'sys'		=> $info[3],
					'idle'		=> $info[4],
					'iowait'	=> $info[5],
					'irq'		=> $info[6],
					'softirq'	=> $info[7]
				);
			}
		}
		return $cores;
	}

	function GetCpuPercentages($stat1, $stat2) {
		if(count($stat1)!==count($stat2)) {
			return;
		}
		$cpus = array();
		for( $i = 0, $l = count($stat1); $i < $l; $i++) {
			$dif				= array();
			$dif['user']		= $stat2[$i]['user'] - $stat1[$i]['user'];
			$dif['nice']		= $stat2[$i]['nice'] - $stat1[$i]['nice'];
			$dif['sys']			= $stat2[$i]['sys'] - $stat1[$i]['sys'];
			$dif['idle']		= $stat2[$i]['idle'] - $stat1[$i]['idle'];
			$dif['iowait']		= $stat2[$i]['iowait'] - $stat1[$i]['iowait'];
			$dif['irq']			= $stat2[$i]['irq'] - $stat1[$i]['irq'];
			$dif['softirq']		= $stat2[$i]['softirq'] - $stat1[$i]['softirq'];
			$total				= array_sum($dif);
			$cpu				= array();
			foreach($dif as $x => $y) $cpu[$x] = round($y / $total * 100, 2);
			$cpus['cpu' . $i]	= $cpu;
		}
		return $cpus;
	}

	$stat1		= GetCoreInformation();sleep(1);
	$stat2		= GetCoreInformation();
	$data		= GetCpuPercentages($stat1, $stat2);
	$cpu_show	= $data['cpu0']['user']."%us,  ".$data['cpu0']['sys']."%sy,  ".$data['cpu0']['nice']."%ni, ".$data['cpu0']['idle']."%id,  ".$data['cpu0']['iowait']."%wa,  ".$data['cpu0']['irq']."%irq,  ".$data['cpu0']['softirq']."%softirq";

	// CPU-related information obtained in accordance with the different systems
	switch(PHP_OS)
	{
		case "Linux":
			$sysReShow = (false !== ($sysInfo = sys_linux()))?"show":"none";
		break;
		case "FreeBSD":
			$sysReShow = (false !== ($sysInfo = sys_freebsd()))?"show":"none";
		break;
	/*
		case "WINNT":
			$sysReShow = (false !== ($sysInfo = sys_windows()))?"show":"none";
		break;
	*/
		default:
		break;
	}

	// Online Time
	$uptime		= $sysInfo['uptime'];

	// Current System Time
	$stime		= date('Y-m-d H:i:s');

	// HDD Total
	$dt			= round(@disk_total_space(".")/(1024*1024*1024),3);

	// Available
	$df			= round(@disk_free_space(".")/(1024*1024*1024),3);

	// Usage
	$du			= $dt-$df;
	$hdPercent	= (floatval($dt)!=0)?round($du/$dt*100,2):0;

	// System Load
	$load		= $sysInfo['loadAvg'];

	// Determine if the memory is less than 1G, on display M, otherwise display G Unit.
	if($sysInfo['memTotal']<1024) {
		$memTotal			= $sysInfo['memTotal']." M";
		$mt					= $sysInfo['memTotal']." M";
		$mu					= $sysInfo['memUsed']." M";
		$mf					= $sysInfo['memFree']." M";

		// Cached Memory
		$mc					= $sysInfo['memCached']." M";

		// Cache
		$mb					= $sysInfo['memBuffers']." M";
		$st					= $sysInfo['swapTotal']." M";
		$su					= $sysInfo['swapUsed']." M";
		$sf					= $sysInfo['swapFree']." M";
		$swapPercent		= $sysInfo['swapPercent'];

		// Real Memory Usage
		$memRealUsed		= $sysInfo['memRealUsed']." M";

		// Real Memory Free
		$memRealFree		= $sysInfo['memRealFree']." M";

		// Real Memory Usage Ratio
		$memRealPercent		= $sysInfo['memRealPercent'];

		// Total Memory Usage Ratio
		$memPercent			= $sysInfo['memPercent'];

		// Cached Memory Usage Ratio
		$memCachedPercent	= $sysInfo['memCachedPercent'];
	} else {
		$memTotal			= round($sysInfo['memTotal']/1024,3)." G";
		$mt					= round($sysInfo['memTotal']/1024,3)." G";
		$mu					= round($sysInfo['memUsed']/1024,3)." G";
		$mf					= round($sysInfo['memFree']/1024,3)." G";
		$mc					= round($sysInfo['memCached']/1024,3)." G";
		$mb					= round($sysInfo['memBuffers']/1024,3)." G";
		$st					= round($sysInfo['swapTotal']/1024,3)." G";
		$su					= round($sysInfo['swapUsed']/1024,3)." G";
		$sf					= round($sysInfo['swapFree']/1024,3)." G";
		$swapPercent		= $sysInfo['swapPercent'];

		// Real Memory Usage
		$memRealUsed		= round($sysInfo['memRealUsed']/1024,3)." G";

		// Real Memory Free
		$memRealFree		= round($sysInfo['memRealFree']/1024,3)." G";

		// Real Memory Usage Ratio
		$memRealPercent		= $sysInfo['memRealPercent'];

		// Total Memory Usage Ratio
		$memPercent			= $sysInfo['memPercent'];

		// Cached Memory Usage Ratio
		$memCachedPercent	= $sysInfo['memCachedPercent'];
	}

	// LAN Traffic
	$strs = @file("/proc/net/dev");
	for ($i = 2; $i < count($strs); $i++ ) {
		preg_match_all( "/([^\s]+):[\s]{0,}(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)/", $strs[$i], $info );
		$NetOutSpeed[$i]	= $info[10][0];
		$NetInputSpeed[$i]	= $info[2][0];
		$NetInput[$i]		= formatsize($info[2][0]);
		$NetOut[$i]			= formatsize($info[10][0]);
	}

	// Ajax Call Real-time Refresh
	if ($_GET['act'] == "rt")
	{
		$arr = array(
			'useSpace'				=> "$du",
			'freeSpace'				=> "$df",
			'hdPercent'				=> "$hdPercent",
			'barhdPercent'			=> "$hdPercent%",
			'TotalMemory'			=> "$mt",
			'UsedMemory'			=> "$mu",
			'FreeMemory'			=> "$mf",
			'CachedMemory'			=> "$mc",
			'Buffers'				=> "$mb",
			'TotalSwap'				=> "$st",
			'swapUsed'				=> "$su",
			'swapFree'				=> "$sf",
			'loadAvg'				=> "$load",
			'uptime'				=> "$uptime",
			'freetime'				=> "$freetime",
			'bjtime'				=> "$bjtime",
			'stime'					=> "$stime",
			'memRealPercent'		=> "$memRealPercent",
			'memRealUsed'			=> "$memRealUsed",
			'memRealFree'			=> "$memRealFree",
			'memPercent'			=> "$memPercent%",
			'memCachedPercent'		=> "$memCachedPercent",
			'barmemCachedPercent'	=> "$memCachedPercent%",
			'swapPercent'			=> "$swapPercent",
			'barmemRealPercent'		=> "$memRealPercent%",
			'barswapPercent'		=> "$swapPercent%",
			'NetOut2'				=> "$NetOut[2]",
			'NetOut3'				=> "$NetOut[3]",
			'NetOut4'				=> "$NetOut[4]",
			'NetOut5'				=> "$NetOut[5]",
			'NetOut6'				=> "$NetOut[6]",
			'NetOut7'				=> "$NetOut[7]",
			'NetOut8'				=> "$NetOut[8]",
			'NetOut9'				=> "$NetOut[9]",
			'NetOut10'				=> "$NetOut[10]",
			'NetInput2'				=> "$NetInput[2]",
			'NetInput3'				=> "$NetInput[3]",
			'NetInput4'				=> "$NetInput[4]",
			'NetInput5'				=> "$NetInput[5]",
			'NetInput6'				=> "$NetInput[6]",
			'NetInput7'				=> "$NetInput[7]",
			'NetInput8'				=> "$NetInput[8]",
			'NetInput9'				=> "$NetInput[9]",
			'NetInput10'			=> "$NetInput[10]",
			'NetOutSpeed2'			=> "$NetOutSpeed[2]",
			'NetOutSpeed3'			=> "$NetOutSpeed[3]",
			'NetOutSpeed4'			=> "$NetOutSpeed[4]",
			'NetOutSpeed5'			=> "$NetOutSpeed[5]",
			'NetInputSpeed2'		=> "$NetInputSpeed[2]",
			'NetInputSpeed3'		=> "$NetInputSpeed[3]",
			'NetInputSpeed4'		=> "$NetInputSpeed[4]",
			'NetInputSpeed5'		=> "$NetInputSpeed[5]"
		);
		$jarr				= json_encode($arr);
		$_GET['callback']	= htmlspecialchars($_GET['callback']);
		echo $_GET['callback'],'(',$jarr,')';
		exit;
	}
?>

@include('admin.header')
@yield('content')

<script>
	$(document).ready(function(){
		getJSONData();
	});

	var OutSpeed2   = <?php echo floor($NetOutSpeed[2]) ?>;
	var OutSpeed3	= <?php echo floor($NetOutSpeed[3]) ?>;
	var OutSpeed4	= <?php echo floor($NetOutSpeed[4]) ?>;
	var OutSpeed5	= <?php echo floor($NetOutSpeed[5]) ?>;
	var InputSpeed2	= <?php echo floor($NetInputSpeed[2]) ?>;
	var InputSpeed3	= <?php echo floor($NetInputSpeed[3]) ?>;
	var InputSpeed4	= <?php echo floor($NetInputSpeed[4]) ?>;
	var InputSpeed5	= <?php echo floor($NetInputSpeed[5]) ?>;

	function getJSONData() {
		setTimeout("getJSONData()", 1000);
		$.getJSON('?act=rt&callback=?', displayData);
	}

	function ForDight(Dight,How) {
	  if (Dight<0){
		var Last = 0+"B/s";
	  }else if (Dight<1024){
		var Last = Math.round(Dight*Math.pow(10,How))/Math.pow(10,How)+"B/s";
	  }else if (Dight<1048576){
		Dight=Dight/1024;
		var Last = Math.round(Dight*Math.pow(10,How))/Math.pow(10,How)+"K/s";
	  }else{
		Dight=Dight/1048576;
		var Last = Math.round(Dight*Math.pow(10,How))/Math.pow(10,How)+"M/s";
	  }
		return Last;
	}

	function displayData(dataJSON) {
		$("#useSpace").html(dataJSON.useSpace);
		$("#freeSpace").html(dataJSON.freeSpace);
		$("#hdPercent").html(dataJSON.hdPercent);
		$("#barhdPercent").width(dataJSON.barhdPercent);
		$("#TotalMemory").html(dataJSON.TotalMemory);
		$("#UsedMemory").html(dataJSON.UsedMemory);
		$("#FreeMemory").html(dataJSON.FreeMemory);
		$("#CachedMemory").html(dataJSON.CachedMemory);
		$("#Buffers").html(dataJSON.Buffers);
		$("#TotalSwap").html(dataJSON.TotalSwap);
		$("#swapUsed").html(dataJSON.swapUsed);
		$("#swapFree").html(dataJSON.swapFree);
		$("#swapPercent").html(dataJSON.swapPercent);
		$("#loadAvg").html(dataJSON.loadAvg);
		$("#uptime").html(dataJSON.uptime);
		$("#freetime").html(dataJSON.freetime);
		$("#stime").html(dataJSON.stime);
		$("#bjtime").html(dataJSON.bjtime);
		$("#memRealUsed").html(dataJSON.memRealUsed);
		$("#memRealFree").html(dataJSON.memRealFree);
		$("#memRealPercent").html(dataJSON.memRealPercent);
		$("#memPercent").html(dataJSON.memPercent);
		$("#barmemPercent").width(dataJSON.memPercent);
		$("#barmemRealPercent").width(dataJSON.barmemRealPercent);
		$("#memCachedPercent").html(dataJSON.memCachedPercent);
		$("#barmemCachedPercent").width(dataJSON.barmemCachedPercent);
		$("#barswapPercent").width(dataJSON.barswapPercent);
		$("#NetOut2").html(dataJSON.NetOut2);
		$("#NetOut3").html(dataJSON.NetOut3);
		$("#NetOut4").html(dataJSON.NetOut4);
		$("#NetOut5").html(dataJSON.NetOut5);
		$("#NetOut6").html(dataJSON.NetOut6);
		$("#NetOut7").html(dataJSON.NetOut7);
		$("#NetOut8").html(dataJSON.NetOut8);
		$("#NetOut9").html(dataJSON.NetOut9);
		$("#NetOut10").html(dataJSON.NetOut10);
		$("#NetInput2").html(dataJSON.NetInput2);
		$("#NetInput3").html(dataJSON.NetInput3);
		$("#NetInput4").html(dataJSON.NetInput4);
		$("#NetInput5").html(dataJSON.NetInput5);
		$("#NetInput6").html(dataJSON.NetInput6);
		$("#NetInput7").html(dataJSON.NetInput7);
		$("#NetInput8").html(dataJSON.NetInput8);
		$("#NetInput9").html(dataJSON.NetInput9);
		$("#NetInput10").html(dataJSON.NetInput10);
		$("#NetOutSpeed2").html(ForDight((dataJSON.NetOutSpeed2-OutSpeed2),3));
		OutSpeed2 = dataJSON.NetOutSpeed2;
		$("#NetOutSpeed3").html(ForDight((dataJSON.NetOutSpeed3-OutSpeed3),3));
		OutSpeed3 = dataJSON.NetOutSpeed3;
		$("#NetOutSpeed4").html(ForDight((dataJSON.NetOutSpeed4-OutSpeed4),3));
		OutSpeed4 = dataJSON.NetOutSpeed4;
		$("#NetOutSpeed5").html(ForDight((dataJSON.NetOutSpeed5-OutSpeed5),3));
		OutSpeed5 = dataJSON.NetOutSpeed5;
		$("#NetInputSpeed2").html(ForDight((dataJSON.NetInputSpeed2-InputSpeed2),3));
		InputSpeed2 = dataJSON.NetInputSpeed2;
		$("#NetInputSpeed3").html(ForDight((dataJSON.NetInputSpeed3-InputSpeed3),3));
		InputSpeed3 = dataJSON.NetInputSpeed3;
		$("#NetInputSpeed4").html(ForDight((dataJSON.NetInputSpeed4-InputSpeed4),3));
		InputSpeed4 = dataJSON.NetInputSpeed4;
		$("#NetInputSpeed5").html(ForDight((dataJSON.NetInputSpeed5-InputSpeed5),3));
		InputSpeed5 = dataJSON.NetInputSpeed5;
	}
</script>

	<div id="wrapper">

		@include('admin.navigation')
		@yield('content')

		<div id="page-wrapper">
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">{{ Lang::get('navigation.admin_server') }}</h1>
				</div>
				{{-- /.col-lg-12 --}}
			</div>
			{{-- /.row --}}
			<div class="row">
				<div class="col-lg-12">
					<div class="table-responsive">
						<table class="table">
							<tbody>
								<tr>
									<th colspan="5">{{ Lang::get('navigation.admin_server') }}</th>
								</tr>
								<tr>
									<td width="15%">{{ Lang::get('admin/server/index.domain_ip') }}</td>
									<td>
										<?php
											echo @get_current_user();
										?> -
										<?php
											echo $_SERVER['SERVER_NAME'];
										?>
										(
										<?php
											if('/' == DIRECTORY_SEPARATOR) {
												echo $_SERVER['SERVER_ADDR'];
											} else {
												echo @gethostbyname($_SERVER['SERVER_NAME']);
											}
										?>
										)&nbsp;&nbsp;{{ Lang::get('admin/server/index.your_ip') }}
										<?php
											echo @$_SERVER['REMOTE_ADDR'];
										?>
									</td>
									<td>{{ Lang::get('admin/server/index.admin_email') }}</td>
									<td colspan="2">
										<?php
											echo $_SERVER['SERVER_ADMIN'];
										?>
									</td>
								</tr>
								<tr>
									<td>{{ Lang::get('admin/server/index.server_os') }}</td>
									<td>
										<?php
											$os = explode(" ", php_uname());
											echo $os[0];
										?>
										&nbsp;{{ Lang::get('admin/server/index.kernel_version') }}:
										<?php
											if('/' == DIRECTORY_SEPARATOR) {
												echo $os[2];
											} else {
												echo $os[1];
											}
										?>
									</td>
									<td>{{ Lang::get('admin/server/index.web_service') }}</td>
									<td colspan="2">
										<?php
											echo $_SERVER['SERVER_SOFTWARE'];
										?>
									</td>
								</tr>
								<? if("show" == $sysReShow) { ?>
								<tr>
									<th colspan="5">{{ Lang::get('admin/server/index.real_time') }}</th>
								</tr>
								<tr>
									<td width="15%">{{ Lang::get('admin/server/index.local_time') }}</td>
									<td><span id="stime">
										<?php
											echo $stime;
										?>
									</span>
									</td>
									<td>{{ Lang::get('admin/server/index.run_time') }}</td>
									<td colspan="2">
										<span id="uptime">
											<?php
												echo $uptime;
											?>
										</span>
									</td>
								</tr>
								<tr>
									<td>{{ Lang::get('admin/server/index.cpu_model') }} [
										<?php
											echo $sysInfo['cpu']['num'];
										?>
										{{ Lang::get('admin/server/index.core') }}]</td>
									<td>
										<?php
											echo $sysInfo['cpu']['model'];
										?>
									</td>
									<td>{{ Lang::get('admin/server/index.cpu_usage') }}</td>
									<td colspan="2">
										<?php
											if('/' == DIRECTORY_SEPARATOR) {
												echo $cpu_show . " ";
											} else {
												echo Lang::get('admin/server/index.linux_support');
											}
										?>
									</td>
								</tr>
								<tr>
									<td>{{ Lang::get('admin/server/index.hdd_usage') }}</td>
									<td colspan="4">
										{{ Lang::get('admin/server/index.hdd_total') }}
										<?php
											echo $dt;
										?>
										&nbsp;G，
										{{ Lang::get('admin/server/index.hdd_used') }}
										<font color='#333333'>
											<span id="useSpace">
												<?php
													echo $du;
												?>
											</span>
										</font>&nbsp;G,
										{{ Lang::get('admin/server/index.hdd_free') }}
										<font color='#333333'>
												<span id="freeSpace">
												<?php
													echo $df;
												?>
											</span>
										</font>&nbsp;G,
										{{ Lang::get('admin/server/index.hdd_percent') }}
										<span id="hdPercent">
											<?php
												echo $hdPercent;
											?>
										</span>%

										<div class="progress progress-striped" style="margin: 10px 0 5px 0;">
											<div class="progress-bar progress-bar-info active" role="progressbar" aria-valuenow="<?php echo $hdPercent;?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $hdPercent;?>%">
												<span class="sr-only"><?php echo $hdPercent;?>% Complete (success)</span>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>{{ Lang::get('admin/server/index.memory_usage') }}</td>
									<td colspan="4">
										<?php
											$tmp = array(
												'memTotal',
												'memUsed',
												'memFree',
												'memPercent',
												'memCached',
												'memRealPercent',
												'swapTotal',
												'swapUsed',
												'swapFree',
												'swapPercent'
											);
											foreach ($tmp AS $v) {
												$sysInfo[$v] = $sysInfo[$v] ? $sysInfo[$v] : 0;
											}
										?>
										{{ Lang::get('admin/server/index.memory_total') }}
										<font color='#CC0000'><?php echo $memTotal;?> </font>
										, {{ Lang::get('admin/server/index.memory_used') }}
										<font color='#CC0000'><span id="UsedMemory"><?php echo $mu;?></span></font>
										, {{ Lang::get('admin/server/index.memory_free') }}
										<font color='#CC0000'><span id="FreeMemory"><?php echo $mf;?></span></font>
										, {{ Lang::get('admin/server/index.memory_percent') }}
										<span id="memPercent"><?php echo $memPercent;?></span>
										<div class="progress progress-striped" style="margin: 10px 0 5px 0;">
											<div class="progress-bar progress-bar-success active" role="progressbar" aria-valuenow="<?php echo $memPercent?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $memPercent?>%">
												<span class="sr-only"><?php echo $memPercent?>% Complete (success)</span>
											</div>
										</div>
										<?php
											// Determine if the cache is 0, don't show
											if($sysInfo[ 'memCached']>0) {
										?>
										{{ Lang::get('admin/server/index.memory_cached') }}
										<span id="CachedMemory"><?php echo $mc;?></span>, {{ Lang::get('admin/server/index.memory_percent') }}
										<span id="memCachedPercent"><?php echo $memCachedPercent;?></span>
										% | {{ Lang::get('admin/server/index.memory_buffers') }} <span id="Buffers"><?php echo $mb;?></span>
										<div class="progress progress-striped" style="margin: 10px 0 5px 0;">
											<div class="progress-bar progress-bar-warning active" role="progressbar" aria-valuenow="<?php echo $memCachedPercent?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $memCachedPercent?>%">
												<span class="sr-only"><?php echo $memCachedPercent?>% Complete (success)</span>
											</div>
										</div>
										{{ Lang::get('admin/server/index.memory_used') }}
										<span id="memRealUsed"><?php echo $memRealUsed;?></span>
										, {{ Lang::get('admin/server/index.memory_free') }}
										<span id="memRealFree"><?php echo $memRealFree;?></span>
										, {{ Lang::get('admin/server/index.memory_percent') }}
										<span id="memRealPercent"><?php echo $memRealPercent;?></span>
										%
										<div class="progress progress-striped" style="margin: 10px 0 5px 0;">
											<div class="progress-bar progress-bar-danger active" role="progressbar" aria-valuenow="<?php echo $memRealPercent?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $memRealPercent?>%">
												<span class="sr-only"><?php echo $memRealPercent?>% Complete (success)</span>
											</div>
										</div>
										<?php
										}

										// Determine if the SWAP is 0, don't show
										if($sysInfo[ 'swapTotal']>0) {
										?>
										SWAP区：共
										<?php
											echo $st;
										?>
										, 已使用
										<span id="swapUsed">
											<?php
												echo $su;
											?>
										</span>
										, 空闲
										<span id="swapFree">
											<?php
												echo $sf;
											?>
										</span>
										, 使用率
										<span id="swapPercent">
											<?php
												echo $swapPercent;
											?>
										</span>
										%
										<div class="progress progress-striped" style="margin: 10px 0 5px 0;">
											<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $swapPercent?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $swapPercent?>%">
												<span class="sr-only"><?php echo $swapPercent?>% Complete (success)</span>
											</div>
										</div>
										<?php
											}
										?>
									</td>
								</tr>
								<?}?>
								<?php
									if (false !== ($strs = @file("/proc/net/dev"))) :
								?>
								<tr>
									<th colspan="4">{{ Lang::get('admin/server/index.network_usage') }}</th></tr>
								<?php
									for ($i = 2; $i < count($strs); $i++ ) :
								?>
								<?php
									preg_match_all( "/([^\s]+):[\s]{0,}(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)/", $strs[$i], $info );
								?>
								<tr>
									<td><?php echo $info[1][0]?> : </td>
									<td width="29%">{{ Lang::get('admin/server/index.network_received') }}: <font color='#CC0000'><span id="NetInput<?php echo $i?>"><?php echo $NetInput[$i]?></span></font></td>
									<td width="14%">{{ Lang::get('admin/server/index.network_real_time') }}: <font color='#CC0000'><span id="NetInputSpeed<?php echo $i?>">0B/s</span></font></td>
									<td width="29%">{{ Lang::get('admin/server/index.network_sent') }}: <font color='#CC0000'><span id="NetOut<?php echo $i?>"><?php echo $NetOut[$i]?></span></font></td>
									<td width="14%">{{ Lang::get('admin/server/index.network_real_time') }}: <font color='#CC0000'><span id="NetOutSpeed<?php echo $i?>">0B/s</span></font></td>
								</tr>
								<?php endfor; ?>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			{{-- /.row --}}
		</div>
		{{-- /#page-wrapper --}}

	</div>
	{{-- /#wrapper --}}

	{{-- jQuery Version 1.11.0 --}}
	{{ HTML::script('assets/js/jquery-1.11.1/jquery.min.js') }}

	{{-- Bootstrap Core JavaScript --}}
	{{ HTML::script('assets/bootstrap-3.3.0/js/bootstrap.min.js') }}

	{{-- Metis Menu Plugin JavaScript --}}
	{{ HTML::script('assets/js/admin/plugins/metisMenu/metisMenu.min.js') }}

	{{-- Flot Charts JavaScript --}}
	{{ HTML::script('assets/js/admin/plugins/flot/excanvas.min.js') }}
	{{ HTML::script('assets/js/admin/plugins/flot/jquery.flot.js') }}
	{{ HTML::script('assets/js/admin/plugins/flot/jquery.flot.pie.js') }}
	{{ HTML::script('assets/js/admin/plugins/flot/jquery.flot.resize.js') }}
	{{ HTML::script('assets/js/admin/plugins/flot/jquery.flot.tooltip.min.js') }}

	{{-- Custom Theme JavaScript --}}
	{{ HTML::script('assets/js/admin/admin.js') }}


</body>
</html>