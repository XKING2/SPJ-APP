<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spj;
use App\Models\spj_feedbacks;
use Illuminate\Support\Facades\Auth;

class spjresponcontrol extends Controller
{

    public function store(Request $request, $spjId)
    {
        $request->validate([
            'section'     => 'required|array|min:1',
            'record_id'   => 'required|array|min:1',
            'field'       => 'required|array|min:1',
            'message'     => 'required|array|min:1',
        ]);

        $spj = Spj::with([
            'pesanan.items',
            'penerimaan.details.pesananItem',
            'kwitansi.pptk',
            'kwitansi.kegiatan',
            'pemeriksaan',
            'serah_barang.plt',
            'serah_barang.pihak_kedua'
        ])->findOrFail($spjId);

        // ------------------------------
        // SIMPAN FEEDBACK
        // ------------------------------
        foreach ($request->field as $i => $fieldName) {
        spj_feedbacks::create([
            'spj_id'    => $spj->id,
            'user_id'   => Auth::id(),
            'section'   => $request->section[$i],
            'record_id' => $request->record_id[$i],
            'field'     => $fieldName,
            'message'   => $request->message[$i],
            'role'      => Auth::user()->role ?? 'admin',
        ]);
    }

        // Ambil semua field error unik
        $errorFields = spj_feedbacks::where('spj_id', $spj->id)
            ->pluck('field')
            ->unique()
            ->toArray();

        // ------------------------------
        // PANGGIL controller responspart sebagai SERVICE internal
        // ------------------------------
        $service = new \App\Http\Controllers\responspart();

        $result = $service->processSPJByType($spj, $errorFields);

        return response()->json($result);
    }


    public function getRecord($id, $section)
    {
        // SECTION yang diizinkan
        $validSections = [
            'kwitansi',
            'pesanan',
            'pemeriksaan',
            'penerimaan',
            'detail_barang'
        ];

        if (!in_array($section, $validSections)) {
            return response()->json([
                'success' => false,
                'message' => 'Section tidak valid.'
            ], 400);
        }

        // Ambil SPJ
        $spj = Spj::find($id);

        if (!$spj) {
            return response()->json([
                'success' => false,
                'message' => 'SPJ tidak ditemukan.'
            ], 404);
        }

        // =============================
        // CASE 1: detail_barang (relasi banyak)
        // =============================
        if ($section === 'detail_barang') {
            $details = $spj->penerimaan?->details;

            return response()->json([
                'success' => true,
                'section' => $section,
                'record_id' => $details ? $details->pluck('id') : []
            ]);
        }

        // =============================
        // CASE 2: section normal (1 record)
        // =============================
        $relation = $spj->{$section};

        return response()->json([
            'success'   => true,
            'section'   => $section,
            'record_id' => $relation?->id ?? null
        ]);
    }

}



