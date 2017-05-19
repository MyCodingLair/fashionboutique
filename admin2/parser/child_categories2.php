<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/new/oldpcstuffshop/system_core/init.php';

$parentID = (int)$_POST['parentID'];  //this is from the ajax request, which is POSTED during AJAX
$childSql = "SELECT * FROM childmenu WHERE parentID = '$parentID' ORDER BY subMenuName ";

$childResult = $dbConnect->query($childSql);

ob_start();  //create a php buffer object, then, output html

?>

<option value=""></option>
<?php while($child = mysqli_fetch_assoc($childResult)): ?>
<option value="<?=$child['id'];?>"> <?=$child['subMenuName'];?> </option>
<?php endwhile; ?>


<?php
echo ob_get_clean();  //echo the buffer and clear the buffer
//when echo, this will pass information back to the ajax request as data:
?>
