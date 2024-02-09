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

const chart_color = [
    "255, 0, 0",
    "0, 255, 0",
    "0, 0, 255",
];


let categoryChartData = {
    labels: ['赤', '青', '黄'],
    datasets: [
        {
            label: '# of Votes',
            data: [12, 19, 3],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)'
            ],
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
