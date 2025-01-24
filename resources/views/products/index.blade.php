@extends('layouts.base')

    @section('page-title', 'Products')

    @section('content')
        <section>
            <div class="container">
                <div class="col-12 col-md-8 col-lg-6">
                    <h2 class="my-4">{{__('Список продуктов')}}</h2>
                    <a class="btn btn-primary mb-4" href="{{ route('products.create')  }}" role="button">
                        {{__('Добавить продукт')}}
                    </a>
                </div>
                @if($products->isEmpty())
                    <h5 class="row">{{__('Нет ни одного продукта')}}</h5>
                @else
                    <div class="row">
                        @foreach($products as $product)
                            <div class="col-md-4">
                                <div class="card mb-4 shadow-sm">
                                    <div class="card-body mb-2">
                                        <div class="card-title mb-2 h5">{{ $product->name }}</div>
                                        <div class="card-text mb-2"><strong>{{__('Артикул:')}}</strong> {{ $product->article }}</div>
                                        <div class="card-text mb-3">
                                            <strong>Статус:</strong>
                                            <span class="badge {{ $product->status === 'available' ? 'bg-success' : 'bg-danger' }}">
                                            {{ $product->status === 'available' ? 'Доступен' : 'Недоступен' }}
                                            </span>
                                        </div>

                                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary">Подробнее</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                            <div class="d-flex justify-content-center">
                                {{ $products->links('pagination::bootstrap-4') }}
                            </div>
                    </div>
                @endif
        </section>
    @endsection

