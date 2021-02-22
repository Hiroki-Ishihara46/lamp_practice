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

$cart_id = get_post('cart_id'); // カート内の商品の商品idの取得
$amount = get_post('amount'); // カート内の商品の購入個数の取得

// カート内商品の購入個数変更操作
if(update_cart_amount($db, $cart_id, $amount)){ // 購入個数の更新
  set_message('購入数を更新しました。'); // 購入数変更メッセージの設定
} else { // 購入個数の更新に失敗した場合
  set_error('購入数の更新に失敗しました。'); // エラーメッセージの設定
}

// /cart.phpへリダイレクト
redirect_to(CART_URL);