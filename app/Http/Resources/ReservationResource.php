<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'total_price' => $this->total_price,
            'status' => $this->status,
            'user' => $this->whenLoaded('user', [
                'id' => $this->user?->id,
                'name' => $this->user?->name,
                'email' => $this->user?->email,
            ]),
            'car' => $this->whenLoaded('car', [
                'id' => $this->car?->id,
                'brand' => $this->car?->brand,
                'model' => $this->car?->model,
            ]),
            'payment' => $this->whenLoaded('latestPayment', function () {
                $payment = $this->latestPayment;
                return $payment ? [
                    'id' => $payment->id,
                    'status' => $payment->status,
                    'provider' => $payment->provider,
                ] : null;
            }),
            'display_name' => $this->display_name,
        ];
    }
}
