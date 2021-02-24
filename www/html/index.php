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

$db = get_db_connect(); // DB接続
$user = get_login_user($db); // ログインユーザ情報の取得

$items = get_open_items($db); // 商品ステータスが公開の商品の情報取得

$csrf_token = get_csrf_token();

include_once VIEW_PATH . 'index_view.php'; // ../view/index_view.phpファイル読み込み