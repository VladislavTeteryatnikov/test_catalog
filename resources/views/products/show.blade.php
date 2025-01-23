@extends('layouts.base')

@section('page-title', $product->name ?? 'Product')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <img src="https://placehold.co/600x400?text={{ rawurlencode($product->name) }}" class="card-img-top" alt="Изображение продукта">

                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <div class="mb-2"><strong>Артикул:</strong> {{ $product->article }}</div>
                        <div class="mb-2"><strong>Статус:</strong>
                            <span class="badge {{ $product->status == 'available' ? 'bg-success' : 'bg-danger' }}">
                            {{ $product->status == 'available' ? 'Доступен' : 'Недоступен' }}
                        </span>
                        </div>

                        @if($product->data)
                            <div><strong>Дополнительные данные:</strong></div>
                            <ul>
                                @foreach(json_decode($product->data, true) as $key => $value)
                                    <li><strong>{{ ucfirst($key) }}:</strong> {{ $value }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    <div class="card-footer text-center block">
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">Вернуться к каталогу</a>
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-info">Редактировать</a>

                        <form class="d-inline-block" action="{{ route('products.destroy', $product->id) }}" method="POST"  onsubmit="return confirm('Вы уверены, что хотите удалить продукт?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Удалить</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
