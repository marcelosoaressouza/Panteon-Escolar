<?php
$username="panteonescolar";
$password="1842panteonescolar";
$database="panteonescolar_stable";

mysql_connect(localhost,$username,$password);
@mysql_select_db($database) or die("Unable to select database");

$query = "SELECT * FROM cidade WHERE uf = '".$_GET["uf"]."'";

mysql_query($query);

$result=mysql_query($query);

while($row = mysql_fetch_assoc($result))
{
    echo '<option value="'.$row['id_cidade'].'">'.utf8_encode($row['nome']).'</option>';
}

mysql_close();

?>
