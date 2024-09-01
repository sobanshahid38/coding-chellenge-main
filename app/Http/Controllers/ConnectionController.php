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
        $userId = $user->id;

        // Set how many records you want to load at once
        $limit = 10;
        $page = $request->input('page', 1);

        $connections = Connection::where(function ($query) use ($userId) {
            $query->where('user_id', $userId)
                ->orWhere('connected_user_id', $userId);
        })->where('status', 'accepted')
            ->paginate($limit, ['*'], 'page', $page);





        foreach ($connections as $connection) {
            // Determine the ID of the connection
            $connectionId = ($connection->user_id == $userId) ? $connection->connected_user_id : $connection->user_id;

            $userConnections = Connection::where(function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->orWhere('connected_user_id', $userId);
            })
                ->where('status', 'accepted')
                ->pluck('user_id', 'connected_user_id')
                ->flatMap(function ($connectedUserIds, $userId) {
                    return collect([$userId])->merge($connectedUserIds);
                })
                ->unique()
                ->reject(function ($id) use ($userId) {
                    return $id == $userId; // Ensure the user ID is excluded
                })
                ->toArray();

            // Fetch common connections
            $connectionConnections = Connection::where(function ($query) use ($connectionId) {
                $query->where('user_id', $connectionId)
                    ->orWhere('connected_user_id', $connectionId);
            })
                ->where('status', 'accepted')
                ->pluck('user_id', 'connected_user_id')
                ->flatMap(function ($connectedUserIds, $userId) {
                    return collect([$userId])->merge($connectedUserIds);
                })
                ->unique()
                ->reject(function ($id) use ($connectionId) {
                    return $id == $connectionId; // Ensure the connection ID is excluded
                })
                ->toArray();

            // Find common user IDs
            $commonUserIds = array_intersect($userConnections, $connectionConnections);

            // Add the count of common connections to the connection object
            $connection->common_count = count($commonUserIds);
        }


        $data['content'] = view('connections.index', compact('connections'))->render();
        $data['next_page'] = $connections->nextPageUrl();

        return response()->json($data);
    }






    public function commonConnections(Request $request)
    {
        $limit = 10;
        $page = $request->input('page', 1);
        $connection = Connection::find($request->connection_id);

        if (!$connection) {
            return response()->json(['message' => 'Connection not found'], 404);
        }

        $userId = auth()->id();
        $connectionId = ($connection->user_id == $userId) ? $connection->connected_user_id : $connection->user_id;

        // Fetch all connections for the authenticated user
        $userConnections = Connection::where(function ($query) use ($userId) {
            $query->where('user_id', $userId)
                ->orWhere('connected_user_id', $userId);
        })
            ->where('status', 'accepted')
            ->pluck('user_id', 'connected_user_id')
            ->flatMap(function ($connectedUserIds, $userId) {
                return collect([$userId])->merge($connectedUserIds);
            })
            ->unique()
            ->reject(function ($id) use ($userId) {
                return $id == $userId; // Ensure the user ID is excluded
            })
            ->toArray();


        // Fetch connections for the connectionId
        $connectionConnections = Connection::where(function ($query) use ($connectionId) {
            $query->where('user_id', $connectionId)
                ->orWhere('connected_user_id', $connectionId);
        })
            ->where('status', 'accepted')
            ->pluck('user_id', 'connected_user_id')
            ->flatMap(function ($connectedUserIds, $userId) {
                return collect([$userId])->merge($connectedUserIds);
            })
            ->unique()
            ->reject(function ($id) use ($connectionId) {
                return $id == $connectionId; // Ensure the connection ID is excluded
            })
            ->toArray();

        // Find common user IDs
        $commonUserIds = array_intersect($userConnections, $connectionConnections);

        if (empty($commonUserIds)) {
            return response()->json(['message' => 'No common connections found'], 404);
        }

        // Fetch user details for common user IDs
        $users = User::whereIn('id', $commonUserIds)
            ->paginate($limit, ['*'], 'page', $page);

        $data['content'] = view('connections.common', compact('users'))->render();
        $data['next_page'] = $users->nextPageUrl();

        return response()->json($data);
    }


    function allCounts()
    {
        $user = auth()->user();

        $suggestionsCount = User::whereNotIn('id', function ($query) use ($user) {
            $query->select('connected_user_id')
                ->from('connections')
                ->where('user_id', $user->id);
        })->whereNotIn('id', function ($query) use ($user) {
            $query->select('user_id')
                ->from('connections')
                ->where('connected_user_id', $user->id);
        })->where('id', '!=', $user->id)->count();

        
            $sendRequestsCount = $user->sentRequests()
                ->where('status', 'pending')
                ->count();
       
            $reciveRequestsCount = $user->receivedRequests()
                ->where('status', 'pending')
                ->count();
            $userId=$user->id;
            $connectionsCount = Connection::where(function ($query) use ($userId) {
                    $query->where('user_id', $userId)
                        ->orWhere('connected_user_id', $userId);
                })->where('status', 'accepted')->count();

        $data['suggestions_count']=$suggestionsCount;
        $data['send_request_count']=$sendRequestsCount;
        $data['recive_request_count']=$reciveRequestsCount;
        $data['connections_count']=$connectionsCount;

        return $data;
        
    }
}
