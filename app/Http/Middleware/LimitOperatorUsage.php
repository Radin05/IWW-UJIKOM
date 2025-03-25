<?php
namespace App\Http\Middleware;

use App\Models\User; // Pastikan ini ada
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LimitOperatorUsage
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->role === 'operator') {
            $maxUsageMinutes = 10; // Set batas waktu 1 menit untuk uji coba

            if (!$user->login_time) {
                $user->login_time = Carbon::now();
                $user->save(); // Menyimpan perubahan
            }

            $user->refresh(); // Mengambil ulang data dari database
            $loginTime = Carbon::parse($user->login_time);

            if (Carbon::now()->diffInMinutes($loginTime) > $maxUsageMinutes) {
                Auth::logout();
                $user->login_time = null;
                $user->save(); // Simpan perubahan

                return redirect()->route('login')->with('warning', 'Waktu penggunaan akun operator telah habis.');
            }
        }

        return $next($request);
    }
}
