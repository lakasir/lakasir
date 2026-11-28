<?php

use Filament\Facades\Filament;

test('tenant panel carries the lakasir theme', function () {
    $panel = Filament::getPanel('tenant');

    expect($panel->getColors()['primary']['500'])->toBe('255, 102, 0')
        ->and($panel->getFontFamily())->toBe('Figtree');
});
