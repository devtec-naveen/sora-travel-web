function showPassword() {
    const passwordField = document.getElementById("password");
    const icon = document.querySelector(".show-password i");

    if (passwordField.type === "password") {
        passwordField.type = "text";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    } else {
        passwordField.type = "password";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    }
}

function logout(event) {
    event.preventDefault();
    Swal.fire({
        title: "Are you sure?",
        text: "You will be logged out!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Logout!",
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById("logout-form").submit();
        }
    });
    return false;
}

function confirmDelete(id) {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            Livewire.dispatch("deleteConfirmed", { id: id });
        }
    });
}

function confirmStatusChange(id) {
    Swal.fire({
        title: "Change status?",
        text: "Status will be updated.",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Yes, update it!",
    }).then((result) => {
        if (result.isConfirmed) {
            Livewire.dispatch("changeStatus", { id: id });
        }
    });
}

function showImagePreview(imagePath) {
    document.getElementById("previewImage").src = imagePath;
    $("#imagePreviewModal").modal("show");
}
