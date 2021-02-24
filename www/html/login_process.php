<?php
require_once '../conf/const.php'; // ../conf/const.phpファイル読み込み
require_once MODEL_PATH . 'functions.php'; // ../model/functions.phpファイル読み込み
require_once MODEL_PATH . 'user.php'; // ../model/user.phpファイル読み込み

// セッション開始
session_start();

// ログイン済みの場合、index.phpへリダイレクト
if(is_logined() === true){
  redirect_to(HOME_URL);
}

$name = get_post('name'); // 入力されたユーザ名の取得
$password = get_post('password'); // 入力されたパスワードの取得

$db = get_db_connect(); // DB接続

$csrf_token = get_post('csrf_token');

if(is_valid_csrf_token($csrf_token) !== false){
  // ログイン処理
  $user = login_as($db, $name, $password); // ログイン処理の結果（true or false）
  if( $user === false){ // ログイン処理に失敗した場合
    set_error('ログインに失敗しました。'); // エラーメッセージの設定
    redirect_to(LOGIN_URL); // login.phpへリダイレクト
  }
} else {
  redirect_to(LOGIN_URL);
}  

set_message('ログインしました。'); // ログイン処理成功メッセージの設定
if ($user['type'] === USER_TYPE_ADMIN){ // ログインユーザが管理者である場合
  redirect_to(ADMIN_URL); // /admin.phpへリダイレクト
}
redirect_to(HOME_URL); // /index.phpへリダイレクト