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
            id: "y-income",
        },
        {
            label: '月間支出',
            data: ['-300','-600','-500','-200','-100'],
            backgroundColor : "rgba(254,97,132,0.2)",
            borderColor : "rgba(254,97,132,0.8)",
            borderWidth: 1,
            id: "y-expenses",
        },
        {
            type: 'bar',
            label: '月間収支',
            data: ['200','-300','300','400','100'],
            backgroundColor : "rgba(75, 192, 192, 0.3)",
            borderColor : "rgba(75, 192, 192, 0.8)",
            borderWidth: 1,
            id: "y-in_ex",
        },
        {
            type: 'list',
            label: '月間収支',
            data: ['-300','-600','-500','-200','-100'],
            backgroundColor : "rgba(75, 192, 192, 0.8)",
            pointBackgroundColor : "rgba(75, 192, 192, 0.8)",
            fill: false,
            yAxisID: "y-in_ex_line",
        },
    ],
};

let in_exChartOption = {
    responsive: true,
    indexAxis: 'x',
    scales: {
        x: {
            stacked: true,
        },
        y: {
            stacked: true,
            // ticks: { // スケール
            //     max: 5000,
            //     min: -5000,
            //     stepSize: 1000
            // },
        },
        // x: {
        //     stacked: true,
        // },
        yAxis: [
            {
                id: "y-income",
                type: "linear",
                ticks: {
                    max: 5000,
                    min: -5000,
                    stepSize: 1000,
                },
                gridLines: {
                    drawOnChartArea: false,
                },
                stacked: true,
            },
            {
                id: "y-expenses",
                type: "linear",
                ticks: {
                    max: 5000,
                    min: -5000,
                    stepSize: 1000,
                },
                gridLines: {
                    drawOnChartArea: false,
                },
                stacked: true,
            },
            {
                id: "y-in_ex",
                type: "linear",
                ticks: {
                    max: 5000,
                    min: -5000,
                    stepSize: 1000,
                },
                gridLines: {
                    drawOnChartArea: false,
                },
                stacked: true,
            },

            {
                id: "y-in_ex_line",
                type: "linear",
                ticks: {
                    max: 5000,
                    min: -5000,
                    stepSize: 1000,
                },
                stacked: false,
            },
        ],
    }
};


const in_exCtx = document.getElementById('in_exChart').getContext('2d');

let in_exChart = new Chart(in_exCtx, {
    type: 'bar',  // 棒グラフ
    data: in_exChartData,
    options: in_exChartOption
});
