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


let chartData = {
    labels: ['2023年10月','2023年11月','2023年12月','2024年1月','2024年2月'],
    datasets: [
        {
            label: '月間収入',
            data: ['500','300','800','600','200'],
            borderColor : "rgba(54,164,235,0.8)",
            backgroundColor : "rgba(54,164,235,0.5)",
        },
        {
            label: '月間支出',
            data: ['-300','-600','-500','-200','-100'],
            borderColor : "rgba(254,97,132,0.8)",
            backgroundColor : "rgba(254,97,132,0.5)",
        },
    ],
};

let chartOption = {
    responsive: true,
};


const ctx = document.getElementById('chart').getContext('2d');

let chart = new Chart(ctx, {
    type: 'bar',  // 棒グラフ
    data: chartData,
    options: chartOption
});
