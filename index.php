<?php


function getPokemonData($randomId)
{
    // 1) genera número aleatorio
    // $randomId = rand(1, 151);

    // 2) lee el contenido de la api 
    $texto = file_get_contents("https://pokeapi.co/api/v2/pokemon/$randomId");

    // 3) lo decodifica
    $pokemonData = json_decode($texto, true);

    // 4) Determina aleatoriamente si es shiny (1 en 20 posibilidades)
    $isShiny = (rand(1, 20) === 1);

     // 5) Selecciona la imagen según si es shiny o no
    if ($isShiny) {
        $imagen = $pokemonData['sprites']['front_shiny'];
    } else {
        $imagen = $pokemonData['sprites']['front_default'];
    }

    // 6) Creo un objeto pokemon (me quedo sólo con los datos que necesito):
    // nombre (name)
    // imagen (sprites[front_default])
    // tipos (types[]-> dentro de cada elemento [type][name])
    $tipos = [];
    foreach ($pokemonData['types'] as $key => $value) {
        array_push($tipos, $value['type']['name']);
    }

    $pokemon = [
        "nombre" => ucfirst($pokemonData['name']),
        "imagen" => $imagen,
        "tipos"  => $tipos,
        "shiny"  => $isShiny
    ];

    return $pokemon;
}


// $pokemon = getPokemonData();


function renderCards($pokeArray)
{
    // Determina la clase de fondo según el estado shiny o el tipo
    $bgClass = '';
    if ($pokeArray["shiny"]) {
        $bgClass = 'bg-shiny';
    } else if (in_array("water", $pokeArray["tipos"])) {
        $bgClass = 'bg-water';
    } else if (in_array("fire", $pokeArray["tipos"])) {
        $bgClass = 'bg-fire';
    } else if (in_array("grass", $pokeArray["tipos"])) {
        $bgClass = 'bg-grass';
    } else if (in_array("electric", $pokeArray["tipos"])) {
        $bgClass = 'bg-electric';
    }

    // Genera el HTML para la tarjeta del Pokémon aplicando la clase de fondo
    echo '<div class="carta ' . $bgClass . '">';
        echo '<div class="img-container">';
            echo '<img src="' . $pokeArray["imagen"] . '" alt="' . $pokeArray["nombre"] . '">';
        echo '</div>';
        echo '<div class="datos">';
            echo '<h3>' . $pokeArray["nombre"] . ($pokeArray["shiny"] ? ' (Shiny)' : '') . '</h3>';
            echo '<div class="tipos-pokemon">';
                // Recorre cada tipo y crea un span con estilo para el texto
                foreach ($pokeArray["tipos"] as $tipo) {
                    $estilo = "";
                    switch ($tipo) {
                        case "water":
                            $estilo = "background-color: blue; color: white;";
                            break;
                        case "fire":
                            $estilo = "background-color: red; color: white;";
                            break;
                        case "grass":
                            $estilo = "background-color: green; color: white;";
                            break;
                        case "electric":
                            $estilo = "background-color: yellow; color: black;";
                            break;
                        default:
                            $estilo = "background-color: gray; color: white;";
                            break;
                    }
                    echo '<span style="' . $estilo . '">' . $tipo . '</span>';
                }
            echo '</div>';
            // Espacio para habilidades u otros datos
            echo '<ul class="habilidades">';
            echo '</ul>';
        echo '</div>';
    echo '</div>';
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PokeWeb</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>PokeCartas</h1>

    <section id="pokecartas">
        <div class="carta">
            <div class="img-container">
                <img src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/25.png" alt="pikachu">
            </div>
            <div class="datos">
                <h3>Pikachu</h3>
                <div class="tipos-pokemon">
                    <span>eléctrico</span>
                    <span>otro más</span>
                </div>
                <ul class="habilidades">
                    <li>impactrueno</li>
                    <li>chispitas</li>
                </ul>
            </div>
        </div>

    </section>
    <?php 
    
    for ($i = 0; $i < 3; $i++) {
        // 1) genera número aleatorio
        $randomId = rand(1, 151);
        
        // 2) Se lo pasamos a la función Para sacar los datos de los Pokemons
        $pokemon = getPokemonData($randomId);

        // 3) Renderizamos los Pokemon
        renderCards($pokemon);
    }
    ?>

</body>

</html>
