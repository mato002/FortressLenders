<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContactMessageThread extends Model
{
    protected $table = 'contact_message_threads';
    
    protected $fillable = ['email', 'name', 'category', 'status', 'last_message_id', 'last_message_at', 'assigned_to'];

    public function messages(): HasMany
    {
        return $this->hasMany(ContactMessage::class, 'thread_id');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeAssignedTo($query, $user)
    {
        return $query->where('assigned_to', $user);
    }
}
