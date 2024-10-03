<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductListResource;
use Illuminate\Support\Facades\Storage;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = request('per_page', 10);
        $search = request('search', '');
        $sortField = request('sort_field', 'created_at');
        $sortDirection = request('sort_direction', 'desc');

        $query = Product::query()
            ->where('title', 'like', "%{$search}%")
            ->orderBy($sortField, $sortDirection)
            ->paginate($perPage);

        return ProductResource::collection($query);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $data = $request->validated(); 
        $user = $request->user();

        try {
            $product = Product::create($data);
            $images = $request->file('images') ?? [];
            
            foreach ($images as $image) {
                $path = $image->store('image/' . $product->id, 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'url' => $image->getClientOriginalName(), 
                    'created_by' => $user->id, 
                    'mime' => $image->getMimeType(), 
                    'path' => $path
                ]);
            }
            
        } catch (\Throwable $th) {
            Log::error('Error storing product image: ' . $th->getMessage());
            throw $th;
        }

        return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product)
    {
        $data = $request->validated();
        $user = $request->user();
        $images = $request->file('images') ?? [];

        if (!empty($data['deleted_images'])) {
            foreach ($data['deleted_images'] as $imageId) {
                $image = ProductImage::find($imageId);
                if ($image) {
                    if (Storage::disk('public')->exists($image->path)) {
                        Storage::disk('public')->delete($image->path); 
                    }
                    $image->delete(); 
                }
            }
        };
    
        try {
            $product->update($data);
    
            foreach ($images as $image) {
                $path = $image->store('image/' . $product->id, 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'url' => $path,
                    'created_by' => $user->id,
                    'mime' => $image->getMimeType(),
                    'path' => $path
                ]);
            }
        } catch (\Throwable $th) {
            Log::error('Error storing product image: ' . $th->getMessage());
            throw $th;
        }
    
        return new ProductResource($product);
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->noContent();
    }
}
