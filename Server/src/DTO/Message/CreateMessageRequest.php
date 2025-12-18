<?php

namespace App\DTO\Message;

use Symfony\Component\Validator\Constraints as Assert;

class CreateMessageRequest
{
    #[Assert\NotBlank(message:"Content is required.")]
    #[Assert\Type(type:"string", message:"Content must be a string.")]
    public string $content;
}