<?php

//CSRF対策でsessionを使用
//tokenを作成する関数はaddTodo関数の上で定義
session_start();

// データベースにアクセスするための定数を設定↓
define('DSN', 'mysql:host=db;dbname=myapp;charset=utf8mb4'); //DataSorceName, mysqlを使用,hostnameとdatanameと文字コードを設定
define('DB_USER', 'myappuser'); //myappdbにアクセスするためのユーザ
define('DB_PASS', 'myapppass'); //myappdbにアクセスするためのパスワード
define('SITE_URL', 'http://' . $_SERVER['HTTP_HOST']); //原型はdefine('SITE_URL', 'http://localhost:8562');で、phpのサーバ変数で取得に変更

//各phpファイルを読み込む
//自動ロードの仕組みを使う↓
//スタンダードPHPライブラリのautoload_registerを使う
//引数には関数を取るため、今回は無名関数を渡す
//まだ読み込まれていないクラスが使われると、クラス名が無名関数の引数に入ってくる為、引数には$classという変数で受ける。
spl_autoload_register(function ($class) {
  //読み込むファイル名をsprintf()で作れば良い
  //require_once(__DIR__ . '/クラス名.php')と同じように、
  //sprintf(__DIR__ . 'クラス名.php', $class);

  //名前空間を使用すると、引数の＄classにMyApp\がついてしまい、ファイルが見つかならいエラーが発生する※1
  //その為、不要なMyApp\文字列を除去する記載が必要となる※2

  //まず名前空間を変更しても良いように除去対象を変数で宣言する※3
  $prefix = 'MyApp\\'; // 「\\」としているのは\が特殊文字なので\自身を表現するには\\とする必要がある為。

  //次にクラスが$prefix（MyApp\のこと）から始まっているかどうかを調べ、あれば$prefixを除去してクラスを読み込む。
  
  //strpos()ストラポスを使い$classの中に$prefixがあるかどうか、そしてその位置が０番目（先頭）がどうかで判定し、そうであればファイル名でファイルを読み込む

  //その際、$prefixを除去した部分文字列をsprintf()に渡すようにする為に、subst()サブストラ を用いてクラスの6文字目以降の部分文字列を渡す。但し名前空間が変わることもあるので($class, 6)とはせずに、($class,strlen($prefix))とする。

  if (strpos($class, $prefix) === 0) {
    $fileName = sprintf(__DIR__ . '/%s.php', substr($class, strlen($prefix)));


    if (file_exists($fileName)) {
      //spl_autoload_register()を使えばクラスを重複して読み込むことはない為、require_onceでなくrequire。
      require($fileName);
    } else {
      echo '次のファイルが見つかりません： ' . $fileName;
      exit;
    }
  }
});