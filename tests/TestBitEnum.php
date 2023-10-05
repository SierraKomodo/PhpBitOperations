<?php

declare(strict_types=1);

namespace SierraKomodo\BitWise\Tests;

use SierraKomodo\BitWise\Trait\BitEnum;

enum TestBitEnum: int
{
    use BitEnum;

    case A = 0;
    case B = 1;
    case C = 2;
    case D = 3;
    case E = 4;
    case F = 5;
}
