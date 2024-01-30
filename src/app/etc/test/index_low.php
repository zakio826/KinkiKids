<?php
require_once("./component/common/dbconnect.php");
include_once("./component/common/functions.php");
include_once("./component/common/session.php");
include_once("./component/index/session-param-handler.php");
echo "接続完了";
echo $child_id;


$page_title = "ホーム";
include_once("./component/common/header.php");
?>

<main class="l-main">
  <h1>年長向け</h1>
  <?php
  include_once("./component/index/data-operation.php");
  include_once("./component/index/calendar-detail.php");
  ?>

  <div class="u-flex-box records-input-calendar">
    <?php
    include_once("./component/index/record-input.php");
    include_once("./component/index/calendar.php");
    ?>
  </div>

  <!-- ガチャ -->
  <section class="p-section p-section__records-output">
    <form class="p-form p-form--input-record" action="./gacha.php" method="POST">
      <input class="c-button c-button--bg-blue" type="submit" value="ガチャへ">
    </form>
  </section>

  <!-- 収支データ入力 -->

  <section class="p-section p-section__records-output js-switch-content fade-in" data-tab="tab-3" id="data-table">
    <h3>収支一覧</h3>
    <?php
    include_once("./component/index/month-search.php");
    include_once("./component/index/filtering_search.php");
    ?>

    <div class="pc_only">
      <?php
      include_once("./component/index/search-result-pc.php");
      include_once("./component/index/search-result-excel.php");
      ?>
    </div>
    <div class="sp_only">
      <?php
      include_once("./component/index/search-result-sp.php");
      ?>
    </div>
  </section>

  <section id="report" class="p-section p-section__report hide js-switch-content fade-in" data-tab="tab-4">
    <?php
    include_once("./component/index/item-pie-chart.php");
    ?>
  </section>
  <?php
  include_once("./component/index/sp-tab.php");
  ?>

</main>

<?php
//ディレクトリ直下の場合
$footer_back = "on"; //login.php以外に記述
include_once("./component/common/footer.php");
?>

<div class="p-back-top" id="page_top">
  <a href="#page-top"></a>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="./js/FileSaver.min.js"></script>
<script src="./js/xlsx.core.min.js"></script>
<script src="./js/tableexport.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/remodal/1.0.5/remodal.min.js"></script>

<script src="https://cdn.plot.ly/plotly-2.16.1.min.js"></script>
<script src="./js/jquery.cookie.js"></script>

<script src="./js/radio.js"></script>
<script src="./js/import.js"></script>
<script src="./js/functions.js"></script>
<script src="./js/record-edit.js"></script>
<script src="./js/jquery.cookie.js"></script>

<script>
  //onchange onblur切り替え
  window.onload = function toggleChangeToBlur() { //画面が読み込まれたら以下の関数を実行
    const calendarMonth = document.getElementById("calendarMonth"); //カレンダー上部のtype=monthを取得
    const searchMonth = document.getElementById("searchMonth"); //データ一覧上部のtype=month取得
    if (window.outerWidth < 900) { //もし画面幅が900px未満であれば

      //両月選択inputからonchange属性を削除
      calendarMonth.removeAttribute("onchange");
      searchMonth.removeAttribute("onchange");

      //両月選択inputにonblur属性とイベントを追加
      calendarMonth.setAttribute("onblur", "onChangeMonth('ym', 'calendarMonth');");
      searchMonth.setAttribute("onblur", "onChangeMonth('search_month', 'searchMonth');");
    }
  }

  window.onload = function() {
    //左辺条件は先程の関数部分と同様、データを表示する要素があるか(データなしのときはfalseで処理は実行されない)
    //document.cookie.indexOf("dataView=group") → cookieに名前がdataViewで値がgroupが保存されているか
    //保存されていないと-1が返ってくる
    if ((groupView !== null || allView !== null) && document.cookie.indexOf("dataView=group") !== -1) {
      toggleStyle.checked = true; //トグルボタンをON状態にする
      groupView.classList.remove("hide"); //日付ごとまとめた表示要素からhideクラスを削除で表示する
      allView.classList.add("hide"); //既存データ一覧表示要素にhideクラスを追加で非表示にする
    }
  }
</script>
<script>
  $(function() {
    $("#table").tableExport({
      formats: ["xlsx"], //エクスポートする形式
      bootstrap: false, //Bootstrapを利用するかどうか
      position: top
    });
  });

  $("#excelExport").on("click", function() {
    $("#table caption button").trigger("click");
  });

  $("#table caption").hide();

  //スマホタブ切り替え
  $(function() {
    // クッキー保存されている or いない場合
    if ($.cookie("num")) {
      num = $.cookie("num");
    } else {
      num = 0;
    }

    // タブ処理
    tabSwitching(num);
    // クリックされた場合
    $("#tab li").click(function() {
      // クリックされた <li> のインデックス番号を取得
      num = $("#tab li").index(this);
      // タブ処理
      tabSwitching(num);
      // クッキーを保存
      // 有効期限は1日(ブラウザを閉じたら初期化)
      $.cookie("num", num, {
        expires: 1
      });
    });

    // タブ切り替え処理
    function tabSwitching(num) {
      $("#tab li").removeClass("is-active");
      $("#tab li").eq(num).addClass("is-active");
      $(".js-switch-content").addClass("hide");
      $(".js-switch-content").eq(num).removeClass("hide");
    }
  });
</script>
<script>
  //グラフタブ切り替え
  $(function() {
    // クッキー保存されている or いない場合
    if ($.cookie("graphtab")) {
      graphtab = $.cookie("graphtab");
    } else {
      graphtab = 0;
    }

    // タブ処理
    tabSwitching(graphtab);
    // クリックされた場合
    $("#graphTab li").click(function() {
      // クリックされた <li> のインデックス番号を取得
      graphtab = $("#graphTab li").index(this);
      // タブ処理
      tabSwitching(graphtab);
      // クッキーを保存
      // 有効期限は1日(ブラウザを閉じたら初期化)
      $.cookie("graphtab", graphtab, {
        expires: 1
      });

    });

    // タブ切り替え処理
    function tabSwitching(graphtab) {
      $("#graphTab li").removeClass("is-active");
      $("#graphTab li").eq(graphtab).addClass("is-active");
      $(".js-graph-content").removeClass("is-active");
      $(".js-graph-content").eq(graphtab).addClass("is-active");
    }
  });

  window.onload = function() {
    const dataSpendingCat = [{ //グラフの情報
      type: "pie", //グラフのタイプ=円グラフ
      values: <?php echo $json_spendingcat_amount; ?>, //値=PHP配列 金額配列
      labels: <?php echo $json_spendingcat_item; ?>, //ラベル=PHP配列 カテゴリー名配列
      textinfo: "label+percent", //グラフに表示するテキスト=割合とカテゴリー名
      textposition: "inside", //グラフに表示するテキストの位置=内側、外側にしたい場合はoutsideを指定
      automargin: true, //自動余白=有効
      direction: "clockwise", //グラフの向き=時計回りに割合の多い順
      hoverinfo: "skip" //ホバーした時の情報=表示しない
    }]

    const dataIncomeCat = [{
      type: "pie",
      values: <?php echo $json_incomecat_amount; ?>,
      labels: <?php echo $json_incomecat_item; ?>,
      textinfo: "label+percent",
      textposition: "inside",
      automargin: true,
      direction: "clockwise",
      hoverinfo: "skip"
    }]

    const dataCredit = [{
      type: "pie",
      values: <?php echo $json_credit_amount; ?>,
      labels: <?php echo $json_credit_item; ?>,
      textinfo: "label+percent",
      textposition: "inside",
      automargin: true,
      direction: "clockwise",
      hoverinfo: "skip"
    }]

    const dataQr = [{
      type: "pie",
      values: <?php echo $json_qr_amount; ?>,
      labels: <?php echo $json_qr_item; ?>,
      textinfo: "label+percent",
      textposition: "inside",
      automargin: true,
      direction: "clockwise",
      hoverinfo: "skip"
    }]

    const layout = { //グラフのレイアウト
      height: 400, //グラフの高さ
      width: 400, //グラフの幅
      margin: { //余白
        "t": 0, //top
        "b": 0, //bottom
        "l": 0, //left
        "r": 0 //right
      },
      font: { //データテキスト
        size: 16 //font-size: 16px;と同じ
      },
      showlegend: false, //グラフ横の情報=非表示にする
    }

    const layoutSp = {
      height: 300,
      width: 300,
      margin: {
        "t": 0,
        "b": 0,
        "l": 0,
        "r": 0
      },
      font: {
        size: 12
      },
      showlegend: false,
    }

    //Plotly.newPlot("表示する要素のid", グラフの情報, グラフのレイアウト)で表示する
    const dataNameList = [dataSpendingCat, dataIncomeCat, dataCredit, dataQr]; //各データ変数を配列に格納
    const dataOutputElement = ["graph-1", "graph-2", "graph-3", "graph-4"]; //出力する要素のidを配列に格納
    if (window.outerWidth > 900) {
      //PCサイズ
      for (let i = 0; i < dataNameList.length; i++) {
        if (dataNameList[i][0].values.length !== 0) {
          Plotly.newPlot(dataOutputElement[i], dataNameList[i], layout);
        }
      }
    } else {
      //スマホサイズ
      for (let i = 0; i < dataNameList.length; i++) {
        if (dataNameList[i][0].values.length !== 0) {
          Plotly.newPlot(dataOutputElement[i], dataNameList[i], layoutSp, {
            displayModeBar: false
          });
        }
      }
    }
  }

  //トップへ戻るボタン
  $(function() {
    let appear = false;
    const pageTop = $("#page_top");
    $(window).scroll(function() {
      if ($(this).scrollTop() > 300) { //1000pxスクロールしたら
        if (appear == false) {
          appear = true;

          if (window.outerWidth > 900) {
            pageTop.stop().animate({
              "bottom": "3.6rem" //下から3.6remの位置に
            }, 300); //0.3秒かけて現れる
          } else {
            pageTop.stop().animate({
              "bottom": "9rem" //下から3.6remの位置に
            }, 300); //0.3秒かけて現れる
          }
        }
      } else {
        if (appear) {
          appear = false;
          pageTop.stop().animate({
            "bottom": "-5rem" //下から-5remの位置に
          }, 300); //0.3秒かけて隠れる
        }
      }
    });
    pageTop.click(function() {
      $("body, html").animate({
        scrollTop: 0
      }, 500); //0.5秒かけてトップへ戻る
      return false;
    });
  });
</script>
</body>

</html>