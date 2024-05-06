<?php
session_start();

function calcular($numero1, $operacao, $numero2)
{
    switch ($operacao) {
        case '+':
            return $numero1 + $numero2;
        case '-':
            return $numero1 - $numero2;
        case '*':
            return $numero1 * $numero2;
        case '/':
            return $numero2 != 0 ? $numero1 / $numero2 : 'Divisão por zero!';
        case '!':
            return fatorial($numero1);
        case '^':
            return pow($numero1, $numero2);
        default:
            return "Operação inválida";
    }
}

function fatorial($n)
{
    return $n <= 1 ? 1 : $n * fatorial($n - 1);
}

$numero1 = $_POST['numero1'] ?? '';
$operacao = $_POST['operacao'] ?? 'adicao';
$numero2 = $_POST['numero2'] ?? '';
$resultado = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['calcular'])) {
        $resultado = calcular($numero1, $operacao, $numero2);
        $_SESSION['historico'][] = "$numero1 $operacao $numero2 = $resultado";
    }

    if (isset($_POST['memoria'])) {
        if (isset($_SESSION['memoria'])) {
            $numero1 = $_SESSION['memoria']['numero1'];
            $operacao = $_SESSION['memoria']['operacao'];
            $numero2 = $_SESSION['memoria']['numero2'];
            unset($_SESSION['memoria']);
        } else {
            $_SESSION['memoria'] = [
                'numero1' => $numero1,
                'operacao' => $operacao,
                'numero2' => $numero2
            ];
        }
    }

    if (isset($_POST['limpar_historico'])) {
        unset($_SESSION['historico']);
    }
}

function exibirHistorico()
{
    if (isset($_SESSION['historico'])) {
        echo '<ul>';
        echo '<h2>';
        foreach ($_SESSION['historico'] as $calc) {
            echo "<li>•⠀⠀$calc</li>";
        }
        echo '</h2>';
        echo '</ul>';
    }
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Calculadora PHP</title>
    <link rel="stylesheet" href="estilo.css">
</head>

<body>
    <div class="calculadora">
        <div class="titulo-bloco">
            <h1>Calculadora PHP</h1>
        </div>
        <form action="Calculadora.php" method="post">
            <div class="entradas">
                <div class="container">
                    <label for="numero1">⠀Numero 1</label>
                    <div class="caixa">
                        <input class="entrada" type="text" id="numero1" name="numero1"
                            value="<?php echo htmlspecialchars($numero1); ?>" required>
                    </div>
                </div>
                <select name="operacao">
                    <option value="+" <?php echo $operacao == 'adicao' ? 'selected' : ''; ?>>+</option>
                    <option value="-" <?php echo $operacao == 'subtracao' ? 'selected' : ''; ?>>-</option>
                    <option value="*" <?php echo $operacao == 'multiplicacao' ? 'selected' : ''; ?>>*</option>
                    <option value="/" <?php echo $operacao == 'divisao' ? 'selected' : ''; ?>>/</option>
                    <option value="!" <?php echo $operacao == 'fatorial' ? 'selected' : ''; ?>>!</option>
                    <option value="^" <?php echo $operacao == 'potencia' ? 'selected' : ''; ?>>^</option>
                </select>
                <div class="container">
                    <label for="numero2">⠀Numero 2</label>
                    <div class="caixa">
                        <input class="entrada" type="text" id="numero2" name="numero2"
                            value="<?php echo htmlspecialchars($numero2); ?>" required>
                    </div>
                </div>
            </div>
            <input type="submit" name="calcular" value="Calcular">
            <div class="Caixa-Resultado">
                <?php if (!empty($resultado))
                    echo "$numero1 $operacao $numero2 = $resultado"; ?>
            </div>
            <input type="submit" name="memoria" value="M">
            <input type="submit" name="limpar_historico" value="Apagar Histórico">
        </form>
        <h2 class="Historico">Histórico:</h2>
        <?php exibirHistorico(); ?>
    </div>
</body>

</html>