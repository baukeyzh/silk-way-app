<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * For every city in the cities table, create one placeholder warehouse
     * named "Склад {city.name_rus}". Then match existing cargo rows by their
     * denormalized city name (cargo.from_location_rus / to_location_rus
     * against cities.name_rus) and point them at the placeholder.
     *
     * Note: the cargo table does NOT have from_city_id/to_city_id columns —
     * city names are stored as denormalized strings (from_location_rus etc.)
     * by the cargo create flow. We match on those strings.
     *
     * Uses raw query-builder only — Eloquent timestamps/observers are
     * intentionally bypassed. Runs inside a transaction so any failure leaves
     * the table untouched.
     */
    public function up(): void
    {
        DB::transaction(function (): void {
            $cities = DB::table('cities')->get();

            // city_id => warehouse_id
            $warehouseByCityId = [];
            // city.name_rus (lowercased+trimmed) => warehouse_id, for cargo backfill matching
            $warehouseByCityNameRus = [];

            $now = now()->toDateTimeString();

            foreach ($cities as $city) {
                $nameRus = 'Склад ' . ($city->name_rus ?: $city->name);
                $nameKaz = $city->name_kaz ? 'Қойма ' . $city->name_kaz : null;
                $nameChn = $city->name_chn ? '仓库 ' . $city->name_chn : null;

                $warehouseId = DB::table('warehouses')->insertGetId([
                    'name'       => $nameRus,
                    'name_rus'   => $nameRus,
                    'name_kaz'   => $nameKaz,
                    'name_chn'   => $nameChn,
                    'address'    => '—',
                    'phone'      => null,
                    'city_id'    => $city->id,
                    'created_by' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                $warehouseByCityId[$city->id] = $warehouseId;

                if ($city->name_rus) {
                    $warehouseByCityNameRus[mb_strtolower(trim($city->name_rus))] = $warehouseId;
                }
            }

            // Match existing cargo rows by denormalized city name
            $cargos = DB::table('cargo')
                ->select('id', 'from_location_rus', 'to_location_rus')
                ->whereNull('from_warehouse_id')
                ->orWhereNull('to_warehouse_id')
                ->get();

            foreach ($cargos as $row) {
                $fromKey = mb_strtolower(trim((string) $row->from_location_rus));
                $toKey   = mb_strtolower(trim((string) $row->to_location_rus));

                $update = [];
                if ($fromKey !== '' && isset($warehouseByCityNameRus[$fromKey])) {
                    $update['from_warehouse_id'] = $warehouseByCityNameRus[$fromKey];
                }
                if ($toKey !== '' && isset($warehouseByCityNameRus[$toKey])) {
                    $update['to_warehouse_id'] = $warehouseByCityNameRus[$toKey];
                }

                if ($update !== []) {
                    DB::table('cargo')->where('id', $row->id)->update($update);
                }
            }
        });
    }

    public function down(): void
    {
        // Remove backfill data only — leaves schema changes to the other migrations
        DB::transaction(function (): void {
            DB::table('cargo')->update([
                'from_warehouse_id' => null,
                'to_warehouse_id'   => null,
            ]);

            // Remove warehouses that were created by this backfill
            // (created_by IS NULL and address = '—')
            DB::table('warehouses')
                ->whereNull('created_by')
                ->where('address', '—')
                ->delete();
        });
    }
};
