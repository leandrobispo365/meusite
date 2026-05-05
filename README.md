#  Simulação de Verificação de Portas em PHP

##  Descrição

Este projeto consiste na implementação de um algoritmo em PHP que simula a verificação sequencial de portas de rede, utilizando estruturas de repetição e controle de fluxo.

A aplicação percorre um conjunto de portas numeradas de 1 a 20, exibindo no navegador o status de verificação de cada porta, de acordo com regras específicas definidas no código.

---

##  Objetivo

Demonstrar, de forma prática, a utilização das seguintes estruturas da linguagem PHP:

* Laço de repetição `while`
* Controle de fluxo com `continue`
* Interrupção de execução com `break`
* Manipulação de saída no navegador

---

## ⚙️ Funcionamento do Sistema

O sistema executa uma varredura sequencial das portas, seguindo as regras abaixo:

### ✔ Regra 1 – Ignorar portas específicas

As portas múltiplas de 5 (5, 10, 15, 20) são consideradas reservadas e, portanto, não são exibidas durante a execução.
Essa funcionalidade é implementada utilizando a instrução `continue`.

---

### ✔ Regra 2 – Interrupção por segurança

Ao atingir a porta de número 18, o sistema interrompe imediatamente a execução, exibindo a mensagem:

```
Varredura interrompida por segurança na porta 18.
```

Essa ação é realizada através da instrução `break`.

---

### ✔ Execução padrão

Para as demais portas, o sistema exibe no navegador:

```
A verificar porta X
```

---

##  Código-Fonte

```php
<?php

$porta = 1;

while ($porta <= 20) {

    if ($porta == 18) {
        echo "Varredura interrompida por segurança na porta 18.<br>";
        break;
    }

    if ($porta % 5 == 0) {
        $porta++;
        continue;
    }

    echo "A verificar porta $porta <br>";

    $porta++;
}

?>
```

---

##  Resultado no Navegador

A execução do código gera uma saída organizada diretamente no navegador, conforme exemplo abaixo:

![Resultado da execução](imagens/resultado.png)

---

##  Como Executar

1. Instale o XAMPP ou outro servidor local;
2. Coloque o arquivo PHP dentro da pasta:

```
C:\xampp\htdocs\
```

3. Inicie o Apache;
4. Acesse no navegador:

```
http://localhost/seuarquivo.php
```

---

##  Conceitos Aplicados

* Estruturas de repetição
* Controle de fluxo em programação
* Lógica condicional
* Execução de scripts PHP em ambiente web

---

##  Considerações

Este projeto demonstra, de forma clara e objetiva, a aplicação prática de conceitos fundamentais da programação, sendo ideal para fins de aprendizado e compreensão da lógica de execução em PHP.

---

##  Autor

Leandro Bispo dos Santos
Projeto Acadêmico – 2026

