'use strict';

$(function () {
  var $carousel = $('.mypattern');

  // slickの初期化
  $carousel.slick({
      // dots: true,
      // autoplay: true,
      // autoplaySpeed: 3000,
      // centerMode: true,
      // centerPadding: '10%',
      // slidesToShow: 1,

      autoplay: true,
      // autoplaySpeed: 2000,
      autoplaySpeed: 2000,
      speed: 2500,
      dots: true,
      arrows: true,
      // centerMode: true,
      // centerPadding: '20%',
      pauseOnHover: true,
      slidesToShow: 3,
      slidesToScroll: 2,
      cssEase: 'ease'//開始から終了まで一定に変化する


  });

  // スクロールイベントの監視
  $carousel.on('wheel', function (e) {
      e.preventDefault();

      if (!$carousel.hasClass('js-slick-moving')) {
          if (e.originalEvent.deltaY < 0) {
              $(this).slick('slickNext');
          } else {
              $(this).slick('slickPrev');
          }
      }
  })

  // スライド送り中を判定するためにクラスを付与する
  $carousel.on('beforeChange', function () {
      $carousel.addClass('js-slick-moving');
  });

  $carousel.on('afterChange', function () {
      $carousel.removeClass('js-slick-moving');
  });
});

// ----------------------------------------------------------------------

//me.indexのアコーディオン
// .s_04 .accordion_one
$(function () {
  //.accordion_oneの中の.accordion_headerがクリックされたら
  $('.s_04 .accordion_one .accordion_header').click(function () {
      //クリックされた.accordion_oneの中の.accordion_headerに隣接する.accordion_innerが開いたり閉じたりする。
      $(this).next('.accordion_inner').slideToggle();
      $(this).toggleClass("open");
      //クリックされた.accordion_oneの中の.accordion_header以外の.accordion_oneの中の.accordion_headerに隣接する.accordion_oneの中の.accordion_innerを閉じる
      $('.s_04 .accordion_one .accordion_header').not($(this)).next('.accordion_one .accordion_inner').slideUp();
      $('.s_04 .accordion_one .accordion_header').not($(this)).removeClass("open");
      $('.s_04 .accordion_one .accordion_header.stay').not($(this)).toggleClass("open");
  });
});

// ----------------------------------------------------------------------
// index.phpタブ機能

$(function () {

  /*
   * Tabs
   */
  $('#work').each(function () {

      // タブの各要素を jQuery オブジェクト化
      var $tabList = $(this).find('.tabs-nav, home-logo'),   // タブのリスト
          $tabAnchors = $tabList.find('a'),          // タブ (リンク)
          $tabPanels = $(this).find('.tabs-panel'); // パネル

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

//iframe内のhtmlを変更する(home)
function changeIFrame(jumpURL) {
  myFrame.location.href = jumpURL;
}

//iframe内のhtmlを変更する(works)
function changeIFrame2(jumpURL2) {
  myFrame2.location.href = jumpURL2;
}

//スクロールを促すマーク
jQuery(function () {
  var scrolldown = $('.scrolldown4');
  scrolldown.hide();
  $(window).scroll(function () {
      if ($(this).scrollTop() > 1) {  //pxスクロールしたら表示
          scrolldown.fadeIn();
      } else {
          scrolldown.fadeOut();
      }
  });
});


//時計-------------------------------------------
(() => {
  class ClockDrawer {
      constructor(canvas) {
          this.ctx = canvas.getContext('2d');
          this.width = canvas.width;
          this.height = canvas.height;
      }
      draw(angle, func) {
          this.ctx.save();

          this.ctx.translate(this.width / 2, this.height / 2);
          this.ctx.rotate(2 * Math.PI / 360 * angle);
          this.ctx.beginPath();
          func(this.ctx);
          this.ctx.stroke();
          this.ctx.restore();
      }
      clear() {
          this.ctx.clearRect(0, 0, this.width, this.height);
      }
  }
  class Clock {
      constructor(drawer) {
          this.r = 60;
          this.drawer = drawer;
      }
      drawFace() {
          for (let angle = 0; angle < 360; angle += 6) {
              this.drawer.draw(angle, ctx => {
                  ctx.moveTo(0, -this.r);
                  if (angle % 30 === 0) {
                      ctx.lineWidth = 3;
                      ctx.lineTo(0, -this.r + 10);
                      ctx.font = '13px Arial';
                      ctx.textAlign = 'center';
                      ctx.strokeStyle = 'white';
                      ctx.fillStyle = 'white';
                      ctx.fillText(angle / 30 || 12, 0, -this.r + 23);
                  } else {
                      ctx.lineTo(0, -this.r + 5);
                      ctx.strokeStyle = 'white';
                  }
              });
          }
      }
      drawHands() {
          //hour
          this.drawer.draw(this.h * 30 + this.m * 0.5, ctx => {
              ctx.strokeStyle = 'white';
              ctx.lineWidth = 4;
              ctx.moveTo(0, 3);
              ctx.lineTo(0, -this.r + 35);
          });

          //minute
          this.drawer.draw(this.m * 6, ctx => {
              ctx.strokeStyle = 'white';
              ctx.lineWidth = 2;
              ctx.moveTo(0, 3);
              ctx.lineTo(0, -this.r + 25);
          });

          //second
          this.drawer.draw(this.s * 6, ctx => {
              ctx.strokeStyle = 'red';
              ctx.moveTo(0, 6);
              ctx.lineTo(0, -this.r + 20);
          });
      }
      update() {
          const d = new Date();
          this.h = d.getHours();
          this.m = d.getMinutes();
          this.s = d.getSeconds();
      }
      run() {
          this.update();
          this.drawer.clear();
          this.drawFace();
          this.drawHands();

          setTimeout(() => {
              this.run();
          }, 100);
      }
  }
  const canvas = document.querySelector('canvas');
  if (typeof canvas.getContext === 'undefined') {
      return;
  }
  const clock = new Clock(new ClockDrawer(canvas));
  clock.run();
})();
//-------------------------------------------

//Todo-------------------------------------------------
{
const token = document.querySelector('.todo-main').dataset.token;
const input = document.querySelector('[name="title"]');
const ul = document.querySelector('.todoform-ul');

input.focus();

ul.addEventListener('click', e => {
  if (e.target.type === 'checkbox') {
    fetch('?action=toggle', {
      method: 'POST',
      body: new URLSearchParams({
        id: e.target.parentNode.dataset.id,
        token: token,
      }),
    })
    .then(response => {
      if (!response.ok) {
        throw new Error('このタスクは既に削除されているようです。画面を更新します・・・。');
      }

      return response.json();
    })
    .then(json => {
      if (json.is_done !== e.target.checked) {
        alert('このタスクは既にチェックされているようです。画面を更新します・・・。');
        e.target.checked = json.is_done;
      }
    })
    .catch(err => {
      alert(err.message);
      location.reload();
    });
  }

  if (e.target.classList.contains('delete')) {
    if (!confirm('選択したタスクを削除しますか?')) {
      return;
    }
    
    fetch('?action=delete', {
      method: 'POST',
      body: new URLSearchParams({
        id: e.target.parentNode.dataset.id,
        token: token,
      }),
    });

    e.target.parentNode.remove();
  }
});

function addTodo(id, titleValue) {
  const li = document.createElement('li');
  li.dataset.id = id;
  const checkbox = document.createElement('input');
  checkbox.type = 'checkbox';
  const title = document.createElement('span');
  title.textContent = titleValue;
  const deleteSpan = document.createElement('span');
  deleteSpan.textContent = 'x';
  deleteSpan.classList.add('delete');

  li.appendChild(checkbox);
  li.appendChild(title);
  li.appendChild(deleteSpan);

  ul.insertBefore(li, ul.firstChild);
}

document.querySelector('form').addEventListener('submit', e => {
  e.preventDefault();

  const title = input.value;

  fetch('?action=add', {
    method: 'POST',
    body: new URLSearchParams({
      title: title,
      token: token,
    }),
  })
  .then(response => response.json())
  .then(json => {
    addTodo(json.id, title);
  });

  input.value = '';
  input.focus();
});

const purge = document.querySelector('.purge');
purge.addEventListener('click', () => {
  if (!confirm('選択したタスクを全て削除しますか？')) {
    return;
  }
  
  fetch('?action=purge', {
    method: 'POST',
    body: new URLSearchParams({
      token: token,
    }),
  });

  const lis = document.querySelectorAll('li');
  lis.forEach(li => {
    if (li.children[0].checked) {
      li.remove();
    }
  });
});
}

//-------------------------------------------

document.addEventListener('submit',async e=>{
  e.preventDefault();
  const target=e.target;
  const url=target.action??"";
  const method="post";
  const body=new FormData(target);
  const txt=await fetch(url,{method,body}).then(res=>res.text());
  console.log(txt);
});




//============================
// const fetchForm = document.querySelector('.contact-form');
// const submit_btn = document.querySelector('.btn_submit');

// const postFetch = () => {
//     e.preventDefault();
//     let formData = new FormData(e.target);
//     let url_search_params = new URLSearchParams(formData);
//     for (let value of formData.entries()) {
//         console.log(value);
//     }

//     fetch('input.php/#contact', {
//         method: 'POST',
//         body: url_search_params,
//     }).then((response) => {
//         if(!response.ok) {
//             console.log('error!');
//         } 
//         console.log('ok!');
//         return response.json();
//     }).then((data)  => {
//         console.log(data);
//     }).catch((error) => {
//         console.log(error);
//     });
// };

// submit_btn.addEventListener('click', postFetch, false);
