<?php

declare(strict_types=1);

namespace SierraKomodo\BitWise\Traits;

use BackedEnum;
use Exception;
use SierraKomodo\BitWise\BitOperations;

/**
 * Helper trait for enumerations intended to be used as bitflags.
 */
trait BitEnum
{
    /**
     * Returns the bit position for this bit flag. By default, this assumes the value of the enum case is the bit
     * position.
     *
     * @return int The bit position for this flag.
     */
    public function toBit(): int
    {
        return $this->value;
    }


    /**
     * Returns the decimal bitmask representation for this bit flag, using the bit position provided by
     * {@link BitEnum::toBit()}.
     *
     * @return int The decimal bitmask representation for this flag.
     */
    public function toMask(): int
    {
        return BitOperations::bitToMask($this->toBit());
    }


    /**
     * Fetches a backed enumeration for the provided bit position. By default, this assumes the value of the enum case
     * is the bit position and uses the result of {@link BackedEnum::tryFrom()}.
     *
     * @param int $bit The bit position to seek.
     * @return BackedEnum Matching enumeration.
     */
    public static function fromBit(int $bit): BackedEnum
    {
        return self::tryFrom($bit);
    }


    /**
     * Converts a decimal bitmask into an array of enumeration values, matching the flipped bits contained in the mask.
     *
     * @param int $mask Bitmask to parse.
     * @return BackedEnum[] Array of matching enum flags.
     * @throws Exception
     */
    public static function fromMask(int $mask): array
    {
        $return = [];
        $bits = BitOperations::bitMaskToBits($mask);
        foreach ($bits as $bit) {
            $enum = self::tryFrom($bit);
            if (!$enum) {
                throw new Exception("Flipped bit in position `{$bit}` is not in the range of valid bits.");
            }
            $return[] = $enum;
        }
        return $return;
    }
}
