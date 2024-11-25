<?php

declare(strict_types=1);

namespace SierraKomodo\BitWise;

use JetBrains\PhpStorm\Pure;

/**
 * General helpers for bitflag operations. Bitmasks in all methods are assumed to be in decimal form.
 */
class BitOperations
{
    /** @var int Semantic constant for a 0 int intended for use as a bitfield */
    public const EMPTY_BITFIELD = 0;


    /**
     * @var int Semantic constant for a ~0 int intended for use as a bitfield
     * @noinspection PhpUnused Not used within the library, but intended to be used by other projects.
     */
    public const ALL_BITFIELDS = ~0;


    // Mask <-> Bits conversion methods

    /**
     * Returns a bitmask with the given bit position flipped. Equivalent of `1 << $bit`.
     *
     * @param int $bit A bit position, 0-indexed.
     * @return int Bitmask.
     */
    #[Pure] public static function bitToMask(int $bit): int
    {
        return self::shiftBitsLeft(1, $bit);
    }


    /**
     * Converts a bitmask to a list of bit positions. Reversal of {@link BitOperations::bitsToMask()}.
     *
     * @param int $field Bitmask.
     * @return int[] Bit positions that are flipped in `$field`, 0-indexed.
     * @noinspection PhpPureFunctionMayProduceSideEffectsInspection
     */
    #[Pure] public static function bitMaskToBits(int $field): array
    {
        $return = [];
        $binary = str_split(decbin($field));
        foreach ($binary as $index => $bit) {
            if ($bit === '1') {
                $position = count($binary) - $index - 1;
                $return[] = $position;
            }
        }
        sort($return);
        return $return;
    }


    /**
     * Converts a list of bit positions into a bit mask, assuming each listed bit is flipped. Reversal of
     * {@link BitOperations::bitMaskToBits()}.
     *
     * @param int[] $bits Bit positions that are flipped, 0-indexed.
     * @return int Bitmask.
     */
    #[Pure] public static function bitsToMask(array $bits): int
    {
        $mask = self::EMPTY_BITFIELD;
        foreach ($bits as $bitPosition) {
            $mask |= self::bitToMask($bitPosition);
        }
        return $mask;
    }


    /// Bit shifting methods.

    /**
     * Shifts all bits in bitmask `$field` right by `$bits` positions. Equivalent of `$field >> $bits`.
     *
     * @param int $field Bitmask.
     * @param int $bits Positions to shift right.
     * @return int Updated bitmask.
     */
    #[Pure] public static function shiftBitsRight(int $field, int $bits): int
    {
        return $field >> $bits;
    }


    /**
     * Shifts all bits in bitmask `$field` left by `$bits` positions. Equivalent of `$field << $bits`.
     *
     * @param int $field Bitmask.
     * @param int $bits Positions to shift left.
     * @return int Updated bitmask.
     */
    #[Pure] public static function shiftBitsLeft(int $field, int $bits): int
    {
        return $field << $bits;
    }


    /// Single bit manipulation methods.

    /**
     * Retrieves the bitmask for the bit in the given position `$bit` in the bitmask `$field`, if flipped.
     *
     * @param int $field Bitmask.
     * @param int $bit A bit position, 0-indexed.
     * @return int Either the bitmask for the given bit position if flipped, or `0`.
     */
    #[Pure] public static function getBit(int $field, int $bit): int
    {
        return $field & self::bitToMask($bit);
    }


    /**
     * Checks if the bit in position `$bit` is set in the bitmask `$field`.
     *
     * @param int $field Bitmask.
     * @param int $bit A bit position, 0-indexed.
     * @return bool `true` if the bit at position `$bit` is set.
     */
    #[Pure] public static function hasBit(int $field, int $bit): bool
    {
        return (bool)self::getBit($field, $bit);
    }


    /**
     * Sets the bit at position `$bit` in the bitmask `$field`.
     *
     * @param int $field Bitmask.
     * @param int $bit A bit position, 0-indexed.
     * @return int New bitmask.
     */
    #[Pure] public static function setBit(int $field, int $bit): int
    {
        return $field | self::bitToMask($bit);
    }


    /**
     * Unsets the bit at position `$bit` in the bitmask `$field`.
     *
     * @param int $field Bitmask.
     * @param int $bit A bit position, 0-indexed.
     * @return int New bitmask.
     */
    #[Pure] public static function clearBit(int $field, int $bit): int
    {
        return $field & ~self::bitToMask($bit);
    }


    /**
     * Flips the bit at position `$bit` in the bitmask `$field`.
     *
     * @param int $field Bitmask.
     * @param int $bit A bit position, 0-indexed.
     * @return int New bitmask.
     */
    #[Pure] public static function flipBit(int $field, int $bit): int
    {
        return $field ^ self::bitToMask($bit);
    }


    /// Bit mask manipulation methods with masks.

    /**
     * Returns bits of `$mask` that are also set in `$field` in the form of a bitmask. Equivalent of `$field & $mask`.
     *
     * @param int $field Bitmask.
     * @param int $mask Bitmask.
     * @return int Bitmask containing the matching bits.
     */
    #[Pure] public static function getFlags(int $field, int $mask): int
    {
        return $field & $mask;
    }


    /**
     * Checks if any bits of `$mask` are set in `$field`. Equivalent to `$field & $mask` converted to a boolean.
     *
     * @param int $field Bitmask.
     * @param int $mask Bitmask.
     * @return bool `true` if any flags match.
     */
    #[Pure] public static function hasAnyFlag(int $field, int $mask): bool
    {
        return (bool)($field & $mask);
    }


    /**
     * Checks if all bits of `$mask` are set in `$field`.
     *
     * @param int $field Bitmask.
     * @param int $mask Bitmask.
     * @return bool `true` if all flags match.
     */
    #[Pure] public static function hasAllFlags(int $field, int $mask): bool
    {
        return ($field & $mask) === $mask;
    }


    /**
     * Set bits of `$mask` in `$field`. Equivalent of `$field | $mask`.
     *
     * @param int $field Bitmask.
     * @param int $mask Bitmask.
     * @return int Bitmask. Merged bitmask.
     */
    #[Pure] public static function setFlags(int $field, int $mask): int
    {
        $field |= $mask;
        return $field;
    }


    /** Unset bits of `$mask` in `$field` */
    #[Pure] public static function clearFlags(int $field, int $mask): int
    {
        $field &= ~$mask;
        return $field;
    }


    /** Flip bits of `$mask` in `$field` */
    #[Pure] public static function flipFlags(int $field, int $mask): int
    {
        $field ^= $mask;
        return $field;
    }
}
