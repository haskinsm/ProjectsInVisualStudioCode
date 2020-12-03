<?PHP


include ("detail.php"); 

$formfield1 = $_POST['formfield1'];
$formfield2 = $_POST['formfield2'];
$formfield3 = $_POST['formfield3'];

## Might need to rewrite below as $sql 


$q  = "INSERT INTO tablename (";
$q .= "DBfield1, DBField2, DBField3";
$q .= ") VALUES (";
$q .= "'$formfield1', '$formfield2', '$formfield3')";

$result = $db->query($q);

?>
<script language="javascript">	

	document.location.replace("thankYou.htm");

</script>


