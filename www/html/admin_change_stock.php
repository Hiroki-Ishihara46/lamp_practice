<?php
require_once '../conf/const.php'; // ../conf/const.phpファイル読み込み
require_once MODEL_PATH . 'functions.php'; // ../model/functions.phpファイル読み込み
require_once MODEL_PATH . 'user.php'; // ../model/user.phpファイル読み込み
require_once MODEL_PATH . 'item.php'; // ../model/item.phpファイル読み込み

// セッション開始
session_start();

// 非ログインの場合、login.phpへリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

// DB接続
$db = get_db_connect();

// ログインユーザ情報の取得
$user = get_login_user($db);

// ログインユーザが管理者でない場合、login.phpへリダイレクト
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

$item_id = get_post('item_id'); // 商品idの取得
$stock = get_post('stock'); // 商品在庫数の変更操作がされたときの値取得

$csrf_token = get_post('csrf_token');

if(is_valid_csrf_token($csrf_token) !== false){
  // 商品在庫数変更操作
  if(update_item_stock($db, $item_id, $stock)){ // 在庫数の更新
    set_message('在庫数を変更しました。'); // 在庫数変更メッセージの設定
  } else { // 在庫数の更新に失敗した場合
    set_error('在庫数の変更に失敗しました。'); // エラーメッセージの設定
  }
}  

// /admin.phpへリダイレクト
redirect_to(ADMIN_URL);