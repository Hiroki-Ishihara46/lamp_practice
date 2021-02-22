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
$changes_to = get_post('changes_to'); // 商品ステータス変更操作がされたときの値取得(open or close)

// 商品ステータス変更操作
if($changes_to === 'open'){ // 非公開→公開の場合
  update_item_status($db, $item_id, ITEM_STATUS_OPEN); // 商品ステータスの更新
  set_message('ステータスを変更しました。'); // ステータス変更メッセージの設定
}else if($changes_to === 'close'){ // 公開→非公開の場合
  update_item_status($db, $item_id, ITEM_STATUS_CLOSE); // 商品ステータスの更新
  set_message('ステータスを変更しました。'); // ステータス変更メッセージの設定
}else { // ステータスの値がopenとclose以外の場合
  set_error('不正なリクエストです。'); // エラーメッセージの設定
}

// /admin.phpへリダイレクト
redirect_to(ADMIN_URL);