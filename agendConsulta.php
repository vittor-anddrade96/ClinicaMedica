<?php

require 'conexao.php';

if(isset($_POST['acao'])) {
    $medicos = $_POST['medico_id'];
    $dia = $_POST['dia_semana'];
    $data = $_POST['dta'];
    $disponibilidade = $_POST['disponibilidade'];
    $paciente = $_POST['paciente_id'];

    if(empty($medicos)|| empty($dia)||empty($data)||empty($disponibilidade) || empty($paciente)) {
        echo '<div class="alert alert-danger">Por favor, preencha todos os campos!</div>';
        exit;
    }

    try {
        $sql = $pdo->prepare("INSERT INTO agenda (medico_id,dia_semana,dta,disponibilidade,paciente_id) VALUES (:medico_id, :dia_semana, :dta, :disponibilidade, :paciente_id)");
        $sql->bindValue(":medico_id", $medicos);
        $sql->bindValue(":dia_semana", $dia);
        $sql->bindValue(":dta", $data);
        $sql->bindValue(":disponibilidade", $disponibilidade);
        $sql->bindValue("paciente_id", $paciente);

        $sql->execute();
        echo '<div class="alert alert-sucess">Paciente agendado com Sucesso!</div>';
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger">Erro ao agendar consulta: '. $e->getMessage() .'</div>';
    }
}


?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>Agendamento de Consultas</title>
    <style>
        body {
            margin: 10px;
            padding: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #6495ED;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: dodgerblue;
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
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
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
    <h3>Agendamento de Consultas</h3>
    <table>
        <h4>Médicos Disponíveis</h4>
        <tr>
            <th>Nome</th>
            <th>Especialidade</th>
        </tr>

        <?php

        $pdo = new PDO('mysql:host=localhost;dbname=clinicamedica', 'root', '');

        $sql = "SELECT nome, especialidade FROM medicos";
        $result = $pdo->query($sql);
        $medicos = $result->fetchAll(PDO::FETCH_ASSOC);

        if (count($medicos) > 0) {
            foreach ($medicos as $row) {
                echo "<tr><td>" . $row["nome"] . "</td><td>" . $row["especialidade"] . "</td></tr>";
            }
        }

        ?>
    </table>

    <table>
        <h4>Pacientes</h4>
        <tr>
            <th>Nome</th>
            <th>Telefone</th>
        </tr>

        <?php

        $pdo = new PDO('mysql:host=localhost;dbname=clinicamedica', 'root', '');

        $sql = "SELECT nome, telefone FROM pacientes";
        $result = $pdo->query($sql);
        $pacientes = $result->fetchAll(PDO::FETCH_ASSOC);

        if (count($pacientes) > 0) {
            foreach ($pacientes as $row) {
                echo "<tr><td>" . $row["nome"] . "</td><td>" . $row["telefone"] . "</td></tr>";
            }
        }

        ?>
    </table>

    <h4>Agendar Consulta</h4>
    <form action="agendConsulta.php" method="POST">
        <label for="medico">Escolha o Especialista:</label>
        <select name="medico_id" id="medico_id" required>
            <?php
            $pdo = new PDO('mysql:host=localhost;dbname=clinicamedica', 'root', '');

            $sql = "SELECT nome FROM medicos";
            $result = $pdo->query($sql);
            $medicos = $result->fetchAll(PDO::FETCH_ASSOC);

            if (count($medicos) > 0) {
                foreach ($medicos as $row) {
                    echo "<option value='" . $row["nome"] . "'>" . $row["nome"] . "</option>";
                }
            }
            ?>
        </select>
        <br><br>
        <label for="dia_semana">Dia da Semana</label>
        <select name="dia_semana" id="dia_semana" required>
            <option value="Segunda">Segunda</option>
            <option value="Terça">Terça</option>
            <option value="Quarta">Quarta</option>
            <option value="Quinta">Quinta</option>
            <option value="Sexta">Sexta</option>
        </select>
        <br><br>
        <label for="data">Data</label>
        <input type="date" name="dta" id="dta" required>
        <br><br>
        <label for="horario">Horário:</label>
        <select name="horario" id="horario" required>
            <option value="08:00">08:00</option>
            <option value="09:00">09:00</option>
            <option value="10:00">10:00</option>
            <option value="14:00">14:00</option>
            <option value="15:00">15:00</option>

        </select>
        <br><br>
        <label for="paciente">Qual o Paciente?:</label>
        <select name="paciente_id" id="paciente_id" required>
            <?php
            $pdo = new PDO('mysql:host=localhost;dbname=clinicamedica', 'root', '');

            $sql = "SELECT nome FROM pacientes";
            $result = $pdo->query($sql);
            $pacientes = $result->fetchAll(PDO::FETCH_ASSOC);

            if (count($pacientes) > 0) {
                foreach ($pacientes as $row) {
                    echo "<option value='" . $row["nome"] . "'>" . $row["nome"] . "</option>";
                }
            }
            ?>
        </select>
        <br><br>
        <input type="submit" name="acao" value="Agendar">
    </form>
</body>

</html>