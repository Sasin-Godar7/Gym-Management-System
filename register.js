function validateForm() {
    // 1️ Get form values
    var username = document.getElementById("username").value.trim();
    var password = document.getElementById("password").value.trim();
    var email    = document.getElementById("email").value.trim();
    var contact  = document.getElementById("contact").value.trim();

    // 2️ Clear previous errors
    document.getElementById("usernameError").innerHTML = "";
    document.getElementById("passwordError").innerHTML = "";
    document.getElementById("emailError").innerHTML    = "";
    document.getElementById("contactError").innerHTML  = "";

    // 3️ Patterns
   var usernamePattern = /^[A-Za-z][A-Za-z0-9 ]{2,}$/;
    var passwordPattern = /^(?=.*[0-9])(?=.*[@#\$%\^&\*])[A-Za-z0-9@#\$%\^&\*]{6,}$/;
    var emailPattern    = /^[A-Za-z0-9._%+-]+@[A-Za-z]+\.[A-Za-z]{2,}$/;
    var phonePattern    = /^(97|98)[0-9]{8}$/;

    // 4 Validation
    if (!usernamePattern.test(username)) {
        document.getElementById("usernameError").innerHTML =
            "Username must start with a letter, numbers optional, min 3 characters";
        return false;
    }

    if (!passwordPattern.test(password)) {
        document.getElementById("passwordError").innerHTML =
            "Password must be at least 6 characters and include a number & a symbol";
        return false;
    }

    if (!emailPattern.test(email)) {
        document.getElementById("emailError").innerHTML =
            "Please enter a valid email address";
        return false;
    }

    if (!phonePattern.test(contact)) {
        document.getElementById("contactError").innerHTML =
            "Phone must start with 97 or 98 and be 10 digits";
        return false;
    }

    //  All good
    return true;
}
