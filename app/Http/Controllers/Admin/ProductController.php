<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::withCount('images')
            ->orderBy('display_order')
            ->orderBy('title')
            ->paginate(12);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $product = new Product([
            'highlight_color' => 'teal',
            'is_active' => true,
        ]);

        return view('admin.products.create', compact('product'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['slug'] = $this->generateUniqueSlug($data['title']);

        $product = Product::create($data);
        $this->storeImages($product, $request);

        return redirect()
            ->route('admin.products.index')
            ->with('status', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $product->load('images');

        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $product->load('images');

        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validatedData($request, $product);

        if ($product->title !== $data['title']) {
            $data['slug'] = $this->generateUniqueSlug($data['title'], $product->id);
        }

        $product->update($data);
        $this->storeImages($product, $request);

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('status', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->load('images');
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->path);
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('status', 'Product deleted.');
    }

    protected function validatedData(Request $request, ?Product $product = null): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'summary' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'highlight_color' => ['required', 'string', 'max:50'],
            'cta_label' => ['nullable', 'string', 'max:120'],
            'cta_link' => ['nullable', 'url', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'images.*' => ['nullable', 'image', 'max:4096'],
        ]);

        return array_merge($validated, [
            'is_active' => $request->boolean('is_active'),
            'display_order' => $request->input('display_order', $product?->display_order ?? 0),
        ]);
    }

    protected function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($title) ?: Str::random(6);
        $slug = $baseSlug;
        $counter = 1;

        while (
            Product::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    protected function storeImages(Product $product, Request $request): void
    {
        if (! $request->hasFile('images')) {
            return;
        }

        foreach ((array) $request->file('images') as $file) {
            if (! $file) {
                continue;
            }

            $path = $file->store('products', 'public');

            ProductImage::create([
                'product_id' => $product->id,
                'path' => $path,
                'is_primary' => $product->images()->where('is_primary', true)->doesntExist(),
                'display_order' => $product->images()->count() + 1,
            ]);
        }
    }
}
