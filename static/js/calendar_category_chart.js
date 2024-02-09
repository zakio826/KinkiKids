/* 
 * line         線グラフ
 * bar          棒グラフ
 * radar        レーダーチャート
 * pie          円グラフ
 * doughnut     ドーナツチャート
 * polarArea    鶏頭図(値によって半径が異なる円グラフ)
 * bubble       バブルチャート
 * scatter      散布図
 */


// const chart_color = [
//     "255, 0, 0",
//     "0, 255, 0",
//     "0, 0, 255",
//     "255, 99, 132",
//     "54, 162, 235",
//     "255, 206, 86",
//     "75, 192, 192",
// ];

const chart_color1 = [
    "rgba(255, 0, 0, 0.2)",
    "rgba(0, 255, 0, 0.2)",
    "rgba(0, 0, 255, 0.2)",
    "rgba(255, 99, 132, 0.2)",
    "rgba(54, 162, 235, 0.2)",
    "rgba(255, 206, 86, 0.2)",
    "rgba(75, 192, 192, 0.2)",
];

const chart_color2 = [
    "rgba(255, 0, 0, 1)",
    "rgba(0, 255, 0, 1)",
    "rgba(0, 0, 255, 1)",
    "rgba(255, 99, 132, 1)",
    "rgba(54, 162, 235, 1)",
    "rgba(255, 206, 86, 1)",
    "rgba(75, 192, 192, 1)",
];


let category_data = ex_category_data;

let categoryChartData = {
    labels: category_data["カテゴリ名"],
    datasets: [
        {
            label: 'カテゴリ別合計金額',
            data: category_data["合計金額"],
            backgroundColor: chart_color1,
            borderColor: chart_color2,
            borderWidth: 1
        }
    ]
};

let categoryChartOption = {
    scales: {
        y: {
            beginAtZero: true
        }
    }
};


const categoryCtx = document.getElementById('categoryChart').getContext('2d');

let categoryChart = new Chart(categoryCtx, {
    type: 'pie',  // 円グラフ
    data: categoryChartData,
    options: categoryChartOption
});


let income_flag = false;
// window.onload = categoryChartDraw();


// ボタンをクリックしたら、グラフを再描画
document.getElementById('in_exSwitch').onclick = function() {
    // すでにグラフ（インスタンス）が生成されている場合は、グラフを破棄する
    // if (categoryChart) categoryChart.destroy();

    // categoryChart.data.labels.pop();
    // categoryChart.data.datasets.pop();
    if (income_flag) {
        // categoryChart.data.labels = ex_category_data["カテゴリ名"];
        // categoryChart.data.datasets.data = ex_category_data["合計金額"];
        category_data = ex_category_data;
        income_flag = false;
    } else {
        // categoryChart.data.labels = in_category_data["カテゴリ名"];
        // categoryChart.data.datasets.data = in_category_data["合計金額"];
        category_data = in_category_data;
        income_flag = true;
    }

    categoryChart.data = {
        labels: category_data["カテゴリ名"],
        datasets: [
            {
                label: 'カテゴリ別合計金額',
                data: category_data["合計金額"],
                backgroundColor: chart_color1,
                borderColor: chart_color2,
                borderWidth: 1
            }
        ]
    };

    categoryChart.update();
  
    // categoryChartDraw(); // グラフを再描画
}