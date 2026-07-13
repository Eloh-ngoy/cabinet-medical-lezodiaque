<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MedicationResource;
use App\Models\Medication;
use App\Models\MedicationMovement;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApiPharmacyController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Medication::class);

        $lowStock = $request->boolean('low_stock');
        $medications = Medication::query()
            ->when($lowStock, fn ($q) => $q->whereColumn('stock_quantity', '<=', 'min_stock_threshold'))
            ->orderBy('name')
            ->paginate(15);

        return MedicationResource::collection($medications);
    }

    public function show(Medication $medication): MedicationResource
    {
        $this->authorize('view', $medication);
        $medication->load('movements.user');

        return new MedicationResource($medication);
    }

    public function store(Request $request): MedicationResource
    {
        $this->authorize('create', Medication::class);

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

        return DB::transaction(function () use ($validated) {
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

            return new MedicationResource($medication);
        });
    }

    public function update(Request $request, Medication $medication): MedicationResource
    {
        $this->authorize('update', $medication);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'unit' => 'required|string|max:50',
            'min_stock_threshold' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $medication->update($validated);

        return new MedicationResource($medication);
    }

    public function dispense(Request $request, Medication $medication): MedicationResource
    {
        $this->authorize('dispense', $medication);

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($medication, $validated) {
            $medication->decrement('stock_quantity', $validated['quantity']);
            MedicationMovement::create([
                'medication_id' => $medication->id,
                'movement_type' => 'delivrance',
                'quantity' => $validated['quantity'],
                'reason' => $validated['reason'] ?? 'Délivrance',
                'user_id' => Auth::id(),
            ]);

            return new MedicationResource($medication->fresh());
        });
    }

    public function restock(Request $request, Medication $medication): MedicationResource
    {
        $this->authorize('restock', $medication);

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($medication, $validated) {
            $medication->increment('stock_quantity', $validated['quantity']);
            MedicationMovement::create([
                'medication_id' => $medication->id,
                'movement_type' => 'entree',
                'quantity' => $validated['quantity'],
                'reason' => $validated['reason'] ?? 'Réapprovisionnement',
                'user_id' => Auth::id(),
            ]);

            return new MedicationResource($medication->fresh());
        });
    }
}
