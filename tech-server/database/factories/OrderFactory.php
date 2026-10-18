<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cities = ['Київ', 'Львів', 'Одеса', 'Харків', 'Дніпро'];

        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => '+380'.fake()->numerify('#########'),
            'city' => fake()->randomElement($cities),
            'address' => null,
            'delivery' => fake()->randomElement(['novaposhta', 'pickup', 'courier', 'ukrposhta']),
            'payment' => fake()->randomElement(['card', 'installments', 'cod', 'invoice']),
            'comment' => null,
            'subtotal' => fake()->numberBetween(5_000, 60_000),
            'discount' => 0,
            'status' => fake()->randomElement(Order::STATUSES),
        ];
    }

    public function configure(): static
    {
        return $this->afterMaking(function (Order $order) {
            $order->total = $order->subtotal - $order->discount;
        });
    }
}
