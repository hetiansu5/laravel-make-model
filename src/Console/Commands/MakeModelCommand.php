<?php

namespace MakeModel\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use MakeModel\Definition\Template\Attribute;
use MakeModel\Definition\Template\ClassTemplate;
use MakeModel\Definition\Field;
use MakeModel\Definition\Table;
use MakeModel\Definition\Template\Getter;
use Symfony\Component\Console\Exception\RuntimeException;

class MakeModelCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:e-model
        {table : Table name of the model that you want to create.} 
        {--m|model= : Name of Model class.} 
        {--c|connection=mysql : Name of the connection configured in `config/database.php`.} 
        {--f|field= : Fields you need getter and setter functions in Model class.} 
        {--i|ignoreField= : Fields you ignore getter and setter functions in Model class.}
        {--t|ignoreTime : ignore time-related(created_at,updated_at,deleted_at) getter and setter functions in Model class.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Eloquent model class filled with getter and setter';

    public function handle()
    {
        $table = $this->argument('table');
        $model = $this->option('model');
        $connection = $this->option('connection');
        $field = $this->option('field');
        $ignoreField = $this->option('ignoreField');
        $ignoreTime = $this->option('ignoreTime');

        $template = new ClassTemplate();
        if (empty($model)) {
            $model = rtrim($template->snakeToBigCamel($table), "s");
        }

        $fields = !empty($field) ? explode(',', $field) : [];
        $ignoreFields = !empty($ignoreField) ? explode(',', $ignoreField) : [];
        $defaultTimes = ['created_at', 'updated_at', 'deleted_at'];
        if ($ignoreTime) {
            $ignoreFields = array_merge($ignoreFields, $defaultTimes);
        }

        $template->setNameSpace('App\Models');
        $template->setClassName($template->snakeToBigCamel($model));
        $template->setUses([
            'Illuminate\Database\Eloquent\Model',
        ]);
        $template->setExtend('Model');

        //table属性
        $template->addAttribute(new Attribute(Attribute::TYPE_PROTECTED, 'table', "'{$table}'"));

        $tableDesc = DB::connection($connection)->select("desc `{$table}`");
        /**
         * @var Field[] $tableDefinition
         */
        $tableDefinition = new Table($tableDesc);
        $existTimes = [];
        $hasDeleted = false;
        foreach ($tableDefinition as $fieldObject) {
            if ($fieldObject->isPrimary()) {
                //主键属性
                $template->addAttribute(new Attribute(Attribute::TYPE_PROTECTED, 'primaryKey', $fieldObject->getField()->quote()));
            }

            if (in_array($fieldObject->getField()->toString(), $defaultTimes)) {
                $existTimes[] = $fieldObject->getField()->toString();
            }

            if ($fieldObject->getField()->toString() == 'deleted_at') {
                $hasDeleted = true;
            }

            if (!empty($fields)) {
                if (!in_array($fieldObject->getField(), $fields)) {
                    continue;
                }
            }
            if (in_array($fieldObject->getField(), $ignoreFields)) {
                continue;
            }

            //setter and getter
            $getter = new Getter($fieldObject->getField()->toString(), $fieldObject->getType()->toString());
            $template->addGetSetters($getter);
        }

        //有删除属性
        if ($hasDeleted) {
            $template->addUse('Illuminate\Database\Eloquent\SoftDeletes');
            $template->addUseTraits('SoftDeletes');
        }

        //dates属性
        if (!empty($existTimes)) {
            $value = "['" . implode("', '", $existTimes) . "']";
            $template->addAttribute(new Attribute(Attribute::TYPE_PROTECTED, 'dates', $value));
        }

        $template->render();
    }

}
