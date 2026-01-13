function validateForm() {

  // values
  var username = document.getElementById("username").value.trim();
  var password = document.getElementById("password").value.trim();
  var email    = document.getElementById("email").value.trim();
  var contact  = document.getElementById("contact").value.trim();

  // error fields
  document.getElementById("usernameError").innerHTML = "";
  document.getElementById("passwordError").innerHTML = "";
  document.getElementById("emailError").innerHTML    = "";
  document.getElementById("contactError").innerHTML  = "";

  // ✅ Patterns
  // username: at least 3 letters, numbers allowed or not allowed (both ok)
 var usernamePattern = /^[A-Za-z][A-Za-z0-9]{2,}$/;
  
  // password: min 6 chars, at least one number & one symbol
  var passwordPattern = /^(?=.*[0-9])(?=.*[@#\$%\^&\*])[A-Za-z0-9@#\$%\^&\*]{6,}$/;

  // email: normal email format
  var emailPattern = /^[A-Za-z0-9._%+-]+@[A-Za-z]+\.[A-Za-z]{2,}$/;

  // phone: starts with 97 or 98, total 10 digits
  var phonePattern = /^(97|98)[0-9]{8}$/;

  // 🔴 Username validation
  if (!usernamePattern.test(username)) {
    document.getElementById("usernameError").innerHTML =
      "Username must be at least 3 characters (letters/numbers allowed)";
    return false;
  }

  // 🔴 Password validation
  if (!passwordPattern.test(password)) {
    document.getElementById("passwordError").innerHTML =
      "Password must be at least 6 characters and include a number & a symbol";
    return false;
  }

  // 🔴 Email validation
  if (!emailPattern.test(email)) {
    document.getElementById("emailError").innerHTML =
      "Please enter a valid email address";
    return false;
  }

  // 🔴 Phone validation
  if (!phonePattern.test(contact)) {
    document.getElementById("contactError").innerHTML =
      "Phone must start with 97 or 98 and be 10 digits";
    return false;
  }

  // ✅ All good
  return true;
}
