<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercício PHP</title>
</head>
<body>

    <h2>1) Caixas de Texto</h2>

    <form>
        <input type="text" placeholder="Digite algo"><br><br>
        <input type="text" placeholder="Digite algo"><br><br>
        <input type="text" placeholder="Digite algo"><br><br>
        <input type="text" placeholder="Digite algo"><br><br>
        <input type="text" placeholder="Digite algo"><br><br>
    </form>

    <hr>

    <h2>2) Lista de Equipamentos de Informática</h2>

    <?php
        $equipamentos = [
            "Monitor",
            "Teclado",
            "Mouse",
            "Impressora",
            "Notebook",
            "Roteador",
            "HD Externo",
            "Switch"
        ];

        echo "<ul>";

        foreach($equipamentos as $item){
            echo "<li>$item</li>";
        }

        echo "</ul>";
    ?>

    <hr>

    <h2>3) Alunos e suas Notas</h2>

    <?php
        $alunos = [
            "Carlos" => 8.5,
            "Maria" => 9.0,
            "João" => 7.5,
            "Ana" => 10,
            "Pedro" => 6.8,
            "Lucas" => 8.0,
            "Fernanda" => 9.5
        ];

        echo "<table border='1' cellpadding='10'>";
        echo "<tr>
                <th>Aluno</th>
                <th>Nota</th>
              </tr>";

        foreach($alunos as $nome => $nota){
            echo "<tr>
                    <td>$nome</td>
                    <td>$nota</td>
                  </tr>";
        }

        echo "</table>";
    ?>

</body>
</html>