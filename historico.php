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
    <h1>Histórico</h1>
    <main>
        <form>
            <fieldset>
                <legend>Detalhes da Simulação</legend>

                <label for="simulacaoid">ID da Simulação:</label>
                <input type="number" name="simulacaoid" id="simulacaoid" value=<?= "\"" . (isset($_GET['simulacaoid']) ? $_GET['simulacaoid'] : "\"\"") . "\"" ?>>
                <button type="submit">Recuperar</button>

            </fieldset>
        </form>

        <?php
        if (isset($_GET['simulacaoid'])) {
            require_once 'classes/r.class.php';

            R::setup('mysql:host=127.0.0.1:3306; dbname=tce2tri', 'root', '');

            $id = $_GET['simulacaoid'];
            $simulacao = R::load('fintech',  $id);

            if ($simulacao->id === 0) {
                echo "<p>ID não encontrado, tente novamente!</p>";
            } else {
                echo "<fieldset>";
                echo "<legend>Dados da Simulação</legend>";
                echo "<p>ID da Simulação: $simulacao->id</p>";
                echo "<p>Cliente: $simulacao->nomecliente</p>";
                echo "<p>Aporte Inicial: $simulacao->aporteinicial</p>";
                echo "<p>Aporte Mensal: $simulacao->aportemensal</p>";
                echo "<p>Rendimento: $simulacao->rendimento</p>";
                echo "<p>Período: $simulacao->periodo</p>";
                echo "</fieldset>";

                $aporteInicial = floatval($simulacao->aporteinicial);
                $aporteMensal = intval($simulacao->aportemensal);
                $rendimentoMensal = floatval($simulacao->rendimento);
                $periodo = floatval($simulacao->periodo);

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
                        $aporte = $aporteMensal;
                    }

                    list($rendimento, $total) = calcularValores($valorAtual, $aporte, $rendimentoMensal);

                    $resultados[] = array(
                        'mês' => $i,
                        'valor_inicial' => $valorAtual,
                        'rendimento' => $rendimento,
                        'total' => $total
                    );

                    $valorAtual = $total;
                }

                echo "<table>
              <tr>
                <th>Mês</th>
                <th>Aplicação (R$)</th>
                <th>Rendimento (R$)</th>
                <th>Total (R$)</th>
              </tr>";

                foreach ($resultados as $resultado) {
                    echo "<tr>";
                    echo "<td>" . $resultado['mês'] . "</td>";
                    echo "<td>" . number_format($resultado['valor_inicial'], 2, ',', '.') . "</td>";
                    echo "<td>" . number_format($resultado['rendimento'], 2, ',', '.') . "</td>";
                    echo "<td>" . number_format($resultado['total'], 2, ',', '.') . "</td>";
                    echo "</tr>";
                }

                echo "</table>";
            }

            R::close();
        }
        ?>
    </main>

    <footer>
        <p>Caetano Veloso e Tales Cordeiro - &copy;2023</p>
    </footer>

</body>

</html>