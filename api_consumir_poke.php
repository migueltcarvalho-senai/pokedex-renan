<?php
$pokemon = $_GET['nome'];
$url = file_get_contents("https://pokeapi.co/api/v2/pokemon/$pokemon");
$dados = json_decode($url, true);
$imagem = $dados["sprites"]['front_default'];
$tipo = $dados["types"][0]["type"]["name"];
$nome = $dados["name"];
echo $tipo
?>


<img src="<?= $imagem ?>">