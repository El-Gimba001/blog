<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ShowTables extends Command
{
    protected $signature = 'tables {--c|columns} {table?}';
    protected $description = 'Show database tables';

    public function handle()
    {
        $db = DB::connection()->getDatabaseName();
        $this->info("Database: {$db}");
        
        $tables = DB::select('SHOW TABLES');
        
        if ($tableName = $this->argument('table')) {
            // Show specific table
            $this->showTable($tableName);
        } else {
            // Show all tables
            $this->showAllTables($tables, $db);
        }
        
        return 0;
    }
    
    protected function showAllTables($tables, $db)
    {
        $tableData = [];
        foreach ($tables as $table) {
            $name = $table->{"Tables_in_{$db}"};
            $tableData[] = [$name];
        }
        
        $this->table(['Table Name'], $tableData);
        
        if ($this->option('columns')) {
            foreach ($tables as $table) {
                $name = $table->{"Tables_in_{$db}"};
                $this->showTable($name);
            }
        }
    }
    
    protected function showTable($tableName)
    {
        $this->line("\n=== {$tableName} ===");
        
        try {
            $columns = DB::select("DESCRIBE {$tableName}");
            $columnData = [];
            
            foreach ($columns as $col) {
                $columnData[] = [
                    $col->Field,
                    $col->Type,
                    $col->Null,
                    $col->Key ?: '',
                    $col->Default ?: 'NULL'
                ];
            }
            
            $this->table(['Field', 'Type', 'Null', 'Key', 'Default'], $columnData);
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
        }
    }
}