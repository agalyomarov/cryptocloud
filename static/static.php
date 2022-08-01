<?php
ini_set('log_errors', 'On');
ini_set('error_log', 'php_errors.log');
require_once "../config.php";
$current_page = 1;
$elements = 20;
if (isset($_GET['page'])) {
   $current_page = $_GET['page'];
}
$start = ($elements * $current_page) - $elements;
$query = "SELECT SUM(`sum`) AS `sum` FROM `payments` WHERE `status` = 'paid'";
$query3 = "SELECT COUNT('id') AS `count`  FROM `payments`";
$query2 = "SELECT *  FROM `payments` LIMIT $start , $elements";
$result = $mysqli->query($query);
$result3 = $mysqli->query($query3);
$result2 = $mysqli->query($query2);
$row = $result->fetch_assoc();
$row3 = $result3->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
   <title>Statistika</title>
</head>

<body>
   <div class="container">
      <div class="row">
         <div class="col-12">
            <h3>Переходы по домену : <?= $row3["count"] ?></h3>
            <h3>Обшая сумма : <?= $row["sum"]  ?> $</h3>
            <table class="table mt-4">
               <thead>
                  <tr>
                     <th scope="col">#</th>
                     <th scope="col">user_id</th>
                     <th scope="col">page</th>
                     <th scope="col">url</th>
                     <th scope="col">status</th>
                     <th scope="col">sum</th>
                  </tr>
               </thead>
               <tbody>
                  <?php while ($row2 = $result2->fetch_assoc()) {
                  ?>
                     <tr>
                        <th scope="row"><?= $row2['id'] ?></th>
                        <td><?= $row2['user_id']  ?></td>
                        <td><?= $row2['page']  ?></td>
                        <td><?= $row2['pay_url']  ?></td>
                        <td><?= $row2['status']  ?></td>
                        <td><?= $row2['sum']  ?></td>
                     </tr>
                  <?php
                  }
                  ?>
               </tbody>
            </table>
            <?php
            if ($row3['count'] > 20) :
               $count = $row3['count'];
               $pages = ceil($count / 20);
               var_dump($pages);
            ?>
               <nav aria-label="Page navigation example">
                  <ul class="pagination">
                     <?php
                     for ($i = 1; $i <= $pages; $i++) :
                     ?>
                        <li class="page-item"><a class="page-link <?= $i == $current_page ? 'active' : '' ?>" href="?page=<?= $i ?>"><?= $i ?></a></li>
                     <?php
                     endfor;
                     ?>
                  </ul>
               </nav>
            <?php
            endif;
            ?>
         </div>
      </div>
   </div>
</body>

</html>