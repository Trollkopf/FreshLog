<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    DB::table('users')->where('id', 1)->update(['role' => 'admin']); // Primer usuario como admin
    DB::table('users')->where('id', '>', 1)->update(['role' => 'worker']); // Otros como trabajadores
}

public function down()
{
    DB::table('users')->update(['role' => 'worker']); // Revertir a trabajadores
}

};
