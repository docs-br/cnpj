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

namespace CNPJ\ORM\Doctrine\Types;


use CNPJ\CNPJ;
use CNPJ\Exceptions\CNPJInvalidoException;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use PHPUnit\Framework\TestCase;

class CNPJTypeTest extends TestCase
{
    /**
     * @return CNPJType
     * @throws DBALException
     */
    public function getCNPJType(): CNPJType
    {
        if (!Type::hasType(CNPJType::CNPJ)) {
            Type::addType(CNPJType::CNPJ, CNPJType::class);
        }

        /** @var CNPJType $cnpj_type */
        $cnpj_type = Type::getType(CNPJType::CNPJ);
        return $cnpj_type;
    }

    /**
     * @throws DBALException
     * @throws CNPJInvalidoException
     */
    public function test_ConvertToDatabaseValue_deve_converter_classe_CNPJ_para_valor_do_bd()
    {
        /** @var AbstractPlatform $platform */
        $platform = $this->createMock(AbstractPlatform::class);

        $cnpj = new CNPJ('04.669.452/0001-11');
        $cnpj_type = $this->getCNPJType();

        $valor_bd = $cnpj_type->convertToDatabaseValue($cnpj, $platform);

        $this->assertIsString($valor_bd);
        $this->assertEquals($cnpj->getCnpjMask(), $valor_bd);
    }

    /**
     * @throws CNPJInvalidoException
     * @throws DBALException
     */
    public function test_ConvertToDatabaseValue_deve_lancar_excecao_quando_tentar_converter_CNPJ_invalido()
    {
        /** @var AbstractPlatform $platform */
        $platform = $this->createMock(AbstractPlatform::class);

        $cnpj = new CNPJ('04.669.452/0001-10');
        $cnpj_type = $this->getCNPJType();

        $this->expectException(CNPJInvalidoException::class);
        $this->expectExceptionCode(CNPJInvalidoException::DV_INCORRETO);

        $cnpj_type->convertToDatabaseValue($cnpj, $platform);
    }

    /**
     * @throws DBALException
     */
    public function test_ConvertToPHPValue_deve_converter_valor_no_bd_para_classe_CNPJ()
    {
        /** @var AbstractPlatform $platform */
        $platform = $this->createMock(AbstractPlatform::class);

        $str_cnpj = '04.669.452/0001-11';
        $cnpj_type = $this->getCNPJType();

        $cnpj = $cnpj_type->convertToPHPValue($str_cnpj, $platform);

        $this->assertInstanceOf(CNPJ::class, $cnpj);
        $this->assertEquals($str_cnpj, $cnpj->getCnpjMask());
    }
}
