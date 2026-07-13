<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use App\Models\MedicationMovement;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PharmacyController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::user()->can('dispense medication')) {
            abort(403);
        }

        $lowStock = $request->boolean('low_stock');
        $search = $request->get('search');
        $medications = Medication::query()
            ->when($lowStock, function ($query) {
                return $query->whereColumn('stock_quantity', '<=', 'min_stock_threshold');
            })
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('generic_name', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(10);

        return view('pharmacy.index', compact('medications', 'lowStock', 'search'));
    }

    public function create()
    {
        if (!Auth::user()->can('manage users')) {
            abort(403);
        }

        $categories = ['Antibiotique', 'Antalgique', 'Anti-inflammatoire', 'Antihistaminique', 'Cardiovasculaire', 'Diabète', 'Gastro-intestinal', 'Vitamines', 'Autre'];

        return view('pharmacy.create', compact('categories'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->can('manage users')) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'unit' => 'required|string|max:50',
            'stock_quantity' => 'required|integer|min:0',
            'min_stock_threshold' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $medication = Medication::create($validated);

        if ($validated['stock_quantity'] > 0) {
            MedicationMovement::create([
                'medication_id' => $medication->id,
                'movement_type' => 'entree',
                'quantity' => $validated['stock_quantity'],
                'reason' => 'Stock initial',
                'user_id' => Auth::id(),
            ]);
        }

        return redirect()->route('pharmacy.index')
            ->with('success', 'Médicament ajouté avec succès.');
    }

    public function show(Medication $pharmacy)
    {
        if (!Auth::user()->can('dispense medication')) {
            abort(403);
        }

        $pharmacy->load(['movements.user']);

        return view('pharmacy.show', compact('pharmacy'));
    }

    public function edit(Medication $pharmacy)
    {
        if (!Auth::user()->can('manage users')) {
            abort(403);
        }

        $categories = ['Antibiotique', 'Antalgique', 'Anti-inflammatoire', 'Antihistaminique', 'Cardiovasculaire', 'Diabète', 'Gastro-intestinal', 'Vitamines', 'Autre'];

        return view('pharmacy.edit', compact('pharmacy', 'categories'));
    }

    public function update(Request $request, Medication $pharmacy)
    {
        if (!Auth::user()->can('manage users')) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'unit' => 'required|string|max:50',
            'min_stock_threshold' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $pharmacy->update($validated);

        return redirect()->route('pharmacy.index')
            ->with('success', 'Médicament mis à jour avec succès.');
    }

    public function destroy(Medication $pharmacy)
    {
        if (!Auth::user()->can('manage users')) {
            abort(403);
        }

        $pharmacy->delete();

        return redirect()->route('pharmacy.index')
            ->with('success', 'Médicament supprimé avec succès.');
    }

    public function dispense(Request $request, Medication $pharmacy)
    {
        if (!Auth::user()->can('dispense medication')) {
            abort(403);
        }

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string',
        ]);

        if ($validated['quantity'] > $pharmacy->stock_quantity) {
            return back()->with('error', 'Stock insuffisant. Stock disponible: ' . $pharmacy->stock_quantity . ' ' . $pharmacy->unit . '(s).')->withInput();
        }

        DB::transaction(function () use ($pharmacy, $validated) {
            $pharmacy->decrement('stock_quantity', $validated['quantity']);

            MedicationMovement::create([
                'medication_id' => $pharmacy->id,
                'movement_type' => 'delivrance',
                'quantity' => $validated['quantity'],
                'reason' => $validated['reason'] ?? 'Délivrance',
                'user_id' => Auth::id(),
            ]);
        });

        return redirect()->route('pharmacy.show', $pharmacy)
            ->with('success', 'Médicament délivré avec succès.');
    }

    public function restock(Request $request, Medication $pharmacy)
    {
        if (!Auth::user()->can('dispense medication')) {
            abort(403);
        }

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string',
        ]);

        DB::transaction(function () use ($pharmacy, $validated) {
            $pharmacy->increment('stock_quantity', $validated['quantity']);

            MedicationMovement::create([
                'medication_id' => $pharmacy->id,
                'movement_type' => 'entree',
                'quantity' => $validated['quantity'],
                'reason' => $validated['reason'] ?? 'Réapprovisionnement',
                'user_id' => Auth::id(),
            ]);
        });

        return redirect()->route('pharmacy.show', $pharmacy)
            ->with('success', 'Stock réapprovisionné avec succès.');
    }
}
