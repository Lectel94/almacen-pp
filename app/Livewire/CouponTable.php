<?php

namespace App\Livewire;

use App\Models\Coupon;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class CouponTable extends PowerGridComponent
{
    public string $tableName = 'coupon-table-wpmo8k-table';
    public int $id_dell=0;
    protected $listeners = ['couponAdded' => '$refresh'];


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
        return Coupon::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('code')
            ->add('discount_amount')

            ->add('is_percentage', fn ($dish) => $dish->is_percentage ? 'Yes' : 'No')
            ->add('expires_at')
            ->add('expires_at_formatted', fn (Coupon $model) => Carbon::parse($model->expires_at)->format('d/m/Y'))
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::action('Action'),
            Column::make('Code', 'code')
                ->sortable()
                ->searchable(),

            Column::make('Discount amount', 'discount_amount')
                ->sortable()
                ->searchable(),

            Column::make('Is percentage', 'is_percentage')
                ->sortable()
                ->searchable(),

            Column::make('Expires at',  'expires_at')
                ->sortable(),



            Column::make('Created at', 'created_at')
                ->sortable()
                ->searchable(),


        ];
    }

    public function filters(): array
    {
        return [
            Filter::datepicker('expires_at'),
        ];
    }




    #[\Livewire\Attributes\On('dell')]
    public function dell(): void
    {
        if($this->id_dell!=0){
            $coupon = Coupon::find($this->id_dell);
                    if ($coupon) {
                        $code = $coupon->code;
                        $coupon->delete();
                        $this->dispatch('swal', [
                            'title' => trans('coupon.eliminado'),
                            'icon' => 'success',
                            'timer' => 3000,
                        ]);
                        $this->resetPage();
                    } else {
                        $this->dispatch('swal', [
                            'title' => trans('coupon.noencontrado'),
                            'icon' => 'warning',
                            'timer' => 3000,
                        ]);
                    }
        }else{
            $this->dispatch('swal', [
                            'title' => trans('coupon.noencontrado'),
                            'icon' => 'warning',
                            'timer' => 3000,
                        ]);
        }

    }


    #[\Livewire\Attributes\On('verif_dell')]
    public function verif_dell($rowId): void
    {
        $this->id_dell=$rowId;
        $coupon = Coupon::find($rowId);
        if ($coupon) {


            $this->dispatch('verif_swal', [

                'id_dell' => $rowId,
            ]);

        } else {
            $this->dispatch('swal', [
                'title' => trans('coupon.noencontrado'),
                'icon' => 'warning',
                'timer' => 3000,
            ]);
        }
    }


    public function actions(Coupon $row): array
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
