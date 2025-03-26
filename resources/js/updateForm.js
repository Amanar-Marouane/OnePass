   // edit form : les données des inputs
   document.addEventListener("DOMContentLoaded", function() {
    const modal = document.getElementById("modal");
    const backdrop = document.getElementById("modal-backdrop");
    const closeButton = modal.querySelector(".fas.fa-times").parentElement;
    const editButtons = document.querySelectorAll(".edit-btn");


    const idInput = modal.querySelector(".password_id");
    const usernameInput = modal.querySelector(".username");
    const serviceInput = modal.querySelector(".service");
    const passwordInput = modal.querySelector(".password");

    function openModal(password) {
        console.log("Modifier le password avec l'ID :", password.id);
        modal.classList.remove("hidden");
        backdrop.classList.remove("hidden");


        idInput.value = password.id;
        usernameInput.value = password.username;
        serviceInput.value = password.service;


        let bytes = CryptoJS.AES.decrypt(password.password, encryptionKey);
        let decryptedPassword = bytes.toString(CryptoJS.enc.Utf8);
        passwordInput.value = decryptedPassword;
    }

    function closeModal() {
        modal.classList.add("hidden");
        backdrop.classList.add("hidden");
    }

    editButtons.forEach(button => {
        button.addEventListener("click", function() {
            const password = {
                id: this.getAttribute("data-id"),
                username: this.getAttribute("data-username"),
                service: this.getAttribute("data-service"),
                password: this.getAttribute("data-password"),
            };

            openModal(password);
        });
    });

    closeButton.addEventListener("click", closeModal);
    backdrop.addEventListener("click", closeModal);
});




// *******
