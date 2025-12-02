<?php

namespace App\Http\Controllers;

use App\Models\CeoMessage;
use Illuminate\View\View;

class CeoMessageController extends Controller
{
    public function index(): View
    {
        $ceoMessage = CeoMessage::active()->latest()->first();

        return view('ceo-message', compact('ceoMessage'));
    }
}
