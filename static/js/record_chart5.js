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


let in_exChartData = {
    labels: ['2023年10月','2023年11月','2023年12月','2024年1月','2024年2月',],
    datasets: [
        {
            type: 'line',
            display: false,
            data: ['200','-300','300','400','100'],
            backgroundColor : "rgba(75, 192, 192, 0.2)",
            borderColor : "rgba(75, 192, 192, 0.8)",
            pointBackgroundColor : "rgba(75, 192, 192, 0.8)",
            pointRadius: 1.5,
            // pointHitRadius: 0,
            // stack: 'line',
            borderWidth: 1.5,
            // fill: true,
            // stepped: "before",
            // xAxisID: "x-in_ex-line",
            // yAxisID: "y-in_ex-line",
        },
        {
            type: 'bar',
            label: '収支合計',
            data: ['200','-300','300','400','100'],
            backgroundColor : "rgba(75, 192, 192, 0.2)",
            borderColor : "rgba(75, 192, 192, 1.0)",
            hoverBackgroundColor : "rgba(75, 192, 192, 0.5)",
            // hoverBorderColor : "rgba(75, 192, 192, 1.0)",
            borderWidth: 1,
            // order: 0,
            // stack: 'Stack 0',
            barPercentage: 1.0,
            barThickness: 20,
            // skipNull: true,
            // xAxisID: "x-in_ex-line",
            // yAxisID: "y-in_ex-line",
        },
        {
            label: '収入',
            data: ['500','300','800','600','200'],
            backgroundColor : "rgba(54, 164, 235, 0.2)",
            borderColor : "rgba(54, 164, 235, 0.8)",
            hoverBackgroundColor : "rgba(54, 164, 235, 0.5)",
            hoverBorderColor : "rgba(54, 164, 235, 1.0)",
            borderWidth: 1,
            // order: 1,
            // stack: 'Stack 1',
            barPercentage: 1.0,
            barThickness: 20,
            // xAxisID: "x-in_ex-bar",
            // yAxisID: "y-in_ex-bar",
        },
        {
            label: '支出',
            data: ['-300','-600','-500','-200','-100'],
            backgroundColor : "rgba(254, 97, 132, 0.2)",
            borderColor : "rgba(254, 97, 132, 0.8)",
            hoverBackgroundColor : "rgba(254, 97, 132, 0.5)",
            hoverBorderColor : "rgba(254, 97, 132, 1.0)",
            borderWidth: 1,
            // order: 1,
            // stack: 'Stack 1',
            barPercentage: 1.0,
            barThickness: 20,
            // xAxisID: "x-in_ex-bar",
            // yAxisID: "y-in_ex-bar",
        },
    ],
};

let in_exChartOption = {
    responsive: true,
    indexAxis: 'x',
    scales: {
        x: {
            stacked: true,
            // display: false,
            // title: {
            //     display: true,
            //     text: 'Value' //X軸のﾗﾍﾞﾙ
            // },
        },
        y: {
            // stacked: true,
            type: "linear",
            ticks: { // スケール
                suggestedMin: -5000,
                suggestedMax: 5000,
                min: -1000,
                max: 1000,
                stepSize: 500,
                // beginAtZero: true,
            },
            // title: {
            //     display: true,
            //     text: 'Value' //Y軸のﾗﾍﾞﾙ
            // },
        },
        // "x-in_ex-bar": {
        //     stacked: true,
        //     display: false,
        //     type: "linear",
        //     ticks: { // スケール
        //         suggestedMin: -5000,
        //         suggestedMax: 5000,
        //         min: -1000,
        //         max: 1000,
        //         stepSize: 500,
        //         reverse: true,
        //         beginAtZero: true,
        //     },
        //     // type: "linear",
        // },
        
        // "y-in_ex-line": {
        //     stacked: true,
        //     // stacked: false,
        //     display: false,
        //     // type: "linear",
        //     ticks: { // スケール
        //         suggestedMin: -5000,
        //         suggestedMax: 5000,
        //         min: -1000,
        //         max: 1000,
        //         stepSize: 500,
        //         // beginAtZero: true,
        //     },
        //     legend: {
        //           display: false,
        //     },
        // },
        // "x-in_ex-bar": {
        //     // stacked: true,
        //     display: false,
        //     // type: "linear",
        // },
        // "x-in_ex-line": {
        //     // stacked: false,
        //     display: false,
        // },
    },
    // interaction: {
    //   intersect: false,
    // },
    plugins: {
        legend:{
            display: true,
            labels: {
                filter: function(items) {
                //dataに設定したlabelが〇〇の凡例を非表示にするなら
                // return items.text != '月間収支折れ線';
        
                //data配列の0番目の凡例を非表示
                  return items.datasetIndex != 0;
                },
            }
        }
    //   title: {
    //     display: true,
    //     text: (ctx) => 'Step ' + ctx.chart.data.datasets[2].stepped + ' Interpolation',
    //   }
    }
};


const in_exCtx = document.getElementById('in_exChart').getContext('2d');

let in_exChart = new Chart(in_exCtx, {
    type: 'bar',  // 棒グラフ
    data: in_exChartData,
    options: in_exChartOption
});
