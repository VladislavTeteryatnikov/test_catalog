@extends('layouts.base')

@section('page-title', 'Изменить продукт')

@section('content')
    <section>
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-4 offset-md-3 offset-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="m-0">
                                {{__('Изменить продукт')}}
                            </h4>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('products.update', $product->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <!-- Поле для name -->
                                    <label>{{__('Название')}}</label>
                                    <input type="text" name="product_name" class="form-control" value="{{old('product_name') ?? $product->name}}" autofocus>
                                    @error('product_name')
                                    <div class="small text-danger">
                                        {{ $errors->first('product_name') }}
                                    </div>
                                    @enderror

                                    <!-- Поле для article -->
                                    <label>{{__('Артикул')}}</label>
                                    <input type="text" name="product_article" class="form-control" value="{{old('product_article') ?? $product->article}}">
                                    @error('product_article')
                                    <div class="small text-danger">
                                        {{ $errors->first('product_article') }}
                                    </div>
                                    @enderror

                                    <!-- Поле для status -->
                                    <label for="product_status">{{__('Статус')}}</label>
                                    <select class="form-control" id="product_status" name="product_status" required>
                                        <option value="available" {{ old('product_status', $product->product_status) == 'available' ? 'selected' : '' }}>Доступен</option>
                                        <option value="unavailable" {{ old('product_status', $product->product_status) == 'unavailable' ? 'selected' : '' }}>Недоступен</option>
                                    </select>

                                    <!-- Поле для data (JSON) -->
                                    <label for="product_data">Дополнительные данные (JSON)</label>
                                    <textarea class="form-control" id="product_data" name="product_data" rows="3">{{ old('product_data') ?? $product->data }}</textarea>
                                    <small class="form-text text-muted">Введите данные в формате JSON (например, {"color": "red", "size": "M"})</small>
                                    @error('product_data')
                                    <div class="small text-danger">
                                        {{ $errors->first('product_data') }}
                                    </div>
                                    @enderror
                                </div>

                                <input type="submit" class="btn btn-primary" value="Обновить">

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
