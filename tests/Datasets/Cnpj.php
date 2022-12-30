<?php

dataset('invalid_formats', function () {
  return [
    "12.312.3000-00",
    "00.000.000.0001.05",
    "aa.aaa.aaa/aaaa-aa",
    "aaaaaaaaaaaaaa",
    "aa.aaa.000/0001-00",
    "00-000-000-0000-05",
    "00-000-000/0001.05",
    "000-000.000-05",
    "111111111111111",
    "00.000.000.0000.00",
    "999999999",
    "000.000/0000-00",
    "123123123123",
    "123.123.123-123",
  ];
});

dataset('non_14_digits', function () {
  return [
    "999999",
    "aaaa",
    "2384729834",
    "23.472.983/0001-4",
    "99.999.999",
    "aaa.a",
    "6767856387465721",
    "12.123.123/0001-123",
    "652-809-610-433-123",
  ];
});

dataset('valid_but_not_formated_cnpjs', function () {
  return [
    "22643.564/0001-85",
    "73.815.9850001-87",
    "42.943.294/000135",
    "87.638/561/0001-07",
    "14.713410/0001-94",
    "68-566-823-0001-12",
    "70.740599.0001-85",
    "26.342.328.0001.80"
  ];
});

dataset('valid_cnpjs', function () {
  return [
    "22.643.564/0001-85",
    "73.815.985/0001-87",
    "42.943.294/0001-35",
    "87.638.561/0001-07",
    "14713410000194",
    "68566823000112",
    "70740599000185",
    "26342328000180"
  ];
});

dataset('invalid_cnpjs', function () {
  return [
    "22.743.564/0001-85",
    "73.825.985/0001-87",
    "42.943.294/2001-35",
    "87.638.561/0001-08",
    "1713410000194",
    "68565823070112",
    "70750199000185",
    "26342328050180"
  ];
});