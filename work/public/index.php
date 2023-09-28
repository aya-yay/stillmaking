<?php

//テーブルの設定が出来たので、phpからPDOを使ってアクセスするための記述↓

//設定ファイルを切り出し
//session start()→config.phpへ
//define群→config.phpへ
//相対パスよりは絶対パスなので現在のファイルが存在するディレクトリの絶対パスを示す__DIR__という特殊なキーワードを使う。'/'で始めるのを忘れない。
require_once(__DIR__ . '/../app/config.php');
// require_once(__DIR__ . '/../config.php');

//Token::create();は、トークンの作成や検証をpost送信するときに必要なものなので、ここには書かずTodoクラスのコンストラクタに入れる

//クラスを使う時に名前空間を使う。
//下記のように書いておけば、指定したクラスが出てきたらMyApp\をつけて呼び出してくれる
use MyApp\Database;
use MyApp\Todo;
use MyApp\Utils;

//※Tokenクラスはこのファイルで使用されていないので、use MyApp\Token;とはしない。また、Tokenクラスを使っているTodo.phpは、先頭でMyAppの名前空間が定義されているので、useで書かない。


// PDOのインスタンスを作るにあたり、エラーになったら例外を投げるようにtry-catchを設定
//内容はfunctions.phpへ
$pdo = Database::getInstance();

//postで送信されたデータの処理と、todoを表示するために配列を取得する処理については、todoクラスのメソッドにまとめたい↓
//最終的にはpdoを使って、Todo()クラスのインスタンスを作った後に、
$todo = new Todo($pdo);
//postで送信されたデータを処理するメソッドはprocessPostにして、switchあたりの処理を置き換える。
$todo->processPost();
//$todoを表示する為に配列を取得するメソッドはgetAll()にして、$todos = getTodos($pdo)あたりの処理を置き換える
$todos = $todo->getAll();
//function群はfunctions.phpへ

//php mailerの利用================================= 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './vendor/autoload.php';
require './vendor/phpmailer/phpmailer/language/phpmailer.lang-ja.php';

$dotenv = Dotenv\Dotenv::createImmutable('./');
$dotenv->load();


$mail = new PHPMailer(true);

if (isset($_POST['btn_submit'])) {
    try {
        //Gmail 認証情報
        $host = 'smtp.gmail.com';
        $username = $_ENV['GMAILUSER'];
        $password = $_ENV['GMAILPASSWORD'];

        //差出人
        $from = isset($_POST['email']) ? htmlspecialchars($_POST['email'], ENT_QUOTES, 'utf-8') : '';
        $fromname = isset($_POST['your_name']) ? htmlspecialchars($_POST['your_name'], ENT_QUOTES, 'utf-8') : '';

        //宛先
        $to = $_ENV['GMAILUSER'];
        $toname = "Ayano's portfolios";

        //件名・本文
        // $subject = '件名';
        $body = isset($_POST['message']) ? htmlspecialchars($_POST['message'], ENT_QUOTES, 'utf-8') : '';

        //メール設定
        // $mail->SMTPDebug = 2; //デバッグ用
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = $host;
        $mail->Username = $username;
        $mail->Password = $password;
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->CharSet = "utf-8";
        $mail->Encoding = "base64";
        $mail->setFrom($from, $fromname);
        $mail->addAddress($to, $toname);
        // $mail->Subject = $subject;
        $mail->Body = $body;

        //メール送信
        $mail->send();
        // echo '成功';

    } catch (Exception $e) {
        echo 'メールの送信に失敗しました: ', $mail->ErrorInfo;
    }
}

//バリデーション記載ファイルを読み込み
require './validation.php';

//クリックジャッキングの対策
header('X-FRAME-OPTIONS:DENY');

// if(!empty($_POST)) {
//   echo '<pre>';
//   var_dump($_POST);
//   echo '<pre>'; 
// };

//xxrの対策
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

$pageFlag = 0;
$errors = validation($_POST);


if (!empty($_POST['btn_confirm']) && empty($errors)) {
    $pageFlag = 1;
}
if (!empty($_POST['btn_submit'])) {
    $pageFlag = 2;
}

//=============================

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <meta name="Description" content="" />
    <meta name="Keywords" content="" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <!-- favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="./images/favicon-32x32.png">
    <!-- font awesome -->
    <script src="https://kit.fontawesome.com/d270b74db9.js" crossorigin="anonymous"></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Yomogi&display=swap" rel="stylesheet">
    <!-- リセットcss -->
    <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css">
    <!-- 自分のcss -->
    <link href="./css/styles.css" rel="stylesheet" type="text/css">


</head>
<!-- Google tag (gtag.js) -->
<!-- GoogleAnalytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-6YCGEWHTXJ"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag() { dataLayer.push(arguments); }
    gtag('js', new Date());

    gtag('config', 'G-6YCGEWHTXJ');
</script>
<!-- Google tag (gtag.js) -->


<body>

    <section class="work-section" id="work">

        <header id="header" class="page-header section-header clearfix">
            <div class="page-header-inner inner wrapper">
                <h1 class="home-logo">Ayano's portfolios</h1>

                <div class="clock-container">
                    <canvas>
                        Canvas not supported.
                    </canvas>
                </div>

                <nav class="nav">
                    <ul class="tabs-nav nav-list">
                        <li class="nav-item"><a class="nav-link" href="#home"><i
                                    class="fa fa-solid fa-house-chimney"></i></a></li>
                        <li class="nav-item"><a class="nav-link" href="#about">Me</a></li>
                        <li class="nav-item"><a class="nav-link" href="#works">Works</a></li>
                        <li class="nav-item"><a class="nav-link" href="#request">Request</a></li>
                        <li class="nav-item"><a class="nav-link" href="#contact"><i
                                    class="fa-solid fa-envelope"></i></a></li>
                    </ul>
                </nav>
            </div>
        </header>

        <div class="section-body">
            <section class="tabs-panel" id="home">
                <div class="content">
                    <div class="inner">
                        <!-- pc表示 -->
                        <div class="portfolio-description wrapper">
                            <iframe src="./description/hello.html" name="myFrame"></iframe>
                        </div>
                        <!-- スマホ表示 -->
                        <div class="mybird-sf">
                            <img src="./images/moving_mybird.gif" alt="blue-fronted-amazon-parrot" width="400">
                        </div>
                        <div class="portfolio-description-sf wrapper">
                            <iframe src="./description/hello-sf.html" name="myFrame"></iframe>
                        </div>
                        <h2 class="heading"><i class="fa fa-solid fa-caret-right"></i>Pick up from Works</h2>
                        <main id="content" class="content wrapper">
                            <div class="moving-column wrapper">
                                <div class="slider mypattern">
                                    <div class="pickup-work"><a
                                            href="javaScript:changeIFrame('./description/myportfoliopage.html')"
                                            class="myportfoliopage"><img src="./images/ayano'sportfolios.png"></a></div>
                                    <div class="pickup-work"><a
                                            href="javaScript:changeIFrame('./description/kaorinoryokucha.html')"
                                            class="kaorinoryokucha"><img src="./images/kaorinoryokucha_caption.png"></a>
                                    </div>
                                    <div class="pickup-work"><a
                                            href="javaScript:changeIFrame('./description/toritomo.html')"
                                            class="toritomo"><img src="./images/toritomo.png"></a></div>
                                    <div class="pickup-work"><a
                                            href="javaScript:changeIFrame('./description/myportfoliopage.html')"
                                            class="myportfoliopage"><img src="./images/ayano'sportfolios.png"></a></div>
                                    <div class="pickup-work"><a
                                            href="javaScript:changeIFrame('./description/kaorinoryokucha.html')"
                                            class="kaorinoryokucha"><img src="./images/kaorinoryokucha_caption.png"></a>
                                    </div>
                                    <div class="pickup-work"><a
                                            href="javaScript:changeIFrame('./description/toritomo.html')"
                                            class="toritomo"><img src="./images/toritomo.png"></a>
                                    </div>
                                    <div class="pickup-work"><a
                                            href="javaScript:changeIFrame('./description/todoapp.html')"
                                            class="toritomo"><img src="./images/todoapp.png"></a>
                                    </div>
                                </div>
                            </div>
                        </main>
                    </div>
                </div>
            </section>

            <section class="tabs-panel" id="about">
                <div class="content">
                    <div class="inner">
                        <div class="section s_04 about">
                            <div class="accordion_one">
                                <div class="accordion_header stay history">
                                    <i class="fa fa-solid fa-clock-rotate-left"></i>History
                                    <div class="i_box">
                                        <i class="one_i"></i>
                                    </div>
                                </div>
                                <div class="accordion_inner stay">
                                    <div class="box_one">

                                        <table class="history-list txt_a_ac">
                                            <tr>
                                                <td class="year">1990</td>
                                                <td>あんまり関西弁を使いこなせない神戸生まれ。大阪育ち、大阪在住。</td>
                                            </tr>
                                            <tr>
                                                <td class="year">2013</td>
                                                <td>関西学院大学文学部 文化歴史学科 美学芸術学専修を卒業。</td>
                                            </tr>
                                            <tr>
                                                <td class="year">2013</td>
                                                <td>人間がどのように出来上がっていくのかに興味を持ち、英語の保育園に勤める。</td>
                                            </tr>
                                            <tr>
                                                <td class="year">2017</td>
                                                <td>親や子の手助けとなる仕事がしたく、社会福祉士の資格を取得。</td>
                                            </tr>
                                            <tr>
                                                <td class="year"></td>
                                                <td>児童養護施設に勤務し子どもたちと笑ったり怒ったり泣いたりして過ごす。</td>

                                            </tr>
                                            <tr>
                                                <td class="year">2020</td>
                                                <td>体力が追いつかず無念の退職。場所を選ばず働ける可能性のあるIT業界に憧れ、テックキャンパーになる。</td>
                                            </tr>
                                            <tr>
                                                <td class="year">2021</td>
                                                <td>未経験採用可能なSES企業に採用していただき、運用・保守やテクニカルサポートを務める。</td>

                                            </tr>
                                            <tr>
                                                <td class="year">2022</td>
                                                <td>念願のアオボウシインコと暮らし始める。</td>

                                            </tr>
                                            <tr>
                                                <td class="year">2023</td>
                                                <td>両親の体の不調に伴い今後を考え退職し、大阪にUターン。</td>
                                            </tr>
                                            <tr>
                                                <td class="year"></td>
                                                <td>ITを通したモノづくりの可能性にPassionを燃やしつつ、職業訓練校（ECサイト運営）に在学中。</td>
                                            </tr>
                                        </table>
                                        <!-- ↑スマホでは非表示↑ -->
                                        <!-- ↓PCでは非表示↓ -->
                                        <table class="history-list txt_a_ac-sf">
                                            <tr>
                                                <td class="year">1990</td>
                                                <td>大阪育ち、大阪在住。</td>
                                            </tr>
                                            <tr>
                                                <td class="year">2013</td>
                                                <td>関西学院大学文学部 文化歴史学科 美学芸術学専修を卒業。人間がどのように出来上がっていくのかに興味を持ち、英語の保育園に勤める。
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="year">2017</td>
                                                <td>親や子の手助けとなる仕事がしたく、社会福祉士の資格を取得。児童養護施設に勤務し子どもたちと笑ったり怒ったり泣いたりして過ごす。</td>
                                            </tr>
                                            <tr>
                                                <td class="year">2020</td>
                                                <td>体力が追いつかず無念の退職。場所を選ばず働ける可能性のあるIT業界に憧れ、テックキャンパーになる。</td>
                                            </tr>
                                            <tr>
                                                <td class="year">2021</td>
                                                <td>未経験採用可能なSES企業に採用していただき、運用・保守やテクニカルサポートを務める。</td>

                                            </tr>
                                            <tr>
                                                <td class="year">2022</td>
                                                <td>念願のアオボウシインコと暮らし始める。</td>

                                            </tr>
                                            <tr>
                                                <td class="year">2023</td>
                                                <td>両親の体の不調に伴い今後を考え退職し、大阪にUターン。ITを通したモノづくりの可能性にPassionを燃やしつつ、職業訓練校（ECサイト運営）に在学中。
                                                </td>
                                            </tr>
                                        </table>
                                        <!-- ↑PCでは非表示↑ -->

                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion_one passions">
                                <div class="accordion_header passion">
                                    <i class="fa fa-solid fa-fire"></i>Passion
                                    <div class="i_box">
                                        <i class="one_i"></i>
                                    </div>
                                </div>
                                <div class="accordion_inner">
                                    <div class="box_one">
                                        <!-- スマホでは非表示 -->
                                        <div class="txt_a_ac">
                                            <p>誰かの助けになるアプリやWebサイトを作りたいという気持ちが先走っているが、プログラミングの経験が乏しい為、</p>
                                            <p>Progateやドットインストール、書籍にてプログラミング言語を学習しつつポートフォリオを作成中。</p>
                                            <p>人と暮らす鳥が幸せになれるようなものを作ってみたい。</p>
                                            <p>　</p>
                                            <p>目標とする生き方は寂しがりやのインコの為にフルリモートで稼ぎつつ、インコの物心を共に満たして生きていくこと。</p>
                                        </div>
                                        <!-- スマホでは非表示 -->
                                        <!-- PCでは非表示 -->
                                        <div class="txt_a_ac-sf">
                                            <p class="underspace">誰かの助けになるアプリやWebサイトを作りたいという気持ちが先走っているが、プログラミングの経験が乏しい為、
                                                Progateやドットインストール、書籍にてプログラミング言語を学習しつつポートフォリオを作成中。
                                                人と暮らす鳥が幸せになれるようなものを作ってみたい。

                                            <p>目標とする生き方は寂しがりやのインコの為にフルリモートで稼ぎつつ、インコの物心を共に満たして生きていくこと。</p>
                                        </div>
                                        <!-- PCでは非表示 -->
                                    </div>
                                </div>

                                <div class="accordion_one skills">
                                    <div class="accordion_header skill">
                                        <i class="fa fa-regular fa-pen-to-square"></i>Skills
                                        <div class="i_box">
                                            <i class="one_i"></i>
                                        </div>
                                    </div>
                                    <div class="accordion_inner">
                                        <div class="box_one">

                                            <!-- スマホでは非表示↓ -->
                                            <div class="skills-container">
                                                <p class="txt_a_ac">まだまだ未熟で恥ずかしいところですが、できることを増やせるように勉強を継続しています。</p>

                                                <table>
                                                    <tr>
                                                        <th class="skills-container-title">成果物を作成できる</th>
                                                        <th class="skills-container-title">集中して勉強中</th>
                                                        <th class="skills-container-title">使えるようになりたい</th>
                                                    </tr>

                                                    <tr>
                                                        <td rowspan="8">HTML&CSS</td>
                                                        <td>JavaScript</td>
                                                        <td>Java</td>
                                                    </tr>

                                                    <tr>
                                                        <td rowspan="7">jQuery</td>

                                                    </tr>

                                                    <tr>
                                                        <td>Ruby</td>
                                                    <tr>
                                                        <td>Python</td>
                                                    </tr>
                                                    <tr>
                                                        <td>PHP</td>
                                                    <tr>
                                                        <td>React</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Vue.js</td>
                                                    </tr>
                                                    <tr>
                                                        <td>※上記から状況に合わせて最適なものを勉強予定</td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <!--↑スマホでは非表示↑-->
                                            <!--↓PCでは非表示↓-->
                                            <div class="skills-container-sf">
                                                <p class="txt_a_ac-sf">できることを増やせるように勉強中<i
                                                        class="fa fa-solid fa-glasses"></i></p>

                                                <table>
                                                    <tr>
                                                        <th class="skills-container-title">使える</th>
                                                        <td>HTML & CSS</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="skills-container-title" rowspan="3">勉強中</th>
                                                        <td>JavaScript & jQuery</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Ruby</td>
                                                    </tr>
                                                    <tr>
                                                        <td>PHP</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="skills-container-title">興味あり</th>
                                                        <td>React/Vue.js/TypeScript/AWS</td>

                                                    </tr>
                                                </table>



                                            </div>
                                            <!--↑PCでは非表示↑-->

                                        </div>
                                    </div>
                                </div>

                                <div class="accordion_one favorites">
                                    <div class="accordion_header favorite">
                                        <i class="fa fa-regular fa-heart"></i>Favorite
                                        <div class="i_box">
                                            <i class="one_i"></i>
                                        </div>
                                    </div>
                                    <div class="accordion_inner">
                                        <div class="box_one">
                                            <!-- スマホでは非表示 -->
                                            <div class="txt_a_ac">
                                                <p>鳥を見たり触れ合ったりする時、最も幸せを感じる。</p>
                                                <p>音楽を聴いたり歌ったりすることが好き。合唱も好き。</p>
                                                <p>海や草花や空など自然を感じることが好き。</p>
                                            </div>
                                            <!-- スマホでは非表示 -->
                                            <!-- PCでは非表示 -->
                                            <div class="txt_a_ac-sf">
                                                <p>鳥を見たり触れ合ったりする時、最も幸せを感じる。
                                                    音楽を聴いたり歌ったりすることが好き。合唱も好き。
                                                    海や草花や空など自然を感じることが好き。</p>
                                            </div>
                                            <!-- PCでは非表示 -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </section>

            <section class="tabs-panel" id="works">

                <div class="content">
                    <!-- ↓スマホでは非表示↓ -->
                    <div class="description-container wrapper">
                        <iframe src="./description/work-hello.html" name="myFrame2" width="1000" height="175"></iframe>
                    </div>
                    <!-- ↑スマホでは非表示↑ -->
                    <div class="inner wrapper">
                        <div class="works wrapper">
                            <!-- ↓スマホでは非表示↓ -->
                            <div class="scrolldown4">
                                <a href="#about"><span><i class="fa fa-solid fa-person-snowboarding"></i></span></a>
                            </div>
                            <!-- ↑スマホでは非表示↑ -->
                            <h2 class="works-category"><i class="fa fa-solid fa-seedling"></i>ウェブサイト<i
                                    class="fa fa-solid fa-seedling"></i></h2>

                            <div class="grid wrapper">
                                <div class="item">
                                    <!-- ↓スマホでは非表示↓ -->
                                    <div class="item-url-pc"><a
                                            href="javaScript:changeIFrame2('./description/myportfoliopage.html')"
                                            class="myportfoliopage"><img src="images/ayano'sportfolios.png"></a></div>
                                    <!-- ↑スマホでは非表示↑ -->
                                    <!-- ↓PCでは非表示↓ -->
                                    <div class="item-url-sf"><a href="./index.ejs" class="myportfoliopage-sf"
                                            target="_blank"><img src="images/ayano'sportfolios.png"></a>
                                    </div>
                                    <!-- ↑PCでは非表示↑ -->
                                    <p class="item-name">Ayano's portfolios</p>
                                    <div class="item-text-sf">
                                        <p>私のポートフォリオ紹介ページ。</p>
                                        <p>制作期間：1か月～随時更新中</p>
                                        <p>使用ツール：Visual Studio Code</p>
                                        <p class="underspace">使用スキル：HTML/CSS/JavaScript(jQuery)</p>
                                    </div>
                                </div>
                                <div class="item">
                                    <!-- ↓スマホでは非表示↓ -->
                                    <div class="item-url-pc"><a
                                            href="javaScript:changeIFrame2('description/kaorinoryokucha.html')"
                                            class="kaorinoryokucha"><img src="images/kaorinoryokucha_caption.png"></a>
                                    </div>
                                    <!-- ↑スマホでは非表示↑ -->
                                    <!-- ↓pcでは非表示↓ -->
                                    <div class="item-url-sf"><a href="./works01/kaori_no_ryoku_cha/index.ejs"
                                            class="kaorinoryokucha-sf" target="_blank"><img
                                                src="images/kaorinoryokucha_caption.png"></a></div>
                                    <!-- ↑pcでは非表示↑ -->
                                    <p class="item-name">香りの緑茶</p>
                                    <div class="item-text-sf">
                                        <p>説明：オリジナル（架空の）LP</p>
                                        <p>制作期間：3週間</p>
                                        <p>使用ツール：Visual Studio Code<br>
                                            Photoshop/Illustrator

                                        </p>
                                        <p class="underspace">使用スキル：HTML/CSS/JavaScript(jQuery)<br>
                                            Photoshop/Illustrator
                                        </p>
                                    </div>
                                </div>
                                <div class="item">
                                    <!-- ↓スマホでは非表示↓ -->
                                    <div class="item-url-pc" width="242" height="242"><a
                                            href="javaScript:changeIFrame2('description/toritomo.html')"
                                            class="toritomo"><img src="images/toritomo.png"></a>
                                    </div>
                                    <!-- ↑スマホでは非表示↑ -->
                                    <!-- ↓pcでは非表示↓ -->
                                    <div class="item-url-sf"><a href="./works02/toritomo/index.ejs" class="toritomo-sf"
                                            target="_blank"><img src="images/toritomo.png"></a>
                                    </div>
                                    <!-- ↑pcでは非表示↑ -->
                                    <p class="item-name">TORITOMO</p>
                                    <div class="item-text-sf">
                                        <p>説明：オリジナル（架空の）LP</p>
                                        <p>制作期間：3日</p>
                                        <p>使用ツール：Visual Studio Code<br>
                                            Photoshop/Illustrator

                                        </p>
                                        <p class="underspace">使用スキル：HTML/CSS<br>
                                            Photoshop/Illustrator
                                        </p>
                                    </div>
                                </div>
                            </div>


                            <h2 class="works-category"><i class="fa fa-solid fa-hippo"></i>バナー・チラシ<i
                                    class="fa fa-solid fa-hippo"></i></h2>
                            <div class="grid wrapper">
                                <div class="item">
                                    <!-- ↓スマホでは非表示↓ -->
                                    <div class="item-url-pc"><a
                                            href="javaScript:changeIFrame2('description/banners-big.html')"
                                            class="banners-big"><img
                                                src="images/banner/summerclearancesale_apparel_caption.png"></a></div>
                                    <!-- ↑スマホでは非表示↑ -->
                                    <!-- ↓PCでは非表示↓ -->
                                    <div class="item-url-sf"><a href="./portfolio_banners_big.html" class="banners-big"
                                            target="_blank"><img
                                                src="images/banner/summerclearancesale_apparel_caption.png"></a></div>
                                    <!-- ↑PCでは非表示↑ -->
                                    <p class="item-name">大きめのバナー広告たち</p>
                                    <div class="item-text-sf">
                                        <p>説明：少し大きめのバナーです</p>
                                        <p>制作期間：3時間程度</p>
                                        <p>使用ツール：Photoshop/Illustrator</p>
                                    </div>
                                </div>

                                <div class="item">
                                    <!-- ↓スマホでは非表示↓ -->
                                    <div class="item-url-pc"><a
                                            href="javaScript:changeIFrame2('description/banners-small.html')"
                                            class="banners-small"><img
                                                src="images/banner/blue_dtp_300_250_caption.jpg"></a></div>
                                    <!-- ↑スマホでは非表示↑ -->
                                    <!-- ↓PCでは非表示↓ -->
                                    <div class="item-url-sf"><a href="./portfolio_banners_small.html"
                                            class="banners-small" target="_blank"><img
                                                src="images/banner/blue_dtp_300_250_caption.jpg"></a></div>
                                    <!-- ↑PCでは非表示↑ -->
                                    <p class="item-name">小さめのバナー広告たち</p>
                                    <div class="item-text-sf">
                                        <p>説明：少し小さめのバナーです</p>
                                        <p>制作期間：30分〜1時間程度</p>
                                        <p>使用ツール：Photoshop/Illustrator</p>
                                    </div>
                                </div>

                                <div class="item">
                                    <a href="#"><img src="images/getting_ready.jpg"></a>
                                    <!-- <p class="item-name"></p> -->
                                </div>
                            </div>


                            <h2 class="works-category"><i class="fa fa-solid fa-carrot"></i>アプリケーション<i
                                    class="fa fa-solid fa-carrot"></i></h2>

                            <div class="grid wrapper">
                                <div class="item">
                                    <!-- ↓スマホでは非表示↓ -->
                                    <div class="item-url-pc"><a
                                            href="javaScript:changeIFrame2('description/todoapp.html')"
                                            class="banners-big"><img src="images/todoapp.png"></a></div>
                                    <!-- ↑スマホでは非表示↑ -->
                                    <!-- ↓PCでは非表示↓ -->
                                    <div class="item-url-sf"><a href="./works03/public/index.ejs" class="banners-big"
                                            target="_blank"><img src="images/todoapp.png"></a></div>
                                    <!-- ↑PCでは非表示↑ -->
                                    <p class="item-name">To Do App</p>
                                    <div class="item-text-sf">
                                        <p>説明：Ayano's portfolios改善の為の参加型・匿名ToDoアプリです。</p>
                                        <p>制作期間：7日</p>
                                        <p>使用ツール・スキル：VisualStudioCode・HTML/CSS/Javascript/Node.js</p>
                                    </div>
                                </div>


                                <div class="item">
                                    <a href="#"><img src="images/getting_ready.jpg"></a>
                                    <!-- <p class="item-name"></p> -->
                                </div>
                                <div class="item">
                                    <a href="#"><img src="images/getting_ready.jpg"></a>
                                    <!-- <p class="item-name"></p> -->
                                </div>
                            </div><!-- wrapper -->
                        </div>
                    </div>
            </section>

            <section class="tabs-panel" id="request">
                <div class="content">
                    <div class="inner">
                        <div class="wrapper">
                            <div class="main-container">
                                <!-- スマホでは非表示↓ -->
                                <div class="request">
                                    <h3>訪問者の皆さまへ<i class="fa fa-solid fa-feather"></i></h3>
                                    <p>ここでは、Ayano's portfoliosをより良くしていくためのご意見箱を設置しています。</p>
                                    <p>「もっとこうだったらいいのにな～」というご意見があれば、</p>
                                    <p> ご遠慮なく以下リストに追加してくださいませ！</p>
                                    <!-- <p style="color: red;">※現在、準備中</p> -->
                                </div>
                                <!-- スマホでは非表示↑ -->
                                <!-- PCでは非表示↓ -->
                                <div class="request-sf">
                                    <h3>訪問者の皆さまへ<i class="fa fa-solid fa-feather"></i></h3>
                                    <p>ここでは、Ayano's portfoliosをより良くしていくためのご意見箱を設置しています。
                                        「もっとこうだったらいいのにな～」というご意見があれば、
                                        ご遠慮なく以下リストに追加してくださいませ！</p>
                                    <!-- <span style="color: red;">※現在、準備中</span> -->
                                </div>
                                <!-- PCでは非表示↑ --> 

                                <!-- formタグを使用する場合はそれぞれのformでtokenをつけていたが、tokenはページ内で同じ値なので、Javascriptで取得する場合はどこかにまとめておけば良いのでmainへ -->
                                <main class="todo-main" data-token="<?= Utils::h($_SESSION['token']); ?>">
                                    <header class="todo-header">
                                    <h1 class="todo-h1">あったらいいなリスト</h1>

                                    <!-- 一括削除機能 -->
                                    <!-- ページ遷移したくないのでform消す -->
                                    <!-- <form action="?action=purge" method="post"> -->
                                    <!-- inputのtokenはカスタムデータ属性にする -->
                                    <span class="purge"><i class="fa-solid fa-trash-can"></i></span>
                                    <!-- 同時に、CSRF対策のためにtokenをセッションに仕込んで送信 -->
                                    <!-- 値を埋め込む時はh関数を使用 -->
                                    <!-- カスタムデータ属性にしたのでinputは消す -->
                                    <!-- <input type="hidden" name="token" value=""> -->
                                    <!-- </form>  -->
                                    </header>


                                    <!-- todoを追加していくためのフォームを作成 -->
                                    <!-- 送信先はindex.phpにする為、actionは空にするところだが、
                                    checkboxのupdateのaction先もindex.phpの為、区別をする為にクエリ文字列を仕込む。
                                    ?値の名前=値とすることで、値送信後にget形式で取得可能になる。
                                    formはpost形式で送信 -->
                                        <!-- データ送信する際に必須のCSRF対策(tokenを使用)も上で実施している -->
                                        <!-- enterキーでtodoを追加できるようにしたい為、formタグは残しておき、他の記述でページ遷移されないように処理する -->
                                        <!-- actionやmethodはfetchで指定するため消す -->
                                    <form class="todo-form">
                                        <!-- タイトルを入力してもらうのでinput要素を配置 -->
                                    <input type="text" name="title" placeholder="ここに入力してください">
                                        <!-- CSRF対策のために、tokenをセッションに仕込んで送信 -->
                                        <!-- 値を埋め込む時はh関数を使用 -->
                                        <!-- tokenはmain要素のカスタムデータ属性が反映されるのでここでは消す -->
                                    <!-- <input type="hidden" name="token" value="< = Utils::h($_SESSION['token']); ?>"> -->
                                        <!-- ※↓formの中にinput type="text"が1つだけの場合、buttonがなくてもenterのみで送信可能 -->
                                    <button type="submit">Add</button>
                                    </form>
                                    <!-- →フォームが送信された時にデータを追加したいのでphpに記述(return $todosの後) -->

                                    <ul class="todoform-ul">
                                    <!-- データの数だけループをすればいいので以下にfor eachを設定 -->
                                        <!-- $todosの各要素を$todoとして次の処理をすると書く -->
                                        <!-- その上で、データの数だけli要素を作成すれば良いのでliを以下に持ってくる -->
                                    <?php foreach ($todos as $todo): ?>
                                                    <!-- todoのidはli要素ごとに振られるので、li要素にカスタムデータ属性でidをつける。 -->
                                                    <li class="todoform-li" data-id="<?= Utils::h($todo->id) ?>">
                                                        <!-- CRUDのうちのUPDATE↓ -->
                                                        <!-- checkboxをチェックする度に、それぞれのレコードのis_doneが切り替わる様にする為にform追加。checkboxを囲む。 -->
                                                        <!-- 送信先(action)はaddと同様index.phpとする。
                                            ただしcheckboxについてはpost後にupdate処理をするのでaddTodoとは区別する必要がある。          
                                            その為、それぞれのactionをクエリ文字列で区別する -->
                                                        <!-- <form action="?action=toggle" method="post"> -->
                                                        <!-- input要素のchecked属性とdoneクラスはtodoのis_doneに応じて付与すればいいので条件演算子を使用する -->
                                                        <!-- is_doneがtrueならcheckedの文字列、そうでなければ空文字とする -->
                                                        <!-- index.phpでfetch()で非同期通信実現の為にdata-idとdata-tokenを設定、input type="hidden"としていたtodoのidとtokenを入れる。 -->
                                                        <!-- さらにformをsubmitするわけではない為formタグも削る -->
                                                        <!-- data-tokenはmain要素に、data-idはli要素に。 -->
                                                        <input type="checkbox" <?= $todo->is_done ? 'checked' : ''; ?>>
                                                        <!-- </form>  -->
                                                        <!-- is_doneがtrueならdoneという文字列を表示する -->
                                                        <!-- <span class="<= $todo->is_done ? 'done' : ''; ?>"> これは削除 -->
                                                        <span>
                                                        <!-- タイトルはtodoのタイトルを以下に埋めこむ ※htmlに値を埋め込むにはhtmlspecialcharsという関数が必要なのでgetTodosの上で定義-->
                                                        <?= Utils::h($todo->title); ?>
                                                        </span>

                                                        <!-- 削除機能 -->
                                                        <!-- ページ遷移したくないのでform消す -->
                                                        <!-- <form action="?action=delete" method="post" class="delete-form"> -->
                                                        <!-- idやtokenはカスタムデータ属性とした方がjavascriptから取得しやすくなるのでそうする -->
                                                        <!-- data-tokenはmain要素に、data-idはli要素に。 -->
                                                        <span class="delete">x</span>
                                                        <!-- 同時に、どのtodoを削除するかを送るので、hiddenでtodoのidも送信する -->
                                                        <!-- カスタムデータ属性にしたのでinputは消す -->
                                                        <!-- <input type="hidden" name="id" value="<= Utils::h($todo->id) ?>"> -->
                                                        <!-- 同時に、CSRF対策のためにtokenをセッションに仕込んで送信 -->
                                                        <!-- 値を埋め込む時はh関数を使用 -->
                                                        <!-- <input type="hidden" name="token" value="<= Utils::h($_SESSION['token']); ?>"> -->
                                                        <!-- </form>  -->
                                                    </li>
                                    <?php endforeach; ?>
                                    </ul>
                                </main>
                            </div>
                        </div>

                    </div>
                </div>
            </section>

            <section class="tabs-panel" id="contact">
                <div class="content">
                    <div class="inner">
                        <div class="section s_04 contact">
                            <div class="accordion_one">
                                <div class="accordion_header stay contact">
                                    <i class="fa fa-solid fa-envelope"></i>Contact
                                    <!-- <span
                                        style="color: red;font-size: small;">※現在、準備中</span> -->

                                    <div class="i_box">
                                        <i class="one_i"></i>
                                    </div>
                                </div>
                                <div class="accordion_inner stay">
                                    <div class="box_one_contact">
                                        <div id="container">
                                            <!-- 確認画面 -->
                                                <?php if ($pageFlag === 1): ?>
                                                        <?php if ($_POST['csrf'] === $_SESSION['csrfToken']): ?>
                                                                <form method="POST" action="" class="contact-form">
                                                                氏名
                                                                <?php echo h($_POST['your_name']); ?>
                                                                <br>
                                                                メールアドレス
                                                                <?php echo h($_POST['email']); ?>
                                                                <br>
                                                                お問い合わせ
                                                                <?php echo h($_POST['message']); ?>
                                                                <br>
                                                                <input type="submit" name="back" value="戻る" class="btn_submit">
                                                                <input type="submit"  name="btn_submit" value="送信する" class="btn_submit">
                                                                <input type="hidden" name="your_name" value="<?php echo h($_POST['your_name']); ?>">
                                                                <input type="hidden" name="email" value="<?php echo h($_POST['email']); ?>">
                                                                <input type="hidden" name="message" value="<?php echo h($_POST['message']); ?>">
                                                                <input type="hidden" name="csrf" value="<?php echo h($_POST['csrf']); ?>">
                                                                </form>
                                                    <?php endif; ?>
                                            <?php endif; ?>
                                            <!-- 完了画面 -->
                                            <?php if ($pageFlag === 2): ?>
                                                    <?php if ($_POST['csrf'] === $_SESSION['csrfToken']): ?>
                                                            送信が完了しました
                                                            <?php unset($_SESSION['csrfToken']); ?>
                                                            <!-- <a href="#">Homeに戻る</a> -->
                                                    <?php endif; ?>
                                            <?php endif; ?>

                                            <!-- 入力画面 -->
                                            <?php if ($pageFlag === 0): ?>
                                                    <!-- csrf対策で合言葉を作る -->
                                                    <?php
                                                    if (!isset($_SESSION['csrfToken'])) {
                                                        $csrfToken = bin2hex(random_bytes(32));
                                                        $_SESSION['csrfToken'] = $csrfToken;
                                                    }
                                                    $token = $_SESSION['csrfToken'];
                                                    ?>

                                                    <?php if (!empty($errors) && !empty($_POST['btn_confirm'])): ?>
                                                            <?php echo '<ul>'; ?>
                                                                <?php
                                                                foreach ($errors as $error) {
                                                                    echo '<li>' . $error . '</li>';
                                                                }
                                                                ?>
                                                            <?php echo '</ul>'; ?>
                                                    <?php endif; ?>
                                                    <form method="POST" action="" class="contact-form">
                                                    氏名
                                                    <input type="text" name="your_name" value="<?php if (!empty($_POST['your_name'])) {
                                                        echo h($_POST['your_name']);
                                                    } ?>">
                                                    <br>
                                                    <br>
                                                    メールアドレス
                                                    <input type="email" name="email" value="<?php if (!empty($_POST['email'])) {
                                                        echo h($_POST['email']);
                                                    } ?>">
                                                    <br>
                                                    <br>
                                                    メッセージ
                                                    <textarea name="message"><?php if (!empty($_POST['message'])) {
                                                        echo h($_POST['message']);
                                                    } ?></textarea>
                                                    <br>
                                                    <br>
                                                    <input type="submit"  name="btn_confirm" value="確認する" class="btn_submit">
                                                    <input type="hidden" name="csrf" value="<?php echo $token; ?>">
                                                    </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </section>
    <footer class="footer">
        <!-- <div class="inner"> -->
        <div>
            <div class="copyright">Copyright&copy;sample All Rights Reserved.</div>
        </div>
    </footer>

    <!--jqueryの読み込み-->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3/dist/jquery.min.js"></script>

    <!-- slick -->
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script type="text/javascript" src="./js/main.js"></script>

</body>

</html>