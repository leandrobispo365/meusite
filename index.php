<?php

$porta = 1; // Inicializa a variável com a primeira porta

while ($porta <= 20) { // Loop enquanto a porta for menor ou igual a 20

    // Regra 2: se chegar na porta 18, interrompe tudo
    if ($porta == 18) {
        echo "Varredura interrompida por segurança na porta 18.<br>";
        break; // Encerra o loop completamente
    }

    // Regra 1: se for múltiplo de 5, ignora
    if ($porta % 5 == 0) {
        $porta++; // Incrementa antes de continuar
        continue; // Pula para a próxima iteração
    }

    // Se não cair nas regras acima, imprime a verificação
    echo "A verificar porta $porta <br>";

    $porta++; // Incrementa para próxima porta
}

?>