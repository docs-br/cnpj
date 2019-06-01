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

namespace CNPJ;

use CNPJ\Validators\ValidarDigitoVerificador;

class CNPJ
{
    /** @var int */
    private $cnpj;

    /**
     * CNPJ constructor.
     * @param $cnpj
     */
    public function __construct(string $cnpj)
    {
        $this->cnpj = preg_replace('~[^0-9]~',  '', $cnpj);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getCnpjMask();
    }

    /**
     * @return int
     */
    public function getCnpj(): int
    {
        return $this->cnpj;
    }

    /**
     * Retornar todos os números desse CNPJ, inclusive zeros a esquerda
     * @return string
     */
    public function getCnpjCompleto(): string
    {
        return str_pad($this->getCnpj(), 14, '0', STR_PAD_LEFT);
    }

    /**
     * Retorna o CNPJ com a máscara aplicada
     * @return string
     */
    public function getCnpjMask(): string
    {
        $numeros_cnpj = $this->getCnpjCompleto();
        $cnpj_mask = substr($numeros_cnpj, 0, 2) . '.' .
            substr($numeros_cnpj, 2, 3) . '.' .
            substr($numeros_cnpj, 5, 3) . '/' .
            substr($numeros_cnpj, 8, 4) . '-' .
            substr($numeros_cnpj, 12, 2);

        return $cnpj_mask;
    }

    /**
     * Retorna o CNPJ com os números do meio ocultos, substituídos por *
     * @return string
     */
    public function getCnpjOculto(): string
    {
        $numeros_cnpj = $this->getCnpjCompleto();
        $cnpj_oculto = substr($numeros_cnpj, 0, 2) . '.' .
            str_repeat('*', 3) . '.' .
            str_repeat('*', 3) . '/' .
            substr($numeros_cnpj, 8, 4) . '-' .
            substr($numeros_cnpj, 12, 2);

        return $cnpj_oculto;
    }

    /**
     * Retorna os números base do CNPJ
     * @return string
     */
    public function getNumerosBase(): string
    {
        $cnpj_completo = $this->getCnpjCompleto();
        return substr($cnpj_completo, 0, 12);
    }

    /**
     * Retorna o dígito verificador do CNPJ informado
     * @return string
     */
    public function getDigitoVerificador(): string
    {
        $cnpj_completo = $this->getCnpjCompleto();
        return substr($cnpj_completo, -2);
    }

    /**
     * Verifica se é um número de CNPJ válido
     * @return bool
     * @throws Exceptions\CNPJInvalidoException
     */
    public function isValido(): bool
    {
        return (new ValidarDigitoVerificador())->validar($this);
    }
}