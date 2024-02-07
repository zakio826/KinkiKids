/*=====================================================
支出・収入radioボタンで表示するカテゴリー要素を切り替えるイベント
======================================================*/
const onRadioChangeType = (number) => {
  typeChecked[number].checked = true; // 選択したradioボタンをcheckedとする

  // 支出収入の切り替えが複数回行われたときにすでに1度選択した項目を初期値に戻す
  spendingCategory.selectedIndex = 0; // 支出カテゴリーを初期値に戻す
  incomeCategory.selectedIndex = 0; // 支出カテゴリーを初期値に戻す

  // 支出radioボタンが選択されたら
  if (typeChecked[0].checked) {
    paymentMethodBox.classList.add("show"); // 支払い方法div要素にshowクラスを付与で表示
    spendingCategoryBox.classList.add("show"); // 支出カテゴリーdiv要素にshowクラスを付与で表示
    incomeCategoryBox.classList.remove("show"); // 収入カテゴリーdiv要素にshowクラス付与で非表示

  // 収入radioボタンが選択されたら
  } else if (typeChecked[1].checked) {
    paymentMethodBox.classList.remove("show");
    spendingCategoryBox.classList.remove("show");
    incomeCategoryBox.classList.add("show");
  }
}

/*====================================
クレジットカードorスマホ決済選択時のイベント
=====================================*/
const hasChildSelect = (methodValue, parentElement) => {
  if (paymentMethod.value === methodValue) {
    parentElement.classList.add("show"); // クレジットorスマホ決済選択div要素にshowクラス付与で表示
  } else {
    parentElement.classList.remove("show"); // クレジットorスマホ決済選択div要素からshowクラス削除で非表示
  }
}
