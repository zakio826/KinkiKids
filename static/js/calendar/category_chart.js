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


// 円グラフ共通オプション
let categoryChartOption = {
    responsive: true,
    plugins: {
        legend:{
            display: true,
            position: "right",
            labels: {
                fullWidth: false,
                boxWidth: 12,
                // filter: function(a, data) { 
                //   return (a.datasetIndex != 0);  // data配列の0番目の凡例を非表示
                // },
            }
        },
        tooltip: {
            enabled: false,  // 色調整用設定
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


// チャート配色セット
const chart_color = [
    "255, 0, 0",        // red
    "255, 80, 0",       // orange
    "250, 150, 0",      // light orange
    "255, 210, 0",      // yellow
    "100, 230, 0",      // light green
    "20, 200, 140",     // green
    "40, 170, 240",     // light blue
    "50, 100, 230",     // blue
    "150, 30, 230",     // purple
    "255, 0, 255",      // magenta
    "240, 40, 150",     // pink
];


// 収入カテゴリ円グラフの色を指定
let in_chart_color_background = [];
let in_chart_color_border = [];
let in_chart_color_hover_background = [];
let in_chart_color_hover_border = [];
for (let i = 0; i < chart_color.length; i++) {
    in_chart_color_background.push("rgba(" + chart_color[i] + ", 0.25)");
    in_chart_color_border.push("rgba(" + chart_color[i] + ", 0.80)");
    in_chart_color_hover_background.push("rgba(" + chart_color[i] + ", 0.40)");
    in_chart_color_hover_border.push("rgba(" + chart_color[i] + ", 1.00)");
}

// 収入カテゴリ円グラフのデータを設定
let in_categoryChartData = {
    labels: in_category_data["カテゴリ名"],
    datasets: [
        {
            label: '合計金額',
            data: in_category_data["合計金額"],
            backgroundColor: in_chart_color_background,
            borderColor: in_chart_color_border,
            hoverBackgroundColor: in_chart_color_hover_background,
            hoverBorderColor: in_chart_color_hover_border,
            borderWidth: 1,
            hoverBorderWidth: 2,
        }
    ]
};

// 収入カテゴリ円グラフを描画
const in_categoryCtx = document.getElementById('in_categoryChart').getContext('2d');
let in_categoryChart = new Chart(in_categoryCtx, {
    type: 'pie',  // 円グラフ
    data: in_categoryChartData,
    options: categoryChartOption
});


// 支出カテゴリ円グラフの色を指定
let ex_chart_color_background = [];
let ex_chart_color_border = [];
let ex_chart_color_hover_background = [];
let ex_chart_color_hover_border = [];

const index = 8;  // チャート配色セットから初期配色を指定
let num = 0;
for (let i = 0; i < chart_color.length; i++) {
    if (i < chart_color.length - index) num = i + index;
    else num = i - chart_color.length + index;
    
    // チャート配色セットを逆順で挿入
    ex_chart_color_background.unshift("rgba(" + chart_color[num] + ", 0.25)");
    ex_chart_color_border.unshift("rgba(" + chart_color[num] + ", 0.80)");
    ex_chart_color_hover_background.unshift("rgba(" + chart_color[num] + ", 0.40)");
    ex_chart_color_hover_border.unshift("rgba(" + chart_color[num] + ", 1.00)");
}

// 支出カテゴリ円グラフのデータを設定
let ex_categoryChartData = {
    labels: ex_category_data["カテゴリ名"],
    datasets: [
        {
            label: '合計金額',
            data: ex_category_data["合計金額"],
            backgroundColor: ex_chart_color_background,
            borderColor: ex_chart_color_border,
            hoverBackgroundColor: ex_chart_color_hover_background,
            hoverBorderColor: ex_chart_color_hover_border,
            borderWidth: 1,
            hoverBorderWidth: 2,
        }
    ]
};

// 支出カテゴリ円グラフを描画
const ex_categoryCtx = document.getElementById('ex_categoryChart').getContext('2d');
let ex_categoryChart = new Chart(ex_categoryCtx, {
    type: 'pie',  // 円グラフ
    data: ex_categoryChartData,
    options: categoryChartOption
});


// 色テスト用
let chart_color_background = [];
let chart_color_border = [];
let chart_color_hover_background = [];
let chart_color_hover_border = [];

const x = 8;
let n = 0;
for (let i = 0; i < chart_color.length; i++) {
    if (i < chart_color.length - x) n = i + x;
    else n = i - chart_color.length + x;
    console.log(n);

    chart_color_background.unshift("rgba(" + chart_color[n] + ", 0.25)");
    chart_color_border.unshift("rgba(" + chart_color[n] + ", 0.80)");
    chart_color_hover_background.unshift("rgba(" + chart_color[n] + ", 0.40)");
    chart_color_hover_border.unshift("rgba(" + chart_color[n] + ", 1.00)");
}
console.log(chart_color_background);
// console.log(chart_color_border);
// console.log(chart_color_hover_background);
// console.log(chart_color_hover_border);


let categoryChartData = {
    labels: ex_category_data["カテゴリ名"].concat(["いろはにほへと", "ちりぬるを", "わかよたれそ", "つねならむ", "うひのおくやま"]),
    datasets: [
        {
            label: '合計金額',
            data: ["20000", "1600", "1000", "900", "800", "700", "600", "500", "440", "320", "300", "240"],
            backgroundColor: chart_color_background,
            borderColor: chart_color_border,
            hoverBackgroundColor: chart_color_hover_background,
            hoverBorderColor: chart_color_hover_border,
            borderWidth: 1,
            hoverBorderWidth: 2,
        }
    ]
    // labels: ex_category_data["カテゴリ名"],
    // datasets: [
    //     {
    //         label: '収入合計',
    //         data: in_category_data["合計金額"],
    //         backgroundColor: in_chart_color_background,
    //         borderColor: in_chart_color_border,
    //         hoverBackgroundColor: in_chart_color_hover_background,
    //         hoverBorderColor: in_chart_color_hover_border,
    //         borderWidth: 1,
    //         hoverBorderWidth: 2,
    //     },
    //     {
    //         label: '支出合計',
    //         data: ex_category_data["合計金額"],
    //         backgroundColor: ex_chart_color_background,
    //         borderColor: ex_chart_color_border,
    //         hoverBackgroundColor: ex_chart_color_hover_background,
    //         hoverBorderColor: ex_chart_color_hover_border,
    //         borderWidth: 1,
    //         hoverBorderWidth: 2,
    //     }
    // ]
};
const categoryCtx = document.getElementById('categoryChart').getContext('2d');
let categoryChart = new Chart(categoryCtx, {
    type: 'pie',  // 円グラフ
    data: categoryChartData,
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