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

// 商品削除処理
if(destroy_item($db, $item_id) === true){ // 商品削除処理が成功した場合
  set_message('商品を削除しました。'); // 商品削除処理成功メッセージの設定
} else { // 商品削除処理が失敗した場合
  set_error('商品削除に失敗しました。'); // エラーメッセージの設定
}


// /admin.phpへリダイレクト
redirect_to(ADMIN_URL);