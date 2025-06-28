<?php

namespace App\Livewire;

use App\Models\Warehouse;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class WarehouseTable extends PowerGridComponent
{
    public string $tableName = 'warehouse-table-ihj2u1-table';
    public int $id_dell=0;

    protected $listeners = ['warehouseAdded' => '$refresh'];
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
        return Warehouse::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
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
            /* Filter::inputText('name', 'name')->placeholder('Warehouse Name'), */

            /* Filter::boolean('in_stock', 'in_stock')
                ->label('In Stock', 'Out of Stock'),

            Filter::number('price_BRL', 'price')
                ->thousands('.')
                ->decimal(',')
                ->placeholder('lowest', 'highest'), */

        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $w = Warehouse::find($rowId);
        if ($w) {
            $this->dispatch('edit-warehouse', ['id' => $w->id, 'name' => $w->name]);
        } else {
            $this->dispatch('warehouse-not-found');
        }
    }


    #[\Livewire\Attributes\On('dell')]
    public function dell(): void
{
    if ($this->id_dell != 0) {
        $warehouse = Warehouse::find($this->id_dell);
        if ($warehouse) {
            // Verificar si hay productos vinculados
            if ($warehouse->products()->count() > 0) {
                // Mostrar alerta: no se puede eliminar
                $this->dispatch('swal', [
                    'title' => trans('Existen productos en este almacen, por tanto no se puede eliminar.'), // agrega este mensaje en tu archivo de traducción
                    'icon' => 'warning',
                    'timer' => 8000,
                ]);
                return; // termina la función aquí
            }

            // Si no hay productos, se puede eliminar
            $warehouse->delete();

            $this->dispatch('swal', [
                'title' => trans('warehouse.eliminado'),
                'icon' => 'success',
                'timer' => 3000,
            ]);
            $this->resetPage();

        } else {
            // Almacen no encontrado
            $this->dispatch('swal', [
                'title' => trans('warehouse.noencontrado'),
                'icon' => 'warning',
                'timer' => 3000,
            ]);
        }
    } else {
        // ID inválido
        $this->dispatch('swal', [
            'title' => trans('warehouse.noencontrado'),
            'icon' => 'warning',
            'timer' => 3000,
        ]);
    }
}


    #[\Livewire\Attributes\On('verif_dell')]
    public function verif_dell($rowId): void
    {
        $this->id_dell=$rowId;
        $w = Warehouse::find($rowId);
        if ($w) {


            $this->dispatch('verif_swal', [

                'id_dell' => $rowId,
            ]);

        } else {
            $this->dispatch('swal', [
                'title' => trans('warehouse.noencontrado'),
                'icon' => 'warning',
                'timer' => 3000,
            ]);
        }
    }

    public function actions(Warehouse $row): array
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
        Warehouse::query()->find($id)->update([
            $field=>$value
        ]);
    }




}
