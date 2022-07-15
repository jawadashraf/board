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

//        PostgreSql::create()
//            ->setHost('ec2-3-219-52-220.compute-1.amazonaws.com')
//            ->setDbName("d99cetgcoeavq8")
//            ->setUserName('wnhzrvdspdhtvn')
//            ->setPassword('97167c85b02cbd4c7dc5d91a0d1bd106cb7c0e146ce6d9383290632f3dc227da')
//            ->dumpToFile('dump.sql');

        return response()->download('dump.sql');

    }


}
