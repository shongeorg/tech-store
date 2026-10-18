<?php

namespace App\Models;

use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    /** @use HasFactory<OrderFactory> */
    use HasFactory;

    public const STATUSES = ['new', 'confirmed', 'shipped', 'done', 'canceled'];

    public const STATUS_LABELS = [
        'new' => 'Нове',
        'confirmed' => 'Підтверджено',
        'shipped' => 'Відправлено',
        'done' => 'Виконано',
        'canceled' => 'Скасовано',
    ];

    public const DELIVERY_LABELS = [
        'novaposhta' => 'Нова Пошта (відділення)',
        'pickup' => 'Самовивіз із магазину',
        'courier' => "Кур'єр до дверей",
        'ukrposhta' => 'Укрпошта Експрес',
    ];

    public const PAYMENT_LABELS = [
        'card' => 'Оплата онлайн карткою',
        'installments' => 'Оплата частинами 0%',
        'cod' => 'Оплата при отриманні',
        'invoice' => 'Безготівковий розрахунок',
    ];

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'city',
        'address',
        'delivery',
        'payment',
        'comment',
        'subtotal',
        'discount',
        'total',
        'promo_code',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
