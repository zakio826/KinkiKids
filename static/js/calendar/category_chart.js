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