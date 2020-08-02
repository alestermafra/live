<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RetrieveMessagesRequest;

use App\Message;

class MessageController extends Controller
{
    public function index(Request $request) {
        $validated = $request->validate([
            'last_message_id' => 'required|integer'
        ]);

        $messages = Message::where('id', '>', $validated['last_message_id'])
            ->orderBy('id', 'desc')
            ->limit(100)
            ->get();

        return $messages;
    }

    public function store(Request $request) {
        return Message::create($request->all());
    }

    public function delete(Message $message) {
        $message->delete();
        return 204;
    }
}
