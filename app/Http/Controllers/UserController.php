<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        echo "Page home";
        ## P1:
//        Yêu cầu 1: Truy vấn tất cả các bản ghi
        User::query()->orderByDesc('id')->ddRawSql();
//        Yêu cầu 2: Truy vấn với điều kiện
        User::query()->where('age', '>', 25)->ddRawSql();
//        Yêu cầu 3: Truy vấn với nhiều điều kiện
        User::query()->where([
            ['age', '>', 25],
            ['status', 'active']
        ])->ddRawSql();
//        Yêu cầu 4: Sắp xếp kết quả
        User::query()->orderByDesc('age')->ddRawSql();
//        Yêu cầu 5: Giới hạn số lượng kết quả
        User::query()->limit(10)->ddRawSql();
//        Yêu cầu 6: Truy vấn với điều kiện OR
        User::query()
            ->where('status', 'completed')
            ->orWhere('total', '>', 100)
            ->ddRawSql();
//        Yêu cầu 7: Truy vấn với LIKE
        User::query()->where('name', 'like', '%John%')->ddRawSql();
//        Yêu cầu 8: Truy vấn với BETWEEN
        User::query()->whereBetween('amount ', [1000, 5000])->ddRawSql();
//        Yêu cầu 9: Truy vấn với IN
        User::query()->whereIn('department_id', [1,2,3])->get();
//        Yêu cầu 10: Thực hiện JOIN
        DB::table('orders')
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->select('orders.*', 'customers.*')
            ->ddRawSql();
//        Yêu cầu 11: Truy vấn với nhóm và tổng hợp
        DB::table('order_items')
            ->selectRaw('SUM(quantity) as total')
            ->groupBy('product_id')
            ->ddRawSql();
//        Yêu cầu 12: Cập nhật bản ghi
        DB::table('orders')
            ->where('status', 'processing')
            ->update([
                'status' => 'shipped'
            ]);
//        Yêu cầu 13: Xóa bản ghi
        DB::table('logs')
            ->whereDate('created_at', '<', '2020-1-1')
            ->delete();
//        Yêu cầu 14: Thêm bản ghi mới
        DB::table('products')
            ->insert([
                'name' => 'Product 1',
                'price' => 2004,
                'quantity' => 10
            ]);
//        Yêu cầu 15: Sử dụng Raw Expressions
        DB::table('users')->whereRaw('MONTH(birth_date) = 5')->ddRawSql();

        ## P2:
//        1:
        DB::table('employees', 'e')
            ->join('departments as d', 'e.department_id', '=', 'd.department_id')
            ->where('d.department_name', 'IT')
            ->select('e.first_name', 'e.last_name', 'd.department_name')
            ->ddRawSql();
//        2:
        DB::table('employees', 'e')
            ->join('departments as d', 'e.department_id', '=', 'd.department_id')
            ->where([
                ['e.salary', '>', 70000],
                ['d.department_name', 'Marketing']
            ])
            ->select('e.first_name', 'e.last_name', 'd.department_name')
            ->ddRawSql();
//        3:
        DB::table('employees', 'e')
            ->join('departments as d', 'e.department_id', '=', 'd.department_id')
            ->whereBetween('e.salary', [50000, 70000])
            ->select('e.first_name', 'e.last_name', 'd.department_name')
            ->ddRawSql();
//        4:
        DB::table('employees', 'e')
            ->join('departments as d', 'e.department_id', '=', 'd.department_id')
            ->where('d.department_name', '<>', 'HR')
            ->select('e.first_name', 'e.last_name', 'd.department_name')
            ->ddRawSql();
//        5:
        DB::table('employees', 'e')
            ->join('departments as d', 'e.department_id', '=', 'd.department_id')
            ->where('e.last_name', 'like', 'D%')
            ->select('e.first_name', 'e.last_name', 'd.department_name')
            ->ddRawSql();
//        6:
##        C1
        $maxSalary = DB::table('employees')
            ->selectRaw('MAX(salary)')
            ->whereColumn('department_id', 'e.department_id');

        DB::table('employees as e')
            ->join('departments as d', 'e.department_id', '=', 'd.department_id')
            ->where(DB::raw('e.salary'), '=', $maxSalary)
            ->select('e.first_name', 'e.last_name', 'd.department_name')
            ->ddRawSql();
//        7:
        // c1
//        date_default_timezone_set('Asia/Ho_Chi_Minh');
//        $data = DB::table('employees', 'e')
//            ->join('departments as d', 'e.department_id', '=', 'd.department_id')
//            ->where('e.hire_date', '<=', date('Y-m-d', strtotime('-3 years')))
//            ->select('e.first_name', 'e.last_name', 'd.department_name')
//            ->ddRawSql();
        // c2:

        $filterDate = Carbon::now(env('LARAVEL_ASIA_TIME'))->subYears(3);
        DB::table('employees', 'e')
            ->join('departments as d', 'e.department_id', '=', 'd.department_id')
            ->where('e.hire_date', '<=', $filterDate)
            ->select('e.first_name', 'e.last_name', 'd.department_name')
            ->ddRawSql();

//        dd(Carbon::now(env('LARAVEL_ASIA_TIME'))->subYears(3));
    }
}
