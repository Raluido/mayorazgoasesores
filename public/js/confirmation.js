function confirmation() {
    let result =
        "Estás seguro de eliminar todas las empresas?";
    if (confirm(result) == true) {
        document.getElementById("confirmationBtn").click();
    }
}
