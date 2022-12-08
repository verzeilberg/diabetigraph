<?php
// src/Twig/AppExtension.php
namespace App\Twig;

use DateTime;
use Exception;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('age', [$this, 'getAge']),
        ];
    }

    /**
     * @throws Exception
     */
    public function getAge($birthDay): int
    {
        $today = new Datetime(date('Y-m-d'));
        $diff = $today->diff($birthDay);
        return $diff->y;
    }
}