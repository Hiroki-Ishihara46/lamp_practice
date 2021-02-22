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
$password_confirmation = get_post('password_confirmation'); // 入力された確認用パスワードの取得

$db = get_db_connect(); // DB接続

try{
  $result = regist_user($db, $name, $password, $password_confirmation); // ユーザ登録処理
  if( $result=== false){ // ユーザ登録処理に失敗した場合
    set_error('ユーザー登録に失敗しました。'); // エラーメッセージの設定
    redirect_to(SIGNUP_URL); // signup.phpへリダイレクト
  }
}catch(PDOException $e){ // 例外処理
  set_error('ユーザー登録に失敗しました。'); // エラーメッセージの設定
  redirect_to(SIGNUP_URL); // signup.phpへリダイレクト
}

set_message('ユーザー登録が完了しました。'); // ユーザ登録処理成功メッセージの設定
login_as($db, $name, $password); // ログイン処理
redirect_to(HOME_URL); // /index.phpへリダイレクト