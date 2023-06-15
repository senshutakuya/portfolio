/**
 * setInterval
 * clearInterval
 * setTimeout
 */

(function(){
  'use strict';

  /// 関数 //////////////////////////////////////////////////
  /**
   * ゲームを開始
   * ＝おじさんの動きとタイマーの開始
   */
  function gameStart() {
    console.log("start");
    elScore.textContent = 0;  // スコアをリセット
    // 500ミリ秒ごとに処理を実施
    clock = setInterval(() => {
      elBoxes.forEach(element => {
        // おじさんから、前回付けた active クラスを削除
        element.classList.remove('active');

        // 30%の確率でおじさんに active クラスを追加
        if (Math.random() < 0.3) {
          element.classList.add('active');
        }
      });
    }, 500);
    elGamePlay.textContent = 'ストップ';

    // 30秒経過したらゲーム終了
    timer = setTimeout(() => {
      gameEnd();
      alert(`スコア：${elScore.textContent}点`);  // できる人は奇麗なダイアログで出す
    }, 1000 * 30);
  }

  /**
   * おじさんクリック時の処理を設定
   * @param {*} e
   */
  function clickEffect(e) {
    elScore.textContent = (Number)(elScore.textContent) + 1;

    const img = document.createElement('img');  // <img>要素を生成
    img.src = 'img/husa.png';  // 生成した要素に img/mole_attacked.png をソースとして設定
    img.style.display = 'block';  // 生成した要素のdisplayをblockに
    e.target.parentNode.appendChild(img); // クリックされた要素の親要素に、生成した要素を追加
    e.target.style.display = 'none';  // クリックされた要素は非表示
    // 叩かれた画像は500ミリ秒後に消去
    setTimeout(() => {
      img.remove(); //削除
    }, 500);
  }

  /**
   * ゲーム終了
   */
  function gameEnd() {
    console.log("stop");
    clearInterval(clock); // おじさんの動きを中断
    clearTimeout(timer);  // プレイ中のゲームを中断
    clock = null;
    timer = null;
    elGamePlay.textContent = 'スタート';
  }


  /// メイン処理 ///////////////////////////////////////////////
  /// HTML要素 ///
  const elScore = document.querySelector('#score');
  const elGamePlay = document.querySelector('#game_play');
  const elBoxes = document.querySelectorAll('.box img');    // .boxの中にある<img> ＝ おじさん を全部取得

  /// 管理用変数 ///
  let timer = null; // ゲームプレイ時間を表すIDを管理
  let clock = null; // ゲームクロックのIDを管理

  // イベント処理の追加
  // おじさん
  elBoxes.forEach(element => {
    element.addEventListener('click', clickEffect);
  });
  // スタート・ストップボタン
  elGamePlay.addEventListener('click', () => {
    if (!clock) {// clock が null のときだけクロック開始
      gameStart();
      
    } else {
      // null じゃないときはクロック停止
      gameEnd();
      alert('中断されました');  // できる人は奇麗なダイアログで出す
    }
  });
  
})();