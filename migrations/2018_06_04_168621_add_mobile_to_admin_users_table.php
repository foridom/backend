<?php

/*
 * This file is part of ibrand/backend.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMobileToAdminUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (Schema::hasTable('admin_users')) {
            if(!Schema::hasColumn('admin_users', 'mobile')){
                Schema::table('admin_users', function (Blueprint $table) {
                    $table->string('mobile')->after('name')->unique()->nullable();
                });
            }

            if(!Schema::hasColumn('admin_users', 'email')){
                Schema::table('admin_users', function (Blueprint $table) {
                    $table->string('email')->after('username')->nullable();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('admin_users', function (Blueprint $table) {
            $table->dropColumn('mobile');
            $table->dropColumn('email');
        });
    }
}
