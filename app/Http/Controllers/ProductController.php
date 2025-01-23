<?php

namespace App\Http\Controllers;

use App\Jobs\SendProductNotificationJob;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Продукты со статусом 'available' отсортированные по id по убыванию
        $products = Product::available()->orderByDesc('id')->get();

        return view('products.index')
            ->with('products', $products);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Форма для создания продукта
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Валидация данных
        $validated = $request->validate([
            'product_name' => 'required|string|min:10',
            'product_article' => 'required|regex:/^[a-zA-Z0-9]+$/|unique:products,article',
            'product_status' => 'required|in:available,unavailable',
            'product_data' => 'nullable|json',
        ]);

        //Добавить продукт
        $product = Product::query()->create([
            'name' => $validated['product_name'],
            'article' => $validated['product_article'],
            'status' => $validated['product_status'],
            'data' => $validated['product_data'],
        ]);

        // Отправляем уведомление в очередь
        SendProductNotificationJob::dispatch($product);

        return redirect()->route('products.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Проверяем, существует ли продукт
        $product = Product::available()->findOrFail($id);

        return view('products.show')
            ->with('product', $product);;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Проверяем, существует ли продукт
        $product = Product::available()->findOrFail($id);

        return view('products.edit')
            ->with('product', $product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //Проверяем, существует ли продукт
        $product = Product::available()->findOrFail($id);

        //Получаем роль из конфига
        $role = config('products.role');

        //Правила валидации
        $rules = [
            'product_name' => 'required|string|min:10',
            'product_status' => 'required|in:available,unavailable',
            'product_data' => 'nullable|json',
        ];

        //Если пользователь — админ, разрешаем редактирование артикула
        if ($role === 'admin') {
            $rules['product_article'] = 'required|regex:/^[a-zA-Z0-9]+$/|unique:products,article,' . $id;
        }

        //Валидируем данные
        $validated = $request->validate($rules);

        //Костыль из-за того что имена полей формы и колонок в таблице бд отличаются
        $data = [
            'name' => $validated['product_name'],
            'status' => $validated['product_status'],
            'data' => $validated['product_data'],
        ];

        if ($role === 'admin') {
            $data['article'] = $validated['product_article'];
        }

        //Обновляем продукт
        $product->update($data);

        return redirect()->route('products.show', $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Проверяем существует ли продукт и удаляем
        $product = Product::available()->findOrFail($id);
        $product->delete();

        return redirect()->route('products.index');
    }

    public function getListProducts()
    {
        $products = Product::available()->orderByDesc('id')->paginate(10);

        // Принудительно меняю json на массив, чтобы при конвертации обратно в json был более читаемый вид
        foreach ($products as $product) {
            if ($product->data) {
                $product->data = is_array($product->data) ? $product->data : json_decode($product->data, true);
            }
        }

        return response()->json($products);

    }
}
