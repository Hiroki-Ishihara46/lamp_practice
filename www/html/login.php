<?php
require_once '../conf/const.php'; // ../conf/const.phpファイル読み込み
require_once MODEL_PATH . 'functions.php'; // ../model/functions.phpファイル読み込み

// セッション開始
session_start();

// ログイン済みの場合、index.phpへリダイレクト
if(is_logined() === true){
  redirect_to(HOME_URL);
}

include_once VIEW_PATH . 'login_view.php'; // ../view/login_view.phpファイル読み込み