<?php

namespace Tests\Unit;

use App\Support\Money;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    public function test_formats_amounts_as_dong(): void
    {
        $this->assertSame("1.234.567\u{00A0}₫", Money::format(1234567));
        $this->assertSame("1.234.567\u{00A0}₫", Money::format('1234567.00'));
        $this->assertSame("5.000.000\u{00A0}₫", Money::format(5000000.0));
    }

    public function test_missing_amount_formats_empty(): void
    {
        $this->assertSame('', Money::format(null));
        $this->assertSame('', Money::format(''));
    }
}
