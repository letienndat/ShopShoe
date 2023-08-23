window.onpageshow = function (event) {
  // Kiểm tra nếu trạng thái của navigation là "back" (quay về trang trước)
  if (event.persisted) {
    // Reload trang
    location.reload();
  }
};
