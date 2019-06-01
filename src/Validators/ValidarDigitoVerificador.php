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

namespace CNPJ\Validators;


use CNPJ\CNPJ;
use CNPJ\Exceptions\CNPJInvalidoException;

class ValidarDigitoVerificador
{
    /**
     * Validar o dígito verificador um CNPJ
     * @param CNPJ $cnpj
     * @return bool
     * @throws CNPJInvalidoException
     */
    public function validar(CNPJ $cnpj): bool
    {
        $base = $cnpj->getNumerosBase();
        $dv_informado = $cnpj->getDigitoVerificador();

        $dv1 = $this->calcularDV($base);
        $dv2 = $this->calcularDV("{$base}{$dv1}");

        $dv_calculado = "{$dv1}{$dv2}";

        if ($dv_calculado !== $dv_informado) {
            throw CNPJInvalidoException::digitoVerificadorInvalido($cnpj->getCnpjMask());
        }

        return true;
    }

    /**
     * Calcula dígito verificador
     * @param string $numero_base
     * @return int
     */
    private function calcularDV(string $numero_base): int
    {
        $numeros = array_reverse(str_split($numero_base));
        $multiplicador = 9;
        $soma = 0;

        foreach ($numeros as $n) {
            if ($multiplicador < 2) {
                $multiplicador = 9;
            }

            $soma += $multiplicador-- * $n;
        }

        $mod11 = $soma % 11;

        return $mod11 === 10 ? 0 : $mod11;
    }
}