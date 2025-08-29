// Import Tailwind CSS configuration
import tailwind from "tailwindcss"

// Configuração do Tailwind
tailwind.config = {
  theme: {
    extend: {
      colors: {
        primary: "#2563eb",
        "primary-dark": "#1d4ed8",
        "primary-light": "#3b82f6",
      },
    },
  },
}

// Máscara para CNPJ
function aplicarMascaraCNPJ(input) {
  input.addEventListener("input", (e) => {
    let value = e.target.value.replace(/\D/g, "")

    // Aplica a máscara progressivamente
    if (value.length <= 2) {
      value = value
    } else if (value.length <= 5) {
      value = value.replace(/^(\d{2})(\d)/, "$1.$2")
    } else if (value.length <= 8) {
      value = value.replace(/^(\d{2})(\d{3})(\d)/, "$1.$2.$3")
    } else if (value.length <= 12) {
      value = value.replace(/^(\d{2})(\d{3})(\d{3})(\d)/, "$1.$2.$3/$4")
    } else {
      value = value.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d)/, "$1.$2.$3/$4-$5")
    }

    e.target.value = value
  })
}

// Validação de CNPJ
function validarCNPJ(cnpj) {
  cnpj = cnpj.replace(/\D/g, "")

  if (cnpj.length !== 14) return false

  // Verifica se todos os dígitos são iguais
  if (/^(\d)\1+$/.test(cnpj)) return false

  return true
}

// Validação do formulário
function validarFormulario(form) {
  const nome = form.querySelector("#nome").value.trim()
  const cnpj = form.querySelector("#cnpj").value
  const endereco = form.querySelector("#endereco").value.trim()
  const estado = form.querySelector("#estado").value

  // Validações
  if (!nome) {
    alert("Por favor, preencha o nome da empresa.")
    form.querySelector("#nome").focus()
    return false
  }

  if (!cnpj) {
    alert("Por favor, preencha o CNPJ.")
    form.querySelector("#cnpj").focus()
    return false
  }

  if (!validarCNPJ(cnpj)) {
    alert("CNPJ inválido. Verifique os dados informados.")
    form.querySelector("#cnpj").focus()
    return false
  }

  if (!endereco) {
    alert("Por favor, preencha o endereço.")
    form.querySelector("#endereco").focus()
    return false
  }

  if (!estado) {
    alert("Por favor, selecione o estado.")
    form.querySelector("#estado").focus()
    return false
  }

  return true
}

// Inicialização quando o DOM estiver carregado
document.addEventListener("DOMContentLoaded", () => {
  // Aplica máscara no campo CNPJ
  const cnpjInput = document.getElementById("cnpj")
  if (cnpjInput) {
    aplicarMascaraCNPJ(cnpjInput)
  }

  // Adiciona validação ao formulário
  const form = document.querySelector("form")
  if (form) {
    form.addEventListener("submit", (e) => {
      e.preventDefault()

      if (validarFormulario(form)) {
        // Simula envio do formulário
        alert("Fornecedor cadastrado com sucesso!")

        // Opcional: limpar formulário após sucesso
        // form.reset();
      }
    })
  }

  // Adiciona funcionalidade ao botão cancelar
  const btnCancelar = form.querySelector('button[type="button"]')
  if (btnCancelar) {
    btnCancelar.addEventListener("click", () => {
      if (confirm("Deseja realmente cancelar o cadastro? Todos os dados serão perdidos.")) {
        form.reset()
      }
    })
  }
})
