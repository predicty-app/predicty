<?php

declare(strict_types=1);

namespace App\Tests\Unit\Validator;

use App\Service\Predicty\PredictySettings;
use App\Validator\TermsOfServiceVersion;
use App\Validator\TermsOfServiceVersionValidator;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 * @covers \App\Validator\TermsOfServiceVersionValidator
 * @covers \App\Validator\TermsOfServiceVersion
 *
 * @extends ConstraintValidatorTestCase<TermsOfServiceVersionValidator>
 */
class TermsOfServiceVersionValidatorTest extends ConstraintValidatorTestCase
{
    private MockObject&PredictySettings $settings;

    protected function setUp(): void
    {
        $this->settings = $this->createMock(PredictySettings::class);

        parent::setUp();
    }

    public function test_valid_value(): void
    {
        $this->settings->expects($this->once())->method('getCurrentTermsOfServiceVersion')->willReturn(1);

        $constraint = new TermsOfServiceVersion();
        $this->validator->validate(1, $constraint);
        $this->assertNoViolation();
    }

    public function test_empty_value(): void
    {
        $this->settings->expects($this->once())->method('getCurrentTermsOfServiceVersion')->willReturn(1);

        $constraint = new TermsOfServiceVersion();
        $this->validator->validate('', $constraint);
        $this->buildViolation('You must accept the terms of service.')->assertRaised();
    }

    protected function createValidator(): ConstraintValidatorInterface
    {
        return new TermsOfServiceVersionValidator($this->settings);
    }
}
