<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Traits\ActionTrait;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasRelationshipTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Livewire\Component;

class BasePage extends Component implements HasTable
{
    use InteractsWithTable, ActionTrait;

    protected $listeners = [
        'refreshComponent' => '$refresh',
    ];

    protected $queryString = [
        'tableFilters',
        'tableSortColumn',
        'tableSortDirection',
        'tableSearchQuery' => ['except' => ''],
        'tableColumnSearchQueries',
    ];

    protected function getTableActions(): array
    {
        return [
            ActionGroup::make([
                Action::make('edit')
                    ->action(function($record){
                        $this->editItem($record);
                    })
                    ->visible(function($record){
                        return auth()->user()->can($this->permission.'update');
                    })->icon('heroicon-s-pencil')->color('primary'),
                Action::make('delete')
                    ->action(function($record){
                        $this->deleteItem($record);
                    })
                    ->visible(function($record){
                        return auth()->user()->can($this->permission.'delete');
                    })->icon('heroicon-o-trash')->color('danger')
                    ->requiresConfirmation(),
            ])
        ];
    }

    public function render()
    {
        return view('livewire.base-page');
    }

}
