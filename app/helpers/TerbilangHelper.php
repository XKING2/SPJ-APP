<?php

namespace App\Helpers;

class TerbilangHelper
{
    public static function terbilang($number)
    {
        $number = abs($number);
        $words = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];
        $temp = "";

        if ($number < 12) {
            $temp = " " . $words[$number];
        } else if ($number < 20) {
            $temp = self::terbilang($number - 10) . " belas";
        } else if ($number < 100) {
            $temp = self::terbilang($number / 10) . " puluh" . self::terbilang($number % 10);
        } else if ($number < 200) {
            $temp = " seratus" . self::terbilang($number - 100);
        } else if ($number < 1000) {
            $temp = self::terbilang($number / 100) . " ratus" . self::terbilang($number % 100);
        } else if ($number < 2000) {
            $temp = " seribu" . self::terbilang($number - 1000);
        } else if ($number < 1000000) {
            $temp = self::terbilang($number / 1000) . " ribu" . self::terbilang($number % 1000);
        } else if ($number < 1000000000) {
            $temp = self::terbilang($number / 1000000) . " juta" . self::terbilang($number % 1000000);
        } else if ($number < 1000000000000) {
            $temp = self::terbilang($number / 1000000000) . " miliar" . self::terbilang(fmod($number, 1000000000));
        } else if ($number < 1000000000000000) {
            $temp = self::terbilang($number / 1000000000000) . " triliun" . self::terbilang(fmod($number, 1000000000000));
        }

        return trim($temp);
    }

    public static function terbilangRupiah($number)
    {
        return ucwords(self::terbilang($number)) . " rupiah";
    }
}
