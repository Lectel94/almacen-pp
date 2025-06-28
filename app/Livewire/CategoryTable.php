<?php

namespace App\Livewire;

use App\Models\Category;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class CategoryTable extends PowerGridComponent
{
    public string $tableName = 'category-table-8dp3bj-table';
    use WithExport;
    protected $listeners = ['categoryAdded' => '$refresh'];
    public int $id_dell=0;
    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
               /*  PowerGrid::exportable(fileName: 'my-export-file')
            ->type( Exportable::TYPE_CSV), */
        ];
    }

    public function datasource(): Builder
    {
        return Category::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::action('Action'),
            Column::make('Name', 'name')
            ->editOnClick(hasPermission:true)
            ->sortable()
            ->searchable(),

            Column::make('Created at', 'created_at')
                ->sortable()
                ->searchable(),


        ];
    }

    public function filters(): array
    {
        return [
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $c = Category::find($rowId);
        if ($c) {
            $this->dispatch('edit-category', ['id' => $c->id, 'name' => $c->name]);
        } else {
            $this->dispatch('category-not-found');
        }
    }


    #[\Livewire\Attributes\On('dell')]
    public function dell(): void
    {
        if($this->id_dell!=0){
            $c = Category::find($this->id_dell);
                    if ($c) {
                            // Verificar si hay productos vinculados
                                if ($c->products()->count() > 0) {
                                    // Mostrar alerta: no se puede eliminar
                                    $this->dispatch('swal', [
                                        'title' => trans('Existen productos en esta categoria, por tanto no se puede eliminar.'), // agrega este mensaje en tu archivo de traducción
                                        'icon' => 'warning',
                                        'timer' => 8000,
                                    ]);
                                    return; // termina la función aquí
                                }

                        $name = $c->name;
                        $c->delete();
                        $this->dispatch('swal', [
                            'title' => trans('category.eliminado'),
                            'icon' => 'success',
                            'timer' => 3000,
                        ]);
                        $this->resetPage();
                    } else {
                        $this->dispatch('swal', [
                            'title' => trans('category.noencontrado'),
                            'icon' => 'warning',
                            'timer' => 3000,
                        ]);
                    }
        }else{
            $this->dispatch('swal', [
                            'title' => trans('category.noencontrado'),
                            'icon' => 'warning',
                            'timer' => 3000,
                        ]);
        }

    }


    #[\Livewire\Attributes\On('verif_dell')]
    public function verif_dell($rowId): void
    {
        $this->id_dell=$rowId;
        $c = Category::find($rowId);
        if ($c) {


            $this->dispatch('verif_swal', [

                'id_dell' => $rowId,
            ]);

        } else {
            $this->dispatch('swal', [
                'title' => trans('category.noencontrado'),
                'icon' => 'warning',
                'timer' => 3000,
            ]);
        }
    }

    public function actions(Category $row): array
    {
        $dell=asset('img/icodel.png');
        $edit=asset('img/ico25.png');
            return [
                /* Button::add('edit')
                ->slot('<img src="'.$edit.'">')
                    ->id()
                    ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                    ->dispatch('edit', ['rowId' => $row->id]), */

                    Button::add('dell')
                    ->slot('<i class="fas fa-trash"></i>')
                    ->id()
                    ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                    ->dispatch('verif_dell', ['rowId' => $row->id]),
            ];
    }

    public function onUpdatedEditable(string|int $id, string $field, string $value): void
    {
        Category::query()->find($id)->update([
            $field=>$value
        ]);
    }

    /*
    public function actionRules($row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($row) => $row->id === 1)
                ->hide(),
        ];
    }
    */
}
