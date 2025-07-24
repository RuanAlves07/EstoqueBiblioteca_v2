function mascara(o, f){//define o objeto e chama a função

    objeto = o
    funcao = f
    setTimeout("executarMascara()", 1)
    }
    
    function executarMascara(){
        objeto.value=funcao(objeto.value)
    
    }

function preco(variavel){
    variavel=variavel.replace(/\D/g,"")
    variavel=variavel.replace(/(\d+)(\d{2})$/,"$1,$2")
    return variavel
}

function quantidade(variavel){
    variavel=variavel.replace(/\D/g,"")
    return variavel
}

function nome(variavel){
    variavel=variavel.replace (/[^a-zA-ZÀ-ÿ\s]/g, "")
    return variavel
}

function categoria(variavel){
    variavel=variavel.replace (/[^a-zA-ZÀ-ÿ\s]/g, "")
    return variavel
}

function fornecedor(variavel){
    variavel=variavel.replace (/[^a-zA-ZÀ-ÿ\s]/g, "")
    return variavel
}