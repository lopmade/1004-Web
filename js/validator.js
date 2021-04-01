function passwordValidator(){
    var myInput = document.getElementById("password");
    var letter = document.getElementById("letter");
    var capital = document.getElementById("capital");
    var number = document.getElementById("number");
    var specialchar = document.getElementById("specialchar");
    var length = document.getElementById("length");

    // When the user clicks on the password field, show the message box
    myInput.onfocus = function() {
      document.getElementById("message").style.display = "block";
    }

    // When the user clicks outside of the password field, hide the message box
    myInput.onblur = function() {
      document.getElementById("message").style.display = "none";
    }

    // When the user starts to type something inside the password field
    myInput.onkeyup = function() {
      // Validate lowercase letters
      var lowerCaseLetters = /[a-z]/g;
      if(myInput.value.match(lowerCaseLetters)) {
        letter.classList.remove("invalid");
        letter.classList.add("valid");
      } else {
        letter.classList.remove("valid");
        letter.classList.add("invalid");
    }

      // Validate capital letters
      var upperCaseLetters = /[A-Z]/g;
      if(myInput.value.match(upperCaseLetters)) {
        capital.classList.remove("invalid");
        capital.classList.add("valid");
      } else {
        capital.classList.remove("valid");
        capital.classList.add("invalid");
      }

      // Validate numbers
      var numbers = /[0-9]/g;
      if(myInput.value.match(numbers)) {
        number.classList.remove("invalid");
        number.classList.add("valid");
      } else {
        number.classList.remove("valid");
        number.classList.add("invalid");
      }

      // Validate length
      if(myInput.value.length >= 8) {
        length.classList.remove("invalid");
        length.classList.add("valid");
      } else {
        length.classList.remove("valid");
        length.classList.add("invalid");
      }

      // Validate special characters
      var specialchars = /[^\w]/g;
      if(myInput.value.match(specialchars)) {
        specialchar.classList.remove("invalid");
        specialchar.classList.add("valid");
      } else {
        specialchar.classList.remove("valid");
        specialchar.classList.add("invalid");
      }
    }
}

function usernameValidator(){
    var myInput1 = document.getElementById("username");
    var length1 = document.getElementById("length1");

    // When the user clicks on the password field, show the message box
    myInput1.onfocus = function() {
      document.getElementById("message1").style.display = "block";
    }

    // When the user clicks outside of the password field, hide the message box
    myInput1.onblur = function() {
      document.getElementById("message1").style.display = "none";
    }

    // When the user starts to type something inside the password field
    myInput1.onkeyup = function() {

      // Validate length
      if(myInput1.value.length <= 20 && myInput1.value.length > 0) {
        length1.classList.remove("invalid");
        length1.classList.add("valid");
      } else {
        length1.classList.remove("valid");
        length1.classList.add("invalid");
      }

    }
}
passwordValidator()
usernameValidator()