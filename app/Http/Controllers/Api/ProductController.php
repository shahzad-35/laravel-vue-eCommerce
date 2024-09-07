<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductListResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \App\Http\Resources\ProductListResource
     */
    public function index()
    {
        $perPage = request('per_page', 10);
        $search = request('search', '');
        $sortField = request('sort_field', 'updated_at');
        $sortDirection = request('sort_dirwection', 'desc');

        $query = Product::query()
            ->where('title', 'like', "%{$search}%")
            ->orderBy($sortField, $sortDirection)
            ->paginate($perPage);
        return ProductListResource::collection($query);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\ProductResource
     */
    public function store(ProductRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;
        $data['updated_by'] = $request->user()->id;

        $image = $data['image'] ?? null;
        if ($image) {
            $relativePath = $this->saveImage($image);
            $data['image'] = $relativePath['filePath'];
            $data['image_mime'] = $relativePath['file_mime'];
            $data['image_size'] = $relativePath['fileSize'];
        }
        $product = Product::create($data);

        return new ProductResource($product);
    }

    private function saveImage(UploadedFile $image)
    {
        $path = public_path('images/');
        !is_dir($path) && mkdir($path, 0777, true);
        
        $fileName   = time().'.'.$image->getClientOriginalExtension();
        $file_name  = $image->getClientOriginalName();
        $file_type  = $image->getClientOriginalExtension();
        $file_size  = $image->getSize();
        $file_mime  = $image->getMimeType();
        $filePath   = 'images/' . $fileName;

        $image->move($path, $fileName);
        return [
            'fileName' => $fileName,
            'fileType' => $file_type,
            'filePath' => $filePath,
            'fileSize' => $file_size,
            'file_mime' => $file_mime
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \App\Http\Resources\ProductResource
     */
    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $product
     * @return \App\Http\Resources\ProductResource
     */
    public function update(ProductRequest $request, $productId)
    {

        $data = $request->validated();
        $data['updated_by'] = $request->user()->id;

        $product = Product::where('id', $productId)->first();
        $image = $data['image'] ?? null;
        if ($image) {
            $relativePath = $this->saveImage($image);
            $data['image'] = $relativePath['filePath'];
            $data['image_mime'] = $relativePath['file_mime'];
            $data['image_size'] = $relativePath['fileSize'];
        }
        
        if ($product?->image) {
            $image_path = public_path().'/'.$product->image;
            if(File::exists($image_path)){
                unlink($image_path);
            }
        }
        $product->update($data);

        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \App\Http\Resources\ProductResource
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->noContent();
    }
}
