<?php

declare(strict_types=1);

namespace SierraKomodo\BitWise\Tests\Unit\Class;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use SierraKomodo\BitWise\BitOperations;

/**
 * PHPUnit class test for {@link BitOperations}.
 */
#[TestDox('Unit testing for methods of BitOperations.')]
#[CoversClass(BitOperations::class)]
class BitFlagTest extends TestCase
{
    /**
     * @return array<string, array{int, int}>
     */
    public static function bitToMaskProvider(): array
    {
        return [
            '0 -> 1' => [0, 1],
            '1 -> 2' => [1, 2],
            '2 -> 4' => [2, 4],
            '8 -> 256' => [8, 256],
        ];
    }


    /**
     * @return array<string, array{int, int[]}>
     */
    public static function maskToBitsProvider(): array
    {
        return [
            '1 -> [0]' => [1, [0]],
            '2 -> [1]' => [2, [1]],
            '3 -> [0, 1]' => [3, [0, 1]],
            '256 -> [8]' => [256, [8]],
        ];
    }


    /**
     * @return array<string, array{int, int, int}>
     */
    public static function shiftBitsRightProvider(): array
    {
        return [
            '1 >> 1 => 0' => [1, 1, 0],
            '8 >> 1 => 4' => [8, 1, 4],
            '5 >> 1 => 2' => [5, 1, 2],
            '1 >> 2 => 0' => [1, 2, 0],
            '5 >> 2 => 1' => [5, 2, 1],
        ];
    }


    /**
     * @return array<string, array{int, int, int}>
     */
    public static function shiftBitsLeftProvider(): array
    {
        return [
            '1 << 1 => 2' => [1, 1, 2],
            '8 << 1 => 16' => [8, 1, 16],
            '5 << 1 => 10' => [5, 1, 10],
            '1 << 2 => 4' => [1, 2, 4],
            '5 << 2 => 20' => [5, 2, 20],
        ];
    }


    /**
     * @return array<string, array{int, int, int}>
     */
    public static function getBitProvider(): array
    {
        return [
            '1, 0 => 1' => [1, 0, 1],
            '1, 1 => 0' => [1, 1, 0],
            '2, 1 => 2' => [2, 1, 2],
            '5, 2 => 4' => [5, 2, 4],
            '5, 3 => 0' => [5, 3, 0],
        ];
    }


    /**
     * @return array<string, array{int, int, bool}>
     */
    public static function hasBitProvider(): array
    {
        return [
            '1, 0 => 1' => [1, 0, true],
            '1, 1 => 0' => [1, 1, false],
            '2, 1 => 2' => [2, 1, true],
            '5, 2 => 4' => [5, 2, true],
            '5, 3 => 0' => [5, 3, false],
        ];
    }


    /**
     * @return array<string, array{int, int, int}>
     */
    public static function setBitProvider(): array
    {
        return [
            '1, 1 => 3' => [1, 1, 3],
            '0, 1 => 2' => [0, 1, 2],
            '3, 1 => 3' => [3, 1, 3],
        ];
    }


    /**
     * @return array<string, array{int, int, int}>
     */
    public static function clearBitProvider(): array
    {
        return [
            '1, 1 => 1' => [1, 1, 1],
            '0, 1 => 0' => [0, 1, 0],
            '3, 1 => 1' => [3, 1, 1],
        ];
    }


    /**
     * @return array<string, array{int, int, int}>
     */
    public static function flipBitProvider(): array
    {
        return [
            '1, 1 => 1' => [1, 1, 3],
            '0, 1 => 0' => [0, 1, 2],
            '3, 1 => 1' => [3, 1, 1],
        ];
    }


    /**
     * @return array<string, array{int, int, int}>
     */
    public static function getFlagsProvider(): array
    {
        return [
            'Empty bitfield' => [0, 1, 0],
            'No match, single flags' => [1, 2, 0],
            'No match, multiple flags' => [3, 12, 0],
            'Identical flags' => [1, 1, 1],
            'Identical multiple flags' => [3, 3, 3],
            'Multiple flags, single flag match' => [7, 2, 2],
            'Multiple flags, one match' => [7, 26, 2],
            'Multiple flags, multiple matches' => [7, 22, 6],
        ];
    }


    /**
     * @return array<string, array{int, int, bool}>
     */
    public static function hasAnyFlagProvider(): array
    {
        return [
            'Empty bitfield' => [0, 1, false],
            'No match, single flags' => [1, 2, false],
            'No match, multiple flags' => [3, 12, false],
            'Identical flags' => [1, 1, true],
            'Identical multiple flags' => [3, 3, true],
            'Multiple flags, single flag match' => [7, 2, true],
            'Multiple flags, one match' => [7, 26, true],
            'Multiple flags, multiple matches' => [7, 22, true],
        ];
    }


    /**
     * @return array<string, array{int, int, bool}>
     */
    public static function hasAllFlagsProvider(): array
    {
        return [
            'Empty bitfield' => [0, 1, false],
            'No match, single flags' => [1, 2, false],
            'No match, multiple flags' => [3, 12, false],
            'Identical flags' => [1, 1, true],
            'Identical multiple flags' => [3, 3, true],
            'Multiple flags, single flag match' => [7, 2, true],
            'Multiple flags, partial match' => [7, 26, false],
            'Multiple flags, full match' => [7, 3, true],
        ];
    }


    /**
     * @return array<string, array{int, int, int}>
     */
    public static function setFlagsProvider(): array
    {
        return [
            'One flag to empty bitmask' => [0, 2, 2],
            'Multiple flags to empty bitmask' => [0, 8, 8],
            'Empty bitmask to one flag' => [2, 0, 2],
            'One flag to one flag' => [1, 2, 3],
            'One flag to one flag (reversed)' => [2, 1, 3],
            'Multiple flags to one flag' => [2, 5, 7],
            'Multiple flags to multiple flags' => [3, 12, 15],
        ];
    }


    /**
     * @return array<string, array{int, int, int}>
     */
    public static function clearFlagsProvider(): array
    {
        return [
            'Empty bitmask minus flag' => [0, 2, 0],
            'Flag minus empty bitmask' => [2, 0, 2],
            'Flag minus itself' => [2, 2, 0],
            'Flag minus other flag' => [2, 1, 2],
            'Multiple flags minus one flag' => [7, 2, 5],
            'Multiple flags minus multiple flags' => [7, 3, 4],
            'Multiple flags minus all flags' => [7, 7, 0],
            'Multiple flags minus multiple flags including other flags' => [7, 11, 4],
        ];
    }


    /**
     * @return array<string, array{int, int, int}>
     */
    public static function flipFlagsProvider(): array
    {
        return [
            'One flag plus empty bitmask' => [2, 0, 2],
            'Empty bitfield plus one flag' => [0, 2, 2],
            'One flag plus one other flag' => [2, 1, 3],
            'One flag plus itself' => [2, 2, 0],
            'Multiple flags plus one other flag' => [7, 8, 15],
            'Multiple flags plus one present flag' => [7, 4, 3],
            'Multiple flags plus some present flags' => [7, 3, 4],
            'Multiple flags plus some present and other flags' => [7, 12, 11],
            'Multiple flags plus all present flags' => [7, 7, 0],
        ];
    }


    #[TestDox('bit to bitmask conversion.')]
    #[DataProvider('bitToMaskProvider')]
    public function testBitToMask(int $bit, int $mask): void
    {
        $this::assertEquals($mask, BitOperations::bitToMask($bit));
    }


    /**
     * @param int $mask
     * @param int[] $bits
     * @return void
     */
    #[TestDox('bitmask to bit list conversion.')]
    #[DataProvider('maskToBitsProvider')]
    public function testBitMaskToBits(int $mask, array $bits): void
    {
        $this::assertEquals($bits, BitOperations::bitMaskToBits($mask));
    }


    /**
     * @param int $mask
     * @param int[] $bits
     * @return void
     */
    #[TestDox('bit list to bitmask conversion.')]
    #[DataProvider('maskToBitsProvider')]
    public function testBitsToMask(int $mask, array $bits): void
    {
        $this::assertEquals($mask, BitOperations::bitsToMask($bits));
    }


    #[TestDox('shift bits right method.')]
    #[DataProvider('shiftBitsRightProvider')]
    public function testShiftBitsRight(int $before, int $positions, int $after): void
    {
        $this::assertEquals($after, BitOperations::shiftBitsRight($before, $positions));
    }

    #[TestDox('shift bits left method.')]
    #[DataProvider('shiftBitsLeftProvider')]
    public function testShiftBitsLeft(int $before, int $positions, int $after): void
    {
        $this::assertEquals($after, BitOperations::shiftBitsLeft($before, $positions));
    }


    #[TestDox('get bit method.')]
    #[DataProvider('getBitProvider')]
    public function testGetBit(int $field, int $bit, int $expected): void
    {
        $this::assertEquals($expected, BitOperations::getBit($field, $bit));
    }
    
    
    #[TestDox('has bit method.')]
    #[DataProvider('hasBitProvider')]
    public function testHasBit(int $field, int $bit, bool $expected): void
    {
        $this::assertEquals($expected, BitOperations::hasBit($field, $bit));
    }


    #[TestDox('set bit method.')]
    #[DataProvider('setBitProvider')]
    public function testSetBit(int $field, int $bit, int $expected): void
    {
        $this::assertEquals($expected, BitOperations::setBit($field, $bit));
    }


    #[TestDox('clear bit method.')]
    #[DataProvider('clearBitProvider')]
    public function testClearBit(int $field, int $bit, int $expected): void
    {
        $this::assertEquals($expected, BitOperations::clearBit($field, $bit));
    }


    #[TestDox('flip bit method.')]
    #[DataProvider('flipBitProvider')]
    public function testFlipBit(int $field, int $bit, int $expected): void
    {
        $this::assertEquals($expected, BitOperations::flipBit($field, $bit));
    }


    #[TestDox('get flags method.')]
    #[DataProvider('getFlagsProvider')]
    public function testGetFlags(int $field, int $mask, int $expected): void
    {
        $this::assertEquals($expected, BitOperations::getFlags($field, $mask));
    }


    #[TestDox('has any flag method.')]
    #[DataProvider('hasAnyFlagProvider')]
    public function testHasAnyFlag(int $field, int $mask, bool $expected): void
    {
        $this::assertEquals($expected, BitOperations::hasAnyFlag($field, $mask));
    }


    #[TestDox('has all flags method.')]
    #[DataProvider('hasAllFlagsProvider')]
    public function testHasAllFlags(int $field, int $mask, bool $expected): void
    {
        $this::assertEquals($expected, BitOperations::hasAllFlags($field, $mask));
    }


    #[TestDox('set flags method.')]
    #[DataProvider('setFlagsProvider')]
    public function testSetFlags(int $field, int $mask, int $expected): void
    {
        $this::assertEquals($expected, BitOperations::setFlags($field, $mask));
    }


    #[TestDox('clear flags method.')]
    #[DataProvider('clearFlagsProvider')]
    public function testClearFlags(int $field, int $mask, int $expected): void
    {
        $this::assertEquals($expected, BitOperations::clearFlags($field, $mask));
    }


    #[TestDox('flip flags method.')]
    #[DataProvider('flipFlagsProvider')]
    public function testFlipFlags(int $field, int $mask, int $expected): void
    {
        $this::assertEquals($expected, BitOperations::flipFlags($field, $mask));
    }
}
