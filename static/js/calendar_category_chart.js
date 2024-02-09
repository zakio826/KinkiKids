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


let pieChartData = {
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

let complexChartOption = {
    scales: {
        y: {
            beginAtZero: true
        }
    }
};


const ctx = document.getElementById('chart').getContext('2d');

let chart = new Chart(ctx, {
    type: 'pie',  // 円グラフ
    data: pieChartData,
    options: complexChartOption
});
