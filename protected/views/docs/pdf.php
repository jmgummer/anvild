<?php ob_start(); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional //EN">
<html>
<head>
	<style type="text/css">
		body {
		  margin: 0;
		  font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
		  font-size: 13px;
		  line-height: 18px;
		  color: #333333;
		  width:1000px;
		}
		.memorandum{
    padding: 15px 15px;
}
.memorandum h2{
    text-align: center;
    text-transform: uppercase;
}
.memorandum h3, .memorandum h4{
    text-align: center;
}
.memorandum h5{
    font-size: 14px;
    clear: both;
}
.memorandum h5>.salutation{
    float: left;
    font-weight: bold;
}
.memorandum h5>.username{
    float: left;
    margin-left: 10px;
    padding-left: 10px;
    margin-right: 10px;
    text-transform: uppercase;
    width: 60%;
    border-bottom: 1px solid #ccc;
}
.memorandum h5>.idno{
    float: left;
    font-weight: bold;
}
.memorandum h5>.idnumber{
    float: left;
    margin-left: 10px;
    padding-left: 10px;
    text-transform: uppercase;
    width: 20%;
    border-bottom: 1px solid #ccc;
}
.memorandum h5>.pobox{
    float: left;
    font-weight: bold;
}
.memorandum h5>.boxnumber{
    float: left;
    margin-left: 10px;
    padding-left: 10px;
    margin-right: 10px;
    text-transform: uppercase;
    width: 60%;
    border-bottom: 1px solid #ccc;
}
.memorandum h5>.mobile{
    float: left;
    font-weight: bold;
}
.memorandum h5>.monumber{
    float: left;
    margin-left: 10px;
    padding-left: 10px;
    text-transform: uppercase;
    width: 20%;
    border-bottom: 1px solid #ccc;
}
.memorandum h5 .regis{
    padding-left: 10px;
}
.memorandum h5 .regis span{
    text-transform: uppercase;
}
.memorandum h5 .regis span label{
    float: left;
    min-width: 250px;
    font-weight: bold;
    font-family: inherit;
    text-rendering: optimizelegibility;
}
.memorandum h5 .regis .error{
    text-transform: lowercase;
}
.memorandum h5 .regis .help-block{
    margin-bottom: 5px;
}
.memorandum .whereby{
    text-decoration: underline;
}
.memorandum h5>.numbering{
    float: left;
    margin-right: 10px;
}
.terms{
    margin-left: 10px;
    padding-left: 10px;
    margin-right: 10px;
    width: 20%;
    border-bottom: 1px solid #ccc;
}
		table {
		  max-width: 100%;
		  border-collapse: collapse;
		  border-spacing: 1;
		}


		.table {
		  width: 100%;
		  margin-bottom: 18px;

		}
		
		.table th, .table td {
		  padding: 8px;
		  line-height: 18px;
		  text-align: left;
		  vertical-align: top;
		  border-top: 1px solid #ddd;
		}
		.table th {
		  font-weight: bold;
		}
		.table thead th {
		  vertical-align: bottom;
		}
		.table thead:first-child tr th, .table thead:first-child tr td {
		  border-top: 0;
		}
		.table tbody + tbody {
		  border-top: 2px solid #ddd;
		}
		.table-condensed th, .table-condensed td {
		  padding: 4px 5px;
		}

		.forced table, td, th
		{
			
		}

		.lower-border{
			border-bottom: 1px solid #333;
		}

		.table-bordered {
		  border: 1px solid #ddd;
		  border-collapse: separate;
		  *border-collapse: collapsed;
		  -webkit-border-radius: 4px;
		  -moz-border-radius: 4px;
		  border-radius: 4px;
		}
		
		.table-bordered th + th,
		.table-bordered td + td,
		.table-bordered th + td,
		.table-bordered td + th {
		  border-left: 1px solid #ddd;
		}
		.table-bordered thead:first-child tr:first-child th, .table-bordered tbody:first-child tr:first-child th, .table-bordered tbody:first-child tr:first-child td {
		  border-top: 0;
		}
		.table-bordered thead:first-child tr:first-child th:first-child, .table-bordered tbody:first-child tr:first-child td:first-child {
		  -webkit-border-radius: 4px 0 0 0;
		  -moz-border-radius: 4px 0 0 0;
		  border-radius: 4px 0 0 0;
		}
		.table-bordered thead:first-child tr:first-child th:last-child, .table-bordered tbody:first-child tr:first-child td:last-child {
		  -webkit-border-radius: 0 4px 0 0;
		  -moz-border-radius: 0 4px 0 0;
		  border-radius: 0 4px 0 0;
		}
		.table-bordered thead:last-child tr:last-child th:first-child, .table-bordered tbody:last-child tr:last-child td:first-child {
		  -webkit-border-radius: 0 0 0 4px;
		  -moz-border-radius: 0 0 0 4px;
		  border-radius: 0 0 0 4px;
		}
		.table-bordered thead:last-child tr:last-child th:last-child, .table-bordered tbody:last-child tr:last-child td:last-child {
		  -webkit-border-radius: 0 0 4px 0;
		  -moz-border-radius: 0 0 4px 0;
		  border-radius: 0 0 4px 0;
		}
		.table-striped tbody tr:nth-child(odd) td, .table-striped tbody tr:nth-child(odd) th {
		  background-color: #f9f9f9;
		}
		.table tbody tr:hover td, .table tbody tr:hover th {
		  background-color: #f5f5f5;
		}

		.container-fluid
		{
			width:720px;
		}
		.row-fluid {
		  width: 100%;
		  position:relative;
		  *zoom: 1;
		}
		.row-fluid:before, .row-fluid:after {
		  display: block;
		  content: "";
		}
		.row-fluid:after {
		  position:relative;
		  clear: both;
		}

		.pull-right {
		  margin-left:480px;
		}
		.pull-left {
		  position:absolute; left:0px; width:480px;
		}

		.clearfix:after { 
		   content: "."; 
		   visibility: hidden; 
		   display: block; 
		   height: 0; 
		   clear: both;
		}

		hr {
		  margin: 18px 0;
		  border: 0;
		  border-top: 1px solid #eeeeee;
		  border-bottom: 1px solid #ffffff;
		}

		h2.big{
		  font-size: 30px;
		  line-height: 36px;
		  text-transform:uppercase;
		}

		.table-money{
		  text-align: right !important;
		}
		
		.span3 {
		  width: 150px;
		}
		
		.box {
		  page-break-after:always;
		}
		
		#footer {
			position: fixed;
			bottom: 0px;
			left: 0px;
			right: 0px;
			height: auto;
			text-align: right;
		}
		
		.adjust{
			page-break-inside: always;
		}

		
	</style>
</head>
<body>
	<?php echo $data; ?>
	<div id="footer">
		Created using PrimeMed Systems on <?php echo date('d M Y H:i:s'); ?>
	</div>
</body>
</html>
<?php ob_end_flush(); ?>
