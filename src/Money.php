<?php

namespace Dvi\Corda\Support;

use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\MoneyFactory;
use NumberFormatter;

/**
 * @method static Money USD(string|int $amount)
 * @method \Money\Money isSameCurrency(\Money\Money $other)
 * @method \Money\Money assertSameCurrency(\Money\Money $other)
 * @method \Money\Money equals(\Money\Money $other)
 * @method \Money\Money compare(\Money\Money $other)
 * @method \Money\Money greaterThan(\Money\Money $other)
 * @method \Money\Money greaterThanOrEqual(\Money\Money $other)
 * @method \Money\Money lessThan(\Money\Money $other)
 * @method \Money\Money lessThanOrEqual(\Money\Money $other)
 * @method \Money\Money getAmount()
 * @method \Money\Money getCurrency()
 * @method \Money\Money add(\Money\Money ...$addends)
 * @method Money subtract(\Money\Money ...$subtrahends)
 * @method \Money\Money assertOperand($operand)
 * @method \Money\Money assertRoundingMode($roundingMode)
 * @method \Money\Money multiply($multiplier, $roundingMode = \Money\Money::ROUND_HALF_UP)
 * @method self divide($divisor, $roundingMode = \Money\Money::ROUND_HALF_UP)
 * @method \Money\Money mod(\Money\Money $divisor)
 * @method \Money\Money allocate(array $ratios)
 * @method \Money\Money allocateTo($n)
 * @method \Money\Money ratioOf(\Money\Money $money)
 * @method \Money\Money round($amount, $rounding_mode)
 * @method \Money\Money absolute()
 * @method \Money\Money negative()
 * @method \Money\Money isZero()
 * @method \Money\Money isPositive()
 * @method \Money\Money isNegative()
 * @method \Money\Money jsonSerialize()
 * @method \Money\Money min(\Money\Money $first, \Money\Money ...$collection)
 * @method \Money\Money max(\Money\Money $first, \Money\Money ...$collection)
 * @method \Money\Money sum(\Money\Money $first, \Money\Money ...$collection)
 * @method \Money\Money avg(\Money\Money $first, \Money\Money ...$collection)
 * @method \Money\Money registerCalculator($calculator)
 * @method \Money\Money initializeCalculator()
 * @method \Money\Money getCalculator()
 */
class Money
{
    /**@var \Money\Money*/
    protected $money;

    use MoneyFactory;

    public function __construct($amount, Currency $currency)
    {
        $this->money = new \Money\Money($amount, $currency);
    }

    /**
     * Convenience factory method for a Money object.
     *
     * <code>
     * $fiveDollar = Money::USD(500);
     * </code>
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return Money
     *
     * @throws \InvalidArgumentException If amount is not integer(ish)
     */
    public static function __callStatic($method, $arguments)
    {
        return new Money($arguments[0], new Currency($method));
    }

    public function format($layout = 'en_US', $style = NumberFormatter::DECIMAL, $currencies = null)
    {
        $numberFormatter = new NumberFormatter($layout, $style);
        $currencies = $currencies ?? new ISOCurrencies();
        $moneyFormatter = new IntlMoneyFormatter($numberFormatter, $currencies);
        return $moneyFormatter->format($this->money);
    }

    public function decimal($layout = 'en_US', $currencies = null)
    {
        $number = str($this->currency($layout, $currencies))->removeLeft('$')->trim()->str();
        return $number;
    }

    public function currency($layout = 'en_US', $currencies = null)
    {
        $numberFormatter = new NumberFormatter($layout, NumberFormatter::CURRENCY);
        $moneyFormatter = new IntlMoneyFormatter($numberFormatter, $currencies ?? new ISOCurrencies());
        $number = $moneyFormatter->format($this->money);
        return $number;
    }

    public function dbFormat()
    {
        return dollarToDatabase($this->decimal());
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this->money, $name)) {
            if (isset($arguments[0]) and $arguments[0] instanceof Money) {
                $arguments = $arguments[0]->objMoney();
                /**@var \Money\Money $result*/
                $result = $this->money->$name($arguments);
            } else {
                $result = call_user_func_array([$this->money, $name], $arguments);
            }

            if ($result instanceof \Money\Money) {
                $money = Money::USD($result->getAmount());

                return $money;
            }
            return $result;
        }
    }

    public function objMoney()
    {
        return $this->money;
    }

    public function real($decimals = 2)
    {
        return 'R$ '.toReal($this->decimal('BRL'));
    }

    public function dollar($decimals = 2)
    {
        $number = $this->decimal($decimals);
        return number_format($number, $decimals, ',', '.');
    }
}
