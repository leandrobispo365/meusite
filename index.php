<?php

// Função para calcular o IMC
function calcularIMC($peso, $altura) {
    return $peso / ($altura * $altura);
}

// Função para classificar o IMC
function classificarIMC($imc) {
    if ($imc < 18.5) {
        return "Abaixo do peso";
    } elseif ($imc < 25) {
        return "Peso normal";
    } elseif ($imc < 30) {
        return "Sobrepeso";
    } elseif ($imc < 35) {
        return "Obesidade grau I";
    } elseif ($imc < 40) {
        return "Obesidade grau II";
    } else {
        return "Obesidade grau III (mórbida)";
    }
}

// Dados de entrada (você pode mudar)
$peso = 70;
$altura = 1.75;

// Validação
if ($peso <= 0 || $altura <= 0) {
    echo "Erro: valores inválidos!";
    exit;
}

// Cálculo
$imc = calcularIMC($peso, $altura);

// Saída formatada
echo "Peso: " . $peso . " kg<br>";
echo "Altura: " . $altura . " m<br>";
echo "IMC: " . number_format($imc, 2) . "<br>";
echo "Classificação: " . classificarIMC($imc);

?>
    
