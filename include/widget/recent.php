<h3 class="text-center">Popular Items</h3>

<?php

  $tranxSql = $dbConnect->query("SELECT * FROM cart WHERE paid = 1 ORDER BY id DESC LIMIT 5");

  $result = array();

  while($row = mysqli_fetch_assoc($tranxSql)){

    $result[] = $row;

  }
  $row_count = $tranxSql->num_rows;
  $usedID = array();

  for ($i=0; $i <$row_count ; $i++) {
    $json_item = $result[$i]['item'];
    //var_dump($json_item);
    $item = json_decode($json_item, true);
    //var_dump($item);
    foreach ($item as $tempItem) {
      if(!in_array($tempItem['id'], $usedID)){
        $usedID[] = $tempItem['id'];
      }
      //var_dump($usedID);
    }
  }

 ?>

<div id="recent_widget">
  <table class="table table-condensed">
    <?php foreach ($usedID as $tempID):
      $productSql = $dbConnect->query("SELECT id, title FROM product WHERE id = '{$tempID}'");
      $product = mysqli_fetch_assoc($productSql);
    ?>
    <tr>
      <td>
        <?=substr($product['title'], 0, 15);?>
      </td>
      <td>
        <a class="text-primary" onclick="productDetailsFuncModal('<?=$tempID;?>')">View</a>
      </td>
    </tr>


  <?php endforeach;  ?>

  </table>

</div>
