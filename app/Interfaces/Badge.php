<?php
declare(strict_types=1);

namespace App\Interfaces;

interface Badge
{
    public function beginner(): string;
    public function intermediate(): string;
    public function advanced(): string;
    public function master(): string;
}
