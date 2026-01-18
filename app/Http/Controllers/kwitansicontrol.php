<?php

namespace App\Http\Controllers;

use App\Models\kegiatan;
use Illuminate\Http\Request;
use App\Models\Kwitansi;
use App\Models\spj_feedbacks;
use App\Models\plt;
use App\Models\SPJ;
use App\Models\pptk;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class KwitansiControl extends Controller
{


        public function showKwitansiLS(Request $request)
    {
        $search = $request->input('search');
        $userId = Auth::id();

        $kwitansis = Kwitansi::with('spj')
            ->whereHas('spj', function ($q) use ($userId) {
                $q->where('types', 'ls')
                ->where('user_id', $userId);
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('pembayaran', 'like', "%{$search}%")
                        ->orWhere('no_rekening', 'like', "%{$search}%")
                        ->orWhere('nama_pt', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);


        $rejectedSpj = [];

        $spjList = SPJ::where('user_id', $userId)
            ->where(function ($q) {
                $q->where('status', 'belum_valid')
                ->orWhere('status2', 'belum_valid');
            })
            ->get(['id', 'types']);


        foreach ($spjList as $spj) {

            $feedback = spj_feedbacks::where('spj_id', $spj->id)->first();

            if ($feedback) {
                $rejectedSpj[$spj->id] = [
                    'type'      => $spj->types,
                    'section'   => $feedback->section,
                    'record_id' => $feedback->record_id,
                    'status'    => $spj->status,
                    'status2'   => $spj->status2 ?? null,
                ];
            }
        }


        return view('users.SpjLs.kwitansils', compact('kwitansis', 'search', 'rejectedSpj'));
    }

    public function showKwitansiGU(Request $request)
    {
        $search = $request->input('search');
        $userId = Auth::id();

        $kwitansis = Kwitansi::with('spj')
            ->whereHas('spj', function ($q) use ($userId) {
                $q->where('types', 'gu')
                ->where('user_id', $userId);
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('pembayaran', 'like', "%{$search}%")
                        ->orWhere('no_rekening', 'like', "%{$search}%")
                        ->orWhere('nama_pt', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

            $rejectedSpj = [];

    $spjList = SPJ::where('user_id', $userId)
        ->where(function ($q) {
            $q->where('status', 'belum_valid')
              ->orWhere('status2', 'belum_valid');
        })
        ->get(['id', 'types']);


    foreach ($spjList as $spj) {

        $feedback = spj_feedbacks::where('spj_id', $spj->id)->first();

        if ($feedback) {
            $rejectedSpj[$spj->id] = [
                'type'      => $spj->types,
                'section'   => $feedback->section,
                'record_id' => $feedback->record_id,
                'status'    => $spj->status,
                'status2'   => $spj->status2,
            ];
        }
    }


        return view('users.SpjGu.kwitansigu', compact('kwitansis', 'search','rejectedSpj'));
    }


    public function showKwitansiPO(Request $request)
    {
        $search = $request->input('search');
        $userId = Auth::id();

        $kwitansis = Kwitansi::with('spj')
            ->whereHas('spj', function ($q) use ($userId) {
                $q->where('types', 'po')
                ->where('user_id', $userId);
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('pembayaran', 'like', "%{$search}%")
                        ->orWhere('no_rekening', 'like', "%{$search}%")
                        ->orWhere('nama_pt', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

            $rejectedSpj = [];

    $spjList = SPJ::where('user_id', $userId)
        ->where(function ($q) {
            $q->where('status', 'belum_valid')
              ->orWhere('status2', 'belum_valid');
        })
        ->get(['id', 'types']);


    foreach ($spjList as $spj) {

        $feedback = spj_feedbacks::where('spj_id', $spj->id)->first();

        if ($feedback) {
            $rejectedSpj[$spj->id] = [
                'type'      => $spj->types,
                'section'   => $feedback->section,
                'record_id' => $feedback->record_id,
                'status'    => $spj->status,
                'status2'   => $spj->status2,
            ];
        }
    }


        return view('users.Spjpo.kwitansipo', compact('kwitansis', 'search','rejectedSpj'));
    }
    

    public function getSubKegiatan($pptk_id) 
    { 
        $kegiatan = Kegiatan::where('id_pptk', $pptk_id) ->select('id', 'subkegiatan') 
        ->get(); return response()
        ->json($kegiatan); 
    }

    public function getNoRekSub($id)
    {
        $kegiatan = kegiatan::find($id);

        if (!$kegiatan) {
            return response()->json(['no_rek_sub' => null]);
        }

        return response()->json([
            'no_rek_sub' => $kegiatan->no_rek_sub
        ]);
    }





}
