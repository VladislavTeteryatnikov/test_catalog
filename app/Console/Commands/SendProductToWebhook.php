<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SendProductToWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-product-to-webhook';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Отправляет инфо о продукте с наибольшим ID на заданный webhook';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Получаем продукт с наибольшим ID
        $product = Product::available()->orderBy('id', 'desc')->first();

        if ($product) {
            // Получаем URL webhook из конфигурации
            $webhookUrl = config('products.webhook');

            // Отправляем POST запрос с данными о продукте
            $response = Http::post($webhookUrl, [
                'id' => $product->id,
                'name' => $product->name,
                'article' => $product->article,
                'data' => json_decode($product->data, true),
            ]);

            // Проверяем успешность отправки
            if ($response->successful()) {
                $this->info('Продукт успешно отправлен на webhook.');
            } else {
                $this->error('Ошибка при отправке продукта на webhook.');
            }
        } else {
            $this->error('Продукты не найдены.');
        }
    }
}
