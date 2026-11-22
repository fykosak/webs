<?php

declare(strict_types=1);

namespace App\Components\Forms;

use Nette\Forms\Controls\BaseControl;
use DateTimeImmutable;

class FormValidators
{
	public static function validateFutureDate(BaseControl $input): bool
	{
		return $input->getValue() > new DateTimeImmutable();
	}
}