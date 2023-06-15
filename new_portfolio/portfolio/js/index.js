document.getElementById("schedule_view").style.display = "none";

function clickBtn1() {
  const div1 = document.getElementById("schedule_view");

  if (div1.style.display == "block") {
    // noneで非表示
    div1.style.display = "none";
  } else {
    // blockで表示
    div1.style.display = "block";
  }
}

/*ページトップ*/
// IE、Safari対応
// smoothscroll.js読み込み
// https://github.com/iamdustan/smoothscroll

// セレクタ名（.pagetop）に一致する要素を取得
const pagetop_btn = document.querySelector(".pagetop");

// .pagetopをクリックしたら
pagetop_btn.addEventListener("click", scroll_top);

// ページ上部へスムーズに移動
function scroll_top() {
  window.scroll({ top: 0, behavior: "smooth" });
}

// スクロールされたら表示
window.addEventListener("scroll", scroll_event);
function scroll_event() {
  if (window.pageYOffset > 100) {
    pagetop_btn.style.opacity = "1";
  } else if (window.pageYOffset < 100) {
    pagetop_btn.style.opacity = "0";
  }
}


/*マップ*/

function initMap() {
  const uluru = { lat: 35.7136547, lng: 139.7076591 };/*ここで緯度・経度を設定している*/
  const map = new google.maps.Map(document.getElementById("map"), {
    zoom: 15,/*最初に表示されるときのマップの大きさ*/
    center: uluru,
  });
  // The marker, positioned at Uluru
  const marker = new google.maps.Marker({
    position: uluru,
    map: map,
  });
}

window.initMap = initMap;

/*ここまで*/