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


$item_id = get_post('item_id'); // 商品idの取得
$csrf_token = get_post('csrf_token');

if(is_valid_csrf_token($csrf_token) !== false){
  // カートへの商品の追加処理
  if(add_cart($db,$user['user_id'], $item_id)){ // 追加処理が成功した場合
    set_message('カートに商品を追加しました。'); // 追加処理成功メッセージの設定
  } else { // 追加処理が失敗した場合
    set_error('カートの更新に失敗しました。'); // エラーメッセージの設定
  }
}

redirect_to(HOME_URL); // /index.phpへリダイレクト