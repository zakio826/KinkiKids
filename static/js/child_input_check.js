let child_nameClear = 0,
  childPasswordClear = 0,
  childPasswordConfirmClear = 0;

if (typeof clearSum === "undefined"){
  let clearSum = 0;
}
if (typeof clearVal === "undefined"){
  let clearVal = 0;
}
if (typeof paramMode == "undefined"){
  const paramMode = new URL(window.location.href).searchParams.get("mode");
}

// const cls_child_name = document.getElementsByClassName("child_name");
// const cls_child_nameCheck = document.getElementsByClassName("child_nameCheck");

const childSubmitButton = document.getElementById("childSubmitButton");

const childCheckLength = (checkVal, checkElement, min) => {
  if (checkVal.value.length < min) {
    checkElement.classList.add("short");
    return min + "文字以上入力してください";
  } else {
    checkElement.classList.remove("short");
    return "";
  }
};

const childCheckStrength = (checkVal, checkElement) => {
  let strength = 1;

  if (checkVal.value.length < 4) {
    checkElement.classList.remove("weak", "normal", "strong");
    return ["パスワードは4文字で入力してください", strength];
  } else if (checkVal.value.length > 4) {
    return ["パスワードは4文字で入力してください", strength];
  } else {
    return ["", strength];
  }
};

const child_nameChange = (num) => {
  const child_name = document.getElementById("child_name" + num);
  const child_nameCheck = document.getElementById("child_nameCheck" + num);

  child_nameCheck.innerHTML = childCheckLength(child_name, child_nameCheck, 1);
  if (child_name.value.length >= 1) child_nameClear = 1;
  else child_nameClear = 0;
};

const childPassChange = (page, ...num) => {
  const childPassword = document.getElementById("childPassword" + num);
  const childPassCheck = document.getElementById("childPassCheck" + num);

  if (page === "join") {
    childPassCheck.innerHTML = childCheckStrength(childPassword, childPassCheck)[0];
    if (
      childCheckStrength(childPassword, childPassCheck)[1] >= 1 &&
      childPassword.value.length >= 4
    )
      childPasswordClear = 1;
    else childPasswordClear = 0;
  } else if (page === "login") {
    childPassCheck.innerHTML = checkLength(childPassword, childPassCheck, 4);
    if (childPassword.value.length == 4) childPasswordClear = 1;
    else childPasswordClear = 0;
  }
};

const childPassConfirmChange = (num) => {
  const childPasswordConfirm = document.getElementById("childPasswordConfirm" + num);

  if (childPasswordConfirm.value.length > 0) childPasswordConfirmClear = 1;
  else childPasswordConfirmClear = 0;
};

const childInputCheck = (page) => {
  if (page === "join") {
    clearSum = child_nameClear + childPasswordClear + childPasswordConfirmClear;
    clearVal = 3;
  } else if (page === "login") {
    clearSum = child_nameClear + childPasswordClear;
    clearVal = 2;
  } else if (page === "resetAuth") {
    clearSum = child_nameClear + emailClear;
    clearVal = 2;
  }

  if (page === "login"){
    if (clearSum === clearVal) submitButton.disabled = false;
    else submitButton.disabled = true;
  } else {
    if (clearSum === clearVal) childSubmitButton.disabled = false;
    else childSubmitButton.disabled = true;
  }
};

if (
  (typeof paramMode !== "undefined" && paramMode === "modify") ||
  typeof childUsernameExist !== "undefined" ||
  typeof childSameStr !== "undefined" ||
  typeof childPasswordMatch !== "undefined"
) {
  child_nameClear = 1;
  childPasswordClear = 1;
  childPasswordConfirmClear = 1;
}

const childCount = () => {
  const children = document.getElementById("children");
  console.log(children);
}

const birthdayChange = (num) => {
  const birthday_id = document.getElementById("birthday" + num);
  const age_id = document.getElementById("age" + num);

  const split = birthday_id.value.split("-");
  const today = new Date();
  const birth = new Date(split[0], split[1] - 1, split[2]);
  const this_year_birth = new Date(today.getFullYear(), split[1] - 1, split[2]);
  let age = today.getFullYear() - birth.getFullYear();

  if (today < this_year_birth) {
    age--;
  }

  age_id.value = age;
};