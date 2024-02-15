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
    "255, 80, 0",
    "250, 150, 0",
    "255, 210, 0",
    "80, 230, 0",
    "20, 180, 160",
    "50, 100, 230",
    "150, 30, 230",
    "240, 20, 140",
];

let chart_color_background = [];
let chart_color_border = [];
let chart_color_hover_background = [];
let chart_color_hover_border = [];
for (let i = 0; i < chart_color.length; i++) {
    chart_color_background.push("rgba(" + chart_color[i] + ", 0.2)");
    chart_color_border.push("rgba(" + chart_color[i] + ", 0.8)");
    chart_color_hover_background.push("rgba(" + chart_color[i] + ", 0.4)");
    chart_color_hover_border.push("rgba(" + chart_color[i] + ", 1.0)");
}


// const chart_color1 = [
//     "rgba(255, 0, 0, 0.2)",
//     "rgba(0, 255, 0, 0.2)",
//     "rgba(0, 0, 255, 0.2)",
//     "rgba(255, 99, 132, 0.2)",
//     "rgba(54, 162, 235, 0.2)",
//     "rgba(255, 206, 86, 0.2)",
//     "rgba(75, 192, 192, 0.2)",
// ];

// const chart_color2 = [
//     "rgba(255, 0, 0, 1)",
//     "rgba(0, 255, 0, 1)",
//     "rgba(0, 0, 255, 1)",
//     "rgba(255, 99, 132, 1)",
//     "rgba(54, 162, 235, 1)",
//     "rgba(255, 206, 86, 1)",
//     "rgba(75, 192, 192, 1)",
// ];


let category_data = ex_category_data;

let in_categoryChartData = {
    labels: in_category_data["カテゴリ名"],
    datasets: [
        {
            label: '合計金額',
            data: in_category_data["合計金額"],
            backgroundColor: chart_color_background,
            borderColor: chart_color_border,
            borderWidth: 1,
            hoverBackgroundColor: chart_color_hover_background,
            hoverBorderColor: chart_color_hover_border,
            hoverBorderWidth: 2,
        }
    ]
};
let ex_categoryChartData = {
    labels: ex_category_data["カテゴリ名"],
    datasets: [
        {
            label: '合計金額',
            data: ex_category_data["合計金額"],
            backgroundColor: chart_color_background,
            borderColor: chart_color_border,
            borderWidth: 1,
            hoverBackgroundColor: chart_color_hover_background,
            hoverBorderColor: chart_color_hover_border,
            hoverBorderWidth: 2,
        }
    ]
};
let categoryChartOption = {
    responsive: true,
    plugins: {
        tooltip: {
            callbacks: {
                label: function(context) { 
                    var label = context.dataset.label || '';

                    if (label) {
                        label += '：';
                    }
                    if (context.formattedValue !== null) {
                        label += context.formattedValue + '円';	// 単位
                    }
                    return label;
                },
            },
        },
        datalabels: {
            formatter: function(value, context) {
                return context.chart.data.labels[context.dataIndex];
            }
        }
    }
    // scales: {
    //     y: {
    //         beginAtZero: true
    //     }
    // }
};

const in_categoryCtx = document.getElementById('in_categoryChart').getContext('2d');
let in_categoryChart = new Chart(in_categoryCtx, {
    type: 'pie',  // 円グラフ
    data: in_categoryChartData,
    options: categoryChartOption
});

const ex_categoryCtx = document.getElementById('ex_categoryChart').getContext('2d');
let ex_categoryChart = new Chart(ex_categoryCtx, {
    type: 'pie',  // 円グラフ
    data: ex_categoryChartData,
    options: categoryChartOption
});


// let income_flag = false;
// document.getElementById('in_exSwitch').onclick = function() {
//     if (income_flag) {
//         category_data = ex_category_data;
//         income_flag = false;
//     } else {
//         category_data = in_category_data;
//         income_flag = true;
//     }

//     categoryChart.data = {
//         labels: category_data["カテゴリ名"],
//         datasets: [
//             {
//                 label: 'カテゴリ別合計金額',
//                 data: category_data["合計金額"],
//                 backgroundColor: chart_color_background,
//                 borderColor: chart_color_border,
//                 borderWidth: 1,
//                 hoverBackgroundColor: chart_color_hover_background,
//                 hoverBorderColor: chart_color_hover_border,
//                 hoverBorderWidth: 2,
//             }
//         ]
//     };
//     categoryChart.update();
// }