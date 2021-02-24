<?php
require_once '../conf/const.php'; // ../conf/const.phpファイル読み込み
require_once MODEL_PATH . 'functions.php'; // ../model/functions.phpファイル読み込み

header('X-FRAME-OPTIONS: DENY');

// セッション開始
session_start();

// ログイン済みの場合、index.phpへリダイレクト
if(is_logined() === true){
  redirect_to(HOME_URL);
}

$csrf_token = get_csrf_token();

include_once VIEW_PATH . 'signup_view.php'; // ../view/signup_view.phpファイル読み込み



