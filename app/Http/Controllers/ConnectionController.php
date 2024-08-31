<?php

namespace App\Http\Controllers;

use App\Models\Connection;
use App\Models\User;
use Illuminate\Http\Request;

class ConnectionController extends Controller
{
    public function suggestions()
    {
        $user = auth()->user();

        $suggestions = User::whereNotIn('id', function ($query) use ($user) {
            $query->select('connected_user_id')
                  ->from('connections')
                  ->where('user_id', $user->id);
        })->whereNotIn('id', function ($query) use ($user) {
            $query->select('user_id')
                  ->from('connections')
                  ->where('connected_user_id', $user->id);
        })->where('id', '!=', $user->id)->get();

        return view('connections.suggestions', compact('suggestions'));
    }

    public function connect($id)
    {
        $user = auth()->user();
        Connection::create([
            'user_id' => $user->id,
            'connected_user_id' => $id,
        ]);

        return redirect()->back();
    }

    public function sentRequests()
    {
        $requests = auth()->user()->sentRequests()->where('status', 'pending')->get();
        return view('connections.sent_requests', compact('requests'));
    }

    public function withdrawRequest($id)
    {
        Connection::where('user_id', auth()->id())
                  ->where('connected_user_id', $id)
                  ->where('status', 'pending')
                  ->delete();

        return redirect()->back();
    }

    public function receivedRequests()
    {
        $requests = auth()->user()->receivedRequests()->where('status', 'pending')->get();
        return view('connections.received_requests', compact('requests'));
    }

    public function acceptRequest($id)
    {
        Connection::where('connected_user_id', auth()->id())
                  ->where('user_id', $id)
                  ->where('status', 'pending')
                  ->update(['status' => 'accepted']);

        return redirect()->back();
    }

    public function connections()
    {
        $connections = auth()->user()->connections()->get();
        return view('connections.index', compact('connections'));
    }

    public function removeConnection($id)
    {
        Connection::where(function($query) use ($id) {
            $query->where('user_id', auth()->id())
                  ->where('connected_user_id', $id);
        })->orWhere(function($query) use ($id) {
            $query->where('user_id', $id)
                  ->where('connected_user_id', auth()->id());
        })->delete();

        return redirect()->back();
    }

    public function commonConnections($id)
    {
        $user = auth()->user();
        $commonConnections = $user->connections()->whereHas('connections', function($query) use ($id) {
            $query->where('connected_user_id', $id);
        })->get();

        return view('connections.common', compact('commonConnections'));
    }
}

