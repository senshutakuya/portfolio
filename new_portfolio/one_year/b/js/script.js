(function() {
  const apiUrl = 'https://dog.ceo/api/breeds/image/random';

  function displayRandomDogImage() {
    fetch(apiUrl)
      .then(response => response.json())
      .then(data => {
        const viewer = document.querySelector('#viewer');
        viewer.src = data.message;
        viewer.alt = '犬の画像';
      })
      .catch(error => {
        console.error('犬の画像を取得できませんでした:', error);
      });
  }

  setInterval(displayRandomDogImage, 2000); // 2秒ごとに画像を切り替える
})();
