<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Status;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class OrderTable extends PowerGridComponent
{
    public string $tableName = 'order-table-iedwmg-table';
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
        return Order::query()
        ->leftJoin('users', 'orders.user_id', '=', 'users.id')
        ->leftJoin('statuses', 'orders.status_id', '=', 'statuses.id')


        ->select('orders.*',
        'users.name as user_name',
        'statuses.description as status_name',
        );
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        $options=Status::all();
        return PowerGrid::fields()
            ->add('id')
            ->add('number')
            ->add('created_at')


            ->add('user_id', fn ($p) => intval($p->user_id))
            ->add('user_name')

            ->add('status_id', fn ($dish) => intval($dish->status_id))
            ->add('status_name', function ($dish) use ($options) {
                if (is_null($dish->status_id)) {

                }

                return Blade::render('<x-select-status type="occurrence" :options=$options  :dishId=$dishId  :selected=$selected/>', ['options' => $options, 'dishId' => intval($dish->id), 'selected' => intval($dish->status_id)]);
            })
            ->add('total')


            ->add('details', function ($dish)  {
                return Blade::render('<x-order-details type="occurrence"   :order=$order/>', [ 'order' => $dish]);
            });
    }

    public function columns(): array
    {
        return [
            Column::action('Action'),

            Column::make('Number', 'number')
                ->sortable()
                ->visibleInExport(true),
            Column::make('User', 'user_name', 'user_id')->sortable()->visibleInExport(true),

            Column::make('Status', 'status_name', 'status_id' )->sortable()->visibleInExport(true),

            Column::make('Total', 'total', )->sortable()->visibleInExport(true),
            Column::make('Details', 'details'),
            Column::make('Created at', 'created_at')
                ->sortable()
                ->searchable(),


        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('number', 'orders.number')->placeholder('Order number'),

            Filter::select('user_name', 'user_id')
                ->dataSource(User::all())
                ->optionLabel('name')
                ->optionValue('id'),

                Filter::select('status_name', 'status_id')
                ->dataSource(Status::all())
                ->optionLabel('description')
                ->optionValue('id'),

                Filter::number('total', 'total')
                ->thousands('.')
                ->decimal(',')
                ->placeholder('lowest', 'highest'),

        ];
    }

     #[\Livewire\Attributes\On('roleChanged')]
    public function statusChanged($statusId, $idorder): void
    {
        $status=Status::find($statusId);
        $order=Order::find($idorder);

        if($idorder){
            if($status){
                $order->status_id=$status->id;
                $order->save();
                $this->dispatch('swal', [
                'title' => trans('Status cambiado'),
                'icon' => 'success',
                'timer' => 3000,
            ]);
            }else{
                 $this->dispatch('swal', [
                'title' => trans('Status no encontrado'),
                'icon' => 'warning',
                'timer' => 3000,
            ]);
            }

        }
        else{
             $this->dispatch('swal', [
                'title' => trans('Orden no encontrado'),
                'icon' => 'warning',
                'timer' => 3000,
            ]);
        }

    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {

        $this->js('alert('.$rowId.')');
    }

    #[\Livewire\Attributes\On('dell')]
    public function dell(): void
    {
        if($this->id_dell!=0){
            $order = Order::find($this->id_dell);
                    if ($order) {
                        $name = $order->name;
                        $order->delete();
                        $this->dispatch('swal', [
                            'title' => trans('order.eliminado'),
                            'icon' => 'success',
                            'timer' => 3000,
                        ]);
                        $this->resetPage();
                    } else {
                        $this->dispatch('swal', [
                            'title' => trans('order.noencontrado'),
                            'icon' => 'warning',
                            'timer' => 3000,
                        ]);
                    }
        }else{
            $this->dispatch('swal', [
                            'title' => trans('order.noencontrado'),
                            'icon' => 'warning',
                            'timer' => 3000,
                        ]);
        }

    }


    #[\Livewire\Attributes\On('verif_dell')]
    public function verif_dell($rowId): void
    {
        $this->id_dell=$rowId;
        $order = Order::find($rowId);
        if ($order) {


            $this->dispatch('verif_swal', [

                'id_dell' => $rowId,
            ]);

        } else {
            $this->dispatch('swal', [
                'title' => trans('order.noencontrado'),
                'icon' => 'warning',
                'timer' => 3000,
            ]);
        }
    }


    public function actions(Order $row): array
    {
        return [
            Button::add('dell')
                ->slot('<i  class="fas fa-trash"></i>')

                    ->id()
                    ->class(' pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                    ->dispatch('verif_dell', ['rowId' => $row->id]),
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
