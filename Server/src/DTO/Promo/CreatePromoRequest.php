<?php

namespace App\DTO\Promo;

use Symfony\Component\Validator\Constraints as Assert;

class CreatePromoRequest
{
    #[Assert\NotBlank(message: "City is required.")]
    #[Assert\Type(type: 'string', message: "City must be a string.")]
    public string $city;

    #[Assert\NotBlank(message: "Year is required.")]
    #[Assert\Type(type: 'integer', message: "Year must be an integer.")]
    public int $year;
}
