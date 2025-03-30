<?php

namespace App\Helpers;

use App\Models\Log;

class LogHelper
{
    public static function store(
        string $action,
        string $table,
        ?int $recordId = null,
        $oldData = null,
        $newData = null,
        ?int $idPerson = null,
        ?int $idCredential = null
    ): void {
        Log::create([
            'action'        => $action,
            'table'         => $table,
            'record_id'     => $recordId,
            'old_data'      => $oldData ? json_encode($oldData) : null,
            'new_data'      => $newData ? json_encode($newData) : null,
            'id_person'     => $idPerson,
            'id_credential' => $idCredential,
            'created_at'    => now(),
        ]);
    }
}
