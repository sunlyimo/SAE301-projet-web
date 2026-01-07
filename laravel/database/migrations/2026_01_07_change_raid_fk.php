<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE VIK_RAID DROP FOREIGN KEY fk_vik_raid_vik_responsable_raid');

        DB::statement('ALTER TABLE VIK_RAID ADD CONSTRAINT fk_vik_raid_vik_utilisateur 
            FOREIGN KEY (UTI_ID) REFERENCES VIK_UTILISATEUR (UTI_ID) ON DELETE RESTRICT ON UPDATE CASCADE');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE VIK_RAID DROP FOREIGN KEY fk_vik_raid_vik_utilisateur');

        DB::statement('ALTER TABLE VIK_RAID ADD CONSTRAINT fk_vik_raid_vik_responsable_raid 
            FOREIGN KEY (UTI_ID) REFERENCES VIK_RESPONSABLE_RAID (UTI_ID) ON DELETE RESTRICT ON UPDATE CASCADE');
    }
};
