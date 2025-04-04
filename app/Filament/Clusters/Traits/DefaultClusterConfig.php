<?php

namespace App\Filament\Clusters\Traits;

use Filament\Pages\SubNavigationPosition;

trait DefaultClusterConfig
{
    public static function getLabel(): string
    {
        return static::getNavigationLabel();
    }

    public function getSubNavigationPosition(): SubNavigationPosition
    {
        return SubNavigationPosition::Top;
    }

    public function sortingTheClusteredComponents(): array
    {
        $sortedClusteredComponents = [];
        $components = static::getClusteredComponents();

        $withSort = [];
        $withoutSort = [];
        $highestSort = 0;

        foreach ($components as $page) {
            $sort = $page::getNavigationSort();
            if ($sort !== null) {
                $withSort[] = ['page' => $page, 'sort' => $sort];
                if ($sort > $highestSort) {
                    $highestSort = $sort;
                }
            } else {
                $withoutSort[] = $page;
            }
        }

        usort($withSort, fn ($a, $b) => $a['sort'] <=> $b['sort']);

        $nextSort = $highestSort + 1;
        foreach ($withoutSort as $page) {
            $withSort[] = ['page' => $page, 'sort' => $nextSort++];
        }

        $sortedClusteredComponents = array_map(fn ($item) => $item['page'], array_values($withSort));

        return $sortedClusteredComponents;
    }
}
