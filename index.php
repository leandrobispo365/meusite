<?php
session_start();

// Configurações Globais do Sistema
$usuario_correto = "leandro";
$senha_correta = "123456";
$arquivo = "matriculas.txt";
$mensagem = "matriculas ";

// Gerenciador de Rota para Sair (Logout)
if (isset($_GET['rota']) && $_GET['rota'] === 'sair') {
    session_destroy();
    header("Location: index.php");
    exit;
}

// -------------------------------------------------------------------------
// LÓGICA DE PROCESSAMENTO DO BACKEND
// -------------------------------------------------------------------------

// 1. Processamento da Autenticação (Login)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['acao_login'])) {
    $usuario = $_POST['usuario'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if ($usuario === $usuario_correto && $senha === $senha_correta) {
        $_SESSION['aluno_logado'] = true;
        header("Location: index.php");
        exit;
    } else {
        $mensagem = "Erro: Usuário ou senha incorretos! Acesso negado.";
    }
}

// 2. Processamento do Cadastro de Alunos (Formulário Avançado)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['acao_cadastro'])) {
    $nome = strip_tags(trim($_POST['nome']));
    $endereco = strip_tags(trim($_POST['endereco']));
    $cpf = strip_tags(trim($_POST['cpf']));
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $curso = strip_tags(trim($_POST['curso']));

    if ($nome && $endereco && $cpf && $email && $curso) {
        $dadosAluno = json_encode([
            "nome" => $nome, "endereco" => $endereco, "cpf" => $cpf, "email" => $email, "curso" => $curso
        ], JSON_UNESCAPED_UNICODE);
        
        file_put_contents($arquivo, $dadosAluno . PHP_EOL, FILE_APPEND);
        $mensagem = "Sucesso: Pré-matrícula registrada! Dados enviados para a triagem da secretaria.";
    } else {
        $mensagem = "Erro: Preencha todas as credenciais e dados corretamente.";
    }
}

// 3. Processamento de Ações do Painel (Aceitar/Rejeitar com Envio de E-mail)
if (isset($_GET['acao']) && isset($_GET['cpf'])) {
    $acao = $_GET['acao'];
    $cpfAlvo = urldecode($_GET['cpf']);
    
    if (file_exists($arquivo)) {
        $linhas = file($arquivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $novasLinhas = [];
        $alunoProcessado = null;

        foreach ($linhas as $linha) {
            $dados = json_decode($linha, true);
            if ($dados && $dados['cpf'] === $cpfAlvo) {
                $alunoProcessado = $dados;
            } else {
                $novasLinhas[] = $linha;
            }
        }

        if ($alunoProcessado) {
            $assunto = "Atualizacao de Status: Sistema de Matriculas";
            if ($acao == "aprovar") {
                file_put_contents("aprovados.txt", json_encode($alunoProcessado, JSON_UNESCAPED_UNICODE) . PHP_EOL, FILE_APPEND);
                $corpo = "Olá, {$alunoProcessado['nome']}!\n\nSua matrícula no curso de {$alunoProcessado['curso']} foi APROVADA com sucesso!\n\nSeja bem-vindo(a).";
                $mensagem = "Sucesso: Aluno aprovado e e-mail enviado para {$alunoProcessado['email']}.";
            } else {
                file_put_contents("rejeitados.txt", json_encode($alunoProcessado, JSON_UNESCAPED_UNICODE) . PHP_EOL, FILE_APPEND);
                $corpo = "Olá, {$alunoProcessado['nome']}.\n\nSua solicitação de matrícula para o curso de {$alunoProcessado['curso']} foi rejeitada.";
                $mensagem = "Aviso: Solicitação de matrícula rejeitada.";
            }
            
            @mail($alunoProcessado['email'], $assunto, $corpo, "From: secretaria@escola.com\r\nContent-Type: text/plain; charset=UTF-8");
            file_put_contents($arquivo, empty($novasLinhas) ? "" : implode(PHP_EOL, $novasLinhas) . PHP_EOL);
        }
    }
}

// Gerenciamento Interno de Telas pela Sessão
$tela = (isset($_SESSION['aluno_logado']) && $_SESSION['aluno_logado'] === true) ? 'dashboard' : 'login';
if (isset($_GET['rota']) && $_GET['rota'] === 'cadastro' && isset($_SESSION['aluno_logado'])) {
    $tela = 'cadastro';
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão-Secretaria Automatizada</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            /* Imagem de fundo profissional com camada de escurecimento (linear-gradient) */
            background: linear-gradient(rgba(15, 23, 42, 0.85), rgba(15, 23, 42, 0.85)), 
                        url('https://images.unsplash.com/photo-1497215728101-856f4ea42174?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: #f8fafc;
            min-height: 100vh;
            overflow-x: hidden;
            padding-top: 120px;
        }

        /* Header Estilizado */
        .header {
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(10px);
            z-index: 1000;
            padding: 1.2rem 0;
            box-shadow: 0 4px 20px rgba(0,0,0,0.4);
        }

        /* Linha neon de efeito tecnológico */
        .header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, #1d4ed8, #3b82f6, #06b6d4, #1d4ed8);
            background-size: 300% 100%;
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            color: #3b82f6;
            text-decoration: none;
        }

        .logo span {
            color: #94a3b8;
            font-size: 1rem;
            font-weight: normal;
            display: block;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 1.5rem;
            align-items: center;
        }

        .nav-links a {
            color: #f1f5f9;
            text-decoration: none;
            font-weight: bold;
            font-size: 0.95rem;
            transition: all 0.3s;
            padding: 8px 16px;
            border-radius: 4px;
        }

        .nav-links a:hover {
            color: #3b82f6;
            background: rgba(59, 130, 246, 0.1);
        }

        .nav-links a.sair {
            background: #ef4444;
            color: white;
        }

        .nav-links a.sair:hover {
            background: #dc2626;
        }

        /* Container Principal do Sistema */
        .main-content {
            max-width: 850px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        /* Boxes de Conteúdo Estilo Glassmorphism translúcido */
        .card-box {
            background: rgba(30, 41, 59, 0.75);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            margin-bottom: 2rem;
        }

        h2 {
            margin-bottom: 25px;
            color: #3b82f6;
            font-size: 1.6rem;
            border-bottom: 2px solid rgba(59, 130, 246, 0.4);
            padding-bottom: 10px;
        }

        /* Formulários e Inputs */
        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #cbd5e1;
            font-size: 0.95rem;
        }

        input, select {
            width: 100%;
            padding: 12px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 6px;
            font-size: 1rem;
            background: #0f172a;
            color: #f8fafc;
            transition: all 0.3s;
        }

        input:focus, select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
            outline: none;
        }

        /* Botões */
        .btn {
            display: inline-block;
            padding: 0.9rem 2rem;
            background: #1d4ed8;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            width: 100%;
            font-size: 1rem;
            text-align: center;
            box-shadow: 0 4px 12px rgba(29, 78, 216, 0.3);
        }

        .btn:hover {
            background: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(37, 99, 235, 0.4);
        }

        .btn-sucesso {
            background: #10b981;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
        }

        .btn-sucesso:hover {
            background: #059669;
            box-shadow: 0 6px 15px rgba(5, 150, 105, 0.3);
        }

        /* Cards de Alunos Cadastrados no Painel */
        .registro-aluno {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-left: 5px solid #3b82f6;
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.15);
        }

        .info-aluno strong {
            color: #f1f5f9;
            font-size: 1.1rem;
        }

        .info-aluno small {
            display: block;
            color: #94a3b8;
            margin-top: 6px;
            font-size: 0.9rem;
        }

        .acoes {
            display: flex;
            gap: 10px;
        }

        .btn-acao {
            padding: 10px 16px;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: bold;
            transition: all 0.2s;
        }

        .btn-acao:hover {
            transform: scale(1.05);
        }

        .btn-acao.aceitar { background: #10b981; }
        .btn-acao.rejeitar { background: #ef4444; }

        /* Mensagens de Alerta */
        .alert {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 25px;
            text-align: center;
            font-weight: bold;
            font-size: 0.95rem;
        }

        .sem-registro {
            color: #94a3b8;
            font-style: italic;
            padding: 10px 0;
        }

        /* Responsivo */
        @media (max-width: 768px) {
            body { padding-top: 140px; }
            .nav-container { flex-direction: column; gap: 15px; text-align: center; }
            .registro-aluno { flex-direction: column; align-items: flex-start; gap: 15px; }
            .acoes { width: 100%; justify-content: flex-end; }
        }
    </style>
</head>
<body>

<header class="header">
    <div class="nav-container">
        <a href="index.php" class="logo">Secretaria <span>Módulo de Matrículas</span></a>
        <?php if ($tela !== 'login'): ?>
            <ul class="nav-links">
                <li><a href="index.php">Painel Geral</a></li>
                <li><a href="?rota=cadastro">Novo Aluno</a></li>
                <li><a href="?rota=sair" class="sair">Sair</a></li>
            </ul>
        <?php endif; ?>
    </div>
</header>

<div class="main-content">
    
    <?php 
    if(!empty($mensagem)) {
        $corEstilo = (strpos($mensagem, 'Erro') !== false) ? 'background:rgba(239,68,68,0.2);color:#ef4444;border:1px solid #ef4444;' : 'background:rgba(16,185,129,0.2);color:#10b981;border:1px solid #10b981;';
        echo "<div class='alert' style='{$corEstilo}'>{$mensagem}</div>";
    }
    ?>

    <?php if ($tela === 'login'): ?>
        <div class="card-box" style="max-width: 420px; margin: 40px auto 0;">
            <h2>Autenticação</h2>
            <form method="POST" action="">
                <input type="hidden" name="acao_login" value="1">
                <div class="form-group">
                    <label>Usuário</label>
                    <input type="text" name="usuario" placeholder="Usuário do sistema" required>
                </div>
                <div class="form-group">
                    <label>Senha</label>
                    <input type="password" name="senha" placeholder="Senha do sistema" required>
                </div>
                <button type="submit" class="btn">Entrar no Sistema</button>
            </form>
        </div>

    <?php elseif ($tela === 'cadastro'): ?>
        <div class="card-box">
            <h2>Formulário de Cadastro do Aluno</h2>
            <form method="POST" action="">
                <input type="hidden" name="acao_cadastro" value="1">
                <div class="form-group">
                    <label>Nome Completo do Aluno</label>
                    <input type="text" name="nome" placeholder="Ex: João da Silva" required>
                </div>
                <div class="form-group">
                    <label>Endereço Residencial</label>
                    <input type="text" name="endereco" placeholder="Rua, Número, Bairro, Cidade" required>
                </div>
                <div class="form-group">
                    <label>CPF</label>
                    <input type="text" name="cpf" placeholder="000.000.000-00" required>
                </div>
                <div class="form-group">
                    <label>E-mail para Contato</label>
                    <input type="email" name="email" placeholder="aluno@email.com" required>
                </div>
                <div class="form-group">
                    <label>Curso Desejado</label>
                    <select name="curso" required>
                        <option value="">Selecione um curso de interesse...</option>
                        <option value="Técnico em Redes de Computadores">Técnico em Redes de Computadores</option>
                        <option value="Desenvolvimento de Sistemas">Desenvolvimento de Sistemas</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-sucesso">Salvar e Registrar Pré-Matrícula</button>
            </form>
        </div>

    <?php elseif ($tela === 'dashboard'): ?>
        <div class="card-box">
            <h2>Alunos Aguardando Análise</h2>
            <?php
            if (file_exists($arquivo) && filesize($arquivo) > 0) {
                $linhas = file($arquivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($linhas as $linha) {
                    $aluno = json_decode($linha, true);
                    if($aluno) {
                        echo '<div class="registro-aluno">';
                        echo '<div class="info-aluno">
                                <strong>' . $aluno['nome'] . '</strong> (CPF: ' . $aluno['cpf'] . ')<br>
                                <small><strong>Curso:</strong> ' . $aluno['curso'] . '</small>
                                <small><strong>E-mail:</strong> ' . $aluno['email'] . ' | <strong>Endereço:</strong> ' . $aluno['endereco'] . '</small>
                              </div>';
                        echo '<div class="acoes">
                                <a href="?acao=aprovar&cpf=' . urlencode($aluno['cpf']) . '" class="btn-acao aceitar">Aceitar</a>
                                <a href="?acao=rejeitar&cpf=' . urlencode($aluno['cpf']) . '" class="btn-acao rejeitar">Rejeitar</a>
                              </div>';
                        echo '</div>';
                    }
                }
            } else {
                echo "<p class='sem-registro'>Nenhum registro pendente de análise.</p>";
            }
            ?>
        </div>

        <div class="card-box" style="border-top: 4px solid #10b981;">
            <h2 style="color: #10b981; border-bottom-color: rgba(16, 185, 129, 0.3);">Histórico Recente (Aprovados)</h2>
            <?php
            if (file_exists("aprovados.txt") && filesize("aprovados.txt") > 0) {
                $linhas = file("aprovados.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach (array_slice(array_reverse($linhas), 0, 5) as $linha) {
                    $aluno = json_decode($linha, true);
                    if($aluno) {
                        echo "<p style='margin-bottom:10px; color:#cbd5e1;'>✓ <strong>{$aluno['nome']}</strong> — <span style='color:#94a3b8;'>{$aluno['curso']}</span></p>";
                    }
                }
            } else {
                echo "<p class='sem-registro'>Nenhum aluno aprovado ainda.</p>";
            }
            ?>
        </div>
    <?php endif; ?>

</div>

>>>>>>> dd9cc9b (Agora vai, sistema completo)
</body>
</html>