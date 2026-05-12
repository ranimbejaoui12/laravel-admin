<?php

namespace App\Http\Controllers;

use App\Models\OrientationLetter;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrientationLtrController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Optionnel : récupérer toutes les lettres pour l'admin ou le médecin connecté
        $letters = OrientationLetter::with('patient')->get();
        return view('orientations.index', compact('letters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Optionnel : récupérer tous les patients pour le form
        $patients = Patient::all();
        return view('orientations.create', compact('patients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Valider les données
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'content' => 'required|string',
        ]);

        // current doctor ID
        $doctor_id = Auth::user()->id;

        // récupérer le patient
        $patient = Patient::find($request->patient_id);

        if (!$patient) {
            return back()->with('error', 'Patient introuvable !');
        }

        // créer la lettre d'orientation
        $patient->orientationLtrs()->create([
            'content' => $request->content,
            'user_id' => $doctor_id
        ]);

        return back()->with('success', 'A new orientation letter is created');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $ltr = OrientationLetter::find($id);

        if (!$ltr) {
            return back()->with('error', 'Orientation letter introuvable !');
        }

        $patient = $ltr->patient;

        return view('orientations.print', [
            'orientationLetter' => $ltr,
            'patient' => $patient
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $ltr = OrientationLetter::find($id);

        if (!$ltr) {
            return back()->with('error', 'Orientation letter introuvable !');
        }

        $patients = Patient::all();

        return view('orientations.edit', compact('ltr', 'patients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $ltr = OrientationLetter::find($id);

        if (!$ltr) {
            return back()->with('error', 'Orientation letter introuvable !');
        }

        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'content' => 'required|string',
        ]);

        $ltr->update([
            'content' => $request->content,
            'user_id' => Auth::user()->id,
            'patient_id' => $request->patient_id
        ]);

        return back()->with('success', 'Orientation letter mise à jour !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $ltr = OrientationLetter::find($id);

        if (!$ltr) {
            return back()->with('error', 'Orientation letter introuvable !');
        }

        $ltr->delete();

        return back()->with('success', 'Orientation letter supprimée !');
    }
}