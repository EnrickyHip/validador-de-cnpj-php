<?php

declare(strict_types=1);

namespace Enricky\CnpjManager;

class Cnpj
{
  const REGEX = "/^(\d{2})\.(\d{3})\.(\d{3})\/(\d{4})-(\d{2})$/";

  /**
   * Checa se o formato enviado corresponde com o formato tradicional de CNPJ's: 99.999.999/0001-99
   * @param string $cnpj CNPJ a ser checado.
   * @return bool
   */
  public static function validateFormat(string $cnpj): bool
  {
    return boolval(preg_match(Self::REGEX, $cnpj));
  }

  /**
   * Remove todo tipo de caractere que não seja um dígito.
   * @param string $cnpj Cnpj a ser limpado.
   * @return string O CNPJ com apenas dígitos.
   *
   * exemplo:
   *
   * ```
   * $CNPJLimpo = Cnpj::cleanUp('36.865.382/0001-63');
   * var_dump($CNPJLimpo);
   * //output: string(14) "36865382000163"
   * ```
   */

  public static function cleanUp(string $cnpj): string
  {
    return preg_replace("/\D+/", "", $cnpj); //remove tudo que não é digito
  }

  /**
   * Formata um CNPJ no formato: 99.999.999/0001-99.
   * @param string $cnpj CNPJ a ser formatado. Esse parâmetro é extremamente livre, pois a função filtra tudo que
   * não for dígito.
   * @return string CNPJ formatado. Caso não seja possível formatar o cnpj por não possuir a quantidade necessária
   * de caracteres, o retorno será `null`
   *
   * exemplo:
   *
   *  ```
   * $cnpjFormatado = Cnpj::format('36865382000163');
   * var_dump($cnpjFormatado);
   * //output: string(18) "36.865.382/0001-63"
   *
   * $cnpjFormatado = Cnpj::format('368.65382000163');
   * var_dump($cnpjFormatado);
   * //output: string(18) "36.865.382/0001-63"
   *
   * $cnpjFormatado = Cnpj::format('36-865-382-0001-63');
   * var_dump($cnpjFormatado);
   * //output: string(18) "36.865.382/0001-63"
   *
   * $cnpjFormatado = Cnpj::format('289 asasa88a   43w2sassa7.56as002');
   * var_dump($cnpjFormatado);
   * //output: string(18) "28.988.432/7560-02"
   * ```
   */
  public static function format(string $cnpj): string | null
  {
    $cleanCnpj = self::cleanUp($cnpj);
    if (strlen($cleanCnpj) < 14) {
      return null;
    }

    return preg_replace("/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/", "$1.$2.$3/$4-$5", $cleanCnpj);
  }

  /**
   * Checa validade de um CNPJ.
   * @param string $cnpj Cnpj a ser validado. O CNPJ obrigatoriamente precisa estar no formato: 12.123.123/0001-12 ou
   * 12123123000112. Mesmo que os dígitos sejam válidos, caso a string não esteja nesses formatos, o retorno será falso.
   * @return bool
  */

  public static function validate(string $cnpj): bool
  {
    $justNumbersRegex = "/^\d{14}$/";
    if (!preg_match($justNumbersRegex, $cnpj) && !Cnpj::validateFormat($cnpj)) {
      return false;
    }

    $cleanCnpj = Cnpj::cleanUp($cnpj);
    if (strlen($cleanCnpj) !== 14) {
      return false;
    }

    $parcialCnpj = substr($cleanCnpj, 0, -2);
    $firstDigit = self::createDigit($parcialCnpj);
    $secondDigit = self::createDigit($parcialCnpj . $firstDigit);

    $newCnpj = $parcialCnpj . $firstDigit . $secondDigit;
    return $newCnpj === $cleanCnpj;
  }

  /**
   * Gera um CNPJ válido aleatório.
   * @return string O cnpj no formato: 99.999.999/0001-99.
   */

  public static function generate(): string
  {
    $cnpj = strval(rand(10000000, 99999999)) . "0001";
    $firstDigit = self::createDigit($cnpj);
    $secondDigit = self::createDigit($cnpj + $firstDigit); // o + concatena o first digit no fim da string
    return strval(self::format($cnpj . $firstDigit . $secondDigit));
  }
  
  private static function createDigit(string $parcialCnpj): string
  {
    $cnpjArray = str_split($parcialCnpj);
    $multiplicator = count($cnpjArray) - 6;

    $cnpjMultiplicateArray = array_map(function (string $digit) use (&$multiplicator) {
      $multiplicator--;
      if ($multiplicator === 1) {
        $multiplicator = 9;
      }
      return intval($digit) * $multiplicator;
    }, $cnpjArray);

    $total = array_reduce($cnpjMultiplicateArray, fn(int $count, int $value) => $value + $count, 0);

    if ($total % 11 < 2) {
      $digit = 0;
    } else {
      $digit = 11 - ($total % 11);
    }

    return strval($digit);
  }
}
