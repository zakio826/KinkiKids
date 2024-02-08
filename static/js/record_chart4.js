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
            label: '月間収入',
            data: ['500','300','800','600','200'],
            backgroundColor : "rgba(54,164,235,0.2)",
            borderColor : "rgba(54,164,235,0.8)",
            borderWidth: 1,
            xAxisID: "x-in_ex",
        },
        {
            label: '月間支出',
            data: ['-300','-600','-500','-200','-100'],
            backgroundColor : "rgba(254,97,132,0.2)",
            borderColor : "rgba(254,97,132,0.8)",
            borderWidth: 1,
            xAxisID: "x-in_ex",
        },
        {
            type: 'bar',
            label: '月間収支',
            data: ['200','-300','300','400','100'],
            backgroundColor : "rgba(75, 192, 192, 0.3)",
            borderColor : "rgba(75, 192, 192, 0.8)",
            borderWidth: 1,
            xAxisID: "x-in_ex-bar",
            // id: "y-in_ex-bar",
        },
        {
            type: 'line',
            label: '月間収支',
            data: ['200','-300','300','400','100'],
            backgroundColor : "rgba(75, 192, 192, 0.8)",
            borderColor : "rgba(75, 192, 192, 0.8)",
            pointBackgroundColor : "rgba(75, 192, 192, 0.8)",
            fill: false,
            // xAxisID: "x-in_ex-bar",
            // id: "y-in_ex-bar",
            xAxisID: "x-in_ex-line",
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
            //     text: 'Value' //Y軸のﾗﾍﾞﾙ
            // },
        },
        y: {
            // stacked: true,
            type: "linear",
            ticks: { // スケール
                max: 5000,
                min: -5000,
                stepSize: 1000
            },
        },
        
        // xAxis: {
        //     id: "x-in_ex",
        //     display: false,
        //     stacked: true, //グラフを積み重ねるにはこれが必要
        // },
        // "y-income": {
        //     ticks: { // スケール
        //         max: 5000,
        //         min: -5000,
        //         stepSize: 1000
        //     },
        //     // gridLines: {
        //     //     drawOnChartArea: false,
        //     // },
        // },
        // "y-expenses": {
        //     ticks: { // スケール
        //         max: 5000,
        //         min: -5000,
        //         stepSize: 1000
        //     },
        // },
        "x-in_ex": {
            stacked: true,
            display: false,
            // type: "linear",
        },
        "x-in_ex-bar": {
            // stacked: false,
            // stacked: false,
            display: false,
        },
        "x-in_ex-line": {
            // stacked: false,
            // stacked: false,
            display: false,
        },
        // "y-in_ex_line": {
        //     type: "linear",
        //     ticks: { // スケール
        //         max: 5000,
        //         min: -5000,
        //         stepSize: 1000
        //     },
        // },
    },
    // legend:{
    //   display: true,
    //   labels: {
    //     filter: function(items, chartData) {
    //       //dataに設定したlabelが〇〇の凡例を非表示にするなら
    //       return items.text != '月間収支折れ線';
  
    //       //data配列の0番目の凡例を非表示
    //     //   return items.datasetIndex != 3;
    //     },
    //   }
    // }
};


const in_exCtx = document.getElementById('in_exChart').getContext('2d');

let in_exChart = new Chart(in_exCtx, {
    type: 'bar',  // 棒グラフ
    data: in_exChartData,
    options: in_exChartOption
});
