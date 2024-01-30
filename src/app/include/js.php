<script src="//cdn.plot.ly/plotly-2.16.1.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="./js/FileSaver.min.js"></script>
<script src="./js/xlsx.core.min.js"></script>
<script src="./js/tableexport.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/remodal/1.0.5/remodal.min.js"></script>

<script src="./js/radio.js"></script>
<script src="./js/import.js"></script>
<script src="./js/functions.js"></script>
<script src="./js/record-edit.js"></script>
<script src="./js/jquery.cookie.js"></script>

<script>
    window.onload = function() {
        let target = $(".message .bg");
        target.scrollTop(target[0].scrollHeight);
    }
</script>

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
</script>

<!-- sp一覧切り替え -->
<script>
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

<!-- 家計簿タブ切り替え -->
<script>
    // window.onload = function() {
    //     if ((input_data !== null || calendar !== null) && document.cookie.indexOf("household=input") !== -1) {
    //         household.checked = false;
    //         input_data.classList.remove("hide");
    //         calendar.classList.add("hide");
    //     }
    // }
    $(function() {
        // クッキー保存されている or いない場合
        if ($.cookie("switch_num")) {
            switch_num = $.cookie("switch_num");
        } else {
            switch_num = 0;
        }

        // タブ処理
        tabSwitching(switch_num);
        // クリックされた場合
        $("#switch li").click(function() {
            // クリックされた <li> のインデックス番号を取得
            switch_num = $("#switch li").index(this);
            // タブ処理
            tabSwitching(switch_num);
            // クッキーを保存
            // 有効期限は1日(ブラウザを閉じたら初期化)
            $.cookie("switch_num", switch_num, {
                expires: 1
            });
        });

        // タブ切り替え処理
        function tabSwitching(switch_num) {
            $("#switch li").removeClass("is-active");
            $("#switch li").eq(switch_num).addClass("is-active");
            $(".switch-household").addClass("hide");
            $(".switch-household").eq(switch_num).removeClass("hide");
        }
    });
</script>

<!-- 振り返りタブ切り替え -->
<script>
    $(function() {
        // クッキー保存されている or いない場合
        if ($.cookie("review_num")) {
            review_num = $.cookie("review_num");
        } else {
            review_num = 0;
        }

        // タブ処理
        tabSwitching(review_num);
        // クリックされた場合
        $("#review .review_switch__item").click(function() {
            // クリックされた <li> のインデックス番号を取得
            review_num = $("#review .review_switch__item").index(this);
            // タブ処理
            tabSwitching(review_num);
            // クッキーを保存
            // 有効期限は1日(ブラウザを閉じたら初期化)
            $.cookie("review_num", review_num, {
                expires: 1
            });
        });

        // タブ切り替え処理
        function tabSwitching(review_num) {
            $("#review .review_switch__item").removeClass("is-active");
            $("#review .review_switch__item").eq(review_num).addClass("is-active");
            $(".switch-review").addClass("hide");
            $(".switch-review").eq(review_num).removeClass("hide");
        }
    });
</script>

<!-- エクセル出力 -->
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
</script>

<!-- ホームボタン -->
<script>
    $(function() {
        if ($.cookie("num")) {
            num = $.cookie("num");
        } else {
            num = 0;
        }

        // タブ処理
        // tabSwitching(num);
        // クリックされた場合

        $(".home").click(function() {
            // クリックされた <li> のインデックス番号を取得
            num = $(".home").data("tab");
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
            $("#mission").addClass("hide");
            $("#household").removeClass("hide");
        }
    });
</script>

<!-- 家計簿ボタン -->
<script>
    $(function() {
        // タブ処理
        tabSwitching(num);
        // クリックされた場合
        $("#householdBtn").click(function() {
            // クリックされた <li> のインデックス番号を取得
            num = $("#householdBtn").data("tab");
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
            $("#mission").addClass("hide");
            // $(".js-switch-content").eq(num).removeClass("hide");
            $("#household").removeClass("hide");
        }
    });
</script>

<!-- お手伝いボタン -->
<script>
    $(function() {
        // タブ処理
        tabSwitching(num);
        // クリックされた場合
        $("#missionBtn").click(function() {
            // クリックされた <li> のインデックス番号を取得
            num = $("#missionBtn").data("tab");
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
            // $(".js-switch-content").eq(num).removeClass("hide");
            $("#household").addClass("hide");
            $("#mission").removeClass("hide");
        }
    });
</script>

<!-- スマホタブ切り替え -->
<script>
    $(function() {
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
            $(".js-switch-content").eq($("#tab li").eq(num).data("tab")).removeClass("hide");
        }
    });
</script>

<!-- ミッションタブ切り替え -->
<script>
    $(function() {
        // クッキー保存されている or いない場合
        if ($.cookie("mission_num")) {
            mission_num = $.cookie("mission_num");
        } else {
            mission_num = 0;
        }

        // タブ処理
        tabSwitching(mission_num);
        // クリックされた場合
        $("#mission li").click(function() {
            // クリックされた <li> のインデックス番号を取得
            mission_num = $("#mission li").index(this);
            // タブ処理
            tabSwitching(mission_num);
            // クッキーを保存
            // 有効期限は1日(ブラウザを閉じたら初期化)
            $.cookie("mission_num", mission_num, {
                expires: 1
            });
        });

        // タブ切り替え処理
        function tabSwitching(mission_num) {
            $("#mission li").removeClass("is-active");
            $("#mission li").eq(mission_num).addClass("is-active");
            $(".mission_switch-content").addClass("hide");
            $(".mission_switch-content").eq(mission_num).removeClass("hide");
            $(".mission_switch-add").addClass("hide");
            $(".mission_switch-add").eq(mission_num).removeClass("hide");
        }
    });
</script>

<!-- ミッション達成 -->
<script>
    $(function() {
        $("#mission .mission-box").click(function() {
            num = $("#mission .mission-box").index(this);
            tabSwitching(num);
        });

        function tabSwitching(num) {
            complete = $("#mission .mission-box").eq(num);
            <?php if ($select == "child") : ?>
                // $("#mission .mission-box").removeClass("complete");
                if (!complete.hasClass("complete")) {
                    console.log("未完了です");

                    if (!confirm("「" + $("#mission .mission-box .name").eq(num).text() + "」の完了を親に通知します")) {
                        return false;
                    } else {
                        tag = $("#mission .mission-box").eq(num);
                        tag.addClass("complete");
                        mission = tag.data("mission");

                        console.log(mission);

                        let parameter;
                        if (tag.hasClass("emergency")) {
                            parameter = {
                                parent_id: <?php echo $user["parent"]; ?>,
                                child_id: <?php echo $user["id"]; ?>,
                                mission: mission,
                                type: "emergent",
                            };
                        } else {
                            parameter = {
                                parent_id: <?php echo $user["parent"]; ?>,
                                child_id: <?php echo $user["id"]; ?>,
                                mission: mission,
                                type: "normal",
                            };
                        }

                        fetch("./component/common/post.php", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json"
                                },
                                body: JSON.stringify(parameter),
                            })
                            .then(response => response.json())
                            .then(res => {
                                console.log({
                                    res
                                });
                            })
                            .catch(error => {
                                console.log({
                                    error
                                });
                            });
                    }
                } else {
                    console.log("完了しています");
                }
            <?php endif; ?>

            // url = new URL(window.location.href);
            // if (!url.searchParam.get("mission_operator")) {
            //     url.searchParam.append("mission_operator", "complete");
            //     location.href = url;
            // } else {
            //     console.log(url.searchParams.get("mission_operator"));
            // }
        }
    });
</script>

<!-- グラフタブ切り替え -->
<script>
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

        <?php if ($select === "adult") : ?>
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

            const dataChild = [{
                type: "pie",
                values: <?php echo $json_child_amount; ?>,
                labels: <?php echo $json_child_item; ?>,
                textinfo: "label+percent",
                textposition: "inside",
                automargin: true,
                direction: "clockwise",
                hoverinfo: "skip"
            }]
        <?php endif; ?>

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
            paper_bgcolor: "000",
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
            paper_bgcolor: "000",
            showlegend: false,
        }

        //Plotly.newPlot("表示する要素のid", グラフの情報, グラフのレイアウト)で表示する
        let dataNameList;
        let dataOutputElement;
        <?php if ($select === "adult") : ?>
            dataNameList = [dataSpendingCat, dataIncomeCat, dataCredit, dataQr, dataChild]; //各データ変数を配列に格納
            dataOutputElement = ["graph-1", "graph-2", "graph-3", "graph-4", "graph-5"]; //出力する要素のidを配列に格納
        <?php elseif ($select === "child") : ?>
            dataNameList = [dataSpendingCat, dataIncomeCat]; //各データ変数を配列に格納
            dataOutputElement = ["graph-1", "graph-2"]; //出力する要素のidを配列に格納
        <?php endif; ?>

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
</script>

<!-- トップへ戻るボタン -->
<script>
    $(function() {
        let appear = false;
        const pageTop = $("#page_top");
        $(window).scroll(function() {
            if ($(this).scrollTop() > 300) { //1000pxスクロールしたら
                if (appear == false) {
                    appear = true;
                    pageTop.stop().animate({
                        "bottom": "9rem" //下から3.6remの位置に
                        // "bottom": "3.6rem" //下から3.6remの位置に
                    }, 300); //0.3秒かけて現れる
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