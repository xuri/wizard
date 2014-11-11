<!DOCTYPE html>
<html>
<head>
	<title>Database Migrate Script</title>

	<!-- The Meta -->
	<meta charset="UTF-8">
	{{ HTML::style('assets/css/bootstrap.css') }}
	{{-- jQuery Version 1.11.0 --}}
    {{ HTML::script('assets/js/jquery-1.11.1.min.js') }}

    {{-- Bootstrap Core JavaScript --}}
	{{ HTML::script('assets/js/bootstrap.min.js') }}
</heade>

<body>
<br />
<div class="row">
	<div class="container">
		<div class="jumbotron">
			<h2>Database Migrate Script</h2>
			<p>This script only use for new pinai521.com database migrate. Written by luxurioust.</p>
			<p><a class="btn btn-success btn-lg" role="button">Migration Success!</a></p>
		</div>

		<?php

		/*
		|--------------------------------------------------------------------------
		| Database Migrate Script Info
		|--------------------------------------------------------------------------
		|
		| This script only use for new pinai521.com database migrate.
		| Create by luxurioust.
		| Visit https://xuri.me
		|
		*/


		/*
		|--------------------------------------------------------------------------
		| Connect Database via PDO
		|--------------------------------------------------------------------------
		|
		*/
			$mHost = array(
					'host'   => 'localhost',
					'dbname' => 'test',
					'dbuser' => 'root',
					'dbpass' => 'password'
				);

			$sHost = array(
					'host'   => 'localhost',
					'dbname' => 'newpinai',
					'dbuser' => 'root',
					'dbpass' => 'password'
				);

		/*
		|--------------------------------------------------------------------------
		| Main Logic Establish Connection
		|--------------------------------------------------------------------------
		|
		*/

			try {
			    $mdbh = new PDO('mysql:host='.$mHost['host'].';dbname='.$mHost['dbname'], $mHost['dbuser'], $mHost['dbpass']);
			    $sdbh = new PDO('mysql:host='.$sHost['host'].';dbname='.$sHost['dbname'], $sHost['dbuser'], $sHost['dbpass']);

			    $mdbh->query("set names utf8");
			    $sdbh->query("set names utf8");

		/*
		|--------------------------------------------------------------------------
		| Main Foreach
		|--------------------------------------------------------------------------
		|
		*/
				$transCount = 0;
			    foreach($mdbh->query('SELECT * from rl_user') as $row) {
			    	$transCount++;
			    	$mData = array(
						'email'      => $row['email'],
						'password'   => Hash::make($row['password']),
						'nickname'   => $row['nickname'],
						'sex'        => $row['sex'],
						'school'     => $row['school'],
						'born_year'  => $row['age'],
						'phone'      => $row['tel'],
						'created_at' => date("Y-m-d 00:00:00", $row['create_time']),
		    		);

		/*
		|--------------------------------------------------------------------------
		| User Portrait Section
		|--------------------------------------------------------------------------
		|
		*/
					$uid = $row['id'];
					foreach ( $mdbh->query("SELECT * FROM rl_resume where uid = '$uid'") as $rowResume){
		    			$mResume = array(
		    				'portrait' => $rowResume['head_pic'],
		    			);
			    	};

			   //  	if ($mResume['portrait'] == '__PUBLIC__/Images/default/head_pic.png') {
			   //  		$mResume['portrait'] = NULL;
			   //  	} else {
			   //  		// $portrait = preg_replace('[\__ROOT\__]','/home/luxurioust/pinai521/Public', $mResume['portrait']);
			   //  		$mPortrait = preg_replace('[\__ROOT\__]','/Users/luxurioust/Sites/test', $mResume['portrait']);

						// $portraitInfo     = pathinfo($mPortrait);
						// $portraitExt      = $portraitInfo['extension'];
						// $portraitFullname = $portraitInfo['basename'];
						// $portraitHashname = date('H.i.s').'-'.md5($portraitFullname).rand(100,999).'.'.$portraitExt; // Hash filename
						// // Storing images of different sizes
						// $portraitSave     = Image::make($mPortrait);
						// // crop the best fitting 1:1 ratio and resize to custom pixel
						// $portraitSave->fit(200)->save(public_path('portrait/large/'.$portraitHashname));
			   //  	}

		/*
		|--------------------------------------------------------------------------
		| User School Section
		|--------------------------------------------------------------------------
		|
		*/
					switch ($mData['school']) {
						case "哈师大":
							$mSchool = '哈尔滨师范大学';
							break;
						case "黑工程":
							$mSchool = '黑龙江工程学院';
							break;
						case "哈工程":
							$mSchool = '哈尔滨工程大学';
							break;
						case "哈理工":
							$mSchool = '哈尔滨理工大学';
							break;
						case "牡丹江师范":
							$mSchool = '牡丹江师范学院';
							break;
						case "哈工大":
							$mSchool = '哈尔滨工业大学';
							break;
						case "东方学院":
							$mSchool = '黑龙江东方学院';
							break;
						case "东北农大":
							$mSchool = '东北农业大学';
							break;
						case "哈尔滨体院":
							$mSchool = '哈尔滨体育学院';
							break;
						case "黑龙江中医药":
							$mSchool = '黑龙江中医药大学';
							break;
						case "黑龙江中医药":
							$mSchool = '黑龙江中医药大学';
							break;
						default:
							$mSchool = $mData['school'];
					}

					if( $mData['sex'] == "男"){
							$mSex = 'M';
						} elseif ($mData['sex'] == '女') {
							$mSex = 'F';
						} else {
							$mSex = 'S';
					}

		/*
		|--------------------------------------------------------------------------
		| Store Process
		|--------------------------------------------------------------------------
		|
		*/
					$stmt = $sdbh->prepare('INSERT INTO pa_users (
							email,
							password,
							nickname,
							sex,
							school,
							born_year,
							phone,
							portrait,
							activated_at,
							created_at,
							update_at
						) VALUES (
							:email,
							:password,
							:nickname,
							:sex,
							:school,
							:born_year,
							:phone,
							:portrait,
							:activated_at,
							:created_at,
							:update_at
						)');

					$stmt->bindParam(':email', 			$mData['email']);
					$stmt->bindParam(':password', 		$mData['password']);
					$stmt->bindParam(':nickname', 		$mData['nickname']);
					$stmt->bindParam(':sex', 			$mSex);
					$stmt->bindParam(':school', 		$mSchool);
					$stmt->bindParam(':born_year', 		$mData['born_year']);
					$stmt->bindParam(':phone', 			$mData['phone']);
					$stmt->bindParam(':portrait', 		$portraitHashname);
					$stmt->bindParam(':activated_at',	$mData['created_at']);
					$stmt->bindParam(':created_at', 	$mData['created_at']);
					$stmt->bindParam(':update_at', 		$mData['created_at']);

					$activation        = new Activation;
	                $activation->email = $mData['email'];
	                $activation->token = str_random(40);

	                // $activation->save();
					// $stmt->execute();

					echo $mData['created_at'];
					echo "<br />";
			        echo $mData['nickname']."<pre>";
			        print_r($mData['email']);
			        echo "</pre>";

			    }

		/*
		|--------------------------------------------------------------------------
		| Emancipation Connection
		|--------------------------------------------------------------------------
		|
		*/
			    $mdbh = null;
				$sdbh = null;
			} catch (PDOException $e) {
			    print "Error!: " . $e->getMessage() . "<br/>";
			    die();
			}
			    echo '<div class="alert alert-success" role="alert"><b>Total Transfer:</b> '.$transCount.' users</div>';

		?>

		<div class="progress">
			<div class="progress-bar" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
			    100%
			</div>
		</div>
		<div id="txtHint"><img src=bar.gif height=10 width=10></div>
	</div>

</div>
</body>
</html>