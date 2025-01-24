<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'role' => $this->role,
            // 'image' => $this->image ? Storage::url($this->image) : '',
            'student' => $this->student ? [
                'id' => $this->student->id,
                'strand' => $this->student->strand ? [
                    'name' => $this->student->strand->name,
                ] : null,
            ] : null,
        ];
    }
}
