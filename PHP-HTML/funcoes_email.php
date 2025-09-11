<?php

function gerarSenhaTemporaria($tamanho = 8) {
    return substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, $tamanho);
}

function gerarCodigoRecuperacao() {
    // Gera um código de 6 dígitos
    return rand(100000, 999999);
}

function simularEnvioEmail($destinatario, $senha, $codigo = null) {
    $mensagem = "Olá! Sua nova senha temporária é: $senha\n";
    
    if ($codigo) {
        $mensagem .= "Seu código de recuperação é: $codigo\n";
    }

    $registro = "Para: $destinatario\n$mensagem\n----------------------\n";
    file_put_contents("emails_simulados.txt", $registro, FILE_APPEND);
}

?>