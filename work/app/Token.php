<?php

//開発規模が大きくなった際にクラス名が衝突しないように、作成した全てのクラスに対して名前空間を設定
//今回はMyAppという名前にする(クラスを記載した他のファイルにも記載する)
//クラスを呼び出す時には名前空間を使う
namespace MyApp;

class Token 

{

//tokenを作るための関数の定義
//インスタンスを作成して操作する処理ではないのでpublic staticキーワードを使う
public static function create() {
  if(!isset($_SESSION['token'])) { //セッションにtokenが仕込まれていなかったら、
    $_SESSION['token'] = bin2hex(random_bytes(32)); //セッションのtokenに推測されにくい文字列をtokenとして設定する
                                                    //bin2hexとrandom_bytesを使用
  }
}

//上でtokenを作ったので、tokenをチェックする関数を定義
//インスタンスを作成して操作する処理ではないのでpublic staticキーワードを使う
public static function todo_validate() {
  if (
    empty($_SESSION['token']) || //セッションのtokenが空の時、または
    $_SESSION['token'] !== filter_input(INPUT_POST, 'token') //セッションのtokenと、フォームが送信された時のtokenが一致していなかったら
  ) {
    exit('Invalid post request');//メッセージを出して、処理を終了させる
  }
}

}

