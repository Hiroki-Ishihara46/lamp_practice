<?php

define('MODEL_PATH', $_SERVER['DOCUMENT_ROOT'] . '/../model/'); // modelディレクトリまでのパス
define('VIEW_PATH', $_SERVER['DOCUMENT_ROOT'] . '/../view/'); // viewディレクトリまでのパス


define('IMAGE_PATH', '/assets/images/'); // imagesディレクトリまでのパス
define('STYLESHEET_PATH', '/assets/css/'); // cssディレクトリまでのパス
define('IMAGE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/assets/images/' ); // アップロードした画像の保存ディレクトリ

// DBの接続情報
define('DB_HOST', 'mysql'); // MySQLのホスト名
define('DB_NAME', 'sample'); // MySQLのDB名
define('DB_USER', 'testuser'); // MySQLのユーザ名
define('DB_PASS', 'password'); // MySQLのパスワード
define('DB_CHARSET', 'utf8'); // MySQLのcharset

define('SIGNUP_URL', '/signup.php'); // signup.phpファイル
define('LOGIN_URL', '/login.php'); // login.phpファイル
define('LOGOUT_URL', '/logout.php'); // logout.phpファイル
define('HOME_URL', '/index.php'); // index.phpファイル
define('CART_URL', '/cart.php'); // cart.phpファイル
define('FINISH_URL', '/finish.php'); // finish.phpファイル
define('ADMIN_URL', '/admin.php'); // admin.phpファイル
define('ORDER_URL', '/order.php');

define('REGEXP_ALPHANUMERIC', '/\A[0-9a-zA-Z]+\z/'); // 半角英数字の正規表現
define('REGEXP_POSITIVE_INTEGER', '/\A([1-9][0-9]*|0)\z/'); // 正の整数の正規表現


define('USER_NAME_LENGTH_MIN', 6); // ユーザ名最小6文字
define('USER_NAME_LENGTH_MAX', 100); // ユーザ名最大100文字
define('USER_PASSWORD_LENGTH_MIN', 6); // パスワード最小6文字
define('USER_PASSWORD_LENGTH_MAX', 100); // パスワード最大100文字

define('USER_TYPE_ADMIN', 1); // ユーザ区別　管理者の場合1
define('USER_TYPE_NORMAL', 2); // ユーザ区別　一般ユーザの場合2

define('ITEM_NAME_LENGTH_MIN', 1); // 商品名最小1文字
define('ITEM_NAME_LENGTH_MAX', 100); // 商品名最大100文字

define('ITEM_STATUS_OPEN', 1); // 商品ステータス　公開の場合1
define('ITEM_STATUS_CLOSE', 0); // 商品ステータス　非公開の場合0

// 商品ステータス情報の連想配列
define('PERMITTED_ITEM_STATUSES', array(
  'open' => 1,
  'close' => 0,
));

// アップロード画像の拡張子情報の連想配列
define('PERMITTED_IMAGE_TYPES', array(
  IMAGETYPE_JPEG => 'jpg', // 画像拡張子jpg
  IMAGETYPE_PNG => 'png', // 画像拡張子png
));