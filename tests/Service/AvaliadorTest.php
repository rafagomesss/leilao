<?php

namespace Alura\Leilao\Tests\Service;

use PHPUnit\Framework\TestCase;
use Alura\Leilao\Model\{
    Lance,
    Leilao,
    Usuario
};
use Alura\Leilao\Service\Avaliador;
use DomainException;
use PHPUnit\Framework\Attributes\DataProvider;

class AvaliadorTest extends TestCase
{
    private $leiloeiro;

    protected function setUp(): void
    {
        $this->leiloeiro = new Avaliador();
    }

    #[DataProvider('leilaoEmOrdemAleatoria')]
    #[DataProvider('leilaoEmOrdemCrescente')]
    #[DataProvider('leilaoEmOrdemDecrescente')]
    public function testAvaliadorDeveEncontrarOMaiorValorDeLances(Leilao $leilao)
    {
        // Arrumo a casa para o teste
        // Arrange - Given
        // Nesse caso a preparação está sendo feita no método setUp()

        // Executo o código a ser testado
        // Act - When
        $this->leiloeiro->avalia($leilao);

        $maiorValor = $this->leiloeiro->getMaiorValor();

        // Verifico se a saída é a esperada
        // Assert - Then
        self::assertEquals(2500, $maiorValor);
    }

    #[DataProvider('leilaoEmOrdemAleatoria')]
    #[DataProvider('leilaoEmOrdemCrescente')]
    #[DataProvider('leilaoEmOrdemDecrescente')]
    public function testAvaliadorDeveEncontrarOMenorValorDeLances(Leilao $leilao)
    {
        // Arrumo a casa para o teste
        // Arrange - Given
        // Nesse caso a preparação está sendo feita no método setUp()

        // Executo o código a ser testado
        // Act - When
        $this->leiloeiro->avalia($leilao);

        $menorValor = $this->leiloeiro->getMenorValor();

        // Verifico se a saída é a esperada
        // Assert - Then
        self::assertEquals(1700, $menorValor);
    }

    #[DataProvider('leilaoEmOrdemAleatoria')]
    #[DataProvider('leilaoEmOrdemCrescente')]
    #[DataProvider('leilaoEmOrdemDecrescente')]
    public function testAvaliadorDeveBuscar3MaioresValores(Leilao $leilao)
    {

        $this->leiloeiro->avalia($leilao);

        $maiores = $this->leiloeiro->getMaioresLances();
        self::assertCount(3, $maiores);
        static::assertEquals(2500, $maiores[0]->getValor());
        static::assertEquals(2000, $maiores[1]->getValor());
        static::assertEquals(1700, $maiores[2]->getValor());
    }

    public function testLeilaoVazioNaoPodeSerAvaliado()
    {
        //'Não é possível avaliar leilão vazio'
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Não é possível avaliar leilão vazio');

        $leilao =  new Leilao('Fusca Azul');
        $this->leiloeiro->avalia($leilao);
    }

    /* ------- DADOS -------- */
    public static function leilaoEmOrdemCrescente()
    {
        $leilao = new Leilao('Fiat 147 0KM');

        $maria = new Usuario('Maria');
        $joao = new Usuario('João');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($ana, 1700));
        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($maria, 2500));

        return [
            'ordem-crescente' => [$leilao]
        ];
    }

    public static function leilaoEmOrdemDecrescente()
    {
        $leilao = new Leilao('Fiat 147 0KM');

        $maria = new Usuario('Maria');
        $joao = new Usuario('João');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($maria, 2500));
        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($ana, 1700));

        return [
            'ordem-decrescente' => [$leilao]
        ];
    }

    public static function leilaoEmOrdemAleatoria()
    {
        $leilao = new Leilao('Fiat 147 0KM');

        $maria = new Usuario('Maria');
        $joao = new Usuario('João');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($maria, 2500));
        $leilao->recebeLance(new Lance($ana, 1700));

        return [
            'ordem-aleatoria' => [$leilao]
        ];
    }

    public function testLeilaoFinalizadoNaoPodeSerAvaliado()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Leilão já finalizado');
        $leilao = new Leilao('Fiat 147 0KM');
        $leilao->recebeLance(new Lance(new Usuario('Teste'), 2000));
        $leilao->finaliza();

        $this->leiloeiro->avalia($leilao);
    }
}
