<?php
require_once("./component/common/dbconnect.php");
include_once("./component/common/functions.php");
include_once("./component/common/session.php");

$page_title = "選択項目月別推移レポート";
include_once("./component/common/header.php");

$table_list = ["spending_category", "income_category", "creditcard", "qr"];

if (isset($_POST["year"]) && isset($_POST["item"]) && isset($_POST["item_table"])) :
    //検索ボタンが押下されたとき
    $item_table = filter_input(INPUT_POST, "item_table", FILTER_SANITIZE_SPECIAL_CHARS);
    $year = filter_input(INPUT_POST, "year", FILTER_SANITIZE_NUMBER_INT);
    $item = filter_input(INPUT_POST, "item", FILTER_SANITIZE_NUMBER_INT);
elseif (isset($_GET["year"]) && isset($_GET["item"]) && $_GET["item"] !== "" && isset($_GET["num"]) && $_GET["num"] < 4) :
    //円グラフ横の表から遷移されたとき
    $num = $_GET["num"];
    $item_table = $table_list[$num];
    $year = $_GET["year"];
    $item = $_GET["item"];
else :
    $stmt = $db->prepare("SELECT id FROM spending_category WHERE user_id = ? ORDER BY id ASC LIMIT 1");
    $stmt->bind_param("i", $user["id"]);
    $count = sql_check($stmt, $db);
    $stmt->bind_result($id);
    $stmt->fetch();
    $stmt->close();
    $year = date("Y");
    $item = $id;
    $num = 0;
    $item_table = $table_list[$num];
//どちらにも当てはまらない場合はエラー付きパラメータを付けて返す
// header("Location: ./index.php?dataOperation=error");
// exit();
endif;

echo $item_table . "<br>" . $year . "<br>" . $item;
?>

<main class="l-main">

    <section class="p-section p-section__bar-graph">

        <form class="p-form--bar-graph u-flex-box" action="" method="POST" name="graphSearch">
            <input type="hidden" id="itemTable" name="item_table" value="<?php echo isset($item_table) ? $item_table : "spending_category"; ?>">
            <select name="year">
                <?php for ($i = 2020; $i <= date("Y"); $i++) : ?>
                    <option value="<?php echo $i; ?>" <?php echo $year == $i ? "selected" : ""; ?>><?php echo $i; ?>年</option>
                <?php endfor; ?>
            </select>

            <select name="item" onchange="onChangeItem();">
                <optgroup id="defaultGroup" label="支出カテゴリー">
                    <?php
                    $stmt = $db->prepare("SELECT id, name FROM spending_category WHERE user_id=?");
                    $stmt->bind_param("i", $user["id"]);
                    $stmt->execute();
                    $stmt->bind_result($id, $name);
                    while ($stmt->fetch()) : ?>
                        <option value="<?php echo $id; ?>" <?php echo ($item_table === "spending_category" && $item == $id) ? "selected" : ""; ?>><?php echo $name; ?></option>
                    <?php endwhile; ?>
                </optgroup>

                <optgroup label="収入カテゴリー">
                    <?php
                    $stmt = $db->prepare("SELECT id, name FROM income_category WHERE user_id=?");
                    $stmt->bind_param("i", $user["id"]);
                    $stmt->execute();
                    $stmt->bind_result($id, $name);
                    while ($stmt->fetch()) : ?>
                        <option value="<?php echo $id; ?>" <?php echo ($item_table === "income_category" && $item == $id) ? "selected" : ""; ?>><?php echo $name; ?></option>
                    <?php endwhile; ?>
                </optgroup>

                <optgroup label="クレジットカード">
                    <?php
                    $stmt = $db->prepare("SELECT id, name FROM creditcard WHERE user_id=?");
                    $stmt->bind_param("i", $user["id"]);
                    $stmt->execute();
                    $stmt->bind_result($id, $name);
                    while ($stmt->fetch()) : ?>
                        <option value="<?php echo $id; ?>" <?php echo ($item_table === "creditcard" && $item == $id) ? "selected" : ""; ?>><?php echo $name; ?></option>
                    <?php endwhile; ?>
                </optgroup>

                <optgroup label="スマホ決済">
                    <?php
                    $stmt = $db->prepare("SELECT id, name FROM qr WHERE user_id=?");
                    $stmt->bind_param("i", $user["id"]);
                    $stmt->execute();
                    $stmt->bind_result($id, $name);
                    while ($stmt->fetch()) : ?>
                        <option value="<?php echo $id; ?>" <?php echo ($item_table === "qr" && $item == $id) ? "selected" : ""; ?>><?php echo $name; ?></option>
                    <?php endwhile; ?>
                </optgroup>
            </select>

            <input type="submit" class="c-button c-button--bg-blue" value="検索" onclick="">
        </form>
        <div class="bar-graph" id="barGraph">
            <?php
            //月配列
            $month_list = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];

            //データを入れる多次元配列の用意
            $data = array();

            //1月~12月まですべて0円の初期多次元配列データを生成
            //初期化データを用意しないと、データのない月が抽出されずグラフに0円と表示されないため
            for ($i = 0; $i < count($month_list); $i++) :
                $data[] = [
                    "year_month" => $year . "-" .  $month_list[$i],
                    "amount" => "0"
                ];
            endfor;

            //LEFT(date, 7) as monthでdateカラムの頭から7文字分「YYYY-mm」を切り出した値とamountカラムの合計値を抽出
            //・user_idが一致する
            //・渡ってきたテーブル情報と同名のrecordsテーブル内カラムが渡ってきた項目idと一致する
            //・dateカラムが渡ってきた年の文字列を含む
            //上記3点を条件に置く
            $sql = "SELECT LEFT(date, 7) as month, SUM(amount)
                    FROM records
                    WHERE user_id=? AND {$item_table}=? AND date LIKE ?
                    GROUP BY {$item_table}, month
                    ORDER BY month ASC";
            $stmt = $db->prepare($sql);
            $year_param = $year . "%"; //WHERE date LIKE ?の?に入れるワイルドカードを含む文字列生成
            $stmt->bind_param("iis", $user["id"], $item, $year_param);
            $stmt->execute();
            $stmt->bind_result($month, $sum);
            while ($stmt->fetch()) :
                //データを入れている初期多次元配列から年月「YYYY-mm」が一致する配列番号を取得
                $key = array_search($month, array_column($data, "year_month"));
                //上記で取得した$data[番目]のkeym名:amountの値を抽出したデータに書き換え
                $data[$key]["amount"] = $sum;
            endwhile;

            //JSON形式にする配列を用意
            $year_month = [];
            $sum = [];

            //連想配列をfor文で回し、それぞれの配列にセットする
            for ($i = 0; $i < count($month_list); $i++) :
                //「YYYY-mm」形式を「YYYY年mm月」形式に変換して配列に追加
                $year_month[] = str_replace("-", "年", $data[$i]["year_month"]) . "月";
                //合計金額を配列に追加
                $sum[] = $data[$i]["amount"];
            endfor;

            //JSONに変換
            $json_year_month = json_encode($year_month);
            $json_amount = json_encode($sum);
            ?>
            <canvas id="canvas"></canvas>
            <span><i class="fa-regular fa-hand-pointer"></i></span>
        </div>
    </section>
    <section class="p-section p-section__item-report-datalist">
        <?php
        $month_list = array();
        $count_list = array();
        $sql = "SELECT LEFT(date, 7) as month, COUNT(*) FROM records WHERE user_id=? AND {$item_table} = ? AND date LIKE ? GROUP BY month";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("iis", $user["id"], $item, $year_param);
        $stmt->execute();
        $stmt->store_result();
        $count = $stmt->num_rows();
        $stmt->bind_result($month_item, $count_item);
        while ($stmt->fetch()) :
            $month_list[] = $month_item;
            $count_list[] = $count_item;
        endwhile;
        ?>

        <?php if ($count > 0) : ?>
            <div id="groupView" class="p-sp-data-box__groupview">

                <?php for ($i = 0; $i < count($month_list); $i++) :
                    $search_month = $month_list[$i];
                ?>

                    <div class="p-toggledate-tab js-toggle" id="date<?php echo h($search_month); ?>" onclick="onClickDataBanner('<?php echo $search_month; ?>');">
                        <p class="date">
                            <?php echo date("y年n月", strtotime($month_list[$i])); ?>
                        </p>
                        <p class="count">( <?php echo h($count_list[$i]); ?>件 )</p>
                    </div>

                    <?php
                    $sql_output = "SELECT records.id, records.date, records.title, records.amount, spending_category.name, income_category.name, records.type, payment_method.name, creditcard.name, qr.name, records.memo
                                    FROM records
                                    LEFT JOIN spending_category ON records.spending_category = spending_category.id
                                    LEFT JOIN income_category ON records.income_category = income_category.id
                                    LEFT JOIN payment_method ON records.payment_method = payment_method.id
                                    LEFT JOIN creditcard ON records.creditcard = creditcard.id
                                    LEFT JOIN qr ON records.qr = qr.id
                                    WHERE records.date LIKE ? AND records.user_id = ? AND records.{$item_table} =?
                                    ORDER BY date DESC, records.input_time DESC";
                    $stmt_output = $db->prepare($sql_output);
                    $month_param = $search_month . "%";
                    $stmt_output->bind_param("sii", $month_param, $user["id"], $item);
                    $stmt_output->execute();
                    $stmt_output->bind_result(
                        $id,
                        $date,
                        $title,
                        $amount,
                        $spending_category,
                        $income_category,
                        $type,
                        $paymentmethod,
                        $credit,
                        $qr,
                        $memo,
                    );
                    ?>
                    <div class="p-sp-data-box__frame hide" id="item<?php echo h($search_month); ?>">

                        <?php while ($stmt_output->fetch()) : ?>
                            <div class="p-sp-data-box item<?php echo h($id); ?>">
                                <div class="u-flex-box p-sp-data-box__overview <?php echo $memo !== '' ? 'hasmemo' : ''; ?>">
                                    <p> <?php echo h($title); ?>
                                        <span>
                                            <?php
                                            if ($type === 0 && $spending_category !== null) {
                                                echo "(" . h($spending_category) . ")";
                                            } else if ($type === 1 && $income_category !== null) {
                                                echo "(" . h($income_category) . ")";
                                            } else {
                                                echo "(カテゴリー不明)";
                                            }
                                            ?>
                                            <i class="fa-regular fa-message" onclick="showMemo('<?php echo h($memo); ?>')"></i> </span>
                                    </p>
                                    <p class="<?php echo $type === 0 ? 'text-red' : 'text-blue' ?>">
                                        <?php echo h($type) === "0" ? "-¥" . number_format($amount) : ""; ?>
                                        <?php echo h($type) === "1" ? "+¥" . number_format($amount) : ""; ?>
                                    </p>
                                </div>
                                <div class="p-sp-data-box__detail">
                                    <p>
                                        <?php
                                        //支払い方法の出力
                                        if ($type === 0 && $paymentmethod !== null) {
                                            echo "支払い方法：" . h($paymentmethod);
                                        } else if ($type === 1) {
                                            echo "";
                                        } else {
                                            echo "支払い方法：不明";
                                        }
                                        ?>
                                    </p>

                                    <?php if ($paymentmethod === "クレジット" || $paymentmethod === "スマホ決済") : ?>
                                        <p>
                                            <?php
                                            //クレジット、スマホ決済の詳細出力
                                            if ($paymentmethod === "クレジット") {
                                                if ($credit !== null) {
                                                    echo "カード種類：" . h($credit);
                                                } else {
                                                    echo "カード種類：不明";
                                                }
                                            } else if ($paymentmethod === "スマホ決済") {
                                                if ($qr !== null) {
                                                    echo "スマホ決済種類：" . h($qr);
                                                } else {
                                                    echo "スマホ決済種類：不明";
                                                }
                                            }
                                            ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                                <div class="u-flex-box p-sp-data-box__button">
                                    <form action="./record-edit.php" method="post">
                                        <input type="hidden" name="record_id" value="<?php echo h($id); ?>">
                                        <input type="submit" class="c-button c-button--bg-green edit" id="" value="編 集">
                                    </form>
                                    <a class="c-button c-button--bg-red delete" id="delete<?php echo h($id); ?>sp" href="./delete.php?id=<?php echo h($id); ?>;" onclick="deleteConfirm('<?php echo h($title); ?>', 'delete<?php echo h($id); ?>sp');">削 除</a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php endfor; ?>
            </div>

        <?php else : ?>
            <div class="p-sp-data-box nodata">
                <p>データがありません</p>
            </div>
        <?php endif; ?>
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
    const itemTable = document.getElementById("itemTable"); //テーブル情報格納 hidden input要素を取得
    const selectForm = document.graphSearch.item; //項目select要素取得
    const tableList = { //key:テーブル名、値:optgroupのlabelの連想配列
        "spending_category": "支出カテゴリー",
        "income_category": "収入カテゴリー",
        "creditcard": "クレジットカード",
        "qr": "スマホ決済"
    };

    //selectボックスで項目が変更されたときのイベント
    const onChangeItem = () => {
        //選択されたoptionが何番目の要素か
        const num = document.graphSearch.item.selectedIndex;
        //選択されたoptionの親要素optgroupのラベル取得
        const optGroupLabel = selectForm.options[num].parentNode.label;
        //取得したoptgroupラベルと一致する連想配列の値を検索し、そのkey名を返す
        const key = Object.keys(tableList).find((key) => tableList[key] === optGroupLabel);
        //テーブル名を入れるinputのvalueを書き換え
        itemTable.value = key;

        console.log(num);
        console.log(optGroupLabel);
        console.log(key);
    }

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
                labels: <?php echo $json_year_month; ?>, //X軸のラベル、PHPで抽出した年月配列をセット
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