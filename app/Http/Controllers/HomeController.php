<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Column;
use Illuminate\Http\Request;
use Spatie\DbDumper\Databases\MySql;
use Spatie\DbDumper\Databases\PostgreSql;
use Spatie\DbDumper\Databases\Sqlite;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $columns = Column::with('cards')->get();

        return view('home', compact('columns'));
    }

    public function dump(){
//        MySql::create()
//            ->setDbName('board')
//            ->setUserName('root')
//            ->setPassword('')
//            ->dumpToFile('dump.sql');

//        Sqlite::create()
//            ->setDbName(database_path('database.sqlite'))
//            ->dumpToFile('dump.sql');

        


        PostgreSql::create()
            ->setHost(config('app.databaseHost'))
            ->setDbName(config('app.databaseName'))
            ->setUserName(config('app.userName'))
            ->setPassword(config('app.password'))
            ->dumpToFile('dump.sql');

        return response()->download('dump.sql');

    }


}
