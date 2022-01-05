<?php

namespace OxidSolutionCatalysts\Unzer\Tests\Unit\Service;

use OxidEsales\Eshop\Core\Language;
use OxidSolutionCatalysts\Unzer\Service\Translator;
use PHPUnit\Framework\TestCase;

class TranslatorTest extends TestCase
{
    /**
     * @dataProvider translateCodeDataProvider
     */
    public function testTranslateCode($expectedKey, $key): void
    {
        $languageMock = $this->createPartialMock(Language::class, ['translateString']);
        $languageMock->expects($this->once())
            ->method('translateString')
            ->with($expectedKey)
            ->willReturn('translation');

        $sut = new Translator($languageMock);
        $this->assertSame(
            'translation',
            $sut->translateCode($key, 'default message')
        );
    }

    public function translateCodeDataProvider(): array
    {
        return [
            ['oscunzer_testMESSAGEKEY', 'testMESSAGEKEY'],
            ['oscunzer_apiMESSAGEKEY', 'API.apiMESSAGEKEY'],
            ['oscunzer_corMESSAGEKEY', 'COR.corMESSAGEKEY'],
            ['oscunzer_sdmMESSAGEKEY', 'SDM.sdmMESSAGEKEY'],
        ];
    }

    public function testTranslateCodeNotFound(): void
    {
        $languageMock = $this->createConfiguredMock(Language::class, [
            'isTranslated' => false,
            'translateString' => 'testmessagekey'
        ]);

        $sut = new Translator($languageMock);
        $this->assertSame(
            'default message',
            $sut->translateCode('testmessagekey', 'default message')
        );
    }

    public function testFormatCurrency(): void
    {
        $languageMock = $this->createConfiguredMock(Language::class, [
            'formatCurrency' => '123,45'
        ]);

        $sut = new Translator($languageMock);
        $this->assertSame(
            '123,45',
            $sut->formatCurrency(123.45)
        );
    }
}
