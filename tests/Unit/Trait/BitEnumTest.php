<?php

declare(strict_types=1);

namespace SierraKomodo\BitWise\Tests\Unit\Trait;

use Exception;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SierraKomodo\BitWise\BitOperations;
use SierraKomodo\BitWise\Tests\TestBitEnum;
use SierraKomodo\BitWise\Trait\BitEnum;

#[TestDox('Unit testing for methods of BitEnum.')]
#[CoversClass(BitEnum::class)]
#[UsesClass(BitOperations::class)]
class BitEnumTest extends TestCase
{
    /**
     * @return array<string, array{TestBitEnum, int, int}>
     */
    public static function singleBitProvider(): array
    {
        return [
            '0th position' => [TestBitEnum::A, 0, 1],
            '2nd position' => [TestBitEnum::C, 2, 4],
            '5th position' => [TestBitEnum::F, 5, 32],
        ];
    }


    /**
     * @return array<string, array{TestBitEnum[], int, bool}>
     */
    public static function multipleBitsProvider(): array
    {
        return [
            'Bit mask with single flag' => [[TestBitEnum::B], 2, false],
            'Bit mask with multiple flags' => [[TestBitEnum::B, TestBitEnum::D], 10, false],
            'Empty bit mask' => [[], 0, false],
            'Bit mask with single flag outside of defined range' => [[], 256, true],
            'Bit mask with multiple flags outside of defined range' => [[], 768, true],
            'Bit mask with additional flag outside of defined range' => [[TestBitEnum::A], 257, true],
        ];
    }


    #[TestDox('conversion of enum to bit position.')]
    #[DataProvider('singleBitProvider')]
    public function testToBit(TestBitEnum $enum, int $bit, int $bitMask): void
    {
        $this->assertEquals($bit, $enum->toBit());
    }


    #[TestDox('conversion of enum to bit mask.')]
    #[DataProvider('singleBitProvider')]
    public function testToMask(TestBitEnum $enum, int $bit, int $bitMask): void
    {
        $this->assertEquals($bitMask, $enum->toMask());
    }


    #[TestDox('creation of enum from bit position (Null-safe).')]
    #[DataProvider('singleBitProvider')]
    public function testTryFromBit(TestBitEnum $enum, int $bit, int $bitMask): void
    {
        $this->assertEquals($enum, TestBitEnum::tryFromBit($bit));
    }


    #[TestDox('creation of enum from bit position (Not null-safe).')]
    #[DataProvider('singleBitProvider')]
    public function testFromBit(TestBitEnum $enum, int $bit, int $bitMask): void
    {
        $this->assertEquals($enum, TestBitEnum::fromBit($bit));
    }


    #[TestDox('handling of non-existent bit position (Null-safe).')]
    public function testTryFromBitHandlesNull(): void
    {
        $this->assertNull(TestBitEnum::tryFromBit(10));
    }


    #[TestDox('handling of non-existent bit position (Not null-safe).')]
    public function testFromBitHandlesNull(): void
    {
        $this->expectException(Exception::class);
        TestBitEnum::fromBit(10);
    }


    /**
     * @param TestBitEnum[] $enumList
     * @param int $mask
     * @param bool $expectError
     * @return void
     */
    #[TestDox('creation of enum list from bit mask.')]
    #[DataProvider('multipleBitsProvider')]
    public function testFromMask(array $enumList, int $mask, bool $expectError): void
    {
        if ($expectError) {
            $this->expectException(Exception::class);
        }
        $this->assertEquals($enumList, TestBitEnum::fromMask($mask));
    }
}
