<form method="post">
	<select name="comp_names">
		<option value="0">Select A Company To Filter</option>
		<?php
		$csql = 'SELECT * from company order by company_name asc';
		if($companies = Company::model()->findAllBySql($csql)){
			foreach ($companies as $cods) {
				echo '<option value="'.$cods->company_id.'">'.$cods->company_name.'</option>';
			}
		}
		?>
	</select>
	<input type="submit" value="Search" />
</form>
<?php
if(isset($_POST['comp_names']) && $_POST['comp_names']!=0){
	$comp_id = $_POST['comp_names'];
	$sql = 'SELECT distinct email, client_users_id, surname, firstname, company FROM client_users inner join company on company.company_id = client_users.co_id inner join industry_company on company.company_id=industry_company.company_id where industry_company.Client=1 and company.company_id ='.$comp_id.' and client_users.email not like "%reelforge%" order by company.company_id asc';
}else{
	$sql = 'SELECT distinct email, client_users_id, surname, firstname, company FROM client_users inner join company on company.company_id = client_users.co_id inner join industry_company on company.company_id=industry_company.company_id where industry_company.Client=1 and client_users.email not like "%reelforge%" order by company.company_id asc limit 20';	
}

if($users = ClientUsers::model()->findAllBySql($sql)){
	foreach ($users as $key) {
		echo '<div class="checkholder"><div class="checks">';
		echo '<input type="checkbox" value="'.$key->client_users_id.'" class="check-checks" ></input>'.$key->email.' - '.$key->company;
		echo '</div></div>';
	}
}else{
	echo 'No Records Found';
}
?>

<style type="text/css">
.checks input{
	padding: 0px 2px 0px 2px;
	margin: -2px 2px 0px 0px;
}
.checks{
	float: left;
	padding: 0px 3px 0px 2px;
	width: 350px;
}
</style>