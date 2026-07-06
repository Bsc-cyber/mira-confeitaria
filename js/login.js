document.addEventListener("DOMContentLoaded", function () {
    const formulario = document.getElementById("formLogin");
    formulario.addEventListener("submit", function (evento) {
        const campoUsuario = document.getElementById("usuario").value.trim();
        const campoSenha = document.getElementById("senha").value.trim();

        if (campoUsuario === "" || campoSenha === "") {
            evento.preventDefault();
            alert("Por favor, preencha todos os campos obrigatórios!");
        }
    });
});
