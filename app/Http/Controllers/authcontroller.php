<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SPJ;

class AuthController extends Controller
{

    public function showLoginForm()
    {
        return view('login');
    }

public function login(Request $request)
{
    $credentials = $request->only('NIP', 'password');

    if (Auth::attempt($credentials)) {

        $user = Auth::user();
        session(['user_id' => $user->id]);

        session()->flash('success', 'Login berhasil! Selamat datang ' . $user->nama);


    
        if ($user->role === 'users') {

            // Ambil SPJ yang status/status2 sudah berubah (valid/belum_valid)
            // tanpa memfilter notified di query — kita cek lebih spesifik per record.
            $spjs_candidate = Spj::where('user_id', $user->id)
                ->where(function ($q) {
                    $q->whereIn('status', ['valid','belum_valid'])
                    ->orWhereIn('status2', ['valid','belum_valid']);
                })
                ->orderBy('id','desc')
                ->get(['id','status','status2','notified','notifiedby_kasubag']);

            $spjs_for_session = collect();

            foreach ($spjs_candidate as $spj) {
                $updates = [];

                // Jika Bendahara sudah mengubah status & user belum diberitahu tentang itu
                if (in_array($spj->status, ['valid','belum_valid']) && $spj->notified == 0) {
                    $updates['notified'] = 1;
                }

                // Jika Kasubag sudah mengubah status2 & user belum diberitahu tentang itu
                if (in_array($spj->status2, ['valid','belum_valid']) && $spj->notifiedby_kasubag == 0) {
                    $updates['notifiedby_kasubag'] = 1;
                }

                // Jika ada perubahan flag yang perlu dilakukan, update sekali dan simpan spj ke session
                if (!empty($updates)) {
                    $spj->update($updates);

                    // Refresh atribut lokal agar menampilkan nilai terbaru (opsional)
                    $spj->refresh();

                    // Tandai jenis perubahan supaya blade/Swal bisa menampilkan pesan yang tepat jika perlu
                    $spj->notif_trigger = [
                        'bendahara' => isset($updates['notified']),
                        'kasubag'   => isset($updates['notifiedby_kasubag'])
                    ];

                    $spjs_for_session->push($spj);
                }
            }

            // Kirim ke session hanya SPJ yang benar-benar kita ubah (notified flag sebelumnya 0)
            if ($spjs_for_session->count() > 0) {
                session()->flash('spj_status_list_user', $spjs_for_session);
            }
        }


        if ($user->role === 'Bendahara') {

            $spjs = Spj::where('status', 'diajukan')
                ->where('notified_bendahara', 0) // belum terbaca bendahara
                ->orderBy('id', 'desc')
                ->get();

            // update notif hanya untuk bendahara
            foreach ($spjs as $spj) {
                $spj->update([
                    'notified_bendahara' => 1,
                ]);
            }

            if ($spjs->count() > 0) {
                session()->flash('spj_status_list_bendahara', $spjs);
            }
        }


        if ($user->role === 'Kasubag') {

            $spjs = Spj::where('status2', 'diajukan')
                ->where('notified_kasubag', 0) // belum terbaca kasubag
                ->orderBy('id', 'desc')
                ->get();

            // update notif khusus kasubag
            foreach ($spjs as $spj) {
                $spj->update([
                    'notified_kasubag' => 1,
                ]);
            }

            if ($spjs->count() > 0) {
                session()->flash('spj_status_list_kasubag', $spjs);
            }
        }

        
        return match ($user->role) {
            'Kasubag'   => redirect()->route('superdashboard'),
            'Bendahara' => redirect()->route('admindashboard'),
            'users'     => redirect()->route('userdashboard'),
            default     => redirect()->route('login'),
        };
    }


    return back()->withErrors([
        'NIP' => 'Nama atau password salah.',
    ]);
}


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    
}
