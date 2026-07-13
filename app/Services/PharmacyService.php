<?php

namespace App\Services;

use App\Models\Medication;
use App\Models\MedicationMovement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PharmacyService
{
    public function getPaginatedMedications(bool $lowStockOnly = false, int $perPage = 10)
    {
        return Medication::query()
            ->when($lowStockOnly, function ($query) {
                return $query->whereColumn('stock_quantity', '<=', 'min_stock_threshold');
            })
            ->orderBy('name')
            ->paginate($perPage);
    }

    public function createMedication(array $data): Medication
    {
        return DB::transaction(function () use ($data) {
            $medication = Medication::create($data);

            if ($data['stock_quantity'] > 0) {
                MedicationMovement::create([
                    'medication_id' => $medication->id,
                    'movement_type' => 'entree',
                    'quantity' => $data['stock_quantity'],
                    'reason' => 'Stock initial',
                    'user_id' => Auth::id(),
                ]);
            }

            return $medication;
        });
    }

    public function dispense(Medication $medication, int $quantity, ?string $reason = null): Medication
    {
        return DB::transaction(function () use ($medication, $quantity, $reason) {
            $medication->decrement('stock_quantity', $quantity);

            MedicationMovement::create([
                'medication_id' => $medication->id,
                'movement_type' => 'delivrance',
                'quantity' => $quantity,
                'reason' => $reason ?? 'Délivrance',
                'user_id' => Auth::id(),
            ]);

            return $medication->fresh();
        });
    }

    public function restock(Medication $medication, int $quantity, ?string $reason = null): Medication
    {
        return DB::transaction(function () use ($medication, $quantity, $reason) {
            $medication->increment('stock_quantity', $quantity);

            MedicationMovement::create([
                'medication_id' => $medication->id,
                'movement_type' => 'entree',
                'quantity' => $quantity,
                'reason' => $reason ?? 'Réapprovisionnement',
                'user_id' => Auth::id(),
            ]);

            return $medication->fresh();
        });
    }

    public function getLowStockCount(): int
    {
        return Medication::whereColumn('stock_quantity', '<=', 'min_stock_threshold')->count();
    }
}
