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

$cart_id = get_post('cart_id'); // カートidの取得

$csrf_token = get_post('csrf_token');

if(is_valid_csrf_token($csrf_token) !== false){
  // カート内の商品削除処理
  if(delete_cart($db, $cart_id)){ // 商品削除処理が成功した場合
    set_message('カートを削除しました。'); // 商品削除処理成功メッセージの設定
  } else { // 商品削除処理が失敗した場合
    set_error('カートの削除に失敗しました。'); // エラーメッセージの設定
  }
}  

// /cart.phpへリダイレクト
redirect_to(CART_URL);