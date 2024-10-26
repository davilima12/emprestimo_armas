<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Features\Medias\Models\Media;
use App\Features\Product\Models\Product;
use Illuminate\Console\Command;

class SetCreatedAtOnProducts extends Command
{
    protected $signature = 'app:set-created-at-on-products';

    protected $description = 'Salva a data de criação do produto com base na data de criação da mídia mais antiga.';

    public function handle(): void
    {
        Product::query()
            ->whereNull('created_at')
            ->each($this->setCreatedAtOnProduct(...));
    }

    private function setCreatedAtOnProduct(Product $product): void
    {
        $oldestMedia = $this->getOldestMedia($product);
        $product->created_at = $oldestMedia->created_at;
        $product->updated_at = $oldestMedia->created_at;
        $product->save();
    }

    private function getOldestMedia(Product $product): Media
    {
        return $product
            ->productMedias
            ->pluck('media')
            ->filter()
            ->sortBy('created_at')
            ->first();
    }
}
