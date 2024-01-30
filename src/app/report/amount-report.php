<?php
//データベース接続、共有機能、セッション管理のためのファイル読み込み
require_once("./component/common/dbconnect.php");
include_once("./component/common/functions.php");
include_once("./component/common/session.php");

//ページのタイトルとヘッダーのインクルード
$page_title = "選択項目月別推移レポート";
include_once("./component/common/header.php");


//年次と選択項目に基づくデータ取得
if (isset($_POST["prev"])) : //前年のデータを取得
    $type = filter_input(INPUT_POST, "type", FILTER_SANITIZE_NUMBER_INT);
    $now_year = filter_input(INPUT_POST, "year", FILTER_SANITIZE_NUMBER_INT);
    $base_year = strtotime($now_year);
    $year = date("Y", strtotime("-1 year", $base_year));
elseif (isset($_POST["next"])) ://来年のデータ取得
    $type = filter_input(INPUT_POST, "type", FILTER_SANITIZE_NUMBER_INT);
    $now_year = filter_input(INPUT_POST, "year", FILTER_SANITIZE_NUMBER_INT);
    $base_year = strtotime($now_year);
    $year = date("Y", strtotime("+1 year", $base_year));
elseif (isset($_POST["type"])) ://POSTリクエストから年次と選択項目の取得
    $type = filter_input(INPUT_POST, "type", FILTER_SANITIZE_NUMBER_INT);
    $now_year = filter_input(INPUT_POST, "year", FILTER_SANITIZE_NUMBER_INT);
    $base_year = strtotime($now_year);
    $year = date("Y", $base_year);
else ://デフォルトの設定（初回アクセス時）
    $type = "0";
    $year = date("Y");
endif;
?>

<main class="l-main">
    <section class="p-section p-section__bar-graph">
        <form class="p-form--bar-graph all-report u-flex-box" action="" method="POST" name="graphSearch">
            <input type="submit" name="prev" class="fas" value="">

            <input type="hidden" name="year" value="<?php echo $year; ?>-01" readonly>
            <input type="text" name="showyear" value="<?php echo $year; ?>年" readonly>

            <input type="submit" name="next" class="fas" value="">
            <div class="p-form__flex-input">
                <input id="spending" type="radio" name="type" value="0" onchange="submit(this.form)" required <?php echo $type === "0" ? "checked" : ""; ?>>
                <label for="spending">支出</label>
                <input type="radio" name="type" id="income" value="1" onchange="submit(this.form)" <?php echo $type === "1" ? "checked" : ""; ?>>
                <label for="income">収入</label>
            </div>
        </form>

        <div class="sum">
            <?php
            $sql = "SELECT SUM(amount) FROM records WHERE user_id=? AND type=? AND date LIKE ?";
            $stmt = $db->prepare($sql);
            $year_param = $year . "%";
            $stmt->bind_param("iis", $user["id"], $type, $year_param);
            $stmt->execute();
            $stmt->bind_result($amount_sum);
            $stmt->fetch(); ?>
            <p>
                年間<?php echo $type === "0" ? "支出" : "収入"; ?>金額：
                <span>¥<?php echo number_format($amount_sum); ?></span>
            </p>
            <?php $stmt->close(); ?>
        </div>

        <div class="bar-graph" id="barGraph">
            <?php
            //すべての月が0円のデータ配列を生成
            $month_list = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
            $data = array();
            for ($a = 0; $a < count($month_list); $a++) :
                $data[] = [
                    "year_month" => $year . "-" .  $month_list[$a],
                    "amount" => "0"
                ];
            endfor;

            //選択された年のデータを抽出
            $sql = "SELECT LEFT(date, 7) as month, SUM(amount)
                    FROM records
                    WHERE user_id=? AND type=? AND date LIKE ?
                    GROUP BY month
                    ORDER BY month ASC";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("iis", $user["id"], $type, $year_param);
            $stmt->execute();
            $stmt->bind_result($month, $sum);
            while ($stmt->fetch()) :
                //取得したデータをデータ配列に再代入（1円以上のデータ月のみ）
                $key = array_search($month, array_column($data, "year_month"));
                $data[$key]["amount"] = $sum;
            endwhile;

            //JSON形式に変換
            $year_month = [];
            $sum = [];
            for ($i = 0; $i < count($month_list); $i++) :
                $year_month[] = str_replace($year . "-", "", $data[$i]["year_month"]) . "月";
                $sum[] = $data[$i]["amount"];
            endfor;
            $json_month = json_encode($year_month);
            $json_amount = json_encode($sum);
            ?>
            <canvas id="canvas"></canvas>
            <span><i class="fa-regular fa-hand-pointer"></i></span>
        </div>
    </section>

    <section class="p-section p-section__back-home">
        <a href="./index.php" class="c-button c-button--bg-gray">ホームに戻る</a>
    </section>
</main>

<?php
//ディレクトリ直下の場合
$footer_back = "on"; //login.php以外に記述
include_once("./component/common/footer.php");
?>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="./js/import.js"></script>
<script src="./js/functions.js"></script>
<script src="//cdn.jsdelivr.net/npm/chart.js@3.7.1"></script>
<script src="//cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

<script>
    const canvas = document.getElementById("canvas"); //グラフを描画するcanvasを取得

    window.onload = function() { //画面が読み込まれたら
        //canvasの高さと幅指定
        const containerWidth = document.getElementById("barGraph").clientWidth; //canvasを囲っているdiv要素の幅を取得
        if (window.outerWidth < 600) { //画面幅が600px未満のとき
            canvas.style.width = "850px"; //canvasの幅を850pxに
        } else { //それ以外（PCのとき）
            canvas.style.width = containerWidth + "px"; //取得したdiv要素の幅と同値を幅に設定
        }
        canvas.style.height = "400px"; //canvasの高さは400pxで固定

        //y軸目盛の間隔と最大値計算処理
        const aryMax = (a, b) => { //配列の頭から二つずつ比較して最大値を返す関数
            return Math.max(a, b);
        }
        //金額配列に対して上記関数を実行し最大値を格納
        const maxVal = <?php echo $json_amount; ?>.reduce(aryMax);
        //y軸目盛間隔、最大値切り上げのための割る数、y軸の最大値変数定義
        let stepVal, roundVal;
        //最大値によってy軸目盛間隔、最大値切り上げのための割る数を設定
        if (maxVal < 1000) { //0-999円
            stepVal = 100;
            roundVal = 100; //100で割る
        } else if (maxVal < 10000) { //1,000-9,999円
            stepVal = 1000;
            roundVal = 1000; //1000で割る
        } else if (maxVal < 50000) { //10,000-49,999円
            stepVal = 5000;
            roundVal = 5000; //5000で割る
        } else if (maxVal < 100000) { //50,000-99,999円
            stepVal = 10000;
            roundVal = 10000; //10000で割る
        } else { //100,000円以上
            stepVal = 100000;
            roundVal = 100000; //10000で割る
        }
        //金額の最大値によってy軸の最大値を設定
        //一度最大金額を切り上げたい桁数が小数点以下になるように割ってから切り上げ→割った数をかけて桁数を戻す
        let ymax = Math.ceil(maxVal / roundVal) * roundVal;
        if (maxVal === 0) { //0円のとき
            ymax = 1000; //強制的にy軸の最大値は1000に設定
        } else if (maxVal == ymax) { //金額最大値とy軸最大値が同じ場合
            ymax = ymax + stepVal; //y軸最大値に目盛間隔分を足す
        }


        //棒グラフを描画
        new Chart(canvas.getContext("2d"), {
            type: "bar", //グラフのタイプ、bar -> 棒グラフ
            data: {
                labels: <?php echo $json_month; ?>, //X軸のラベル、PHPで抽出した年月配列をセット
                datasets: [{
                    data: <?php echo $json_amount; ?>, //棒グラフにするデータ、PHPで抽出した金額配列をセット
                    backgroundColor: ["#28a7e0"], //棒グラフの色
                    datalabels: { //金額データラベル設定
                        color: "#28a7e0", //文字色
                        font: { //フォント設定
                            size: "11px",
                            weight: "bold"
                        },
                        anchor: "end", // 金額データラベルの位置（"end" は上端）
                        align: "end", // 金額データラベルの位置("end"は上記アンカーポイントの後ろ)
                        formatter: function(value, context) { // 金額データラベルを「,」で区切り「円」を付け足す
                            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + " 円";
                        },
                    },
                }],
            },

            plugins: [ChartDataLabels], //データラベルのプラグインを読み込む

            options: {
                responsive: false, //レスポンシブOFF
                scales: {
                    y: { //y軸の設定
                        max: ymax,
                        ticks: {
                            stepSize: stepVal,
                            callback: function(tick) { //y軸ラベルを「,」で区切り「円」を付け足す
                                return tick.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + " 円";
                            },
                        }
                    },
                },
                plugins: {
                    legend: { //グラフの凡例OFF
                        display: false,
                    },
                    tooltip: { //ツールチップOFF
                        enabled: false,
                    },
                },
            },
        })
    }
</script>
</body>

</html>