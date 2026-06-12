<?php
$imagem = null;
$nome = null;
$tipo = null;
$tipo2 = null;
$height = null;
$weight = null;


$pokemon = $_GET['nome'];
if (isset($_GET['nome'])) {
    $pokemon = $_GET['nome'];
    $url = file_get_contents("https://pokeapi.co/api/v2/pokemon/$pokemon");
    $dados = json_decode($url, true);
    $imagem = $dados["sprites"]['front_default'];
    $tipo = $dados["types"][0]["type"]["name"];
    $tipo2 = $dados["types"][1]["type"]["name"] ?? null;
    $nome = $dados["name"];
    $height = $dados["height"];
    $weight = $dados["weight"];
}
?>

<header class="titulo">
    <h1>Pokedex Foda</h1>

</header>

<form method="$_GET">
    <h2>Digite aqui o seu pokemon</h2>
    <input type="text" placeholder="digite seu pokemon aqui" name="nome">
    <button type="submit">Pesquisar</button>
</form>

<?php
echo
'<img src="' . $imagem . '"> <br>
<h3>' . $nome . '</h3>
<h3>' . $tipo . '</h3>
<h3>' . $tipo2 . '</h3>
<h3>' . $height . 'Centimetros</h3>
<h3>' . $weight . ' KGs</h3>
'

?>