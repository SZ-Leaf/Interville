<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateCategoryRequest
{
    #[Assert\NotBlank(message: "Name is required.")]
    #[Assert\Type(type: 'string', message: "Name must be a string.")]
    public string $name;


}