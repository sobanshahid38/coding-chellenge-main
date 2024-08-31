<?php

namespace App\Http\Controllers;

use App\Models\Connection;
use App\Models\User;
use Illuminate\Http\Request;

class ConnectionController extends Controller
{
    public function suggestions(Request $request)
    {
        $user = auth()->user();

        // Set how many records you want to load at once
        $limit = 10;
        $page = $request->input('page', 1);

        $suggestions = User::whereNotIn('id', function ($query) use ($user) {
            $query->select('connected_user_id')
                ->from('connections')
                ->where('user_id', $user->id);
        })->whereNotIn('id', function ($query) use ($user) {
            $query->select('user_id')
                ->from('connections')
                ->where('connected_user_id', $user->id);
        })->where('id', '!=', $user->id)
            ->paginate($limit, ['*'], 'page', $page);

        $data['content'] = view('connections.suggestion', compact('suggestions'))->render();
        $data['next_page'] = $suggestions->nextPageUrl();

        return response()->json($data);
    }


    public function connect(Request $request)
    {
        $connection_user_id = $request->suggestion_id;
        $user_id = $request->user_id;
        Connection::create([
            'user_id' => $user_id,
            'connected_user_id' => $connection_user_id,
        ]);

        return response()->json('success');
    }

    public function sentRequests(Request $request)
    {
        $user = auth()->user();

        // Set how many records you want to load at once
        $limit = 10;
        $page = $request->input('page', 1);
        $mode = $request->mode;

        if ($mode == 'sent') {
            $requests = $user->sentRequests()
                ->where('status', 'pending')
                ->paginate($limit, ['*'], 'page', $page);
        } else {
            $requests = $user->receivedRequests()
                ->where('status', 'pending')
                ->paginate($limit, ['*'], 'page', $page);
        }

        // Get the next page URL and append the mode parameter
        $nextPageUrl = $requests->nextPageUrl();
        if ($nextPageUrl) {
            $nextPageUrl = $nextPageUrl . '&mode=' . $mode;
        }


        $data['content'] = view('connections.send_requests', compact('requests', 'mode'))->render();
        $data['next_page'] = $nextPageUrl;

        return response()->json($data);
    }


    public function withdrawRequest(Request $request)
    {
        Connection::where('user_id', $request->user_id)
            ->where('id', $request->request_id)
            ->where('status', 'pending')
            ->delete();

        return response()->json('success');
    }

    public function receivedRequests()
    {
        $requests = auth()->user()->receivedRequests()->where('status', 'pending')->get();
        return view('connections.received_requests', compact('requests'));
    }

    public function acceptRequest(Request $request)
    {
        Connection::where('connected_user_id', $request->user_id)
            ->where('id', $request->request_id)
            ->where('status', 'pending')
            ->update(['status' => 'accepted']);

        return response()->json('success');
    }

    public function connections(Request $request)
    {
        $user = auth()->user();
        $userId=$user->id;

        // Set how many records you want to load at once
        $limit = 10;
        $page = $request->input('page', 1);

        $connections = Connection::where(function($query) use ($userId) {
            $query->where('user_id', $userId)
                  ->orWhere('connected_user_id', $userId);
        })->where('status','accepted')
            ->paginate($limit, ['*'], 'page', $page);


      
        

        foreach ($connections as $connection) {
            // Fetch common connections count for the current connection
            $connection->common_count = Connection::where(function($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->orWhere('connected_user_id', $userId);
            })->where('status','accepted')
                ->count();
        }

        $data['content'] = view('connections.index', compact('connections'))->render();
        $data['next_page'] = $connections->nextPageUrl();

        return response()->json($data);
    }

    private function getCommonConnectionsCount($userId, $connectionId)
    {
        return $user->connections()
            ->whereHas('connections', function ($query) use ($connectionId) {
                $query->where('connected_user_id', $connectionId);
            })
            ->count();
    }


    public function removeConnection($id)
    {
        Connection::where(function ($query) use ($id) {
            $query->where('user_id', auth()->id())
                ->where('connected_user_id', $id);
        })->orWhere(function ($query) use ($id) {
            $query->where('user_id', $id)
                ->where('connected_user_id', auth()->id());
        })->delete();

        return redirect()->back();
    }

    public function commonConnections($id)
    {
        $user = auth()->user();
        $commonConnections = $user->connections()->whereHas('connections', function ($query) use ($id) {
            $query->where('connected_user_id', $id);
        })->get();

        return view('connections.common', compact('commonConnections'));
    }
}
