<?php

namespace AssistedMindfulness\Pwned\Tests;

use AssistedMindfulness\Pwned\PwnedRule;
use Illuminate\Support\Facades\Cache;

class PwnedRuleTest extends TestCase
{
    /**
     * @var PwnedRule
     */
    protected $rule;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::clear();
        $this->rule = new PwnedRule();
    }

    /**
     * Check that the complex password passes
     *
     * @dataProvider complexPasswords
     *
     * @param int|float $number
     *
     * @return void
     */
    public function testComplexPasswordPass(string $password)
    {
        $this->assertTrue($this->rule->passes('test', $password));
    }

    /**
     * Check that the easy password fail
     *
     * @dataProvider easyPasswords
     *
     * @param int|float $number
     *
     * @return void
     */
    public function testEasyPasswordFail(string $password)
    {
        $this->assertFalse($this->rule->passes('test', $password));
    }

    public static function complexPasswords(): array
    {
        return [
            ['B#xhhg$OmH5jG|huZV4n'],
            ['kmaQstvPZnw1vGGgNNPc'],
            ['Z@nCL#gLYV6zEQL|Nj~8'],
            ['PiqcQ%{1I16E{u0n2T~4'],
            ['WJc#t8m6AHw0lo%~vGeq'],
            ['b73dAyl~2oSF917rq1e3'],
        ];
    }

    public static function easyPasswords()
    {
        return [
            ['123456'],
            ['password'],
            ['qwerty'],
        ];
    }
}
