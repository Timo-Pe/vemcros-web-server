<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class StatusExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('status_badge', [$this, 'renderStatusBadge'], ['is_safe' => ['html']]),
        ];
    }

    public function renderStatusBadge(string $status): string
    {
        return match ($status) {
            'Payée' => '<div class="bg-green-100 text-green-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded">' . htmlspecialchars($status) . '</div>',
            'Non payée' => '<div class="bg-red-100 text-red-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded">' . htmlspecialchars($status) . '</div>',
            default => '<div class="bg-gray-100 text-gray-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded">' . htmlspecialchars($status) . '</div>',
        };
    }
}
