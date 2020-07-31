<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>
<body id="login" class="animated fadeInDown">
	<header id="header">
		<div id="logo-group">
			<span id="logo"> <img src="<?php echo Yii::app()->request->baseUrl . '/images/reelforge_logo.png'; ?>" alt="<?php echo CHtml::encode(Yii::app()->name); ?>"> </span>
		</div>
	</header>
	<div id="main" role="main">
		<div id="content" class="container">
			<div class="row">
				<?php //echo $content; ?>
				<h2>Error</h2>
				<p>There was an Error Processing your Request. Please try again Later.</p>
				<br>
				<a href="<?=Yii::app()->createUrl("home/index");?>" class="btn btn-danger">Go Back Home ?</a>
			</div>
		</div>
	</div>
	<script>
  !function(g,s,q,r,d){r=g[r]=g[r]||function(){(r.q=r.q||[]).push(
  arguments)};d=s.createElement(q);q=s.getElementsByTagName(q)[0];
  d.src='//d1l6p2sc9645hc.cloudfront.net/tracker.js';q.parentNode.
  insertBefore(d,q)}(window,document,'script','_gs');

  _gs('GSN-097805-I');
</script>
</body>
</html>
