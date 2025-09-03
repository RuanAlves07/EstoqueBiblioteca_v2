// Atualiza o campo de quantidade em estoque ao selecionar um livro
document.addEventListener('DOMContentLoaded', function () {
    const selectLivro = document.getElementById('titulo');
    const campoQuantidade = document.getElementById('quantidade_estoque');

    if (selectLivro && campoQuantidade) {
        selectLivro.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const quantidade = selectedOption.getAttribute('data-quantidade');

            campoQuantidade.value = quantidade ? quantidade : '';
        });
    }
});