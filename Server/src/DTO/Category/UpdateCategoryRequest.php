<?php

namespace App\DTO\Category;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateCategoryRequest
{
    #[Assert\NotBlank(message: "Name is required.")]
    #[Assert\Type(type: 'string', message: "Name must be a string.")]
    public string $name;
}