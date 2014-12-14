<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html" charset=utf-8" />
<meta name="viewport" content="width=320, initial-scale=1">
<link rel="stylesheet" type="text/css" href="css/main.css"> 
<link rel="stylesheet" href="//code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css"/>
<script src="//code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="//code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet">
<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css" rel="stylesheet">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/modernizr.mq.js"></script>
<script type="text/javascript" src="js/jquery.joyride-2.1.js"></script>
<title>Hey DJ Play My Song - Music</title>
</head>
<body>
<?php
/* error_reporting(E_ALL); */
/* ini_set('display_errors', '1'); */
include('db.php');
session_start();
/* var_dump($); */
/* Collecting the user email */
$playlistname = $_SESSION['playlistname'];
$userid = $_SESSION['userid'];
if(!isset($_SESSION['userid']) && empty($_SESSION['userid'])) {
	$link = '/';
}
else{
	$link = 'dashboard.php';
	$manageplaylistcheck = "CALL manageplaylistcheck ('$userid', '$playlistname')";
	$stmtd = $dsn->prepare($manageplaylistcheck);
	$stmtd->execute();
	$rsx = $stmtd->fetch(PDO::FETCH_ASSOC);
	if ($rsx){
		$songedit= '
		<div class="delete-span"><!-- voting-->
			<div class="admin" data-action="res" title="Reset song">
				<i class="fa fa-undo"></i>
			</div><!--reset song-->
			<div class="admin" data-action="del" title="Delete song">
				<i class="fa fa-trash-o"></i>
			</div><!--delete song-->
		</div>
		';
	}
	else{
		$songedit= null;
	}
}
$stmtd = null;
/* TOP20 TOP20 TOP20 TOP20 TOP20 TOP20 TOP20 TOP20 TOP20 TOP20 TOP20 TOP20 TOP20 TOP20 */
$getplaylist = "CALL gettop20 ('$playlistname')";
$stmt = $dsn->prepare($getplaylist);
$stmt->execute();
?>
<div data-role="header" data-theme="b">
	<button onclick="window.location='<?php echo $link; ?>'" data-icon="back" data-theme="b">Back</button>
	<h1>Music</h1>
	<button onclick="window.location='music.php'" data-icon="refresh" data-theme="b">Refresh</button>
</div>
<div data-role="collapsible-set" data-theme="b" data-content-theme="d" data-collapsed-icon="arrow-r" data-expanded-icon="arrow-d">
		<div data-role="collapsible">
			<h2>Top 20 Requested Songs</h2>
			<ul data-role="listview" data-inset="true" data-filter="true" data-filter-theme="c" data-divider-theme="d">
				<?php
				while ($rs = $stmt->fetch(PDO::FETCH_ASSOC)){
					?>
					<li>
						<div class="item" data-postid="<?php echo $rs['ID']; ?>" data-score="<?php echo $rs['RATING']; ?>">
							<div class="vote-span"><!-- voting-->
								<div class="vote" data-action="up" title="Vote up">
									<i class="fa fa-chevron-up"></i>
								</div><!--vote up-->
								<div class="vote-score"><?php echo $rs['RATING']; ?></div>
								<div class="vote" data-action="down" title="Vote down">
									<i class="fa fa-chevron-down"></i>
								</div><!--vote down-->
							</div>
							<div class="post"><!-- post data -->
								<h2><?php echo $rs['AName'] ?></h2>
								<p><?php echo $rs['Artist'] ?></p>
							</div>
							<?php echo $songedit; ?>
						</div><!--item-->
					</li>
				<?php
				}
				?>
			</ul>
		</div>
	<?php
	/* ALL ALL ALL ALL ALL ALL ALL ALL ALL ALL ALL ALL ALL ALL ALL ALL ALL ALL ALL ALL ALL */
	$getplaylist = "CALL getallsongs ('$playlistname')";
	$stmt = $dsn->prepare($getplaylist);
	$stmt->execute();
	?>
	<div data-role="collapsible">
		<h2>All Songs</h2>
		<ul data-role="listview" data-inset="true" data-filter="true" data-filter-reveal="true" data-filter-placeholder="Search songs...">
		<?php
		while ($rs = $stmt->fetch(PDO::FETCH_ASSOC)){
			?>
			<li>
				<div class="item" data-postid="<?php echo $rs['ID'] ?>" data-score="<?php echo $rs['RATING'] ?>">
					<div class="vote-span"><!-- voting-->
						<div class="vote" data-action="up" title="Vote up">
							<i class="fa fa-chevron-up"></i>
						</div><!--vote up-->
						<div class="vote-score"><?php echo $rs['RATING'] ?></div>
						<div class="vote" data-action="down" title="Vote down">
							<i class="fa fa-chevron-down"></i>
						</div><!--vote down-->
					</div>
						<div class="post"><!-- post data -->
							<h2><?php echo $rs['AName'] ?></h2>
							<p><?php echo $rs['Artist'] ?></p>
						</div>
						<?php echo $songedit; ?>
				</div><!--item-->
			</li>
		<?php
		}
		?>
		</ul>
	</div>
</div>
<script type="text/javascript">
/**
* jQuery Voting System with PHP and MySQL
* @author Resalat Haque
* @link http://www.w3bees.com/2013/09/voting-system-with-jquery-php-and-mysql.html
*/

$(document).ready(function(){
	// ajax setup
	$.ajaxSetup({
		url: 'ajaxvote.php',
		type: 'POST',
		cache: 'false'
	});

	// any voting button (up/down) clicked event
	$('.vote').click(function(){
		var self = $(this); // cache $this
		var action = self.data('action'); // grab action data up/down 
		var parent = self.parent().parent(); // grab grand parent .item
		var postid = parent.data('postid'); // grab post id from data-postid
		var score = parent.data('score'); // grab score form data-score

		// only works where is no disabled class
		if (!parent.hasClass('.disabled')) {
			// vote up action
			if (action == 'up') {
				// increase vote score and color to orange
				parent.find('.vote-score').html(++score).css({'color':'orange'});
				// change vote up button color to orange
				self.css({'color':'orange'});
				// send ajax request with post id & action
				$.ajax({data: {'postid' : postid, 'action' : action }});
				/* alert('postid is=' + postid + 'action is= ' + action); */
			}
			// voting down action
			else if (action == 'down'){
				// decrease vote score and color to red
				parent.find('.vote-score').html(--score).css({'color':'red'});
				// change vote up button color to red
				self.css({'color':'red'});
				// send ajax request
				$.ajax({data: {'postid' : postid, 'action' : action}});
				/* alert('postid is=' + postid + 'action is= ' + action); */
			};

			// add disabled class with .item
			parent.addClass('.disabled');
		};
	});
	
	// any voting button (up/down) clicked event
	$('.admin').click(function(){
		var self = $(this); // cache $this
		var action = self.data('action'); // grab action data up/down 
		var parent = self.parent().parent(); // grab grand parent .item
		var postid = parent.data('postid'); // grab post id from data-postid

		// only works where is no disabled class
		if (!parent.hasClass('.disabled')) {
			// reset action
			if (action == 'res') {
				// change reset up button color to orange
				self.css({'color':'orange'});
				// send ajax request with post id & action
				$.ajax({data: {'postid' : postid, 'action' : action}});
				/* alert('postid is=' + postid + 'action is= ' + action); */ 
			}
			// delete action
			else if (action == 'del'){
				// change delete button color to red
				self.css({'color':'red'});
				// send ajax request
				$.ajax({data: {'postid' : postid, 'action' : action}});
				/* alert('postid is=' + postid + 'action is= ' + action); */
			};

			// add disabled class with .item
			parent.addClass('.disabled');
		};
	});
});
</script>
<!--
<script type="text/javascript">
$('ul').children('li').on('click', function () {
	var songid = $(this).text();
	if (confirm(songid)) { 
		var js_var = $(this).text();
		var js_var2 = '<?php echo $userid; ?>';
		// ajax start
		var xhr;
		if (window.XMLHttpRequest){
			xhr = new XMLHttpRequest(); // all browsers 
		}
		else{
		xhr = new ActiveXObject("Microsoft.XMLHTTP");  // for IE
		}
		
		var url = 'check-exists.php?userid=' + js_var2;
		xhr.open('GET', url, false);
		xhr.onreadystatechange = function () {
			if (xhr.readyState===4 && xhr.status===200) {
				var div = document.getElementById('update');
				div.innerHTML = xhr.responseText;
			}
		};
		xhr.send();
		// ajax stop
		return false;
	}
});
</script>
-->
<ol id="joyRideTipContent">
	<li data-text="Ok" data-options="tipLocation:bottom;">
		<p>Here you can vote up/down a song as well as search for specific song.</p>
	</li>
</ol>
 <!--
 * Markup for jQuery Joyride Plugin 2.1
 * www.ZURB.com/playground
 * Copyright 2013, ZURB
 * Free to use under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 -->
<a href="#" data-role="button" class="button" id="openTour" data-icon="info" data-theme="b">Start Tour</a>
<script type="text/javascript">
	$('#openTour').click(function() {
        $('#joyRideTipContent').joyride({
            autoStart : true,
            cookieMonster: false,
        });
    });
</script>
<?php
$con->close();
$dsn = null;
?>
</body>
</html>