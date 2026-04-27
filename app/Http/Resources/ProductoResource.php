<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductoResource extends JsonResource
{
    /**
     * Transforma el recurso en un array.
     * Define exactamente qué datos quieres devolver en la API.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'descripcion_corta' => $this->descripcion_corta,
            'imagen_url' => $this->imagen_url, // Podrías usar un Accessor aquí para la URL completa con asset()
            'precio' => $this->precio,
            // No incluyas 'es_popular' o 'ventas' si el modal no los necesita directamente
            // 'precio_formateado' => $this->formatted_price, // Si tuvieras un accessor para esto
            // 'imagen_display_url' => $this->display_image_url, // Si tuvieras un accessor
        ];
    }
}