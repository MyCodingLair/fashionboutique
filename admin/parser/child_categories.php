<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/new/fashionboutique/system_core/init.php';

$parentID = (int)$_POST['parentID'];  //this is from the ajax request, which is POSTED during AJAX
$selected = (int)$_POST['selected'];  //the selected is also from ajax request, but this is for when edit is clicked

$childSql = "SELECT * FROM categories WHERE parent = '$parentID' ORDER BY categoryName ";

$childResult = $dbConnect->query($childSql);

ob_start();  //create a php buffer object, then, output html

?>

<option value=""<?=(($selected == '')?' selected':'');?>></option>
<?php while($categories = mysqli_fetch_assoc($childResult)): ?>
<option value="<?=$categories['id'];?>" <?=(($selected == $categories['id'])?' selected':'');?>> <?=$categories['categoryName'];?> </option>
<?php endwhile; ?>


<?php
echo ob_get_clean();  //echo the buffer and clear the buffer
//when echo, this will pass information back to the ajax request as data:
?>
