<?php

class DashboardCharts{
	public static function RenderTrend($radio,$tv,$print,$total,$title,$subtitle){
		/* Obtain Currency */
		$currency =Common::CountryCurrency(Yii::app()->user->country_id);
		$container_name = 'trend';
		$chart_object = array();
		$chart = new Highchart();
		$chart->chart = array('renderTo' => $container_name,'type' => 'line','marginRight' => 130,'marginBottom' => 25);
		$chart->title = array('text' => $title,'x' => - 20);
		$chart->subtitle = array('text' => $subtitle,'x' => - 20);
		$chart->xAxis->categories = array('Start','Week 1','Week 2','Week 3','Week 4');
		$chart->yAxis = array('title' => array('text' => 'Spend ('.$currency.')'),'plotLines' => array(array('value' => 0,'width' => 1,'color' => '#808080')));
		$chart->legend = array('layout' => 'vertical','align' => 'right','verticalAlign' => 'top','x' => - 10,'y' => 100,'borderWidth' => 0);
		$chart->series[] = array('name' => 'Radio','data' => $radio);
		$chart->series[] = array('name' => 'TV','data' => $tv);
		$chart->series[] = array('name' => 'Print','data' => $print);
		$chart->series[] = array('name' => 'Total','data' => $total);
		
		$chart_object['chart'] = array('renderTo' => $container_name,'type' => 'line');
		$chart_object['title'] = array('text' => $title);
		$chart_object['subtitle'] = array('text' => $subtitle);
		$chart_object['xAxis'] = array('categories' => array('Start','Week 1','Week 2','Week 3','Week 4'));
		$chart_object['yAxis'] = array('title' => array('text' => 'Spend ('.$currency.')'));
		$chart_object['series'] = array(
			array('name' => 'Radio','data' => $radio),
			array('name' => 'TV','data' => $tv),
			array('name' => 'Print','data' => $print),
			array('name' => 'Total','data' => $total)
			);

		// echo json_encode($chart_object);
		$chart->tooltip->formatter = new HighchartJsExpr("function() { return '<b>'+ this.series.name +'</b><br/>'+ this.x +': $currency '+ this.y +' ';}");
		$chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');
		$chart->includeExtraScripts(array('export'));
		$chart->printScripts();
		$package = '<div id="'.$container_name.'"></div>';
		$package.= '<script type="text/javascript">';
		$package.= $chart->render("chart1");
        $package.= '</script>';
        echo $package;
	}

	public static function RenderSOVSummary($radio,$tv,$print,$total,$title,$subtitle){
		/* Obtain Currency */
		$currency =Common::CountryCurrency(Yii::app()->user->country_id);
		$container_name = 'sov';
		$chart = new Highchart();
		$chart->chart->renderTo = "sov";
		$chart->chart->plotBackgroundColor = null;
		$chart->chart->plotBorderWidth = null;
		$chart->chart->plotShadow = false;
		$chart->title->text = $title;
		$chart->tooltip->pointFormat = '{series.name}: <b>{this.y:.1f}</b>';
		$chart->subtitle->text = $subtitle;
		$chart->tooltip->formatter = new HighchartJsExpr(
		    "function() {
		    return '<b>'+ this.point.name +'</b>: $currency. '+ this.y +' '; }");

		$chart->plotOptions->pie->allowPointSelect = 1;
		$chart->plotOptions->pie->cursor = "pointer";
		$chart->plotOptions->pie->dataLabels->enabled = true;
		$chart->plotOptions->pie->showInLegend = 1;
		$chart->legend = array('layout' => 'vertical','align' => 'right','verticalAlign' => 'top','x' => - 10,'y' => 100,'borderWidth' => 0);

		$chart->series[] = array(
		    'type' => 'pie',
		    'name' => 'Media Share',
		    'data' => array(
		        array('Radio', $radio),
		        array('TV', $tv),
		        array('Print',$print)
		    )
		);

		$chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');
		$chart->includeExtraScripts(array('export'));
		$chart->printScripts();
		$package = '<div id="'.$container_name.'"></div>';
		$package.= '<script type="text/javascript">';
		$package.= $chart->render("chart1");
        $package.= '</script>';
        echo $package;
	}

	public static function RenderSOVRadio($radio,$title,$subtitle){
		/* Obtain Currency */
		$currency =Common::CountryCurrency(Yii::app()->user->country_id);
		$container_name = 'sovradio';
		$chart = new Highchart();
		$chart->chart->renderTo = "sovradio";
		$chart->chart->plotBackgroundColor = null;
		$chart->chart->plotBorderWidth = null;
		$chart->chart->plotShadow = false;
		$chart->title->text = $title;
		$chart->tooltip->pointFormat = '{series.name}: <b>{this.y:.1f}</b>';
		$chart->subtitle->text = $subtitle;
		$chart->tooltip->formatter = new HighchartJsExpr(
		    "function() {
		    return '<b>'+ this.point.name +'</b>: $currency '+ this.y +' '; }");

		$chart->plotOptions->pie->allowPointSelect = 1;
		$chart->plotOptions->pie->cursor = "pointer";
		$chart->plotOptions->pie->dataLabels->enabled = true;
		$chart->plotOptions->pie->showInLegend = 1;
		$chart->legend = array('layout' => 'vertical','align' => 'right','verticalAlign' => 'top','x' => - 10,'y' => 100,'borderWidth' => 0);

		$chartarray = array();
		$count = 0;
		foreach ($radio as $key) {
			$chartarray[$count] = array($key['station_name'], round($key['station_value']));
			$count++;
		}

		$chart->series[] = array(
		    'type' => 'pie',
		    'name' => 'Share of Voice',
		    'data' => $chartarray
		);

		$chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');
		$chart->includeExtraScripts(array('export'));
		$chart->printScripts();
		$package = '<div id="'.$container_name.'"></div>';
		$package.= '<script type="text/javascript">';
		$package.= $chart->render("chart1");
        $package.= '</script>';
        echo $package;
	}

	public static function RenderSOVTV($tv,$title,$subtitle){
		/* Obtain Currency */
		$currency =Common::CountryCurrency(Yii::app()->user->country_id);
		$container_name = 'sovtv';
		$chart = new Highchart();
		$chart->chart->renderTo = "sovtv";
		$chart->chart->plotBackgroundColor = null;
		$chart->chart->plotBorderWidth = null;
		$chart->chart->plotShadow = false;
		$chart->title->text = $title;
		$chart->tooltip->pointFormat = '{series.name}: <b>{this.y:.1f}</b>';
		$chart->subtitle->text = $subtitle;
		$chart->tooltip->formatter = new HighchartJsExpr(
		    "function() {
		    return '<b>'+ this.point.name +'</b>: $currency '+ this.y +' '; }");

		$chart->plotOptions->pie->allowPointSelect = 1;
		$chart->plotOptions->pie->cursor = "pointer";
		$chart->plotOptions->pie->dataLabels->enabled = true;
		$chart->plotOptions->pie->showInLegend = 1;
		$chart->legend = array('layout' => 'vertical','align' => 'right','verticalAlign' => 'top','x' => - 10,'y' => 100,'borderWidth' => 0);

		$chartarray = array();
		$count = 0;
		foreach ($tv as $key) {
			$chartarray[$count] = array($key['station_name'], round($key['station_value']));
			$count++;
		}

		$chart->series[] = array(
		    'type' => 'pie',
		    'name' => 'Share of Voice',
		    'data' => $chartarray
		);

		$chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');
		$chart->includeExtraScripts(array('export'));
		$chart->printScripts();
		$package = '<div id="'.$container_name.'"></div>';
		$package.= '<script type="text/javascript">';
		$package.= $chart->render("chart1");
        $package.= '</script>';
        echo $package;
	}

	public static function RenderSOVPrint($print,$title,$subtitle){
		/* Obtain Currency */
		$currency =Common::CountryCurrency(Yii::app()->user->country_id);
		$container_name = 'sovprint';
		$chart = new Highchart();
		$chart->chart->renderTo = "sovprint";
		$chart->chart->plotBackgroundColor = null;
		$chart->chart->plotBorderWidth = null;
		$chart->chart->plotShadow = false;
		$chart->title->text = $title;
		$chart->tooltip->pointFormat = '{series.name}: '.$currency.' <b>{this.y:.1f}</b>';
		$chart->subtitle->text = $subtitle;
		$chart->tooltip->formatter = new HighchartJsExpr(
		    "function() {
		    return '<b>'+ this.point.name +'</b>: $currency '+ this.y +' '; }");

		$chart->plotOptions->pie->allowPointSelect = 1;
		$chart->plotOptions->pie->cursor = "pointer";
		$chart->plotOptions->pie->dataLabels->enabled = true;
		$chart->plotOptions->pie->showInLegend = 1;
		$chart->legend = array('layout' => 'vertical','align' => 'right','verticalAlign' => 'top','x' => - 10,'y' => 100,'borderWidth' => 0);

		$chartarray = array();
		$count = 0;
		foreach ($print as $key) {
			$chartarray[$count] = array($key['station_name'], round($key['station_value']));
			$count++;
		}

		$chart->series[] = array(
		    'type' => 'pie',
		    'name' => 'Share of Voice',
		    'data' => $chartarray
		);

		$chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');
		$chart->includeExtraScripts(array('export'));
		$chart->printScripts();
		$package = '<div id="'.$container_name.'"></div>';
		$package.= '<script type="text/javascript">';
		$package.= $chart->render("chart1");
        $package.= '</script>';
        echo $package;
	}

	public static function RenderMediaSpendRadio($print,$title,$subtitle){
		/* Obtain Currency */
		$currency =Common::CountryCurrency(Yii::app()->user->country_id);
		$container_name = 'mediaspendradio';
		$chart = new Highchart();
		$chart->chart->renderTo = "mediaspendradio";
		$chart->chart->plotBackgroundColor = null;
		$chart->chart->plotBorderWidth = null;
		$chart->chart->plotShadow = false;
		$chart->title->text = $title;
		$chart->tooltip->pointFormat = '{series.name}: '.$currency.' <b>{this.y:.1f}</b>';
		$chart->subtitle->text = $subtitle;
		$chart->tooltip->formatter = new HighchartJsExpr(
		    "function() {
		    return '<b>'+ this.point.name +'</b>: $currency '+ this.y +' '; }");

		$chart->plotOptions->pie->allowPointSelect = 1;
		$chart->plotOptions->pie->cursor = "pointer";
		$chart->plotOptions->pie->dataLabels->enabled = true;
		$chart->plotOptions->pie->showInLegend = 1;
		$chart->legend = array('layout' => 'vertical','align' => 'right','verticalAlign' => 'top','x' => - 10,'y' => 100,'borderWidth' => 0);

		$chartarray = array();
		$count = 0;
		foreach ($print as $key) {
			$chartarray[$count] = array($key['brand_name'], round($key['brand_value']));
			$count++;
		}

		$chart->series[] = array(
		    'type' => 'pie',
		    'name' => 'Share of Voice',
		    'data' => $chartarray
		);

		$chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');
		$chart->includeExtraScripts(array('export'));
		$chart->printScripts();
		$package = '<div id="'.$container_name.'"></div>';
		$package.= '<script type="text/javascript">';
		$package.= $chart->render("chart1");
        $package.= '</script>';
        echo $package;
	}

	public static function RenderMediaSpendTV($print,$title,$subtitle){
		/* Obtain Currency */
		$currency =Common::CountryCurrency(Yii::app()->user->country_id);
		$container_name = 'mediaspendtv';
		$chart = new Highchart();
		$chart->chart->renderTo = "mediaspendtv";
		$chart->chart->plotBackgroundColor = null;
		$chart->chart->plotBorderWidth = null;
		$chart->chart->plotShadow = false;
		$chart->title->text = $title;
		$chart->tooltip->pointFormat = '{series.name}: '.$currency.' <b>{this.y:.1f}</b>';
		$chart->subtitle->text = $subtitle;
		$chart->tooltip->formatter = new HighchartJsExpr(
		    "function() {
		    return '<b>'+ this.point.name +'</b>: $currency '+ this.y +' '; }");

		$chart->plotOptions->pie->allowPointSelect = 1;
		$chart->plotOptions->pie->cursor = "pointer";
		$chart->plotOptions->pie->dataLabels->enabled = true;
		$chart->plotOptions->pie->showInLegend = 1;
		$chart->legend = array('layout' => 'vertical','align' => 'right','verticalAlign' => 'top','x' => - 10,'y' => 100,'borderWidth' => 0);

		$chartarray = array();
		$count = 0;
		foreach ($print as $key) {
			$chartarray[$count] = array($key['brand_name'], round($key['brand_value']));
			$count++;
		}

		$chart->series[] = array(
		    'type' => 'pie',
		    'name' => 'Share of Voice',
		    'data' => $chartarray
		);

		$chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');
		$chart->includeExtraScripts(array('export'));
		$chart->printScripts();
		$package = '<div id="'.$container_name.'"></div>';
		$package.= '<script type="text/javascript">';
		$package.= $chart->render("chart1");
        $package.= '</script>';
        echo $package;
	}

	public static function RenderMediaSpendPrint($print,$title,$subtitle){
		/* Obtain Currency */
		$currency =Common::CountryCurrency(Yii::app()->user->country_id);
		$container_name = 'mediaspendprint';
		$chart = new Highchart();
		$chart->chart->renderTo = "mediaspendprint";
		$chart->chart->plotBackgroundColor = null;
		$chart->chart->plotBorderWidth = null;
		$chart->chart->plotShadow = false;
		$chart->title->text = $title;
		$chart->tooltip->pointFormat = '{series.name}: '.$currency.' <b>{this.y:.1f}</b>';
		$chart->subtitle->text = $subtitle;
		$chart->tooltip->formatter = new HighchartJsExpr(
		    "function() {
		    return '<b>'+ this.point.name +'</b>: $currency '+ this.y +' '; }");

		$chart->plotOptions->pie->allowPointSelect = 1;
		$chart->plotOptions->pie->cursor = "pointer";
		$chart->plotOptions->pie->dataLabels->enabled = true;
		$chart->plotOptions->pie->showInLegend = 1;
		$chart->legend = array('layout' => 'vertical','align' => 'right','verticalAlign' => 'top','x' => - 10,'y' => 100,'borderWidth' => 0);

		$chartarray = array();
		$count = 0;
		foreach ($print as $key) {
			$chartarray[$count] = array($key['brand_name'], round($key['brand_value']));
			$count++;
		}

		$chart->series[] = array(
		    'type' => 'pie',
		    'name' => 'Share of Voice',
		    'data' => $chartarray
		);

		$chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');
		$chart->includeExtraScripts(array('export'));
		$chart->printScripts();
		$package = '<div id="'.$container_name.'"></div>';
		$package.= '<script type="text/javascript">';
		$package.= $chart->render("chart1");
        $package.= '</script>';
        echo $package;
	}

	public static function RenderIndustryTrend($companyarray,$title,$subtitle){
		/* Obtain Currency */
		$currency =Common::CountryCurrency(Yii::app()->user->country_id);
		$container_name = 'industrytrend';
		$chart = new Highchart();
		$chart->chart = array('renderTo' => $container_name,'type' => 'line','marginRight' => 130,'marginBottom' => 25);
		$chart->title = array('text' => $title,'x' => - 20);
		$chart->subtitle = array('text' => $subtitle,'x' => - 20);
		$chart->xAxis->categories = array('Start','Week 1','Week 2','Week 3','Week 4');
		$chart->yAxis = array('title' => array('text' => 'Spend ('.$currency.')'),'plotLines' => array(array('value' => 0,'width' => 1,'color' => '#808080')));
		$chart->legend = array('layout' => 'vertical','align' => 'right','verticalAlign' => 'top','x' => - 10,'y' => 100,'borderWidth' => 0);

		foreach ($companyarray as $key) {
			$name = $key['company'];
			$array_date = $key['company_array'];
			$chart->series[] = array('name' => $name,'data' => $array_date);
		}
		$chart->tooltip->formatter = new HighchartJsExpr("function() { return '<b>'+ this.series.name +'</b><br/>'+ this.x +': $currency '+ this.y +' ';}");
		$chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');
		$chart->includeExtraScripts(array('export'));
		$chart->printScripts();
		$package = '<div id="'.$container_name.'"></div>';
		$package.= '<script type="text/javascript">';
		$package.= $chart->render("chart1");
        $package.= '</script>';
        echo $package;
	}

	public static function RenderIndustrySOVSummary($radio,$tv,$print,$total,$title,$subtitle){
		/* Obtain Currency */
		$currency =Common::CountryCurrency(Yii::app()->user->country_id);
		$container_name = 'industrysov';
		$chart = new Highchart();
		$chart->chart->renderTo = "industrysov";
		$chart->chart->plotBackgroundColor = null;
		$chart->chart->plotBorderWidth = null;
		$chart->chart->plotShadow = false;
		$chart->title->text = $title;
		$chart->tooltip->pointFormat = '{series.name}: <b>{this.y:.1f}</b>';
		$chart->subtitle->text = $subtitle;
		$chart->tooltip->formatter = new HighchartJsExpr(
		    "function() {
		    return '<b>'+ this.point.name +'</b>: $currency '+ this.y +' '; }");

		$chart->plotOptions->pie->allowPointSelect = 1;
		$chart->plotOptions->pie->cursor = "pointer";
		$chart->plotOptions->pie->dataLabels->enabled = true;
		$chart->plotOptions->pie->showInLegend = 1;
		$chart->legend = array('layout' => 'vertical','align' => 'right','verticalAlign' => 'top','x' => - 10,'y' => 100,'borderWidth' => 0);

		$chart->series[] = array(
		    'type' => 'pie',
		    'name' => 'Media Share',
		    'data' => array(
		        array('Radio', $radio),
		        array('TV', $tv),
		        array('Print',$print)
		    )
		);

		$chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');
		$chart->includeExtraScripts(array('export'));
		$chart->printScripts();
		$package = '<div id="'.$container_name.'"></div>';
		$package.= '<script type="text/javascript">';
		$package.= $chart->render("chart1");
        $package.= '</script>';
        echo $package;
	}

	public static function RenderIndustryBreakdown($companyarray,$title,$subtitle)
	{
		/* Obtain Currency */
		$currency =Common::CountryCurrency(Yii::app()->user->country_id);
		$container_name = 'industrysummary';
		$chart = new Highchart();
		$chart->chart->renderTo = "industrysummary";
		$chart->chart->type = "column";
		$chart->title->text = $title;
		$chart->subtitle->text = $subtitle;

		$category_array = array();
		foreach ($companyarray as $key) {
			$name = $key['company'];
			$category_array[] = $name;
		}
		$chart->xAxis->categories = $category_array;
		// $chart->xAxis->categories = array('Radio','TV','Print');

		$chart->yAxis->min = 0;
		$chart->yAxis->title->text = "Spend ($currency)";
		$chart->legend->layout = "vertical";
		$chart->legend->backgroundColor = "#FFFFFF";
		$chart->legend->align = "left";
		$chart->legend->verticalAlign = "top";
		$chart->legend->x = 100;
		$chart->legend->y = 70;
		$chart->legend->floating = 1;
		$chart->legend->shadow = 1;

		$chart->tooltip->formatter = new HighchartJsExpr("function() {
		    return '' + this.x +': '+ this.y +' ';}");

		$chart->plotOptions->column->pointPadding = 0.2;
		$chart->plotOptions->column->borderWidth = 0;

		$newtvarray = array();
		$newprintarray = array();
		$newradioarray = array();
		$array_count = 0;

		foreach ($companyarray as $key) {
			$name = $key['company'];
			$radio = $key['radio'];
			$tv = $key['tv'];
			$print = $key['print'];
			$newtvarray[$array_count]=$tv;
			$newprintarray[$array_count]=$print;
			$newradioarray[$array_count]=$radio;
			$array_count++;
		}


		$chart->series[] = array('name' => "Radio",'data' => $newradioarray);
		$chart->series[] = array('name' => "TV",'data' => $newtvarray);
		$chart->series[] = array('name' => "Print",'data' => $newprintarray);

		$chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');
		$chart->includeExtraScripts(array('export'));
		$chart->printScripts();
		$package = '<div id="'.$container_name.'"></div>';
		$package.= '<script type="text/javascript">';
		$package.= $chart->render("chart1");
        $package.= '</script>';
        echo $package;
	}


	
}