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

$carts = get_user_carts($db, $user['user_id']); // ログインユーザのカート情報の取得

// カート内商品の購入処理
if(purchase_carts($db, $carts) === false){ // 購入処理が失敗した場合
  set_error('商品が購入できませんでした。'); // エラーメッセージの設定
  redirect_to(CART_URL); // /cart.phpへリダイレクト
} 

$total_price = sum_carts($carts); // カート内商品の合計金額

include_once '../view/finish_view.php'; // ../view/finish_view.phpファイル読み込み