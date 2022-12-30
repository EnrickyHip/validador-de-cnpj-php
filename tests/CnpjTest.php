<?php

declare(strict_types=1);

require_once __DIR__ . "/../vendor/autoload.php";

use Enricky\CnpjManager\Cnpj;

//* validateFormat

it("should validate format", function (string $cnpj) {
  expect(Cnpj::validateFormat($cnpj))->toBeTrue();
})->with(["00.000.000/0001-00", "99.999.999/9999-99"]);

it("should not validate format", function (string $cnpj) {
  expect(Cnpj::validateFormat($cnpj))->toBeFalse();
})->with("invalid_formats");

//* generate

it("should generate cnpj with valid format", function () {
  for ($i = 0; $i < 15; $i++) {
    $cnpj = Cnpj::generate();
    expect(Cnpj::validateFormat($cnpj))->toBeTrue();
  }
});

it("should generate valid CNPJ", function () {
  for ($i = 0; $i < 15; $i++) {
    $cnpj = Cnpj::generate();
    expect(Cnpj::validate($cnpj))->toBeTrue();
  }
});

//* validate

it("should return false if cnpj has not 14 digits", function (string $cnpj) {
  expect(Cnpj::validate($cnpj))->toBeFalse();
})->with("non_14_digits");

it("should not validate when format is not correct", function (string $cnpj) {
  expect(Cnpj::validate($cnpj))->toBeFalse();
})->with("valid_but_not_formated_cnpjs");

it("should not validate when cnpj is not valid", function (string $cnpj) {
  expect(Cnpj::validate($cnpj))->toBeFalse();
})->with("invalid_cnpjs");

it("should validate cnpjs", function (string $cnpj) {
  expect(Cnpj::validate($cnpj))->toBeTrue();
})->with("valid_cnpjs");

//* format

it("should return null if is not possible to format cnpj", function (string $cnpj) {
  expect(Cnpj::format($cnpj))->toBeNull();
})->with("non_14_digits");

it("should format cnpj", function () {
  expect(Cnpj::format("27303239456634"))->toBe("27.303.239/4566-34");
  expect(Cnpj::format("649.98136054354"))->toBe("64.998.136/0543-54");
  expect(Cnpj::format("65-280-961-0001-43"))->toBe("65.280.961/0001-43");
  expect(Cnpj::format("289 asasa88a   43w2sassa7.56as002"))->toBe("28.988.432/7560-02");
});

it("should format not formated cnpjs and validate it", function (string $cnpj) {
  $formatedCnpj = Cnpj::format($cnpj);
  expect(Cnpj::validateFormat($formatedCnpj))->toBeTrue();
  expect(Cnpj::validate($formatedCnpj))->toBeTrue();
})->with("valid_but_not_formated_cnpjs");

//* cleanUp

it("should clean up cnpj", function () {
  expect(Cnpj::cleanUp("27.103.239/0001-56"))->toBe("27103239000156");
  expect(Cnpj::cleanUp("649.98136054123"))->toBe("64998136054123");
  expect(Cnpj::cleanUp("65-280-961-0001-43"))->toBe("65280961000143");
  expect(Cnpj::cleanUp("289 asasa88a  43w2sassa7.56as002"))->toBe("28988432756002");
});

