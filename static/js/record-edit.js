if (select.value == "adult") {
  if (typeChecked[0].checked) {
    spendingCategoryBox.classList.add("show");
    paymentMethodBox.classList.add("show");
  } else if (typeChecked[1].checked) {
    incomeCategoryBox.classList.add("show");
  }

  const paymentMethodValue = paymentMethod.value;
  if (paymentMethodValue === "2") {
    creditSelectBox.classList.add("show");
  } else if (paymentMethodValue === "3") {
    qrSelectBox.classList.add("show");
  }
}
