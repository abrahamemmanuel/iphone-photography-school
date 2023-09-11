<?php
declare(strict_types=1);

namespace App\Interfaces;

interface Badge
{
    public function beginner(): int;
    public function intermediate(): int;
    public function advanced(): int;
    public function master(): int;
}
