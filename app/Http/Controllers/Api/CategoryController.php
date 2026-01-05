<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryFullResource;
use App\Models\Category;

class CategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth:api']);
    }

    /**
     * Lista todas las categorías disponibles.
     *
     * Este método obtiene todas las categorías y las transforma mediante CategoryFullResource para devolver una colección.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return CategoryFullResource::collection(Category::all());
    }

    /**
     * Crea una nueva categoría y devuelve su recurso.
     * Payload esperado (JSON):
     * - name: string
     *
     * @param  \App\Http\Requests\StoreCategoryRequest  $request
     * @return \App\Http\Resources\CategoryFullResource
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->validated());
        return new CategoryFullResource($category);
    }

    /**
     * Muestra una categoría específica.
     *
     * @param  \App\Models\Category  $category
     * @return \App\Http\Resources\CategoryFullResource
     */
    public function show(Category $category)
    {
        $category->load('tasks');
        return new CategoryFullResource($category);
    }

    /**
     * Actualiza una categoría y devuelve su recurso actualizado.
     *
     * Payload esperado (JSON):
     * - name: string
     *
     * @param  \App\Http\Requests\UpdateCategoryRequest  $request
     * @param  \App\Models\Category  $category
     * @return \App\Http\Resources\CategoryFullResource
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());
        return new CategoryFullResource($category->load('tasks'));
    }

    /**
     * Elimina una categoría.
     *
     * @param  \App\Models\Category $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return response()->noContent();
    }
}
