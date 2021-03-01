<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入明細</title>
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <h1>購入明細</h1>
  <div class="container">
    
    <ul class="list-group list-group-flush">
      <li class="list-group-item">注文番号: <?php print($order['order_id']); ?></li>
      <li class="list-group-item">購入日時: <?php print($order['created']); ?></li>
      <li class="list-group-item">合計金額: <?php print(number_format($order['total'])); ?>円</li>  
    </ul>

    <table class="table table-bordered">
    　<thead class="thead-light">
        <tr>
        　<th>商品名</th>
        　<th>購入時価格</th>
          <th>購入数</th>
    　    <th>小計</th>
        </tr>
　    </thead>
      <tbody>
        <?php foreach($order_details as $order_detail){ ?>
    　  <tr>
          <td><?php print(h($order_detail['name'])); ?></td>
　        <td><?php print(number_format($order_detail['price'])); ?>円</td>
          <td><?php print($order_detail['amount']); ?></td>
　        <td><?php print(number_format($order_detail['price'] * $order_detail['amount'])); ?>円</td>
        </tr>
        <?php } ?>
    　</tbody>
    </table>
  </div>
</body>
</html>