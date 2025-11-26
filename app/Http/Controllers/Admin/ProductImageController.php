<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    public function destroy(Product $product, ProductImage $image): RedirectResponse
    {
        $this->ensureImageBelongsToProduct($product, $image);

        Storage::disk('public')->delete($image->path);
        $image->delete();

        $this->normalizeOrder($product);

        return back()->with('status', 'Image removed successfully.');
    }

    public function makePrimary(Product $product, ProductImage $image): RedirectResponse
    {
        $this->ensureImageBelongsToProduct($product, $image);

        DB::transaction(function () use ($product, $image): void {
            $product->images()->update(['is_primary' => false]);
            $image->is_primary = true;
            $image->save();
        });

        return back()->with('status', 'Primary image updated.');
    }

    public function reorder(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['integer', 'min:0'],
        ]);

        DB::transaction(function () use ($product, $data): void {
            foreach ($data['order'] as $imageId => $position) {
                $image = $product->images()->whereKey($imageId)->first();
                if (! $image) {
                    continue;
                }

                $image->display_order = $position;
                $image->save();
            }

            $this->normalizeOrder($product);
        });

        return back()->with('status', 'Image order saved.');
    }

    protected function ensureImageBelongsToProduct(Product $product, ProductImage $image): void
    {
        abort_if($image->product_id !== $product->id, 404);
    }

    protected function normalizeOrder(Product $product): void
    {
        $product->load(['images' => fn ($query) => $query->orderBy('display_order')]);

        foreach ($product->images as $index => $image) {
            $image->display_order = $index + 1;
            $image->save();
        }
    }
}

