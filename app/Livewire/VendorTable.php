<?php

namespace App\Livewire;

use App\Models\Vendor;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class VendorTable extends PowerGridComponent
{
    public string $tableName = 'vendor-table-cl1r8n-table';
    public int $id_dell=0;

    protected $listeners = ['vendorAdded' => '$refresh'];
    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Vendor::query();
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
        $v = Vendor::find($rowId);
        if ($v) {
            $this->dispatch('edit-vendor', ['id' => $v->id, 'name' => $v->name]);
        } else {
            $this->dispatch('vendor-not-found');
        }
    }


    #[\Livewire\Attributes\On('dell')]
    public function dell(): void
    {
        if($this->id_dell!=0){
            $v = Vendor::find($this->id_dell);
                    if ($v) {

                        // Verificar si hay productos vinculados
                                if ($v->products()->count() > 0) {
                                    // Mostrar alerta: no se puede eliminar
                                    $this->dispatch('swal', [
                                        'title' => trans('Existen productos de este proovedor, por tanto no se puede eliminar.'), // agrega este mensaje en tu archivo de traducción
                                        'icon' => 'warning',
                                        'timer' => 8000,
                                    ]);
                                    return; // termina la función aquí
                                }


                        $name = $v->name;
                        $v->delete();
                        $this->dispatch('swal', [
                            'title' => trans('vendor.eliminado'),
                            'icon' => 'success',
                            'timer' => 3000,
                        ]);
                        $this->resetPage();
                    } else {
                        $this->dispatch('swal', [
                            'title' => trans('vendor.noencontrado'),
                            'icon' => 'warning',
                            'timer' => 3000,
                        ]);
                    }
        }else{
            $this->dispatch('swal', [
                            'title' => trans('vendor.noencontrado'),
                            'icon' => 'warning',
                            'timer' => 3000,
                        ]);
        }

    }


    #[\Livewire\Attributes\On('verif_dell')]
    public function verif_dell($rowId): void
    {
        $this->id_dell=$rowId;
        $v = Vendor::find($rowId);
        if ($v) {


            $this->dispatch('verif_swal', [

                'id_dell' => $rowId,
            ]);

        } else {
            $this->dispatch('swal', [
                'title' => trans('vendor.noencontrado'),
                'icon' => 'warning',
                'timer' => 3000,
            ]);
        }
    }

    public function actions(Vendor $row): array
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
        Vendor::query()->find($id)->update([
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
