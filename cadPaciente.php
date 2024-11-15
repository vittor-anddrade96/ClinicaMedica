<?php

require 'conexao.php';

$erro = '';
$sucesso = '';

if (isset($_POST['acao'])) {
    $nome = $_POST['nome'];
    $data_nascimento = $_POST['data_nascimento'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];
    $sexo = $_POST['sexo'];

    if (empty($nome)) {
        $erro = 'O nome não pode estar vazio.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'E-mail inválido.';
    } elseif (!preg_match('/^[0-9]{10,11}$/', $telefone)) {
        $erro = 'O telefone deve conter apenas números e ter de 10 ou 11 dígitos.';
    } elseif (strtotime($data_nascimento) > strtotime('-18 years')) {
        $erro = 'O paciente deve ser maior de idade.';
    } else {

        $sql = $pdo->prepare("INSERT INTO pacientes (nome, data_nascimento, email, telefone, endereco, sexo) VALUES (:nome, :data_nascimento, :email, :telefone, :endereco, :sexo)");
        $sql->bindValue(':nome', $nome);
        $sql->bindValue(':data_nascimento', $data_nascimento);
        $sql->bindValue(':email', $email);
        $sql->bindValue(':telefone', $telefone);
        $sql->bindValue(':endereco', $endereco);
        $sql->bindValue(':sexo', $sexo);
        
        $sql->execute();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Cadastro de Paciente</title>
    <style>
        body {
            padding: 10px;
            margin: 10px;
        }
    </style>
</head>

<body>
<header>
        <div class="elemento-pai">
            <a href="index.php"><img id="imagem1" src="imagens/logo.png" width="300"></a>
        </div>
    </header>
    <br>
    <div class="p-3 text-primary-emphasis bg-primary-subtle border border-primary-subtle rounded-3">
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">PÁGINA INICIAL</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarScroll">
      <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            CONSULTAS
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="cadPaciente.php">CADASTRO DE PACIENTES</a></li>
            <li><a class="dropdown-item" href="agendConsulta.php">AGENDAMENTO DE CONSULTAS</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">CANCELAMENTO DE CONSULTAS</a></li>
          </ul>
        </li>
      </ul>
      <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" placeholder="PESQUISAR" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Pesquisar</button>
      </form>
    </div>
  </div>
  </div>
</nav>
</nav>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Cadastro de Paciente - Clínica Médica</h2>

        <?php if ($erro): ?>
            <div class="alert alert-danger"><?= $erro ?></div>
        <?php elseif ($sucesso): ?>
            <div class="alert alert-success"><?= $sucesso ?></div>
        <?php endif; ?>

        <form action="" method="POST" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="name" class="form-label">Nome Completo</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
                <div class="invalid-feedback">O nome é obrigatório.</div>
            </div>
            <div class="mb-3">
                <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" required>
                <div class="invalid-feedback">Informe uma data válida</div>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" class="form-control" id="email" name="email" required>
                <div class="invalid-feedback">Informe um e-mail válido.</div>
            </div>
            <div class="mb-3">
                <label for="telefone" class="form-label">Telefone</label>
                <input type="text" class="form-control" id="telefone" name="telefone" pattern="\d{10,11}" required>
                <div class="invalid-feedback">Informe um telefone com 10 ou 11 dígitos.</div>
            </div>
            <div class="mb-3">
                <label for="endereco" class="form-label">Endereço</label>
                <input type="text" class="form-control" id="endereco" name="endereco" required>
                <div class="invalid-feedback">O endereço é obrigatório.</div>
            </div>
            <div class="mb-3">
                <label for="sexo" class="form-label">Sexo</label>
                <select class="form-select" id="sexo" name="sexo" required>
                    <option value="" disabled selected>Selecione</option>
                    <option value="masculino">Masculino</option>
                    <option value="feminino">Feminino</option>
                </select>
                <div class="invalid-feedback">Informe o sexo.</div>
            </div>
            <button type="submit" class="btn btn-primary" name="acao">Cadastrar</button>
            <a href="index.php">
                <button type="button" class="btn btn-danger">Voltar</button>
            </a>
        </form>
    </div>

    <script>
        (function() {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')

            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }

                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>
</body>

</html>