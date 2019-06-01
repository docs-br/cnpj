<?php
/**
 * MIT License
 *
 * Copyright (c) 2018 PHP DLX
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace CNPJ\Tests\Validators;

use CNPJ\CNPJ;
use CNPJ\Exceptions\CNPJInvalidoException;
use CNPJ\Validators\ValidarDigitoVerificador;
use PHPUnit\Framework\TestCase;

/**
 * Class ValidarDigitoVerificadorTest
 * @package CNPJ\Tests\Validators
 * @coversDefaultClass \CNPJ\Validators\ValidarDigitoVerificador
 */
class ValidarDigitoVerificadorTest extends TestCase
{
    /**
     * @throws CNPJInvalidoException
     * @covers ::validar
     */
    public function test_Validar_deve_lancar_excecao_quando_digito_verificador_for_incorreto()
    {
        $cnpj = new CNPJ('53.707.384/0001-42');

        $this->expectException(CNPJInvalidoException::class);
        $this->expectExceptionCode(CNPJInvalidoException::DV_INCORRETO);

        (new ValidarDigitoVerificador())->validar($cnpj);
    }

    /**
     * @throws CNPJInvalidoException
     * @covers ::validar
     */
    public function test_Validar_deve_retornar_true_quando_digito_verificador_valido()
    {
        $cnpj = new CNPJ('16.511.545/0001-00');

        $is_valido = (new ValidarDigitoVerificador())->validar($cnpj);
        $this->assertTrue($is_valido);
    }
}
