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

$name = get_post('name'); // 商品名の取得
$price = get_post('price'); // 商品価格の取得
$status = get_post('status'); // 商品ステータスの取得
$stock = get_post('stock'); // 商品在庫数の取得

$image = get_file('image'); // アップロードされた画像ファイル情報の取得

// 商品登録処理
if(regist_item($db, $name, $price, $stock, $status, $image)){ // 商品登録処理が成功した場合
  set_message('商品を登録しました。'); // 商品登録処理成功メッセージの設定
}else { // 商品登録処理が失敗した場合
  set_error('商品の登録に失敗しました。'); // エラーメッセージの設定
}

// /admin.phpへリダイレクト
redirect_to(ADMIN_URL);