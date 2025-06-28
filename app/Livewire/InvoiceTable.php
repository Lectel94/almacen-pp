<?php

namespace App\Livewire;

use App\Models\Invoice;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class InvoiceTable extends PowerGridComponent
{
    public string $tableName = 'invoice-table-oouokz-table';
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
        ];
    }

    public function datasource(): Builder
    {
        return Invoice::query()
        ->leftJoin('orders', 'invoices.order_id', '=', 'orders.id')
        ->leftJoin('users', 'orders.user_id', '=', 'users.id')


        ->select('invoices.*',
        'orders.number as order_number',
        'users.name as user_name',
        );
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')

            ->add('order_id', fn ($p) => intval($p->order_id))
            ->add('order_number')

            ->add('user_id', fn ($p) => intval($p->order->user_id))
            ->add('user_name')

            ->add('total_amount')


            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::action('Action'),
            Column::make('Order Number', 'order_number','order_id')->sortable()
                ->searchable(),
            Column::make('User', 'user_name'),

            Column::make('invoice Amount', 'total_amount'),
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

    #[\Livewire\Attributes\On('download')]
    public function download($rowId)
    {
        $factura = Invoice::findOrFail($rowId);
        $filePath = $factura->pdf_path;

        // Verificar si el archivo existe
        if (!Storage::exists($filePath)) {
            // Aquí emites el evento con el mensaje
            $this->dispatch('swal', [
                'title' => trans('Invoice PDF no encontrado'),
                'icon' => 'warning',
                'timer' => 3000,
            ]);

        }else{
            return Storage::download($filePath);
        }


    }

    public function actions(Invoice $row): array
    {
        return [
            Button::add('download')
                ->slot('<i class="fas fa-download"></i>')
                ->id()
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->dispatch('download', ['rowId' => $row->id]),
        ];
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
