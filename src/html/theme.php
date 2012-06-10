<?php
/**
 * Main theme layout file.
 *
 * @package    Joomla.PullTester
 *
 * @copyright  Copyright (C) 2011 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<title>Requests - Joomla Pull Request Tester</title>
	<link href="/css/bootstrap.css" rel="stylesheet" type="text/css" />
	<link href="/css/bootstrap.extended.css" rel="stylesheet" type="text/css" />
	<link href="/css/theme.css" rel="stylesheet" type="text/css" />
	<link href="/css/bootstrap-responsive.css" rel="stylesheet" type="text/css" />
	<!-- Le fav and touch icons -->
	<link rel="shortcut icon" href="/ico/favicon.ico">
	<link rel="apple-touch-icon" href="/ico/apple-touch-icon.png">
	<link rel="apple-touch-icon" sizes="72x72" href="/ico/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="114x114" href="/ico/apple-touch-icon-114x114.png">
</head>
<body id="top">
<!-- Admin Toolbar  -->
<div class="navbar navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<span class="brand">Pull Request Tester</span>
			<div class="nav-collapse">
				<ul class="nav pull-right">
					<li class="divider-vertical"></li>
					<li><a class="btn btn-navbar" target="_blank" href="../../admin/index.html"><i class="icon-lock icon-white"></i> Admin</a></li>
					<li class="dropdown">
		              <a href="#" class="btn btn-navbar dropdown-toggle" data-toggle="dropdown"><i class="icon-user icon-white"></i> username <b class="caret"></b></a>
		              <ul class="dropdown-menu">
		                <li><a href="#">Profile</a></li>
		                <li><a href="#">Edit Account</a></li>
		                <li class="divider"></li>
		                <li><a href="#">Sign out</a></li>
		              </ul>
		            </li>
				</ul>
			</div>
			<!--/.nav-collapse -->
		</div>
	</div>
</div>
<!-- Header -->
<div class="header">
	<div class="header-inner">
		<div class="container">
			<a class="brand pull-left" href="../index.html">
				<img src="/img/joomla.png" alt="Joomla" />
			</a>
			<a class="tip-small" href="#" rel="popover" data-original-title="Site Logo" data-placement="right">
				<i class="icon-info-sign"></i>
			</a>
			<ul class="nav nav-pills pull-right">
				<li>
					<a class="tip-small" href="#" rel="popover" data-original-title="Main Navigation" data-placement="left">
						<i class="icon-info-sign"></i>
					</a>
				</li>
				<li class="active"><a href="/">Home</a></li>
				<li class=""><a href="https://github.com/joomla/joomla-platform">GitHub</a></li>
				<li class=""><a href="http://developer.joomla.org">Developer Network</a></li>
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#">Examples <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li class=""><a href="../blog/index.html">Blog</a></li>
						<li class=""><a href="../community/index.html">Community</a></li>
						<li class=""><a href="../gallery/index.html">Gallery</a></li>
						<li class=""><a href="../shop/index.html">Shop</a></li>
						<li class=""><a href="../events/index.html">Events</a></li>
					</ul>
				</li>
			</ul>
			<!--/.nav-collapse -->
		</div>
	</div>
</div>
<!-- Container -->
<div class="container">
	<div class="page-header">
		<div class="btn-group pull-right" data-toggle="buttons-radio">
			<button class="btn">Open</button>
			<button class="btn">Closed</button>
		</div>
		<div class="btn-group pull-right">
			<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
				Sort
				<span class="caret"></span>
			</a>
			<ul class="dropdown-menu">
				<li class=""><a href="">Blog</a></li>
				<li class=""><a href="../community/index.html">Community</a></li>
				<li class=""><a href="../gallery/index.html">Gallery</a></li>
				<li class=""><a href="../shop/index.html">Shop</a></li>
				<li class=""><a href="../events/index.html">Events</a></li>
			</ul>
		</div>
		<h1>Pull Requests</h1>
	</div>
	<p>Dapibus dapibus nunc phasellus dolor, aliquam augue mattis et sed tortor turpis? Phasellus elit ultricies, urna porttitor nunc eros egestas dis mus vut nunc, dolor ultricies ac urna turpis magna, sed. Auctor? Natoque a, sagittis, lectus. Elementum dapibus, sed, natoque? Ac lorem? Nisi a in etiam nec diam? Velit, elit! Facilisis egestas? </p>
	<div class="row">
		<div class="span12">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Request</th>
						<th>Author</th>
						<th>Title</th>
						<th width="15%">State</th>
						<th width="15%">Updated Date</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($this->model->getRequests() as $request) : $body = substr(strip_tags($request->data->body), 0, 250); ?>
					<tr>
						<td>
							<a href="<?php echo $request->data->html_url; ?>">
								<?php echo $request->data->number; ?></a>
						</td>
						<td>
							<a href="https://github.com/<?php echo $request->data->user->login; ?>">
								<img src="https://secure.gravatar.com/avatar/<?php echo $request->data->user->gravatar_id; ?>?s=80&d=mm" class="thumbnail" alt="" />
								<?php echo $request->data->user->login; ?></a>
						</td>
						<td>
							<h4><?php echo $request->data->title; ?></h4>
							<p><?php echo (strlen($request->data->body) > 250) ? $body . '...' : $body; ?></p>
							<p>
								<span class="btn btn-mini"><i class="icon-ok icon"></i> Style Errors</span>
								<span class="btn btn-mini btn-warning"><i>3</i> Style Warnings</span>
								<span class="btn btn-mini btn-danger"><i>7</i> Test Errors</span>
								<span class="btn btn-mini"><i class="icon-ok icon"></i> Test Warnings</span>
							</p>
						</td>
						<td>
							<?php if ($request->data->state == 'open' && $request->data->mergeable) : ?>
							<span class="btn btn-large btn-success"><i class="icon-ok icon-white"></i> Mergeable</span>
							<?php elseif ($request->data->state == 'open' && !$request->data->mergeable) : ?>
							<span class="btn btn-large btn-danger"><i class="icon-remove icon-white"></i> Not Mergeable</span>
							<?php elseif (!$request->data->merged) : ?>
							<span class="btn btn-large btn-inverse"><i class="icon-white icon-remove-circle"></i> Not Merged</span>
							<?php else : ?>
							<span class="btn btn-large"><i class="icon icon-ok-circle"></i> Merged</span>
							<?php endif; ?>
						</td>
						<td><?php echo JFactory::getDate($request->data->updated_at)->format('Y-m-j, g:i a'); ?></td>
						</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<div class="span4">
			<h3>Odio tincidunt nisi </h3>
			<p>Odio tincidunt nisi nascetur phasellus nunc purus proin ut dapibus cursus enim mus, mattis hac scelerisque! Non, et sit aliquet, vel lectus! Dignissim nec integer magna sit pulvinar porta ultricies, mus velit integer aliquet adipiscing ac. Cum mattis amet ridiculus lectus scelerisque lorem nec mattis natoque porta lundium, duis massa, vel. Ut ac. Ut sit sed magna vut? Cras sed? Vel enim adipiscing nisi dapibus cursus, pellentesque in.</p>
		</div>
		<div class="span4">
			<h3>Elementum ac velit sit in</h3>
			<p>Elementum ac velit sit in! Amet platea mid in dis facilisis nascetur odio, est ac, tincidunt sit a ac, in elementum et turpis nisi adipiscing cras! Parturient, nec rhoncus egestas in aenean, nunc! Sit turpis mus? Eu proin, aenean. Amet aliquet! Scelerisque nec? Massa! </p>
		</div>
		<div class="span4">
			<h3>Ut dis ac elit ac porta </h3>
			<p> Ut dis ac elit ac porta adipiscing? Vel amet scelerisque urna massa aenean mid magnis, porttitor etiam pellentesque purus ut mauris, facilisis nisi enim! Amet porttitor lorem natoque etiam, ac rhoncus mus diam. Augue nunc! Et, turpis etiam integer, nunc natoque integer enim, ac aliquam! Magna integer, pulvinar tortor nec ac scelerisque sed! Lectus, odio, pulvinar enim et, eu! Lectus nisi! Duis amet magnis magna urna, mus? Scelerisque placerat, cras urna elementum mauris purus egestas.</p>
		</div>
	</div>
	<hr />
	<div class="footer">
		<p class="pull-right"><a href="#top" id="back-top">Back to top</a></p>
		<p>&copy; Copyright OpenSourceMatters, Inc. 2012, All rights reserved.</p>
	</div>
</div>
<!-- Image Zoom Modal -->
<div class="modal fade" id="image-modal">
	<div class="modal-header"> <a class="close" data-dismiss="modal">×</a>
		<h3>Thumbnail Caption</h3>
	</div>
	<div class="modal-body">
		<p><img src="http://placehold.it/530x397" alt=""></p>
		<p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
	</div>
	<div class="modal-footer"><a href="#" class="btn" data-dismiss="modal">Close</a> </div>
</div>
<!-- Le javascript -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="/js/jquery-1.7.1.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script type="text/javascript">
	$('.dropdown-toggle').dropdown()
	$('#myModal').modal('hide')
	$('.typeahead').typeahead()
	$('.tabs').button()
	$('.tip').popover('hide')
	$('.tip-small').tooltip('hide')
	$(".alert-message").alert()
	$(window).bind("load resize", function() {
		var windowHeight = "height:"+($(window).height()-45)+"px"; // height of full document
		var windowWidth = "width:"+($(window).width()-30)+"px"; // width of full document
		$('.side-nav, .fluid-content.main, .modal.full').attr('style', windowHeight);
	});
	$(document).ready(function() {
		$(".tip-small").hide();
        $(".tip-hide").click(function(){
           $(".tip-small").toggle();
        });
	});
</script>
</body>
</html>
