

// avant l'ajout :  cryptage de password avnt envoyer à le server

let encryptionKey = "mySecretKey12345";
document.getElementById("passwordForm").addEventListener("submit", function(event) {
    event.preventDefault();

    let passwordField = document.getElementById("password");
    let encryptedPasswordField = document.getElementById("encryptedPassword");

    let encryptedPassword = CryptoJS.AES.encrypt(passwordField.value, encryptionKey).toString();

    encryptedPasswordField.value = encryptedPassword;

    passwordField.value = "";

    this.submit();
});



 // avant la modification :  cryptage de password avnt envoyer à le server
 document.getElementById("updatePasswordForm").addEventListener("submit", function(event) {
    event.preventDefault();

    let passwordUpdateField = document.getElementById("newPassword1");
    let encryptedPasswordUpdateField = document.getElementById("encryptedPasswordUpdate");

    let encryptedPassword = CryptoJS.AES.encrypt(passwordUpdateField.value, encryptionKey).toString();

    encryptedPasswordUpdateField.value = encryptedPassword;

    passwordUpdateField.value = "";

    this.submit();
});



