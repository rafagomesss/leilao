<?php

use Alura\Leilao\Model\{
    Lance,
    Leilao,
    Usuario
};
use Alura\Leilao\Service\Avaliador;

require 'vendor/autoload.php';

// Arrumo a casa para o teste
// Arrange - Given
$leilao = new Leilao('Fiat 147 0KM');

$maria = new Usuario('Maria');
$joao = new Usuario('João');

$leilao->recebeLance(new Lance($joao, 2000));
$leilao->recebeLance(new Lance($maria, 2500));

$leiloeiro = new Avaliador();

// Executo o código a ser testado
// Act - When
$leiloeiro->avalia($leilao);

$maiorValor = $leiloeiro->getMaiorValor();

// Verifico se a saída é a esperada
$valorEsperado = 2500;

if ($valorEsperado == $maiorValor) {
    echo 'Teste OK';
} else {
    echo 'Teste Falhou';
}
