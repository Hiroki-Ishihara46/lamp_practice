<?php
require_once '../conf/const.php'; // ../conf/const.phpファイル読み込み
require_once MODEL_PATH . 'functions.php'; // ../model/functions.phpファイル読み込み
require_once MODEL_PATH . 'user.php'; // ../model/user.phpファイル読み込み
require_once MODEL_PATH . 'item.php'; // ../model/item.phpファイル読み込み

header('X-FRAME-OPTIONS: DENY');

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

$items = get_all_items($db); // 登録された全ての商品の情報の取得

$csrf_token = get_csrf_token();

include_once VIEW_PATH . '/admin_view.php'; // ../view/admin_view.phpファイル読み込み
