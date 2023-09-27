<?php

//開発規模が大きくなった際にクラス名が衝突しないように、作成した全てのクラスに対して名前空間を設定
//今回はMyAppという名前にする(クラスを記載した他のファイルにも記載する)
//クラスを呼び出す時には名前空間を使う
namespace MyApp;

class Utils 
{
 //HTMLの中に値を埋め込むための定義 関数名は短くhにしておく
public static function h($str)
{
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8'); //文字列を受け取ったら、htmlspecialcharsで処理をして返すという処理とする。h関数は($todo->title)で使用
}
}