<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Projeto 2Tri - WebDev3</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
</head>

<body>
    <header>
        <h2>Desenvolvimento Web</h2>
        <a href="./index.php" class="home"> Home </a>
    </header>
    <h1>Simulação: Resultado</h1>
    <main>

        <?php

        require_once 'classes/autoloader.class.php';
        require_once 'classes/r.class.php';

        R::setup('mysql:host=127.0.0.1:3306; dbname=tce2tri', 'root', '');


        if (isset($_GET['aporteinicial']) && isset($_GET['aportemensal'])) {
            $cliente = $_GET['cliente'];
            $aporteinicial =  $_GET['aporteinicial'];
            $aportemensal =  $_GET['aportemensal'];
            $rendimento = $_GET['rendimento'];
            $periodo = $_GET['periodo'];

            $fintech = R::dispense('fintech');
            $fintech->nomecliente = $_GET['cliente'];
            $fintech->aporteinicial = $_GET['aporteinicial'];
            $fintech->aportemensal = $_GET['aportemensal'];
            $fintech->rendimento = $_GET['rendimento'];
            $fintech->periodo = $_GET['periodo'];

            $id = R::store($fintech);

            R::close();
        }

        echo "<fieldset>";
        echo "<legend>Dados</legend>";
        echo "<p>ID da Simulação: $id</p>";
        echo "<p>Cliente: $cliente</p>";
        echo "<p>Aporte Mensal: $aporteinicial</p>";
        echo "<p>Aporte Mensal: $aportemensal</p>";
        echo "<p>Rendimento: $rendimento</p>";
        echo "<p>Periodo: $periodo</p>";
        echo "</fieldset>";

        if (isset($_GET['aporteinicial']) && isset($_GET['aportemensal']) && isset($_GET['rendimento']) && isset($_GET['periodo'])) {
            $aporteInicial = floatval($_GET['aporteinicial']);
            $aportemensal = intval($_GET['aportemensal']);
            $rendimentoMensal = floatval($_GET['rendimento']);
            $periodo = floatval($_GET['periodo']);

            function calcularValores($valorAtual, $aporte, $rendimentoMensal)
            {
                $total = $valorAtual + $aporte;
                $rendimento = $total * ($rendimentoMensal / 100);
                $total += $rendimento;
                $valores = array($rendimento, $total);
                return $valores;
            }

            $resultados = array();
            $valorAtual = $aporteInicial;

            for ($i = 1; $i <= $periodo; $i++) {
                if ($i == 1) {
                    $aporte = 0;
                } else {
                    $aporte = $aportemensal;
                }

                list($rendimento, $total) = calcularValores($valorAtual, $aporte, $rendimentoMensal);

                $resultados[] = array(
                    'mes' => $i,
                    'valorinicial' => $valorAtual,
                    'rendimento' => $rendimento,
                    'total' => $total
                );

                $valorAtual = $total;
            }

            echo "<h2>Resultados da Simulação</h2>
              <table>
                <tr>
                  <th>Mês</th>
                  <th>Aplicação (R$)</th>
                  <th>Rendimento (R$)</th>
                  <th>Total (R$)</th>
                </tr>";

            foreach ($resultados as $resultado) {
                echo "<tr>";
                echo "<td>" . $resultado['mes'] . "</td>";
                echo "<td>" . number_format($resultado['valorinicial'], 2, ',', '.') . "</td>";
                echo "<td>" . number_format($resultado['rendimento'], 2, ',', '.') . "</td>";
                echo "<td>" . number_format($resultado['total'], 2, ',', '.') . "</td>";
                echo "</tr>";
            }

            echo "</table>";
        }

        ?>

        <hr>
    </main>

    <footer>
        <p>Caetano Veloso e Tales Cordeiro - &copy;2023</p>
    </footer>

</body>

</html>