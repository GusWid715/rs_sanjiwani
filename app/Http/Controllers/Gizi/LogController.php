<?php

namespace App\Http\Controllers\Gizi;

use App\Http\Controllers\Controller;
use App\Models\log_aktivitas;
use Illuminate\Http\Request;
use App\Models\User;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $q        = $request->query('q');
        $user_id  = $request->query('user_id');
        $from     = $request->query('from');
        $to       = $request->query('to');

        $query = log_aktivitas::with('user')->orderByDesc('waktu');

        if ($q) {
            $query->where(function($sub) use ($q) {
                $sub->where('aktivitas', 'like', "%{$q}%")
                    ->orWhere('entity', 'like', "%{$q}%")
                    ->orWhere('entity_id', 'like', "%{$q}%");
            });
        }

        if ($user_id) {
            $query->where('user_id', $user_id);
        } elseif ($request->filled('name')) {
            // optional: filter by username/name via relationship
            $name = $request->query('name');
            $query->whereHas('user', function($q2) use ($name) {
                $q2->where('name','like', "%{$name}%");
            });
        }

        if ($from) {
            $query->where('waktu', '>=', $from . ' 00:00:00');
        }
        if ($to) {
            $query->where('waktu', '<=', $to . ' 23:59:59');
        }

        $logs = $query->paginate(15)->withQueryString();

        // buat list users untuk dropdown filter (opsional)
        $users = User::select('id','name',)->orderBy('name')->get();

        return view('gizi.logs.index', compact('logs','users','q','user_id','from','to'));
    }
}
