<?php

namespace App\Models\Tenants;

use Filament\Actions\Imports\Models\Import as ModelsImport;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperImport
 */
class Import extends ModelsImport
{
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
