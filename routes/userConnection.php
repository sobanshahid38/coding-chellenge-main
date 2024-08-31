<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConnectionController;

Route::middleware(['auth'])->group(function () {
    Route::get('/suggestions', [ConnectionController::class, 'suggestions'])->name('connections.suggestions');
    Route::post('/connect', [ConnectionController::class, 'connect'])->name('connections.connect');
    Route::get('/sent-requests', [ConnectionController::class, 'sentRequests'])->name('connections.sent_requests');
    Route::post('/withdraw-request', [ConnectionController::class, 'withdrawRequest'])->name('connections.withdraw_request');
    Route::get('/received-requests', [ConnectionController::class, 'receivedRequests'])->name('connections.received_requests');
    Route::post('/accept-request', [ConnectionController::class, 'acceptRequest'])->name('connections.accept_request');
    Route::get('/connections', [ConnectionController::class, 'connections'])->name('connections.index');
    Route::delete('/remove-connection/{id}', [ConnectionController::class, 'removeConnection'])->name('connections.remove_connection');
    Route::get('/common-connections/{id}', [ConnectionController::class, 'commonConnections'])->name('connections.common_connections');
});
