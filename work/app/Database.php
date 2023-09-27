<?php

//開発規模が大きくなった際にクラス名が衝突しないように、作成した全てのクラスに対して名前空間を設定
//今回はMyAppという名前にする(クラスを記載した他のファイルにも記載する)
//クラスを呼び出す時には名前空間を使う
namespace MyApp;
//→但し、pdoのようなPHPが標準で用意しているクラスにもその名前空間をつけて呼び出してしまうようになる為、下に記載したPDOやPDOExceptionクラスは「\PDO」等にしておく必要がある

class Database
{
  //このgetInstance()が呼ばれる度にDBに接続してしまうと、複数の接続ができてしまって無駄なので、このPDOクラスから作られるinstanceは必ず一つになるようにする①

  //まずはこのクラスに一つだけの値となるように、クラス変数を作成②
  //外部から呼び出すわけではないのでprivateとする③
  private static $instance;


  public static function getInstance()
  //PDOのインスタンスを返しているだけなのでpublic staticとしてクラスメソッドにする  
  {
    try {
      //条件分岐の作成。もしこのクラス変数がセットされていなかった時だけPDOのインスタンスを作るようにする④
      //インスタンスはクラス変数にする⑤
      if (!isset(self::$instance)) {
        // tryの中でインスタンスを作成,3つを渡す
        self::$instance = new \PDO(
          DSN,
          DB_USER,
          DB_PASS,
          [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
              //オプションとしてエラーが出た時は例外を投げるよう設定
              //getTodos関数を記載した後、PDOのオプションを追加する
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
              //オブジェクト形式で結果を取得したいのでFETCH_MODEのオプションをFETCHオブジェクトにしておく
            \PDO::ATTR_EMULATE_PREPARES => false,
            //取得したデータの型をSQLで定義した型に合わせて取得する為に、EMULATE_PREPARESをfalseにする（別lessons参照）
          ]
        );
      }



      return self::$instance;

    } catch (\PDOException $e) { //PDOException型の例外が投げられた時、eで例外を受け、メッセージを表示し、終了
      echo $e->getMessage();
      exit;
    }
  }
}