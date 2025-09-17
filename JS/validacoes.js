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

    // Adiciona máscaras e validações aos campos
    aplicarMascaras();
    adicionarValidacoes();
});

function mostrarSenha() {
    const senhaInput = document.getElementById("senha");
    
    if (senhaInput.type === "password") {
        senhaInput.type = "text";   
    } else {
        senhaInput.type = "password"; 
    }
}

function atualizaEstoque(select) {
    const quantidade = select.selectedOptions[0].getAttribute('data-quantidade');
    document.getElementById('quantidade_estoque').value = quantidade;
}

// Função para aplicar máscaras aos campos
function aplicarMascaras() {
    // Máscara para CPF
    const cpfInputs = document.querySelectorAll('input[name="cpf"], #cpf');
    cpfInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 11) {
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            }
            e.target.value = value;
        });
    });

    // Máscara para CNPJ
    const cnpjInputs = document.querySelectorAll('input[name="cnpj"], #cnpj');
    cnpjInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 14) {
                value = value.replace(/(\d{2})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d)/, '$1/$2');
                value = value.replace(/(\d{4})(\d{1,2})$/, '$1-$2');
            }
            e.target.value = value;
        });
    });

    // Máscara para Telefone
    const telefoneInputs = document.querySelectorAll('input[name="telefone"], #telefone');
    telefoneInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 11) {
                value = value.replace(/(\d{2})(\d)/, '($1) $2');
                value = value.replace(/(\d{5})(\d)/, '$1-$2');
            }
            e.target.value = value;
        });
    });


// Máscara para ISBN
const isbnInputs = document.querySelectorAll('input[name="isbn"], #isbn');
isbnInputs.forEach(input => {
    input.addEventListener('input', function(e) {
        let value = e.target.value.replace(/[^\d\-]/g, '');
        
        // Remove todos os hífens e formata automaticamente
        let numeros = e.target.value.replace(/[^\d]/g, '');
        
        if (numeros.length > 0) {
            // Para o formato: 999-0-3333-2222-1 (3-1-4-4-1 dígitos)
            if (numeros.length <= 3) {
                value = numeros;
            } else if (numeros.length <= 4) {
                value = numeros.substring(0, 3) + '-' + numeros.substring(3);
            } else if (numeros.length <= 8) {
                value = numeros.substring(0, 3) + '-' + numeros.substring(3, 4) + '-' + numeros.substring(4);
            } else if (numeros.length <= 12) {
                value = numeros.substring(0, 3) + '-' + numeros.substring(3, 4) + '-' + 
                       numeros.substring(4, 8) + '-' + numeros.substring(8);
            } else if (numeros.length <= 13) {
                value = numeros.substring(0, 3) + '-' + numeros.substring(3, 4) + '-' + 
                       numeros.substring(4, 8) + '-' + numeros.substring(8, 12) + '-' + 
                       numeros.substring(12);
            } else {
                // Limita a 13 dígitos no total
                numeros = numeros.substring(0, 13);
                value = numeros.substring(0, 3) + '-' + numeros.substring(3, 4) + '-' + 
                       numeros.substring(4, 8) + '-' + numeros.substring(8, 12) + '-' + 
                       numeros.substring(12, 13);
            }
        }
        
        e.target.value = value;
    });
});}

// Função para adicionar validações de formulário
function adicionarValidacoes() {
    // Validação de formulário de cliente
    const formCliente = document.querySelector('form[action="cadastro_cliente.php"]');
    if (formCliente) {
        formCliente.addEventListener('submit', function(e) {
            const cpf = document.getElementById('cpf').value;
            const telefone = document.getElementById('telefone').value;
            
            if (!validarCPF(cpf)) {
                alert('CPF inválido!');
                e.preventDefault();
                return false;
            }
            
            if (!validarTelefone(telefone)) {
                alert('Telefone inválido!');
                e.preventDefault();
                return false;
            }
        });
    }

    // Validação de formulário de fornecedor
    const formFornecedor = document.querySelector('form[action="cadastro_fornecedor.php"]');
    if (formFornecedor) {
        formFornecedor.addEventListener('submit', function(e) {
            const cnpj = document.getElementById('cnpj').value;
            const telefone = document.getElementById('telefone').value;
            
            if (!validarCNPJ(cnpj)) {
                alert('CNPJ inválido!');
                e.preventDefault();
                return false;
            }
            
            if (!validarTelefone(telefone)) {
                alert('Telefone inválido!');
                e.preventDefault();
                return false;
            }
        });
    }

    // Validação de formulário de produto
    const formProduto = document.querySelector('form[action="cadastro_produto.php"]');
    if (formProduto) {
        formProduto.addEventListener('submit', function(e) {
            const isbn = document.getElementById('isbn').value;
            
            if (!validarISBN(isbn)) {
                alert('ISBN inválido! Use o formato: 978-0-7334-2609-4');
                e.preventDefault();
                return false;
            }
        });
    }

    // Validação de formulário de funcionário
    const formFuncionario = document.querySelector('form[action="cadastro_funcionario.php"]');
    if (formFuncionario) {
        formFuncionario.addEventListener('submit', function(e) {
            const cpf = document.getElementById('cpf').value;
            const telefone = document.getElementById('telefone').value;
            
            if (!validarCPF(cpf)) {
                alert('CPF inválido!');
                e.preventDefault();
                return false;
            }
            
            if (!validarTelefone(telefone)) {
                alert('Telefone inválido!');
                e.preventDefault();
                return false;
            }
        });
    }
}

// Função para validar CPF
function validarCPF(cpf) {
    cpf = cpf.replace(/[^\d]/g, '');
    
    if (cpf.length !== 11) return false;
    
    // Verifica se todos os dígitos são iguais
    if (/^(\d)\1{10}$/.test(cpf)) return false;
    
    // Valida 1º dígito
    let soma = 0;
    for (let i = 0; i < 9; i++) {
        soma += parseInt(cpf.charAt(i)) * (10 - i);
    }
    let resto = 11 - (soma % 11);
    let digito1 = (resto === 10 || resto === 11) ? 0 : resto;
    
    if (digito1 !== parseInt(cpf.charAt(9))) return false;
    
    // Valida 2º dígito
    soma = 0;
    for (let i = 0; i < 10; i++) {
        soma += parseInt(cpf.charAt(i)) * (11 - i);
    }
    resto = 11 - (soma % 11);
    let digito2 = (resto === 10 || resto === 11) ? 0 : resto;
    
    if (digito2 !== parseInt(cpf.charAt(10))) return false;
    
    return true;
}

// Função para validar CNPJ
function validarCNPJ(cnpj) {
    cnpj = cnpj.replace(/[^\d]/g, '');
    
    if (cnpj.length !== 14) return false;
    
    // Verifica se todos os dígitos são iguais
    if (/^(\d)\1{13}$/.test(cnpj)) return false;
    
    // Valida 1º dígito
    let tamanho = cnpj.length - 2;
    let numeros = cnpj.substring(0, tamanho);
    let digitos = cnpj.substring(tamanho);
    let soma = 0;
    let pos = tamanho - 7;
    
    for (let i = tamanho; i >= 1; i--) {
        soma += numeros.charAt(tamanho - i) * pos--;
        if (pos < 2) pos = 9;
    }
    
    let resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado !== parseInt(digitos.charAt(0))) return false;
    
    // Valida 2º dígito
    tamanho = tamanho + 1;
    numeros = cnpj.substring(0, tamanho);
    soma = 0;
    pos = tamanho - 7;
    
    for (let i = tamanho; i >= 1; i--) {
        soma += numeros.charAt(tamanho - i) * pos--;
        if (pos < 2) pos = 9;
    }
    
    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado !== parseInt(digitos.charAt(1))) return false;
    
    return true;
}

// Função para validar telefone
function validarTelefone(telefone) {
    telefone = telefone.replace(/[^\d]/g, '');
    // Aceita telefone com 10 ou 11 dígitos (fixo ou celular)
    return telefone.length === 10 || telefone.length === 11;
}

// Função para validar ISBN (seu formato específico)
function validarISBN(isbn) {
    // Remove espaços
    isbn = isbn.replace(/\s/g, '');
    
    // Formato: 999-0-3333-2222-1 
    const isbnRegex = /^\d{3}-\d{1}-\d{4}-\d{4}-\d{1}$/;
    
    return isbnRegex.test(isbn);
}

function validarSenhaForte($senha) {
    // Mínimo 8 caracteres
    if (strlen($senha) < 8) return false;
    
    // Pelo menos uma letra maiúscula
    if (!preg_match('/[A-Z]/', $senha)) return false;
    
    // Pelo menos uma letra minúscula
    if (!preg_match('/[a-z]/', $senha)) return false;
    
    // Pelo menos um caractere especial
    if (!preg_match('/[!@#$%&*]/', $senha)) return false;
    
    return true;
}

