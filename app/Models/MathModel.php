<?php

declare(strict_types = 1);

namespace App\Models;

final class MathModel
{
    public const ROMAN_NUMBER_MAPPING = [
        'I' => 1,
        'V' => 5,
        'X' => 10,
        'L' => 50,
        'C' => 100,
        'D' => 500,
        'M' => 1000,
    ];

    public const ROMAN_NUMBER_SUBTRACT_MAPPING = [
        // -1
        'IV' => 4,
        'IX' => 9,
        'IL' => 49,
        'IC' => 99,
        'ID' => 499,
        'IM' => 999,
        // -5
        'VL' => 45,
        'VC' => 95,
        'VD' => 495,
        'VM' => 995,
        // -10
        'XL' => 40,
        'XC' => 90,
        'XD' => 490,
        'XM' => 990,
        // -50
        'LD' => 450,
        'LM' => 950,
        // -100
        'CD' => 400,
        'CM' => 900,
    ];

    public function getRomanToInt(string $string): int
    {
        $int = 0;
        $strlen = strlen($string);

        for ($i = 0; $i < $strlen; $i++) {
            $index = $i;
            $letter = $string[$index];
            $nextLetter = $string[++$index] ?? '';

            if (array_key_exists(strtoupper($letter . $nextLetter), self::ROMAN_NUMBER_SUBTRACT_MAPPING)) {
                $int += self::ROMAN_NUMBER_SUBTRACT_MAPPING[$letter . $nextLetter];
                $i++;
            } elseif (array_key_exists(strtoupper($letter), self::ROMAN_NUMBER_MAPPING)) {
                $int += self::ROMAN_NUMBER_MAPPING[$letter];
            }
        }

        return $int;
    }

    /**
     * @return array<int, string>
     */
    public function getUniqueLetters(string $string): array
    {
        $iMax = strlen($string);
        $uniqueLetters = [];

        foreach (range(0, --$iMax) as $item) {
            if (in_array($string[$item], $uniqueLetters, true)) {
                continue;
            }

            $uniqueLetters[] = $string[$item];
        }

        return $uniqueLetters;
    }

    /**
     * @return array<int, string>
     */
    public function getVowels(string $string): array
    {
        $iMax = strlen($string);
        $vowels = ['a', 'e', 'i', 'o', 'u'];
        $vowelArray = [];

        foreach (range(0, --$iMax) as $item) {
            if (!in_array($string[$item], $vowels, true)) {
                continue;
            }

            $vowelArray[] = $string[$item];
        }

        return $vowelArray;
    }

    /**
     * @return array<int, int>
     */
    public function getMultiples(int $count, int $number): array
    {
        $multiples = [];
        $value = 0;

        foreach (range(0, $count) as $ignored) {
            $value += $number;
            $multiples[] = $value;
        }

        return $multiples;
    }

    public function getAverageWordLength(string $string): string
    {
        $string = str_replace([',', '.'], '', $string);
        $wordCount = count(explode(' ', $string));
        $textLength = strlen(str_replace(' ', '', $string));

        return number_format((float)($textLength / $wordCount), 2);
    }

    /**
     * @return array<int, int>
     */
    public function getFactors(int $number): array
    {
        $factors = [];

        foreach (range(1, $number) as $item) {
            if ($number % $item !== 0) {
                continue;
            }

            $factors[] = (int)$item;
        }

        return $factors;
    }

    public function isPalindrome(mixed $word): bool
    {
        return $word === strrev($word);
    }

    /**
     * @return array<int|string, float|int>
     */
    public function getItems(?string $settings, array $numbersParam): array
    {
        if ($settings === null || $settings === 'all') {
            return $this->getEvenAndOdd($numbersParam);
        }

        if ($settings === 'even') {
            return $this->getItemsEven($numbersParam);
        }

        return $this->getItemsOdd($numbersParam);
    }

    /**
     * @return array<int|string, float|int>
     */
    public function getEvenAndOdd(array $numbersParam): array
    {
        return [$numbersParam, 'sum' => array_sum($numbersParam)];
    }

    /**
     * @return array<int|string, float|int>
     */
    public function getItemsEven(array $numbersParam): array
    {
        $numbers = array_filter($numbersParam, static fn($item) => (int)$item % 2 === 0);
        return [array_values($numbers), 'sum' => array_sum($numbers)];
    }

    /**
     * @return array<int|string, float|int>
     */
    public function getItemsOdd(array $numbersParam): array
    {
        $numbers = array_filter($numbersParam, static fn($item) => (int)$item % 2 !== 0);
        return [array_values($numbers), 'sum' => array_sum($numbers)];
    }

    /**
     * @return array<int, int|null>
     */
    public function getFibonacciSequence(int $count): array
    {
        $sequence = [];

        foreach (range(1, $count) as $item) {
            $sequence[] = $this->getFibonacciValue($item);
        }

        return $sequence;
    }

    public function getFibonacciValue(int $number): ?int
    {
        if ($number <= 0) {
            return null;
        }

        if ($number === 1) {
            return 0;
        }

        if ($number === 2 || $number === 3) {
            return 1;
        }

        $fibValues = [0, 1, 1];

        for ($i = 3; $i < $number; $i++) {
            $fibValues[$i] = $fibValues[$i - 2] + $fibValues[$i - 1];
        }

        return $fibValues[$number - 1];
    }

    /**
     * @return array<int, int|string>
     */
    public function getFizzBuzzResult(int $number): array
    {
        $result = [];

        foreach (range(0, $number) as $item) {
            $result[] = $this->getFizzbuzzFromNumber($item);
        }

        return $result;
    }

    public function getFizzbuzzFromNumber(int $number): string|int
    {
        switch ($number) {
            case $number % 15 === 0:
                return 'fizzbuzz';

            case $number % 3 === 0:
                return 'fizz';

            case $number % 5 === 0:
                return 'buzz';

            default:
                return $number;
        }
    }

    /**
     * @return array<int, int>
     */
    public function getPrimes(int $from, int $count): array
    {
        $i = $from - 1;
        $primes = [];

        while (count($primes) !== $count) {
            $i++;

            if (!$this->isPrimeNumber($i)) {
                continue;
            }

            $primes[] = $i;
        }

        return $primes;
    }

    public function isPrimeNumber(int $i): bool
    {
        return count($this->getDividers($i)) <= 2;
    }

    /**
     * @return array<int, int>
     */
    public function getDividers(int $number): array
    {
        $dividers = [];
        $divider = $number;

        while ($divider > 0) {
            if ($number % $divider === 0) {
                $dividers[] = $divider;
            }

            $divider--;
        }

        return $dividers;
    }
}