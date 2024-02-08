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



const in_exChartLabels = income_data["年月"];
const incomeData = income_data["合計金額"];

let amount_difference = [];
for (let i = 0; i < 5; i++) {
    amount_difference[i] = income_data["合計金額"] - expense_data["合計金額"];
}

let in_exChartData = {
    labels: income_data["年月"],
    datasets: [
        {
            type: 'bar',
            label: '月間収入',
            data: income_data["合計金額"],
            backgroundColor : "rgba(54, 164, 235, 0.5)",
            borderColor : "rgba(54, 164, 235, 0.8)",
            borderWidth: 1
        },
        {
            type: 'bar',
            label: '月間支出',
            data: expense_data["合計金額"],
            backgroundColor : "rgba(254, 97, 132, 0.5)",
            borderColor : "rgba(254, 97, 132, 0.8)",
            borderWidth: 1
        },
        {
            type: 'bar',
            label: '月間収支',
            data: amount_difference,
            backgroundColor : "rgba(75, 192, 192, 0.3)",
            borderColor : "rgba(75, 192, 192, 0.8)",
            borderWidth: 1
        },
    ],
};

let in_exChartOption = {
    responsive: false,
};


const in_exCtx = document.getElementById('in_exChart').getContext('2d');

let in_exChart = new Chart(in_exCtx, {
    type: 'bar',  // 棒グラフ
    data: in_exChartData,
    options: in_exChartOption
});
