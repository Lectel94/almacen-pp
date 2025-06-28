<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use Spatie\Permission\Models\Role;

final class UserTable extends PowerGridComponent
{
    public string $tableName = 'user-table-7u1ekp-table';
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
        return User::query()
    ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')    // unión con la tabla de relación
    ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')             // unión con la tabla de roles
    ->select('users.*', 'roles.name as role_name', 'roles.id as role_id');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        $options = $this->roleSelectOptions();


        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            /* ->add('avatar',  fn ($item) => $item->profile_photo_path ? '<img class="w-8 h-8 rounded-full shrink-0 grow-0" src="' . asset("storage/{$item->profile_photo_path}") . '">': '') */
            ->add('email')
            ->add('created_at')

            ->add('role_id', fn ($p) => intval($p->role_id))

            ->add('role_id', fn ($dish) => intval($dish->role_id))
            ->add('role_name', function ($dish) use ($options) {
                if (is_null($dish->role_id)) {

                }

                return Blade::render('<x-select-role type="occurrence" :options=$options  :dishId=$dishId  :selected=$selected/>', ['options' => $options, 'dishId' => intval($dish->id), 'selected' => intval($dish->role_id)]);
            });
    }

    public function columns(): array
    {
        return [
            Column::action('Action'),
            /* Column::make('Id', 'id'), */
            Column::make('Name', 'name')
                ->sortable()
                ->searchable()
                ->editOnClick(hasPermission:true),

                 Column::make('Role', 'role_name', 'role_id')->sortable(),



                /* Column::make('Avatar', 'avatar'), */

            Column::make('Email', 'email')
                ->sortable()
                ->searchable(),

            /* Column::make('Created at', 'created_at_formatted', 'created_at')
                ->sortable(), */

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

    public function roleSelectOptions(): \Illuminate\Support\Collection
    {
        return Role::all(['id', 'name'])->mapWithKeys(function ($item) {
            return [
                 $item->id => match (strtolower($item->name)) {

                default => $item->name ,
            },
            ];
        });
    }

     #[\Livewire\Attributes\On('roleChanged')]
    public function roleChanged($roleId, $iduser): void
    {
        $user=User::find($iduser);
        $role=Role::find($roleId);

        if($iduser){
            if($role){
                $user->syncRoles($role);
                $this->dispatch('swal', [
                'title' => trans('Role asignado'),
                'icon' => 'success',
                'timer' => 3000,
            ]);
            }else{
                 $this->dispatch('swal', [
                'title' => trans('Role no encontrado'),
                'icon' => 'warning',
                'timer' => 3000,
            ]);
            }

        }
        else{
             $this->dispatch('swal', [
                'title' => trans('Usuario no encontrado'),
                'icon' => 'warning',
                'timer' => 3000,
            ]);
        }

    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $user = User::find($rowId);
        if ($user) {
            $this->dispatch('edit-user', ['id' => $user->id, 'name' => $user->name]);
        } else {
            $this->dispatch('user-not-found');
        }
    }


    #[\Livewire\Attributes\On('dell')]
    public function dell(): void
    {
        if($this->id_dell!=0){
            $u = User::find($this->id_dell);
                    if ($u) {
                        $name = $u->name;
                        $u->delete();
                        $this->dispatch('swal', [
                            'title' => trans('user.eliminado'),
                            'icon' => 'success',
                            'timer' => 3000,
                        ]);
                        $this->resetPage();
                    } else {
                        $this->dispatch('swal', [
                            'title' => trans('user.noencontrado'),
                            'icon' => 'warning',
                            'timer' => 3000,
                        ]);
                    }
        }else{
            $this->dispatch('swal', [
                            'title' => trans('user.noencontrado'),
                            'icon' => 'warning',
                            'timer' => 3000,
                        ]);
        }

    }


    #[\Livewire\Attributes\On('verif_dell')]
    public function verif_dell($rowId): void
    {
        $this->id_dell=$rowId;
        $u = User::find($rowId);
        if ($u) {


            $this->dispatch('verif_swal', [

                'id_dell' => $rowId,
            ]);

        } else {
            $this->dispatch('swal', [
                'title' => trans('user.noencontrado'),
                'icon' => 'warning',
                'timer' => 3000,
            ]);
        }
    }

    public function actions(User $row): array
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
    User::query()->find($id)->update([
        $field=>$value
    ]);
}


}
