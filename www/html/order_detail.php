<?php
require_once '../conf/const.php'; // ../conf/const.phpファイル読み込み
require_once MODEL_PATH . 'functions.php'; // ../model/functions.phpファイル読み込み
require_once MODEL_PATH . 'user.php'; // ../model/user.phpファイル読み込み
require_once MODEL_PATH . 'item.php'; // ../model/item.phpファイル読み込み
require_once MODEL_PATH . 'cart.php'; // ../model/cart.phpファイル読み込み

// セッション開始
session_start();

// 非ログインの場合、login.phpへリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect(); // DB接続
$user = get_login_user($db); // ログインユーザ情報の取得

$order_id = get_post('order_id');

$csrf_token = get_post('csrf_token');

if(is_valid_csrf_token($csrf_token) !== false){

    $order = get_user_order($db, $order_id);

    $order_details = get_order_details($db, $order_id); 
} else {
    redirect_to(ORDER_URL);
}

include_once VIEW_PATH . 'order_detail_view.php'; 