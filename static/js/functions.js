/*=====================================================
支出・収入radioボタンで表示するカテゴリー要素を切り替えるイベント
======================================================*/

if (location.pathname == "KinkiKids/bank/lending_list.php") {
  $(function () {
    let lending = $(".lending > .list-box.hide");
    lending.hide(230);
  });
}

const onRadioChangeType = (number) => {
  typeChecked[number].checked = true; //選択したradioボタンをcheckedとする

  //支出収入の切り替えが複数回行われたときにすでに1度選択した項目を初期値に戻す
  spendingCategory.selectedIndex = 0; //支出カテゴリーを初期値に戻す
  incomeCategory.selectedIndex = 0; //支出カテゴリーを初期値に戻す
  if (select.value == "adult") {
    paymentMethod.selectedIndex = 0; //支払い方法を初期値に戻す
    creditChecked[0].checked = true; //クレジット選択を初期値に戻す
    qrChecked[0].checked = true; //スマホ決済選択を初期値に戻す
  }

  //支出radioボタンが選択されたら
  if (typeChecked[0].checked) {
    if (select.value === "adult") {
      paymentMethodBox.classList.add("show"); //支払い方法div要素にshowクラスを付与で表示
    }
    spendingCategoryBox.classList.add("show"); //支出カテゴリーdiv要素にshowクラスを付与で表示
    incomeCategoryBox.classList.remove("show"); //収入カテゴリーdiv要素にshowクラス付与で非表示

    //収入radioボタンが選択されたら
  } else if (typeChecked[1].checked) {
    spendingCategoryBox.classList.remove("show");
    incomeCategoryBox.classList.add("show");
    if (select.value === "adult") {
      paymentMethodBox.classList.remove("show");
      creditSelectBox.classList.remove("show");
      qrSelectBox.classList.remove("show");
    }
  }
};

/*====================================
クレジットカードorスマホ決済選択時のイベント
=====================================*/
const hasChildSelect = (methodValue, parentElement, checkedItem) => {
  if (paymentMethod.value === methodValue) {
    parentElement.classList.add("show"); //クレジットorスマホ決済選択div要素にshowクラス付与で表示
  } else if (paymentMethod.value !== methodValue) {
    parentElement.classList.remove("show"); //クレジットorスマホ決済選択div要素からshowクラス削除で非表示
    checkedItem[0].checked = true; //選択を初期値に戻す
  }
};

const deleteConfirm = (title, target) => {
  const confirmText = confirm(title + "を本当に削除しますか？");

  const targetRecord = document.getElementById(target);
  if (!confirmText) {
    targetRecord.setAttribute("href", "");
  }
};

if (doneOperateBox !== null) {
  body.classList.add("openedModal");
} else {
  body.classList.remove("openedModal");
}

const onClickOkButton = (param) => {
  const url = new URL(window.location.href); //現在のURLを取得
  url.searchParams.delete("dateOperation"); //パラメータdeleteOperationを削除
  history.pushState("", "", url.pathname + param); //URLパラメータを削除したURL（index.php）に変更
  location.reload(); //画面をリロード
};

const onClickUpdate = (id, name, point = null) => {
  const itemAddElement = document.getElementById("itemAddElement");
  const itemEditElement = document.getElementById("itemEditElement");
  itemAddElement.classList.add("hide");
  itemEditElement.classList.add("show");

  const updateId = document.getElementById("updateId");
  const updateName = document.getElementById("updateName");
  const updatePoint = document.getElementById("updatePoint");
  updateId.value = id;
  updateName.value = name;
  updatePoint.value = point;
};

const logoutConfirm = () => {
  const logoutButton = document.getElementById("logoutButton");
  const confirmText = confirm("ログアウトしますか?");

  if (!confirmText) {
    logoutButton.setAttribute("href", "");
  } else {
    if (navigator.cookieEnabled) {
      document.cookie = "num=0";
    }
  }
};

function showMemo(memo) {
  alert(memo);
}

const onClickCatEdit = (buttonName) => {
  //各カテゴリー、支払い方法、クレジットカードやスマホ決済の編集ボタン<a>を取得（id値は引数に指定）
  const catEditButton = document.getElementById(buttonName);
  //日付inputのvalueを取得
  const date = document.getElementById("date").value;
  //タイトルinputのvalueを取得
  const title = document.getElementById("title").value;
  //金額inputのvalueを取得
  const amount = document.getElementById("amount").value;
  //支出or収入のradioボタンの選択状態を取得
  const type = typeChecked.value;
  //支出カテゴリーselectのvalueを取得
  const spendingCatValue = spendingCategory.value;
  //支払い方法selectのvalueを取得
  const paymentMethodValue = paymentMethod.value;
  //押下された編集ボタン<a>のhref属性（遷移先）を取得
  const oldHref = catEditButton.getAttribute("href");
  //編集ボタンのhrefに追加するリンク（パラメータ）を変数に格納
  const addHref =
    "&date=" +
    date +
    "&title=" +
    title +
    "&amount=" +
    amount +
    "&type=" +
    type +
    "&spendingCat=" +
    spendingCatValue +
    "&paymentMethod=" +
    paymentMethodValue;
  //該当編集ボタンのhrefをパラメータ付きに書き換え
  catEditButton.setAttribute("href", oldHref + addHref);
};

if (detailModalBox != null) {
  body.classList.add("openedModal");
} else {
  body.classList.remove("openedModal");
}

const onChangeMonth = (param, button) => {
  const monthInput = document.getElementById(button);
  const url = new URL(window.location.href);
  if (url.searchParams.has("page_id")) {
    url.searchParams.delete("page_id");
  }
  url.searchParams.set(param, monthInput.value);
  history.pushState("", "", url);
  location.reload();
};

const onClickDetailModalAdd = () => {
  if (navigator.cookieEnabled) {
    // document.cookie = "switch_num=;max-age=0";
    document.cookie = "switch_num=1";
    document.cookie = "num=4";
  }

  const url = new URL(window.location.href);
  url.searchParams.delete("detail");
  history.pushState("", "", url);
  location.reload();
};

function onClickDataBanner(date) {
  $("#date" + date).toggleClass("is-active");
  $("#item" + date).slideToggle(230);
}

function onClickDebtBanner(date, id) {
  // let lending = $(".lending > .list-box.hide");
  let key = id + "-" + date;

  let list = $("#debtDate" + key);
  let item = $("#debtItem" + key);
  if (item.hasClass("hidden")) {
    list.addClass("is-active");
    // item.addClass("hidden");
  } else {
    list.removeClass("is-active");
    // item.removeClass("hidden");
  }
  // key.hide(230);

  $(item).toggleClass("hidden");
}

const onChangeListView = () => {
  if ((groupView !== null || allView !== null) && toggleStyle.checked) {
    groupView.classList.remove("hide");
    allView.classList.add("hide");
    document.cookie = "dataView=group";
  } else if ((groupView !== null || allView !== null) && !toggleStyle.checked) {
    groupView.classList.add("hide");
    allView.classList.remove("hide");
    document.cookie = "dataView=all";
  } else {
    return;
  }
};

const onChangeHousehold_book = () => {
  if ((input_data !== null || calendar !== null) && !household.checked) {
    input_data.classList.remove("hide");
    calendar.classList.add("hide");
    document.cookie = "household=input";
  } else if ((input_data !== null || calendar !== null) && household.checked) {
    input_data.classList.add("hide");
    calendar.classList.remove("hide");
    document.cookie = "household=calendar";
  } else {
    return;
  }
};

const onChangeTitle = (titleValue) => {
  const title = document.getElementById("title");
  title.value = titleValue;
};

const onChangeInputDate = (change) => {
  //日付inputを取得
  const inputDate = document.getElementById("date");
  //日付inputにセットされているvalueを取得し日付オブジェクト生成
  const baseDate = new Date(inputDate.value);

  if (change === "past") {
    //引数がpast(＜ ボタン)なら
    //日付inputにセットされている日付オブジェクトを1日過去にする
    baseDate.setDate(baseDate.getDate() - 1);
  } else if (change === "future") {
    //引数がfuture(＞ ボタン)なら
    //日付inputにセットされている日付オブジェクトを1日未来にする
    baseDate.setDate(baseDate.getDate() + 1);
  }

  //if文で計算された日付で再度日付オブジェクトを生成
  const changeDate = new Date(baseDate);

  let month, date; //日付inputを書き換える月と日の変数定義

  if (changeDate.getMonth() < 9) {
    //計算された日付の月部分が一桁（1〜9月）の場合
    //月部分の頭に「0」を入れる
    month = "0" + (changeDate.getMonth() + 1);
  } else {
    //それ以外(10月〜12月)
    //計算された日付の月部分をそのまま代入
    month = changeDate.getMonth() + 1;
  }

  if (changeDate.getDate().toString().length === 1) {
    //計算された日付の日部分が一桁（1〜9日）の場合
    //日部分の頭に「0」を入れる
    date = "0" + changeDate.getDate();
  } else {
    //それ以外(10日〜31日)
    //計算された日付の日部分をそのまま代入
    date = changeDate.getDate();
  }

  //書き換える日付を文字列連結で生成
  const changeDateValue = changeDate.getFullYear() + "-" + month + "-" + date; //日付inputのvalueに上記で生成した日付を挿入し書き換え
  inputDate.value = changeDateValue;
};

const onRemoveSearchModal = () => {
  window.history.replaceState(null, "", location.pathname + location.search);
};

const onToggleNavigation = () => {
  const hamburgerButton = document.getElementById("hamburgerButton");
  const navigation = document.getElementById("navigation");
  if (hamburgerButton.classList.contains("is-open")) {
    //閉じるボタンクリック時
    hamburgerButton.classList.remove("is-open");
    navigation.classList.remove("is-open");
    body.classList.remove("is-fixed");
    hamburgerButton.classList.add("is-close");
    navigation.classList.add("is-close");
  } else {
    //開くボタンクリック時
    hamburgerButton.classList.remove("is-close");
    navigation.classList.remove("is-close");
    hamburgerButton.classList.add("is-open");
    navigation.classList.add("is-open");
    body.classList.add("is-fixed");
  }
};

const onClickMissionButton = (param) => {
  const url = new URL(window.location.href); //現在のURLを取得
  url.searchParams.delete("mission_operator"); //パラメータdeleteOperationを削除
  history.pushState("", "", url.pathname + param); //URLパラメータを削除したURL（index.php）に変更
  location.reload(); //画面をリロード
};

const tradeMoney = () => {
  const rate = $("#trade_rate");
  const money = $("#trade_money");
  const point = $(".trade_point");
  const point_hide = $("#trade_point");
  const trade = $("#trade");
  let use_point = Math.floor(Number(money.val()) * Number(rate.val()));

  point_hide.val(use_point);
  point.text(use_point + "pt");

  if (money.val() < 10) {
    trade.prop("disabled", true);
  } else {
    trade.prop("disabled", false);
  }
};

const repayment_money = () => {
  const amount = Number($("#amount").val());
  const division = Number($("#division").val());
  const repayment = $(".repayment_money");
  const repayment_hide = $("#repayment_once");
  const interest = Number($("#interest").val());
  const total_amount = $(".total_amount");
  const total_amount_hide = $("#total_amount");
  let total = Math.floor(amount * interest);
  let once = Math.round(total / division);

  repayment.text(once);
  repayment_hide.val(once);
  total_amount.text(total);
  total_amount_hide.val(total);
};

if (
  location.pathname !== "/KinkiKids/record-edit.php" &&
  location.pathname !== "/KinkiKids/account.php"
) {
  $(".bought_check").change(function () {
    let errand_id = $(".bought_check").index(this);
    let errand_sum = $("#bought_sum");
    let surplus = $("#surplus");
    let snack = $("#snack");
    let price = $(".errand").eq(errand_id);

    if ($(".bought_check").eq(errand_id).prop("checked") == true) {
      if ($(".bought_check:last").prop("checked") == true) {
        errand_sum.text(Number(errand_sum.text()) + Number(snack.val()));
      }
      if ($(".bought_check").eq(errand_id).prop("checked") == true) {
        errand_sum.text(Number(errand_sum.text()) + Number(price.val()));
        surplus.text(Number(surplus.text()) - Number(price.val()));
      }
      price.prop("disabled", true);
    } else {
      if ($(".bought_check:last").prop("checked") == false) {
        errand_sum.text(Number(errand_sum.text()) - Number(snack.val()));
      }
      if ($(".bought_check").eq(errand_id).prop("checked") == false) {
        errand_sum.text(Number(errand_sum.text()) - Number(price.val()));
        surplus.text(Number(surplus.text()) + Number(price.val()));
      }
      price.prop("disabled", false);
    }
  });
}
