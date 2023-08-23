const Validate = (obj) => {
  obj.rules.forEach((e) => {
    console.log(e["status"]);
    if (!e["status"]) {
      Object.assign(document.querySelector(e.selector).style, {
        borderColor: "red",
      });
    } else {
      Object.assign(document.querySelector(e.selector).style, {
        borderColor: "gray",
      });
    }
  });

  return obj.rules.every((e) => e["status"]);
};

const isRequired = (selector) => {
  const element = document.querySelector(selector);

  return {
    selector,
    status: element.value.trim() !== "",
  };
};

const isUsername = (selector) => {
  const element = document.querySelector(selector);

  var regex_username = /^[a-zA-Z][a-zA-Z0-9_]{2,19}$/;

  return {
    selector,
    status: regex_username.test(element.value),
  };
};

const isPassword = (selector) => {
  const element = document.querySelector(selector);

  var regex_password =
    /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()-_+=])[A-Za-z\d!@#$%^&*()-_+=]{8,}$/;

  return {
    selector,
    status: regex_password.test(element.value),
  };
};

const confirmPassword = (selector_first, selector_second) => {
  const element_first = document.querySelector(selector_first);
  const element_second = document.querySelector(selector_second);

  return {
    selector: selector_second,
    status: isRequired(selector_second)["status"]
      ? element_first.value === element_second.value
      : false,
  };
};
