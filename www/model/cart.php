<?php 
require_once MODEL_PATH . 'functions.php'; // ../model/functions.phpファイル読み込み
require_once MODEL_PATH . 'db.php'; // ../model/db.phpファイル読み込み
require_once MODEL_PATH . 'user.php';

// ユーザのカート内の全ての商品の情報をDBから参照するSQL文を生成・引き渡し関数
function get_user_carts($db, $user_id){
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = ?
  ";
  return fetch_all_query($db, $sql, array($user_id));
}

// ユーザのカート内の特定の商品の情報をDBから参照するSQL文を生成・引き渡し関数
function get_user_cart($db, $user_id, $item_id){
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = ?
    AND
      items.item_id = ?
  ";

  return fetch_query($db, $sql, array($user_id, $item_id));

}

// 追加した商品がカート内に存在するか否かによって追加・更新の処理を分岐する関数
function add_cart($db, $user_id, $item_id ) {
  $cart = get_user_cart($db, $user_id, $item_id);
  if($cart === false){ // カート内に追加した商品が存在しない場合
    return insert_cart($db, $user_id, $item_id); // 商品をカートに新規追加する処理へ
  }
  return update_cart_amount($db, $cart['cart_id'], $cart['amount'] + 1); // カート内に既に同じ商品がある場合、購入個数を1つ増やす更新処理へ
}

// カート内に新規追加する商品の情報をDBに追加するSQL文を生成・引き渡し関数
function insert_cart($db, $user_id, $item_id, $amount = 1){
  $sql = "
    INSERT INTO
      carts(
        item_id,
        user_id,
        amount
      )
    VALUES(?, ?, ?)
  ";

  return execute_query($db, $sql, array($item_id, $user_id, $amount));
}

// 変更した商品の購入個数を更新するSQL文を生成・引き渡し関数
function update_cart_amount($db, $cart_id, $amount){
  $sql = "
    UPDATE
      carts
    SET
      amount = ?
    WHERE
      cart_id = ?
    LIMIT 1
  ";
  
  return execute_query($db, $sql, array($amount, $cart_id));
}

// 特定商品をカートから削除するSQL文を生成・引き渡し関数
function delete_cart($db, $cart_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      cart_id = ?
    LIMIT 1
  ";

  return execute_query($db, $sql, array($cart_id));
}

function purchase_carts($db, $carts){
  if(validate_cart_purchase($carts) === false){
    return false;
  }
  $db->beginTransaction();
  foreach($carts as $cart){
    if(update_item_stock(
        $db, 
        $cart['item_id'], 
        $cart['stock'] - $cart['amount']
      ) === false){
      set_error($cart['name'] . 'の購入に失敗しました。');
    }
  }
  delete_user_carts($db, $carts[0]['user_id']);
  insert_order($db, $carts[0]['user_id']);
  $order_id = $db->lastInsertId();
  foreach($carts as $cart){
    insert_order_detail($db, $order_id, $cart['item_id'], $cart['price'], $cart['amount']);
  }
  if(has_error()){
    $db->rollback();
    return false;
  } else {
    $db->commit();
    return true;
  }  

  /*
  //$db->beginTransaction();
  if(delete_user_carts($db, $carts[0]['user_id'])
    && insert_order($db, $carts[0]['user_id'])){
    $order_id = $db->lastInsertId();
    foreach($carts as $cart){
      insert_order_detail($db, $order_id, $cart['item_id'], $cart['price'], $cart['amount']);
    }    
    //$db->commit();
    return true;      
  }
  //$db->rollback();
  return false;
  */
}

function delete_user_carts($db, $user_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      user_id = ?
  ";

  execute_query($db, $sql, array($user_id));
}


function sum_carts($carts){
  $total_price = 0;
  foreach($carts as $cart){
    $total_price += $cart['price'] * $cart['amount'];
  }
  return $total_price;
}

function validate_cart_purchase($carts){
  if(count($carts) === 0){
    set_error('カートに商品が入っていません。');
    return false;
  }
  foreach($carts as $cart){
    if(is_open($cart) === false){
      set_error($cart['name'] . 'は現在購入できません。');
    }
    if($cart['stock'] - $cart['amount'] < 0){
      set_error($cart['name'] . 'は在庫が足りません。購入可能数:' . $cart['stock']);
    }
  }
  if(has_error() === true){
    return false;
  }
  return true;
}

function insert_order($db, $user_id){
  $sql = "
    INSERT INTO
      orders(
        user_id
      )
    VALUES(?)
  ";

  return execute_query($db, $sql, array($user_id));
}

function insert_order_detail($db, $order_id, $item_id, $price, $amount){
  $sql = "
    INSERT INTO
      order_details(
        order_id,
        item_id,
        price,
        amount
      )
    VALUES(?, ?, ?, ?)
  ";

  return execute_query($db, $sql, array($order_id, $item_id, $price, $amount));  
}

function get_user_orders($db, $user, $user_id){
  $sql = '
    SELECT
      orders.order_id,
      orders.created,
      SUM(order_details.price * order_details.amount) AS total
    FROM
      orders
    JOIN
      order_details
    ON
      orders.order_id = order_details.order_id
    GROUP BY
      orders.order_id
    ORDER BY
      order_id DESC       
  ';
  if(is_admin($user) === false){
    $sql = '
      SELECT
        orders.order_id,
        orders.created,
        SUM(order_details.price * order_details.amount) AS total
      FROM
        orders
      JOIN
        order_details
      ON
        orders.order_id = order_details.order_id
      WHERE
        orders.user_id = ?  
      GROUP BY
        orders.order_id
      ORDER BY
        order_id DESC
    ';

    return fetch_all_query($db, $sql, array($user_id));

  }
    
  return fetch_all_query($db, $sql);
}

function get_user_order($db, $order_id){
  $sql = '
    SELECT
      orders.order_id,
      orders.created,
      SUM(order_details.price * order_details.amount) AS total
    FROM
      orders
    JOIN
      order_details
    ON
      orders.order_id = order_details.order_id
    WHERE
      orders.order_id = ?  
    GROUP BY
      orders.order_id      
  ';
  return fetch_query($db, $sql, array($order_id));
}

function get_order_details($db, $order_id){
  $sql = "
  SELECT
    items.item_id,
    items.name,
    order_details.price,
    order_details.amount
  FROM
    items
  JOIN
    order_details
  ON
    items.item_id = order_details.item_id    
  WHERE
    order_details.order_id = ?
";
return fetch_all_query($db, $sql, array($order_id));
}