<?php if(!isset($_SESSION['view'])){ $class = 'unminified'; }else{ $class = $_SESSION['view']; } ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/url.js"></script>
</head>
<script type="text/javascript">
    setInterval("checkLoad()",1000);
</script>
<body class=" fixed-header fixed-navigation <?=$class;?>">
	<div id="preLoaderDiv">
	    <img id="preloaderAnimation" src="<?php echo Yii::app()->request->baseUrl . '/images/loading.gif'; ?>" />
	</div>
<!-- HEADER -->
	<header id="header">
		<div id="logo-group">
			<span id="logo"> <a href="<?=Yii::app()->createUrl("site");?>"><img src="<?php echo Yii::app()->request->baseUrl . '/images/reelforge_logo.png'; ?>" alt="<?php echo CHtml::encode(Yii::app()->name); ?>" ></a> </span>
		</div>


		<!-- pulled right: nav area -->
		<div class="pull-right">
			<img src="<?php echo Yii::app()->request->baseUrl . '/images/anvil_logo.png'; ?>" alt="Anvil Logo" >

			<!-- collapse menu button -->
			<div id="hide-menu" class="btn-header pull-right">
				<span> <a href="javascript:void(0);" title="Collapse Menu"><i class="fa fa-reorder"></i></a> </span>
			</div>
			<!-- end collapse menu -->

			<!-- logout button -->
			<div id="logout" class="btn-header transparent pull-right">
				<span> <a href="<?=Yii::app()->createUrl("site/logout");?>" title="Sign Out"><i class="fa fa-sign-out"></i></a> </span>
			</div>
			<!-- end logout button -->


		</div>
		<!-- end pulled right: nav area -->

	</header>
	<!-- END HEADER -->



	<aside id="left-panel" style="min-height:100%">
		<!-- User info -->
			<div class="login-info">
				<span> <!-- User image size is adjusted inside CSS, it should stay as it --> 
					
					<a href="javascript:void(0);" id="show-shortcut">
						<img src="<?php echo Yii::app()->request->baseUrl . '/images/avatars/male.png'; ?>" alt="me" class="online" /> 
						<span>
							<?php echo Yii::app()->user->company_name; ?>
						</span>
					</a> 
					
				</span>
			</div>
			<nav>
				<ul class="nav">
					<li>
						<a href="<?=Yii::app()->createUrl("home/index");?>" title="Dashboard"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Anvil Dashboard</span></a>
					</li>

					<!-- This Section is not meant to be viewed by Adflite -->
					<?php if(Yii::app()->user->usertype!='adflite'){ ?>
					<li>
						<a href="#" title="Media Reports"><i class="fa fa-lg fa-fw fa-file-image-o"></i> <span class="menu-item-parent">Media Reports</span></a>
						<ul>
							<?php
							/** 
							* Check User Subscriptions, Build Menu Based Upon Subscriptions
							*/
							if($subscriptions=AnvilSubscriptions::IfSubscribed(Yii::app()->user->subscriptions,1)){
								echo '<li><a href="'.Yii::app()->createUrl("media/electronic").'">Proof of Flight(Electronic)</a></li>';
							}
							$company_id = Yii::app()->user->company_id;
							if(Yii::app()->user->usertype=='client' && $subscriptions=AnvilSubscriptions::IfSubscribed(Yii::app()->user->subscriptions,26)){
								echo '<li><a href="'.Yii::app()->createUrl("media/keywordpof").'">POF - Keywords</a></li>';
							}
							if(Yii::app()->user->usertype=='client' && $subscriptions=AnvilSubscriptions::IfSubscribed(Yii::app()->user->subscriptions,27)){
								echo '<li><a href="'.Yii::app()->createUrl("media/pofback").'">B2B POF</a></li>';
							}
							if(Yii::app()->user->usertype=='client' && $subscriptions=AnvilSubscriptions::IfSubscribed(Yii::app()->user->subscriptions,28)){
								echo '<li><a href="'.Yii::app()->createUrl("media/callin").'">Call-in POF</a></li>';
							}
							if(Yii::app()->user->usertype=='client' && $subscriptions=AnvilSubscriptions::IfSubscribed(Yii::app()->user->subscriptions,33)){
								echo '<li><a href="'.Yii::app()->createUrl("media/competitorpof").'">Competitor POF</a></li>';
							}
							
							if(Yii::app()->user->usertype=='agency'){
								echo '<li><a href="'.Yii::app()->createUrl("qc/analyticspof").'">Analytics POF</a></li>';
							}

							if($subscriptions=AnvilSubscriptions::IfSubscribed(Yii::app()->user->subscriptions,4)){
								echo '<li><a href="'.Yii::app()->createUrl("media/regular").'">Proof of Print</a></li>';
							}
							if($subscriptions=AnvilSubscriptions::IfSubscribed(Yii::app()->user->subscriptions,16)){
								echo '<li><a href="'.Yii::app()->createUrl("media/outdoor").'">Outdoor Channel Reports</a></li>';
							}
							

							
							?>
						</ul>
					</li>
					<?php if(Yii::app()->user->usertype=='agency'){ ?>
					<!-- <li>
						<a href="#" title="TRP Reports"><i class="fa fa-lg fa-fw fa-bar-chart"></i> <span class="menu-item-parent">TRP Reports</span></a>
						<ul>
							<li><a href="<?=Yii::app()->createUrl("trp/index");?>">Media Cost Per TRP</a></li>
						</ul>
					</li> -->
					<?php }?>
					<li>
						<a href="#" title="Competitor Analysis"><i class="fa fa-lg fa-fw fa-users"></i> <span class="menu-item-parent">Competitor Analysis</span></a>
						<ul>
							<?php
							if($subscriptions=AnvilSubscriptions::IfSubscribed(Yii::app()->user->subscriptions,2)){
								echo '<li><a href="'.Yii::app()->createUrl("competitor/company").'">Industry Competitor (Company) Report</a></li>';
							}

							if($subscriptions=AnvilSubscriptions::IfSubscribed(Yii::app()->user->subscriptions,3)){
								echo '<li><a href="'.Yii::app()->createUrl("competitor/brand").'">Industry Competitor (Brand) Report</a></li>';
							}
							if($subscriptions=AnvilSubscriptions::IfSubscribed(Yii::app()->user->subscriptions,17)){
								echo '<li><a href="'.Yii::app()->createUrl("media/companyairplay").'">Total Airplay(Company) Report</a></li>';
							}
							if($subscriptions=AnvilSubscriptions::IfSubscribed(Yii::app()->user->subscriptions,18)){
								echo '<li><a href="'.Yii::app()->createUrl("media/brandairplay").'">Total Airplay(Brand) Report</a></li>';
							}
							if($subscriptions=AnvilSubscriptions::IfSubscribed(Yii::app()->user->subscriptions,2)){
								echo '<li><a href="'.Yii::app()->createUrl("competitor/adselectronic").'">Competitor Ads (Electronic Media)</a></li>';
							}
							if($subscriptions=AnvilSubscriptions::IfSubscribed(Yii::app()->user->subscriptions,2)){
								echo '<li><a href="'.Yii::app()->createUrl("competitor/adsprint").'">Competitor Ads (Print Media)</a></li>';
							}
							if($subscriptions=AnvilSubscriptions::IfSubscribed(Yii::app()->user->subscriptions,2)){
								echo '<li><a href="'.Yii::app()->createUrl("competitor/archives").'">Archives - Downloads</a></li>';
							}
							?>
							<!-- <li><a href="<?=Yii::app()->createUrl("competitor/marketshare");?>">Share of Market</a></li> -->
						</ul>
					</li>
					<li>
						<a href="#" title="Company Ranking"><i class="fa fa-lg fa-fw fa-line-chart"></i> <span class="menu-item-parent">Company Ranking</span></a>
						<ul>
							<?php
							if($subscriptions=AnvilSubscriptions::IfSubscribed(Yii::app()->user->subscriptions,11)){
								echo '<li><a href="'.Yii::app()->createUrl("ranking/companyspenders").'">Top Spenders By Company</a></li>';
							}
							if($subscriptions=AnvilSubscriptions::IfSubscribed(Yii::app()->user->subscriptions,12)){
								echo '<li><a href="'.Yii::app()->createUrl("ranking/brandspenders").'">Top Spenders By Brand</a></li>';
							}
							if($subscriptions=AnvilSubscriptions::IfSubscribed(Yii::app()->user->subscriptions,13)){
								echo '<li><a href="'.Yii::app()->createUrl("ranking/summaryspends").'">Summary Spends By Media</a></li>';
							}
							
							?>
						</ul>
					</li>
					<?php if(Yii::app()->user->usertype=='agency'){ ?>
					<li>
						<a href="#" title="Miscellaneous Reports"><i class="fa fa-lg fa-fw fa-bookmark-o"></i> <span class="menu-item-parent">Miscellaneous Reports</span></a>
						<ul>
							<?php
							
							if($subscriptions=AnvilSubscriptions::IfSubscribed(Yii::app()->user->subscriptions,14)){
								echo '<li><a href="'.Yii::app()->createUrl("misc/industrysummary").'">Industry Summary</a></li>';
							}
							if($subscriptions=AnvilSubscriptions::IfSubscribed(Yii::app()->user->subscriptions,19)){
								echo '<li><a href="'.Yii::app()->createUrl("misc/ratechange").'">Rate Change</a></li>';
							}
							if($subscriptions=AnvilSubscriptions::IfSubscribed(Yii::app()->user->subscriptions,22)){
								echo '<li><a href="'.Yii::app()->createUrl("media/electronicprogram").'">Proof of Flight(Program)</a></li>';
								echo '<li><a href="'.Yii::app()->createUrl("ranking/stationspends").'">Agency Spends By Station</a></li>';
								echo '<li><a href="'.Yii::app()->createUrl("ranking/brandspends").'">Agency Spends By Brand</a></li>';
								echo '<li><a href="'.Yii::app()->createUrl("ranking/companyspends").'">Agency Summaries</a></li>';

							}
							if(Yii::app()->user->rpts_only==1){
								echo '<li><a href="'.Yii::app()->createUrl("ranking/stationsummary").'">Station Spends</a></li>';
							}
							?>
						</ul>
					</li>
					<?php }?>
					<?php }?>
					<?php
					if(Yii::app()->user->rpts_only==1){
						echo '<li>
						<a href="#" title="QC Reports"><i class="fa fa-lg fa-fw fa-cog"></i> <span class="menu-item-parent">QC Reports</span></a>
						<ul>';
						echo '<li><a href="'.Yii::app()->createUrl("qc/index").'">Home</a></li>';
						echo '<li><a href="'.Yii::app()->createUrl("qc/compliance").'">Compliance Dashboards</a></li>';
						echo '<li><a href="'.Yii::app()->createUrl("qc/companybrands").'">Company Brands</a></li>';
						echo '<li><a href="'.Yii::app()->createUrl("qc/stationlog").'">Station Log</a></li>';
						echo '<li><a href="'.Yii::app()->createUrl("qc/pof").'">POF</a></li>';
						echo '<li><a href="'.Yii::app()->createUrl("qc/mediahouse").'">Media House Activity</a></li>';
						echo '<li><a href="'.Yii::app()->createUrl("qc/analyticspof").'">Analytics POF</a></li>';
						echo '<li><a href="'.Yii::app()->createUrl("qc/competitorpof").'">Competitor POF</a></li>';
						echo '<li><a href="'.Yii::app()->createUrl("qc/audience").'">POF & Audience</a></li>';
						echo '<li><a href="'.Yii::app()->createUrl("qc/agencyreport").'">Agency Report</a></li>';
						echo '<li><a href="'.Yii::app()->createUrl("qc/companymediareport").'">Company Report - Media</a></li>';
						echo '</ul></li>';
					}
					?>



					<?php if(Yii::app()->user->usertype=='adflite'){ ?>
					<li>
						<a href="#" title="Logs"><i class="fa fa-lg fa-fw fa-calendar-o"></i> <span class="menu-item-parent">Logs</span></a>
						
						<ul>
							<li><a href="<?=Yii::app()->createUrl("misc/reconciliationlog");?>" title="reconciliation log"><i class="fa fa-lg fa-fw fa-calendar-o"></i> <span class="menu-item-parent">Reconciliation Log</span></a></li>
							<?php if($subscriptions=AnvilSubscriptions::IfSubscribed(Yii::app()->user->adflite_reports,2)){ ?>
							<li><a href="<?=Yii::app()->createUrl("misc/mylog");?>" title="station log"><i class="fa fa-lg fa-fw fa-calendar-o"></i> <span class="menu-item-parent">My Station Log</span></a></li>
							<?php } ?>
							<?php if($subscriptions=AnvilSubscriptions::IfSubscribed(Yii::app()->user->adflite_reports,3)){ ?>
							<li><a href="<?=Yii::app()->createUrl("misc/competitorlog");?>" title="reconciliation log"><i class="fa fa-lg fa-fw fa-calendar-o"></i> <span class="menu-item-parent">Competitor Log</span></a></li>
							<?php } ?>
						</ul>
					</li>
					<?php }?>
					<!-- <li>
						<a href="<?=Yii::app()->createUrl("planning/index");?>" title="Planning Tools"><i class="fa fa-lg fa-fw fa-pie-chart"></i> <span class="menu-item-parent">Planning Tools</span></a>
					</li> -->
					<li>
						<a href="<?=Yii::app()->createUrl("account/index");?>" title="My Account"><i class="fa fa-lg fa-fw fa-user"></i> <span class="menu-item-parent">My Account</span></a>
					</li>
				</ul>
			</nav>
			<span class="minifyme" id="slideopen"><i class="fa fa-arrow-circle-left hit" style="margin-top:4px;"></i></span>
		
	</aside>
	<div id="main" role="main">
		<div id="ribbon">
			<?php if(isset($this->breadcrumbs)):?>
				<?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
					'links'=>$this->breadcrumbs,
				)); ?><!-- breadcrumbs -->
			<?php endif?>
		</div>
		<?php
		$this->widget('bootstrap.widgets.TbAlert', array(
		    'fade'=>true,
		    'closeText'=>'&times;',
		    'alerts'=>array(
		        'success'=>array('block'=>false, 'fade'=>true, 'closeText'=>'&times;'),
		        'info'=>array('block'=>false, 'fade'=>true, 'closeText'=>'&times;'), 
		        'warning'=>array('block'=>false, 'fade'=>true, 'closeText'=>'&times;'),
		        'error'=>array('block'=>false, 'fade'=>true, 'closeText'=>'&times;'),
		        'danger'=>array('block'=>false, 'fade'=>true, 'closeText'=>'&times;')
		    )
		)); 
		?>
		<?php echo $content; ?>
	</div>

	

<a href="#" class="back-to-top">Back to Top</a>
<div id="bottom"></div>
</body>

<!-- Tracking - Google & GoSquared -->

<script>
  // (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  // (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  // m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  // })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  // ga('create', 'UA-63368301-1', 'auto');
  // ga('send', 'pageview');

</script>

<script>
  // !function(g,s,q,r,d){r=g[r]=g[r]||function(){(r.q=r.q||[]).push(
  // arguments)};d=s.createElement(q);q=s.getElementsByTagName(q)[0];
  // d.src='//d1l6p2sc9645hc.cloudfront.net/tracker.js';q.parentNode.
  // insertBefore(d,q)}(window,document,'script','_gs');

  // _gs('GSN-097805-I');
</script>
</html>
<style type="text/css">
.alert {
    margin-bottom: 0px !important;
    }
#content{
	min-height: 900px;
}
.back-to-top {
    position: fixed;
    bottom: 2em;
    right: 0px;
    text-decoration: none;
    color: #000000;
    background-color: rgba(135, 135, 135, 0.50);
    font-size: 12px;
    padding: 1em;
    display: none;
    text-decoration: none;
}

.back-to-top:hover {    
    background-color: rgba(135, 135, 135, 0.50);
    text-decoration: none;
    color: #000000;
}
.nav .open > a, .nav .open > a:hover, .nav .open > a:focus {
    background-color: transparent !important;
    border-color: #3276B1;
}

</style>
<script type="text/javascript">
function checkLoad()
{
   if(document.getElementById("bottom"))
   {
	document.getElementById("preLoaderDiv").style.visibility = "hidden";
   }
}
function SetMinified(){
    var view = 'minified';
    $.post("../site/minified", {"view": view}, function(results) {
        // $('#slideopen').html(results);
    });
}
$(document).ready(function(){
	$("#slideopen").click(function(){
		SetMinified();
	});
});
</script>
<script>
	jQuery(document).ready(function() {
    var offset = 220;
    var duration = 500;
    jQuery(window).scroll(function() {
        if (jQuery(this).scrollTop() > offset) {
            jQuery('.back-to-top').fadeIn(duration);
        } else {
            jQuery('.back-to-top').fadeOut(duration);
        }
    });
    
    jQuery('.back-to-top').click(function(event) {
        event.preventDefault();
        jQuery('html, body').animate({scrollTop: 0}, duration);
        return false;
    })
});
$( "#submitp" ).click(function() {
document.getElementById("preLoaderDiv").style.visibility = "visible";
});
</script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-125781802-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-125781802-1');
</script>
