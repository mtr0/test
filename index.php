<?php 
	include("db.php");
	$db = new DataBase();
?>
<form action="index.php" method="post">
	<select name="table">
	<?php
		//$db->get_tables();
		foreach($db->tables_list as $table)
			echo "<option>".$table."</option>";
		
	?>
	</select>
	<input type="submit" name="table_submit" value="Выбрать таблицу"/>
</form>
<?php
	if((isset($_POST['table_submit']))&&(!empty($_POST['table']))){
?>
<div>Структура таблицы</div>
<table border="1">
<tr>
	<td>Field name</td>
	<td>Type</td>
	<td>Null</td>
	<td>Key</td>
	<td>Default</td>
	<td>Extra</td>
</tr>
<?php
	//$db->get_fields();
		foreach($db->tables_fields[$_POST['table']] as $field){
			echo "<tr>";
				echo "<td>".$field['Field']."</td>";
				echo "<td>".$field['Type']."</td>";
				echo "<td>".$field['Null']."</td>";
				echo "<td>".$field['Key']."</td>";
				echo "<td>".$field['Default']."</td>";
				echo "<td>".$field['Extra']."</td>";
			echo "</tr>";
			
		}
			
		
?>
</table>
<br/><br/><br/>
<div>Данные</div>
<table border="1">	
<?php
	echo "<tr>";
	foreach($db->tables_fields[$_POST['table']] as $field)
		echo "<td>".$field['Field']."</td>";
	echo "</tr>";
	$data = $db->get_data($_POST['table']);
	

	foreach($data as $row){
		echo "<tr>";
		foreach($db->tables_fields[$_POST['table']] as $field)
			echo "<td>".$row[$field['Field']]."</td>";
		echo "</tr>";
	}
	
?>
</table>
<br/>
<form method="post" action="index.php">
	<input type="submit" name="generate" value="генерировать данные"/>
	<input type="text" name="table" hidden value="<?php echo $_POST['table'] ?>"/>
</form>

<?php
	}
	
	if($_POST['generate'])	
		$db->insert_data($_POST['table']);
?>

