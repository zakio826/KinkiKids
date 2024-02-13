// ガチャのプールを初期化します
let gachaPool = ["ねるねるねるね", "ラムネ", "うまい棒", "ポッキー", "コアラのマーチ", "チョコレート", "ポテトチップス", "ハイチュウ", "グミ", "じゃがりこ"];

function addToGachaPool() {
  const inputElement = document.getElementById("gachaInput");
  const newItem = inputElement.value;
  gachaPool.push(newItem);
  inputElement.value = "";  // 入力フィールドをクリアする
}

function drawGacha() {
  const result = gachaPool[Math.floor(Math.random() * gachaPool.length)];
  return result;
}

function drawTenGacha() {
  const results = [];
  for (let i = 0; i < 1; i++) {
    const result = drawGacha();
    results.push(result);
  }
  displayResults(results);
}

function displayResults(results) {
  const resultsContainer = document.getElementById("results");
  resultsContainer.innerHTML = "";
  for (let i = 0; i < results.length; i++) {
    const resultElement = document.createElement("p");
    resultElement.textContent = results[i];
    resultsContainer.appendChild(resultElement);
  }
}
