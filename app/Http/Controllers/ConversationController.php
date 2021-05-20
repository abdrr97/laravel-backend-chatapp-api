<?php

namespace App\Http\Controllers;

use App\Http\Resources\ConversationResource;
use App\Models\Conversation;
use App\Models\Message;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $conversations = Conversation::where('user_id', auth()->id())
            ->orWhere('other_id', auth()->id())
            ->orderByDesc(
                Message::select('created_at')
                    ->whereColumn('conversation_id', 'conversations.id')
                    ->orderByDesc('created_at')
                    ->limit(1)
            )->get();
        return ConversationResource::collection($conversations);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'other_id' => 'required',
            'body' => 'required',
            'read' => '',
            'conversation_id' => '',
        ]);

        $conversation =  Conversation::create([
            'user_id' => auth()->id(),
            'other_id' => $request->other_id
        ]);

        Message::create([
            'body' => $request->body,
            'read' => false,
            'user_id' => auth()->id(),
            'conversation_id' => $conversation->id,
        ]);

        return response()->json(['message' => 'successfully created conversation'], 200);
    }

    public function markeConversationAsRead(Request $request)
    {
        $request->validate(['conversation_id' => 'required']);
        $conversation = Conversation::findOrFail($request->conversation_id);
        foreach ($conversation->messages  as $message)
        {
            $message->update(['read' => true]);
        }
        return response()->json(['message' => 'All messages were successfully set to read 😙'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Conversation  $conversation
     * @return \Illuminate\Http\Response
     */
    public function show(Conversation $conversation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Conversation  $conversation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Conversation $conversation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Conversation  $conversation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Conversation $conversation)
    {
        //
    }
}
