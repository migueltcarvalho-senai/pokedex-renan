<?php
/*
 * ============================================================
 * POKÉDEX - index.php
 * Lógica de busca via PokeAPI mantida intacta.
 * Apenas o HTML/CSS foi estilizado com aparência de Pokédex.
 * ============================================================
 */

// Inicializa variáveis para os dados do Pokémon
$imagem   = null;  // URL da sprite frontal
$nome     = null;  // Nome do Pokémon
$tipo     = null;  // Tipo primário
$tipo2    = null;  // Tipo secundário (pode ser nulo)
$erro     = false; // Flag de erro na busca

// ---- Lógica PHP original (não alterada) ----
// Verifica se o formulário foi enviado com um nome de Pokémon
if (isset($_GET['nome']) && !empty(trim($_GET['nome']))) {

    $pokemon = strtolower(trim($_GET['nome'])); // Normaliza o input para minúsculas
    $url     = file_get_contents("https://pokeapi.co/api/v2/pokemon/$pokemon");

    // Verifica se a API retornou dados válidos
    if ($url !== false) {
        $dados  = json_decode($url, true);         // Decodifica o JSON
        $imagem = $dados["sprites"]['front_default']; // Sprite frontal
        $tipo   = $dados["types"][0]["type"]["name"]; // Tipo primário (sempre presente)
        $tipo2  = $dados["types"][1]["type"]["name"] ?? null; // Tipo secundário (opcional)
        $nome   = $dados["name"];                    // Nome oficial do Pokémon
    } else {
        // Se a API não respondeu, ativa o estado de erro
        $erro = true;
    }
}
// ---- Fim da lógica PHP original ----
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <!-- Metadados essenciais para SEO e responsividade -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Pokédex interativa - Pesquise qualquer Pokémon e veja seus dados completos com visual estilo Pokédex do anime Pokémon.">
    <title>Pokédex — Enciclopédia Pokémon</title>

    <!-- Pré-conexão com Google Fonts para melhor performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Stylesheet principal com estilo Pokédex -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Título da página acima do dispositivo -->
    <h1 class="page-title">★ Pokédex ★</h1>

    <!-- Wrapper externo para o efeito de flutuação e sombra -->
    <div class="pokedex-wrapper">

        <!-- Antena decorativa no topo -->
        <div class="pokedex-antenna" aria-hidden="true"></div>

        <!-- ======================================================
             CORPO PRINCIPAL DA POKÉDEX
             ====================================================== -->
        <div class="pokedex-body" role="main">

            <!-- ---- SEÇÃO SUPERIOR: Luzes e tela grande ---- -->
            <section class="pokedex-top" aria-label="Tela principal da Pokédex">

                <!-- Barra de luzes decorativas (câmera + LEDs coloridos) -->
                <div class="pokedex-lights-bar" aria-hidden="true">
                    <!-- Luz azul grande - câmera/radar icônica -->
                    <div class="light-main"></div>

                    <!-- LEDs coloridos pequenos -->
                    <div class="lights-small">
                        <div class="light-small red"    title="STATUS"></div>
                        <div class="light-small yellow" title="BATTERY"></div>
                        <div class="light-small green"  title="ONLINE"></div>
                    </div>
                </div>

                <!-- ---- TELA PRINCIPAL LCD ---- -->
                <div class="pokedex-screen-main" aria-label="Tela de exibição do Pokémon">
                    <div class="screen-lcd">

                        <!--
                            Camadas de efeito visual da tela (puramente decorativas).
                            .screen-sweep   → faixa de brilho que varre de cima para baixo em loop
                            .screen-noise   → textura de interferência estática (CSS puro)
                            aria-hidden=true pois são apenas efeitos visuais, sem conteúdo semântico
                        -->
                        <div class="screen-sweep"  aria-hidden="true"></div>
                        <div class="screen-noise"  aria-hidden="true"></div>

                        <?php if ($erro): ?>
                            <!-- Estado de ERRO: Pokémon não encontrado -->
                            <div class="error-screen" role="alert" aria-live="assertive">
                                <div class="error-icon" aria-hidden="true">✗</div>
                                <p class="error-text">
                                    POKEMON<br>
                                    NAO<br>
                                    ENCONTRADO!
                                </p>
                            </div>

                        <?php elseif ($nome): ?>
                            <!-- Estado ATIVO: Exibe os dados do Pokémon encontrado -->
                            <div class="pokemon-display" aria-label="Dados do Pokémon <?= htmlspecialchars(strtoupper($nome)) ?>">

                                <!-- Sprite (imagem pixel art) do Pokémon -->
                                <img
                                    class="pokemon-sprite"
                                    src="<?= htmlspecialchars($imagem) ?>"
                                    alt="Sprite do Pokémon <?= htmlspecialchars($nome) ?>"
                                    id="pokemon-sprite-img"
                                >

                            </div>

                        <?php else: ?>
                            <!-- Estado IDLE: Aguardando pesquisa -->
                            <div class="idle-screen" aria-label="Aguardando busca de Pokémon">
                                <!-- Pokébola como ícone de espera -->
                                <div class="pixel-pokemon" aria-hidden="true">⬤</div>
                                <p>AGUARDANDO<br>DADOS...</p>
                            </div>
                        <?php endif; ?>

                    </div><!-- /.screen-lcd -->
                </div><!-- /.pokedex-screen-main -->

            </section><!-- /.pokedex-top -->

            <!-- ---- SEÇÃO INFERIOR: Controles e informações ---- -->
            <section class="pokedex-bottom" aria-label="Controles e informações">

                <!-- Linha divisória decorativa (dobradiça) -->
                <div class="divider-hinge" aria-hidden="true"></div>

                <!-- ---- PAINEL DE INFO DO POKÉMON (abaixo da divisória) ---- -->
                <?php if ($nome): ?>
                    <!-- Exibe nome e tipos quando há um Pokémon carregado -->
                    <div class="pokemon-info-panel" aria-label="Informações do Pokémon">

                        <!-- Nome do Pokémon -->
                        <div class="pokemon-name" id="pokemon-name-display">
                            <?= htmlspecialchars(strtoupper($nome)) ?>
                        </div>

                        <!-- Badges de tipo -->
                        <div class="pokemon-types" aria-label="Tipos do Pokémon">

                            <!-- Tipo primário (sempre existe) -->
                            <span
                                class="type-badge type-<?= htmlspecialchars($tipo) ?>"
                                aria-label="Tipo primário: <?= htmlspecialchars($tipo) ?>"
                            >
                                <?= htmlspecialchars($tipo) ?>
                            </span>

                            <!-- Tipo secundário (condicional) -->
                            <?php if ($tipo2): ?>
                                <span
                                    class="type-badge type-<?= htmlspecialchars($tipo2) ?>"
                                    aria-label="Tipo secundário: <?= htmlspecialchars($tipo2) ?>"
                                >
                                    <?= htmlspecialchars($tipo2) ?>
                                </span>
                            <?php endif; ?>

                        </div><!-- /.pokemon-types -->
                    </div><!-- /.pokemon-info-panel -->
                <?php endif; ?>

                <!-- ---- FORMULÁRIO DE BUSCA ---- -->
                <div class="search-container" role="search">

                    <!-- Label do campo de busca -->
                    <label for="pokemon-input">▶ BUSCAR POKÉMON</label>

                    <!-- Formulário GET (lógica original mantida) -->
                    <form method="GET" action="" aria-label="Formulário de busca de Pokémon">
                        <!-- Campo de texto para o nome do Pokémon -->
                        <input
                            type="text"
                            id="pokemon-input"
                            class="search-input"
                            placeholder="EX: PIKACHU"
                            name="nome"
                            value="<?= isset($_GET['nome']) ? htmlspecialchars($_GET['nome']) : '' ?>"
                            autocomplete="off"
                            spellcheck="false"
                            aria-label="Nome do Pokémon"
                        >

                        <!-- Botão de envio -->
                        <button
                            type="submit"
                            class="search-btn"
                            id="search-submit-btn"
                            aria-label="Pesquisar Pokémon"
                        >
                            OK
                        </button>
                    </form>

                </div><!-- /.search-container -->

                <!-- ---- PAINEL DE CONTROLES (D-Pad + botões A/B) ---- -->
                <div class="controls-panel" aria-hidden="true">

                    <!-- D-Pad direcional decorativo -->
                    <div class="dpad" role="img" aria-label="D-Pad decorativo">
                        <div class="dpad-btn"></div><!-- Cima -->
                        <div class="dpad-btn"></div><!-- Esquerda -->
                        <div class="dpad-btn dpad-center"></div><!-- Centro -->
                        <div class="dpad-btn"></div><!-- Direita -->
                        <div class="dpad-btn"></div><!-- Baixo -->
                    </div>

                    <!-- Texto central decorativo -->
                    <div class="controls-label" aria-hidden="true">
                        <div class="gold-strip"></div>
                        <span>POKÉDEX v2.0</span>
                        <div class="gold-strip"></div>
                    </div>

                    <!-- Botões de ação A, B + Start/Select -->
                    <div class="action-buttons" aria-hidden="true">
                        <div class="ab-row">
                            <button class="action-btn btn-b" tabindex="-1">B</button>
                            <button class="action-btn btn-a" tabindex="-1">A</button>
                        </div>
                        <div class="start-select-row">
                            <button class="ss-btn" tabindex="-1">SEL</button>
                            <button class="ss-btn" tabindex="-1">STA</button>
                        </div>
                    </div>

                </div><!-- /.controls-panel -->

                <!-- ---- RODAPÉ DO DISPOSITIVO ---- -->
                <div class="pokedex-footer" aria-hidden="true">
                    <!-- Número do modelo decorativo -->
                    <span class="model-number">MODEL PDX-R001</span>

                    <!-- LEDs de status -->
                    <div class="status-leds">
                        <div class="led-status on"  title="Sistema ativo"></div>
                        <div class="led-status off" title="Modo sleep"></div>
                        <div class="led-status off" title="Bluetooth"></div>
                    </div>
                </div><!-- /.pokedex-footer -->

            </section><!-- /.pokedex-bottom -->

        </div><!-- /.pokedex-body -->
    </div><!-- /.pokedex-wrapper -->

</body>
</html>
