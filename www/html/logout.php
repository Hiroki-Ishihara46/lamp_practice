<?php
require_once '../conf/const.php'; // ../conf/const.phpファイル読み込み
require_once MODEL_PATH . 'functions.php'; // ../model/functions.phpファイル読み込み

// セッション開始
session_start();
$_SESSION = array(); // セッション変数を全て削除
$params = session_get_cookie_params(); // sessionに関連する設定を取得
setcookie(session_name(), '', time() - 42000, // sessionに利用しているクッキーの有効期限を過去に設定することで無効化
  $params["path"], 
  $params["domain"],
  $params["secure"], 
  $params["httponly"]
);
session_destroy(); // セッションidを無効化

redirect_to(LOGIN_URL); // login.phpへリダイレクト

