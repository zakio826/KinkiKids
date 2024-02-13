/*============
チェック変数定義
=============*/
let nicknameClear = 0, //ニックネームチェッククリア(1が代入されるとクリア)
  usernameClear = 0, //ユーザーネームチェッククリア(上記同様)
  passwordClear = 0, //パスワードチェッククリア(上記同様)
  passwordConfirmClear = 0, //パスワード確認チェッククリア(上記同様)
  emailClear = 0,
  clearSum = 0, //クリアした項目数の合計
  clearVal = 0; //必要なクリア数

/*===============
チェック要素定数定義
================*/
//URLのGETパラメータ「mode」を取得(修正するボタン押下時使用)
const paramMode = new URL(window.location.href).searchParams.get("mode");
//各チェックinput要素取得
const input_check = document.getElementsByClassName("js-check");
//input要素下、メッセージ表示p要素取得
//送信ボタン要素取得
const submitButton = document.getElementById("submitButton");

//引数(チェックするinput要素, メッセージ表示p要素, 指定文字数)
const checkLength = (checkVal, checkElement, min) => {
  if (checkVal.value.length < min) {
    //inputの値が指定文字数(min)未満の場合
    //メッセージ表示要素にshortクラス追加
    checkElement.classList.add("short");
    //メッセージ本文を返す
    return min + "文字以上入力してください";
  } else {
    //inputの値が指定文字数(min)以上のとき
    //メッセージ表示要素からshortクラス削除
    checkElement.classList.remove("short");
    //メッセージ本文(空文字)を返す
    return "";
  }
};

//引数(チェックするinput要素, メッセージ表示p要素)
const checkStrength = (checkVal, checkElement) => {
  let strength = 0; //パスワードの強度

  // 英字の大文字と小文字を含んでいれば+1
  if (checkVal.value.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) strength += 1;
  // 英字と数字を含んでいれば+1
  if (checkVal.value.match(/([a-zA-Z])/) && checkVal.value.match(/([0-9])/))
    strength += 1;

  // 点数を元に強さを計測
  if (checkVal.value.length < 6) {
    //inputの値が6文字未満のとき
    //メッセージ表示要素からweak, normal, strongクラスを削除
    checkElement.classList.remove("weak", "normal", "strong");
    //メッセージ本文とパスワード強度の数値を配列形式で返す
    return ["パスワードは6文字以上で入力してください", strength];
  } else if (checkVal.value.length >= 6 && strength < 1) {
    //inputの値が6文字以上かつ強度の数値が1未満のとき
    //メッセージ表示要素からnormal, strongクラスを削除
    checkElement.classList.remove("normal", "strong");
    //メッセージ要素にweakクラスを追加
    checkElement.classList.add("weak");
    //メッセージ本文とパスワード強度の数値を配列形式で返す
    return ["英数字を各1文字以上入れてください", strength];
  } else if (checkVal.value.length >= 6 && strength === 1) {
    //inputの値が6文字以上かつ強度の数値が1のとき
    //メッセージ表示要素からweak, strongクラスを削除
    checkElement.classList.remove("weak", "strong");
    //メッセージ要素にnormalクラスを追加
    checkElement.classList.add("normal");
    //メッセージ本文とパスワード強度の数値を配列形式で返す
    return ["パスワード強度：中", strength];
  } else if (checkVal.value.length >= 6 && strength > 1) {
    //inputの値が6文字以上かつ強度の値が1より大きいとき
    //メッセージ表示要素からweak, normalクラスを削除
    checkElement.classList.remove("weak", "normal");
    //メッセージ要素にstrongクラスを追加
    checkElement.classList.add("strong");
    //メッセージ本文とパスワード強度の数値を配列形式で返す
    return ["パスワード強度：強", strength];
  }
};

//メールアドレス正規表現チェック(パスワード強度チェックの下に追記)
const checkEmail = (checkVal, checkElement) => {
  //正規表現を格納
  const pattern = /^[a-zA-Z0-9_+-]+(\.[a-zA-Z0-9_+-]+)*@([a-zA-Z0-9][a-zA-Z0-9-]*[a-zA-Z0-9]*\.)+[a-zA-Z]{2,}$/;

  //正規表現に一致しているかの結果を格納
  const result = pattern.test(checkVal.value);

  if (!result) {
    //正しくない形式のメールアドレスが入力されている場合
    checkElement.classList.add("incorrect"); //メッセージ要素にincorrectクラス追加
    return ["正しいメールアドレスを入力してください", result]; //メッセージ本文と正規表現の結果を返す
  } else {
    //正しい形式のメールアドレスが入力されている場合
    checkElement.classList.remove("incorrect"); //メッセージ要素からincorrectクラスを削除
    return ["", result]; //メッセージ本文(空文字)と正規表現の結果を返す
  }
};

const nicknameChange = (num) => {
  const nickname = document.getElementById("nickname" + num);
  const nicknameCheck = document.getElementById("nicknameCheck" + num);

  //先ほど作成した長さをチェックする関数を実行返ってきたメッセージをメッセージ要素に挿入
  //引数(ニックネームinput要素, ニックネームinput下メッセージ要素, 最低必要文字数)
  nicknameCheck.innerHTML = checkLength(nickname, nicknameCheck, 1);

  //ニックネームinputの値が6文字以上のときはチェッククリア
  if (nickname.value.length > 0) nicknameClear = 1;
  else
    //6文字未満のときはチェッククリアを0にする
    nicknameClear = 0;
};

const usernameChange = (num) => {
  const username = document.getElementById("username" + num);
  const usernameCheck = document.getElementById("usernameCheck" + num);

  //先ほど作成した長さをチェックする関数を実行し返ってきたメッセージをメッセージ要素に挿入
  //引数(ユーザーネームinput要素, ユーザーネームinput下メッセージ要素, 最低必要文字数)
  usernameCheck.innerHTML = checkLength(username, usernameCheck, 6);
  //ユーザーネームinputの値が6文字以上のときはチェッククリア
  if (username.value.length >= 6) usernameClear = 1;
  else
    //6文字未満のときはチェッククリアを0にする
    usernameClear = 0;
};

const passChange = (page, num) => {
  const password = document.getElementById("password" + num);
  const passCheck = document.getElementById("passCheck" + num);

  if (page === "join") {
    //先ほど作成した強度をチェックする関数を実行し返ってきたメッセージをメッセージ要素に挿入
    //引数(ユーザーネームinput要素, ユーザーネームinput下メッセージ要素)
    //配列で返されるので、末尾に[0]をつける
    passCheck.innerHTML = checkStrength(password, passCheck)[0];
    if (
      //先ほど作成した強度をチェックする関数を実行し帰ってきた強度の数値が1以上かつパスワードinputの値が6文字以上のとき
      checkStrength(password, passCheck)[1] >= 1 &&
      password.value.length >= 6
    )
      //パスワードチェッククリア
      passwordClear = 1;
    else
      //それ以外のときはパスワードチェッククリアを0にする
      passwordClear = 0;
  } else if (page === "login") {
    passCheck.innerHTML = checkLength(password, passCheck, 6);
    if (password.value.length >= 6) passwordClear = 1;
    else passwordClear = 0;
  }
};

const passConfirmChange = (num) => {
  const passwordConfirm = document.getElementById("passwordConfirm" + num);

  //パスワード確認inputの値が1文字以上のときはチェッククリア
  if (passwordConfirm.value.length > 0) passwordConfirmClear = 1;
  else
    //パスワード確認inputの値が1文字未満のときはチェッククリアを０にする
    passwordConfirmClear = 0;
};

const emailChange = (num) => {
  const email = document.getElementById("email" + num);
  const emailCheck = document.getElementById("emailCheck" + num);

  //正規表現チェック関数を実行
  const checkResult = checkEmail(email, emailCheck);
  //正規表現チェック関数から返されたメッセージ本文をメッセージ要素に挿入
  emailCheck.innerHTML = checkResult[0];

  //正規表現チェック関数クリアならメールアドレスチェッククリア
  if (checkResult[1]) emailClear = 1;
  else
    //正規表現チェック関数を通っていない場合はメールアドレスチェックを0にする(未クリア状態)
    emailClear = 0;
};

const inputCheck = (page) => {
  //引数がjoinのとき
  if (page === "join") {
    //チェッククリアの合計を計算(対象項目は4項目)
    clearSum =
      nicknameClear + usernameClear + passwordClear + passwordConfirmClear;
    //チェッククリアに必要な数値(4項目分のため)
    clearVal = 4;
  } else if (page === "login") {
    clearSum = usernameClear + passwordClear;
    clearVal = 2;
  } else if (page === "resetAuth") {
    //パスワード再設定認証ページの場合
    clearSum = usernameClear + emailClear; //入力クリア合計計算し格納
    clearVal = 2; //入力クリア値の２を格納
  }

  //チェッククリアの合計とクリアに必要な数が一致したら送信ボタンから
  //disabled属性を外し押せるようにする
  if (clearSum === clearVal) submitButton.disabled = false;
  else
    //チェッククリアの合計とクリアに必要な数が不一致のときは送信ボタンに
    //disabled属性を追加し無効にする
    submitButton.disabled = true;
};

if (
  paramMode === "modify" || //予め取得していたGETパラメータのmodeがmodifyのときまたは
  typeof usernameExist !== "undefined" || //すでに登録されているユーザー名エラー変数が存在するときまたは
  typeof sameStr !== "undefined" || //ユーザー名とパスワードが同一文字列エラ変数が存在するときまたは
  typeof passwordMatch !== "undefined" //パスワード確認が一致しない場合
) {
  //ニックネーム、ユーザー名、パスワード入力チェックをクリア状態にセット
  nicknameClear = 1;
  usernameClear = 1;
  passwordClear = 1;
}
