# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.0.0] - 2023-10-05

### Added

- BitOperations class (`SierraKomodo\BitWise\BitOperations`) and static methods for:
  - Conversion between bit positions and bitmasks. *Note: Bit positions are 0-indexed in all functions and parameters*.
  - Bit position shifting.
  - Manipulation of single bits in a bitmask.
  - Manipulation of multiple bits via bitmask (Flags) against another bitmask.
- Bitfield trait for use by Enums serving as semantic defines for bit flags (`SierraKomodo\BitWise\Traits\BitEnum`).
