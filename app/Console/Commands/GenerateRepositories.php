<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateRepositories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repo {model : The name of the model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Repositories';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $modelName = $this->argument('model');
        $interfaceFilePath = app_path()."/Repositories/Interfaces/${modelName}RepositoryInterface.php";
        $filePath = app_path()."/Repositories/Eloquent/${modelName}Repository.php";
        $interfaceFileContent = '<?php
namespace App\Repositories\Interfaces;
use App\Models\\'.$modelName.';


interface '.$modelName.'RepositoryInterface{

}
        ';
        $fileContent = '<?php
namespace App\Repositories\Eloquent;
use App\Models\\'.$modelName.';
use App\Repositories\Interfaces\\'.$modelName.'RepositoryInterface;

class '.$modelName.'Repository extends BaseRepository implements '.$modelName.'RepositoryInterface{
    public function __construct('.$modelName.' $model){
        $this->model = $model;
    }

}
        ';
        file_put_contents($interfaceFilePath, $interfaceFileContent);
        file_put_contents($filePath, $fileContent);
        $this->info("Repository Created Successfully");
        return 0;
    }
}
