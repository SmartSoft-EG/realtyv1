<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_origins', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('name')->unique();
            $table->tinyInteger('level')->default(1);
            $table->string('state', 10)->default('active');
            $table->string('type')->default('debit');
            $table->float('debit')->default(0);
            $table->float('credit')->default(0);
            $table->float('balance')->default(0);
            $table->string('balance_type', 20)->default('debit');
            $table->timestamps();
        });
        //add root element for accounts
        DB::table('account_origins')->insert(['name' => 'root', 'type' => 'root']);
        DB::table('account_origins')->update(['id' => 0, 'parent_id' => 0]);

        DB::table('account_origins')->insert(['id' => 1, 'name' => 'الميزانية العمومية', 'level' => 1, 'parent_id' => 0]);
        DB::table('account_origins')->insert(['id' => 2, 'name' => 'الارباح و الخسائر', 'level' => 1, 'parent_id' => 0]);

        DB::table('account_origins')->insert(['id' => 11, 'name' => 'الاصول', 'level' => 2, 'parent_id' => 1]);
        DB::table('account_origins')->insert(['id' => 12, 'name' => 'الخصوم', 'level' => 2, 'parent_id' => 1]);
        DB::table('account_origins')->insert(['id' => 21, 'name' => 'المصروفات', 'level' => 2, 'parent_id' => 2]);
        DB::table('account_origins')->insert(['id' => 22, 'name' => 'الايرادات', 'level' => 2, 'parent_id' => 2]);
        DB::table('account_origins')->insert(['id' => 111, 'name' => 'الاصول الثابتة', 'level' => 3, 'parent_id' => 11]);
        DB::table('account_origins')->insert(['id' => 112, 'name' => 'الاصول المتداولة', 'level' => 3, 'parent_id' => 11]);
        DB::table('account_origins')->insert(['id' => 123, 'name' => 'الخصوم المتداولة', 'level' => 3, 'parent_id' => 12]);
        DB::table('account_origins')->insert(['id' => 124, 'name' => 'حقوق الشركاء', 'level' => 3, 'parent_id' => 12]);
        DB::table('account_origins')->insert(['id' => 125, 'name' => 'ارصدة دائنة اخرى', 'level' => 3, 'parent_id' => 12]);
        DB::table('account_origins')->insert(['id' => 126, 'name' => 'الاجور المستحقة', 'level' => 3, 'parent_id' => 12]);
        DB::table('account_origins')->insert(['id' => 219, 'name' => 'المشتريات', 'level' => 4, 'parent_id' => 21]);
        DB::table('account_origins')->insert(['id' => 2110, 'name' => 'المصاريف العمومية والادارية', 'level' => 4, 'parent_id' => 21]);
        DB::table('account_origins')->insert(['id' => 227, 'name' => 'المبيعات', 'level' => 4, 'parent_id' => 22]);
        DB::table('account_origins')->insert(['id' => 22701, 'name' => 'ايراد الوحدات', 'level' => 5, 'parent_id' => 227]);

        DB::table('account_origins')->insert(['id' => 228, 'name' => 'ايرادات اخرى', 'level' => 4, 'parent_id' => 22]);
        DB::table('account_origins')->insert(['id' => 1111, 'name' => 'اثاث وتجهيزات ومعدات مكتبية', 'level' => 4, 'parent_id' => 111]);
        DB::table('account_origins')->insert(['id' => 1112, 'name' => 'السيارات', 'level' => 4, 'parent_id' => 111]);
        DB::table('account_origins')->insert(['id' => 1113, 'name' => 'كمبيوترات وملحقاتها', 'level' => 4, 'parent_id' => 111]);
        DB::table('account_origins')->insert(['id' => 1114, 'name' => 'هواتف وملحقاتها', 'level' => 4, 'parent_id' => 111]);
        DB::table('account_origins')->insert(['id' => 1126, 'name' => 'البنوك', 'level' => 4, 'parent_id' => 112]);
        DB::table('account_origins')->insert(['id' => 1125, 'name' => 'الصناديق', 'level' => 4, 'parent_id' => 112]);
        DB::table('account_origins')->insert(['id' => 1127, 'name' => 'العملاء', 'level' => 4, 'parent_id' => 112]);
        DB::table('account_origins')->insert(['id' => 1128, 'name' => 'ذمم موظفين', 'level' => 4, 'parent_id' => 112]);
        DB::table('account_origins')->insert(['id' => 11265, 'name' => 'سلفه مواظفين ', 'level' => 4, 'parent_id' => 112]);
        DB::table('account_origins')->insert(['id' => 112201, 'name' => 'المخزون', 'level' => 4, 'parent_id' => 112]);
        DB::table('account_origins')->insert(['id' => 1239, 'name' => 'الموردين', 'level' => 4, 'parent_id' => 123]);
        DB::table('account_origins')->insert(['id' => 12310, 'name' => 'الدائنون', 'level' => 4, 'parent_id' => 123]);
        DB::table('account_origins')->insert(['id' => 12411, 'name' => 'راس المال', 'level' => 4, 'parent_id' => 124]);

        DB::table('account_origins')->insert(['id' => 12412, 'name' => 'جاري الشركاء', 'level' => 4, 'parent_id' => 124]);
        DB::table('account_origins')->insert(['id' => 124181, 'name' => 'الارباح والخسائـر', 'level' => 4, 'parent_id' => 124]);
        DB::table('account_origins')->insert(['id' => 12513, 'name' => 'مخصص ديون مشكوك فيها', 'level' => 4, 'parent_id' => 125]);
        DB::table('account_origins')->insert(['id' => 12514, 'name' => 'ارباح وخسائر', 'level' => 4, 'parent_id' => 125]);
        DB::table('account_origins')->insert(['id' => 126161, 'name' => 'اجور مستحقة', 'level' => 4, 'parent_id' => 126]);


        DB::table('account_origins')->insert(['id' => 21918, 'name' => 'مشتريات', 'level' => 4, 'parent_id' => 219]);
        DB::table('account_origins')->insert(['id' => 22715, 'name' => 'مبيعـات نقدية', 'level' => 4, 'parent_id' => 227]);
        DB::table('account_origins')->insert(['id' => 22716, 'name' => 'مبيعات اجلة', 'level' => 4, 'parent_id' => 227]);
        DB::table('account_origins')->insert(['id' => 22817, 'name' => 'ايرادات اخرى متنوعة', 'level' => 4, 'parent_id' => 228]);
        DB::table('account_origins')->insert(['id' => 211020, 'name' => 'الرواتب', 'level' => 4, 'parent_id' => 2110]);
        DB::table('account_origins')->insert(['id' => 211021, 'name' => 'بدل انتقال وبنزين', 'level' => 4, 'parent_id' => 2110]);
        DB::table('account_origins')->insert(['id' => 211022, 'name' => 'قرطاسية ومطبوعات', 'level' => 4, 'parent_id' => 2110]);
        DB::table('account_origins')->insert(['id' => 211023, 'name' => 'خصم مسموح به', 'level' => 4, 'parent_id' => 2110]);
        DB::table('account_origins')->insert(['id' => 211024, 'name' => 'صيانة سيارات', 'level' => 4, 'parent_id' => 2110]);
        DB::table('account_origins')->insert(['id' => 211025, 'name' => 'صيانة اجهزة المكتب', 'level' => 4, 'parent_id' => 2110]);

        DB::table('account_origins')->insert(['id' => 211026, 'name' => 'مصروفات متنوعة', 'level' => 4, 'parent_id' => 2110]);
        DB::table('account_origins')->insert(['id' => 211027, 'name' => 'مصروفات توزيع', 'level' => 4, 'parent_id' => 2110]);
        DB::table('account_origins')->insert(['id' => 211028, 'name' => 'مصروفات تاسيس', 'level' => 4, 'parent_id' => 2110]);
        DB::table('account_origins')->insert(['id' => 211029, 'name' => 'مصروفات طباعة', 'level' => 4, 'parent_id' => 2110]);
        DB::table('account_origins')->insert(['id' => 211030, 'name' => 'مصروفات تصميم', 'level' => 4, 'parent_id' => 2110]);
        DB::table('account_origins')->insert(['id' => 211031, 'name' => 'مصروفات بوفية', 'level' => 4, 'parent_id' => 2110]);
        DB::table('account_origins')->insert(['id' => 211032, 'name' => 'مصروفات تليفون وبرق وهاتف', 'level' => 4, 'parent_id' => 2110]);
        DB::table('account_origins')->insert(['id' => 211033, 'name' => 'رسوم واشتراكات', 'level' => 4, 'parent_id' => 2110]);
        DB::table('account_origins')->insert(['id' => 211034, 'name' => 'مصروفات دعاية ونشر', 'level' => 4, 'parent_id' => 2110]);
        DB::table('account_origins')->insert(['id' => 211035, 'name' => 'مرتجع رواتب', 'level' => 4, 'parent_id' => 2110]);
        DB::table('account_origins')->insert(['id' => 211036, 'name' => 'اقساط سيارات', 'level' => 4, 'parent_id' => 2110]);
        DB::table('account_origins')->insert(['id' => 211037, 'name' => 'مصروفات نثرية', 'level' => 4, 'parent_id' => 2110]);
        DB::table('account_origins')->insert(['id' => 2110121, 'name' => 'سلف الموظفين', 'level' => 4, 'parent_id' => 2110]);
        DB::table('account_origins')->insert(['id' => 2110122, 'name' => 'المسحوبات', 'level' => 4, 'parent_id' => 2110]);
        DB::table('account_origins')->insert(['id' => 2110141, 'name' => 'الايجارات', 'level' => 4, 'parent_id' => 2110]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_origins');
    }
};
