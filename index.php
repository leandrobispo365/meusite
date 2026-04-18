<?php
    $sql = "INSERT INTO posts (titulo,conteudo) VALUES ('$titulo','$conteudo')";
    $conn->query($sql);
}

if (isset($_GET['deletar'])) {
    $id = $_GET['deletar'];
    $conn->query("DELETE FROM posts WHERE id=$id");
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Meu Site PHP</title>
</head>
<body>

<h1>Meu Site</h1>

<?php if (!isset($_SESSION['user'])): ?>

<h2>Login</h2>
<form method="POST">
    Email: <input type="email" name="email"><br>
    Senha: <input type="password" name="senha"><br>
    <button name="login">Entrar</button>
</form>

<h2>Cadastro</h2>
<form method="POST">
    Nome: <input type="text" name="nome"><br>
    Email: <input type="email" name="email"><br>
    Senha: <input type="password" name="senha"><br>
    <button name="cadastrar">Cadastrar</button>
</form>

<?php else: ?>

<h2>Bem-vindo <?php echo $_SESSION['user']; ?></h2>
<a href="?logout=1">Sair</a>

<h2>Criar Post</h2>
<form method="POST">
    Título: <input type="text" name="titulo"><br>
    Conteúdo: <textarea name="conteudo"></textarea><br>
    <button name="postar">Postar</button>
</form>

<h2>Posts</h2>

<?php
$res = $conn->query("SELECT * FROM posts");
while ($row = $res->fetch_assoc()) {
    echo "<h3>".$row['titulo']."</h3>";
    echo "<p>".$row['conteudo']."</p>";
    echo "<a href='?deletar=".$row['id']."'>Deletar</a><hr>";
}
?>

<?php endif; ?>

<?php
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
}
?>

</body>
</html>

-- SQL PARA CRIAR O BANCO --

CREATE DATABASE meusite;

USE meusite;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    email VARCHAR(100),
    senha VARCHAR(100)
);

CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255),
    conteudo TEXT
);
?>