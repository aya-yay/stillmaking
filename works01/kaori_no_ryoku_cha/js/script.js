// 画面が読み込まれたらすぐに動かしたい場合の記述
$(window).on('load', function () {
  //spanタグを追加する
  var element = $(".eachTextAnime");
  element.each(function () {
    var text = $(this).text();
    var textbox = "";
    text.split('').forEach(function (t, i) {
      if (t !== " ") {
        if (i < 10) {
          textbox += '<span style="animation-delay:.' + i + 's;">' + t + '</span>';
        } else {
          var n = i / 10;
          textbox += '<span style="animation-delay:' + n + 's;">' + t + '</span>';
        }

      } else {
        textbox += t;
      }
    });
    $(this).html(textbox);
  });

  EachTextAnimeControl();
  /* アニメーション用の関数を呼ぶ*/
});
// ここまで画面が読み込まれたらすぐに動かしたい場合の記述


//果物のサークル
$(function () {
  var item_num = $('div.lineup-item').length;
  var deg = 360.0 / item_num;
  var red = (deg * Math.PI / 180.0);
  var circle_r = $("div.lineup-item").width() * 1.4;
  $('div.lineup-item').each(function (i, elem) {
    var x = Math.cos(red * i) * circle_r + circle_r;
    var y = Math.sin(red * i) * circle_r + circle_r;
    $(elem).css('left', x);
    $(elem).css('top', y);
  });
});


//豊かな香りの中で、ほっとするひと時を
$(function(){
  $(".fadein1").fadeIn(1000);
  $(".fadein2").delay(2000).fadeIn(2000);
  $(".fadeout1").delay(1000).fadeOut(2000);
  $(".fadeout2").fadeOut(6000);
  $(".fadeout3").delay(4000).fadeOut(1000);
  $(".fadein3").delay(4500).fadeIn(2500);
  $(".fadein4").delay(6000).fadeIn(3000);
});

//TOPへ戻る
jQuery(function() {
  var pagetop = $('#page_top');   
  pagetop.hide();
  $(window).scroll(function () {
      if ($(this).scrollTop() > 100) {  //pxスクロールしたら表示
          pagetop.fadeIn();
      } else {
          pagetop.fadeOut();
      }
  });
$('a[href^="#"]').click(function(){
  var time = 500;
  var href= $(this).attr("href");
  var target = $(href == "#" ? 'html' : href);
  var distance = target.offset().top;
  $("html, body").animate({scrollTop:distance}, time, "swing");
  return false;
});
});

// index.ejsタブ機能

$(function () {

  /*
   * Tabs
   */
  $('#work').each(function () {

      // タブの各要素を jQuery オブジェクト化
      var $tabList    = $(this).find('.nav-list'),   // タブのリスト
          $tabAnchors = $tabList.find('a'),          // タブ (リンク)
          $tabPanels  = $(this).find('.tabs-panel'); // パネル

      // タブがクリックされたときの処理
      // 引数としてイベントオブジェクトを受け取る
      $tabList.on('click', 'a', function (event) {

          // リンクのクリックに対するデフォルトの動作をキャンセル
          event.preventDefault();

          // クリックされたタブを jQuery オブジェクト化
          var $this = $(this);

          // もしすでに選択されたタブならなにもせず処理を中止
          if ($this.hasClass('active')) {
              return;
          }

          // すべてのタブの選択状態をいったん解除し、
          // クリックされたタブを選択状態に
          $tabAnchors.removeClass('active');
          $this.addClass('active');

          // すべてのパネルをいったん非表示にし、
          // クリックされたタブに対応するパネルを表示
          $tabPanels.hide();
          $($this.attr('href')).show();

      });

      // 最初のタブを選択状態に
      $tabAnchors.eq(0).trigger('click');

  });

});

//スクロールすると要素が出現
$(window).scroll(function () {
  var scrollAnimationElm = document.querySelectorAll('.scroll_up');
  var scrollAnimationFunc = function () {
    for (var i = 0; i < scrollAnimationElm.length; i++) {
      var triggerMargin = 1;
      if (window.innerHeight > scrollAnimationElm[i].getBoundingClientRect().top + triggerMargin) {
        scrollAnimationElm[i].classList.add('on');
      }
    }
  }
  window.addEventListener('load', scrollAnimationFunc);
  window.addEventListener('scroll', scrollAnimationFunc);
});

