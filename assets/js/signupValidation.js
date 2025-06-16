document.addEventListener("DOMContentLoaded", function () {
  const firstName = document.getElementById("first_name");
  const lastName = document.getElementById("last_name");
  const email = document.getElementById("email");
  const password = document.getElementById("password");
  const confirmPassword = document.getElementById("confirm_password");
  const dob = document.getElementById("dob");
  const phone = document.getElementById("phone");
  const countryCode = document.getElementById("country_code");

  const firstNameError = document.getElementById("first_name_error");
  const lastNameError = document.getElementById("last_name_error");
  const emailError = document.getElementById("email_error");
  const passwordError = document.getElementById("password_error");
  const confirmPasswordError = document.getElementById("confirm_password_error");
  const dobError = document.getElementById("dobError");
  const phoneError = document.getElementById("phone_error");

  const reqLength = document.getElementById("length-check");
  const reqUpper = document.getElementById("uppercase-check");
  const reqNumber = document.getElementById("number-check");
  const reqSpecial = document.getElementById("special-check");

  // Track if user touched fields
  let touched = {
    firstName: false,
    lastName: false,
    email: false,
    password: false,
    confirmPassword: false,
    dob: false,
    phone: false,
  };

  // Utility: set error message and input class
  function setError(input, errorDiv, message) {
    if (message) {
      errorDiv.textContent = message;
      input.classList.add("invalid");
      input.classList.remove("valid");
    } else {
      errorDiv.textContent = "";
      input.classList.remove("invalid");
      input.classList.add("valid");
    }
  }

  // First Name Validation
  function validateFirstName() {
    const regex = /^[A-Za-z]+$/;
    if (firstName.value.trim() === "") {
      setError(firstName, firstNameError, "First name is required.");
    } else if (!regex.test(firstName.value)) {
      setError(firstName, firstNameError, "Only alphabet letters allowed, no spaces.");
    } else {
      setError(firstName, firstNameError, "");
    }
  }

  // Last Name Validation
  function validateLastName() {
    const regex = /^[A-Za-z]+$/;
    if (lastName.value.trim() === "") {
      setError(lastName, lastNameError, "Last name is required.");
    } else if (!regex.test(lastName.value)) {
      setError(lastName, lastNameError, "Only alphabet letters allowed, no spaces.");
    } else {
      setError(lastName, lastNameError, "");
    }
  }

  // Email Validation
  function validateEmail() {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (email.value.trim() === "") {
      setError(email, emailError, "Email is required.");
    } else if (!regex.test(email.value)) {
      setError(email, emailError, "Invalid email format.");
    } else {
      setError(email, emailError, "");
    }
  }

  function validatePassword() {
  const val = password.value;

  // Show password checklist on first interaction
  const reqContainer = document.getElementById("password-reqs");
  if (val.length > 0) {
    reqContainer.classList.remove("hidden");
  }

  let hasError = false;

if (val.length >= 8) {
  reqLength.classList.add("valid");
  reqLength.classList.remove("invalid");
  reqLength.textContent = "✅ Minimum 8 characters";
} else {
  reqLength.classList.add("invalid");
  reqLength.classList.remove("valid");
  reqLength.textContent = "✖ Minimum 8 characters";
  hasError = true;
}

if (/[A-Z]/.test(val)) {
  reqUpper.classList.add("valid");
  reqUpper.classList.remove("invalid");
  reqUpper.textContent = "✅ At least one uppercase letter";
} else {
  reqUpper.classList.add("invalid");
  reqUpper.classList.remove("valid");
  reqUpper.textContent = "✖ At least one uppercase letter";
  hasError = true;
}

if (/[0-9]/.test(val)) {
  reqNumber.classList.add("valid");
  reqNumber.classList.remove("invalid");
  reqNumber.textContent = "✅ At least one number";
} else {
  reqNumber.classList.add("invalid");
  reqNumber.classList.remove("valid");
  reqNumber.textContent = "✖ At least one number";
  hasError = true;
}

if (/[!@#\$%\^&\*]/.test(val)) {
  reqSpecial.classList.add("valid");
  reqSpecial.classList.remove("invalid");
  reqSpecial.textContent = "✅ At least one special character";
} else {
  reqSpecial.classList.add("invalid");
  reqSpecial.classList.remove("valid");
  reqSpecial.textContent = "✖ At least one special character";
  hasError = true;
}


  if (val.trim() === "") {
    setError(password, passwordError, "Password is required.");
    hasError = true;
  } else if (hasError) {
    setError(password, passwordError, "Password does not meet the requirements.");
  } else {
    setError(password, passwordError, "");
  }
}


  // Confirm Password Validation
  function validateConfirmPassword() {
    if (confirmPassword.value.trim() === "") {
      setError(confirmPassword, confirmPasswordError, "Please re-enter your password.");
    } else if (confirmPassword.value !== password.value) {
      setError(confirmPassword, confirmPasswordError, "Passwords do not match.");
    } else {
      setError(confirmPassword, confirmPasswordError, "");
    }
  }

  // DOB Validation (18+ check)
  function validateDOB() {
    if (dob.value === "") {
      setError(dob, dobError, "Date of birth is required.");
      return;
    }
    const birthDate = new Date(dob.value);
    const today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    const m = today.getMonth() - birthDate.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
      age--;
    }
    if (age < 18) {
      setError(dob, dobError, "You must be at least 18 years old.");
    } else {
      setError(dob, dobError, "");
    }
  }

  // Phone Validation based on country code
  function validatePhone() {
    const selected = countryCode.value;
    const value = phone.value.replace(/\D/g, "");
    phone.value = value; // sanitize input to digits only

    // Required length per country code
    let requiredLength = 10;
    switch (selected) {
      case "+94": // Sri Lanka
        requiredLength = 9;  // Actually SL numbers are 9 digits after country code, but your original had 10; adjust if needed
        break;
      case "+91": // India
        requiredLength = 10;
        break;
      case "+1": // USA
        requiredLength = 10;
        break;
      case "+44": // UK
        requiredLength = 10;
        break;
      default:
        requiredLength = 10;
    }

    if (value.length === 0) {
      setError(phone, phoneError, "Phone number is required.");
    } else if (!/^[0-9]+$/.test(value)) {
      setError(phone, phoneError, "Phone number must contain only digits.");
    } else if (value.length !== requiredLength) {
      setError(phone, phoneError, `Phone number must be exactly ${requiredLength} digits.`);
    } else {
      setError(phone, phoneError, "");
    }
  }

  // Add event listeners with "touched" flag and validation on input & blur

  // Generic helper for input validation with touched flag
  function setupValidation(input, validateFn, touchedKey) {
    input.addEventListener("focus", () => {
      touched[touchedKey] = true;
    });
    input.addEventListener("input", () => {
      if (touched[touchedKey]) validateFn();
    });
    input.addEventListener("blur", () => {
      if (touched[touchedKey]) validateFn();
    });
  }

  setupValidation(firstName, validateFirstName, "firstName");
  setupValidation(lastName, validateLastName, "lastName");
  setupValidation(email, validateEmail, "email");
  setupValidation(password, validatePassword, "password");
  setupValidation(confirmPassword, validateConfirmPassword, "confirmPassword");
  setupValidation(dob, validateDOB, "dob");
  setupValidation(phone, validatePhone, "phone");

  // Validate country code change too (phone depends on it)
  countryCode.addEventListener("change", () => {
    if (touched.phone) validatePhone();
  });

  // Optional: On form submit validate all fields regardless of touched flags
  const form = document.querySelector(".signup-form");
  form.addEventListener("submit", (e) => {
    // Mark all fields as touched to force show errors if any
    Object.keys(touched).forEach(key => (touched[key] = true));

    // Run all validations
    validateFirstName();
    validateLastName();
    validateEmail();
    validatePassword();
    validateConfirmPassword();
    validateDOB();
    validatePhone();

    // Prevent submit if any errors present
    const errorsPresent = [
      firstNameError.textContent,
      lastNameError.textContent,
      emailError.textContent,
      passwordError.textContent,
      confirmPasswordError.textContent,
      dobError.textContent,
      phoneError.textContent,
    ].some(msg => msg !== "");

    if (errorsPresent) {
      e.preventDefault();
    }
  });
});
