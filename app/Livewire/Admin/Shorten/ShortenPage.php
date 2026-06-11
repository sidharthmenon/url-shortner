<?php

namespace App\Livewire\Admin\Shorten;

use App\Helpers\HashHelper;
use App\Jobs\createUrl;
use App\Jobs\deleteUrl;
use App\Models\Shorten;
use App\Services\ShortLinkAnalyticsService;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class ShortenPage extends Component implements HasTable, HasForms, HasActions
{
    use InteractsWithTable, InteractsWithForms, InteractsWithActions;

    public function table(Table $table): Table
    {
        return $table
            ->query(Shorten::query())
            ->heading('Shorten Url')
            ->defaultSort('created_at', direction: 'desc')
            ->columns([
                TextColumn::make('id')->label('Url')->getStateUsing(function ($record) {
                    return 'https://ksum.in/' . $record->code;
                })
                ->copyable()->copyMessage('Link copied')
                ->icon(Heroicon::OutlinedClipboardDocument)
                ->iconPosition(IconPosition::After),
                TextColumn::make('user.name')->toggleable(true, true),
                TextColumn::make('code')->searchable()->label('Short Code')->toggleable(true, true),
                TextColumn::make('url')->label('Long Url')->searchable()->wrap(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->model(Shorten::class)
                    ->schema([
                        TextInput::make('url')->label('Long Url')->required()->activeUrl(),
                        Toggle::make('generate')->label('Edit Short Code')->reactive(),
                        TextInput::make('code')->label('Short Code')->hidden(function (callable $get) {
                            return $get('generate') != true;
                        })->unique('shortens', 'code')->required(),
                    ])
                    ->mutateDataUsing(function ($data) {
                        $latest = Shorten::latest()->first();
                        $id = $latest?->id ?? 1;
                        $code = HashHelper::HashId('url', $id + 1);

                        $data['code'] = $data['code'] ?? $code;
                        $data['user_id'] = auth()->user()->id;

                        return $data;
                    })
                    ->after(function ($record) {
                        dispatch(new createUrl($record));
                    })
                    ->createAnother(false)
                    ->visible(auth()->user()->can('admin:urls:create'))
            ])
            ->filters([
                Filter::make('my_urls')->query(function (Builder $query) {
                    return $query->where('user_id', auth()->user()->id);
                })->default(true)
            ])
            ->recordActions([
                Action::make('analytics')
                    ->icon('heroicon-o-chart-bar-square')
                    ->modalHeading(fn (Shorten $record) => 'Analytics for ' . $record->code)
                    ->modalContent(function (Shorten $record) {
                        return view('analytics', [
                            'record' => $record,
                            'analytics' => app(ShortLinkAnalyticsService::class)->buildReport($record),
                        ]);
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close')
                    ->modalWidth(Width::FourExtraLarge),
                Action::make('qrcode')->modalContent(function ($record) {
                    return view('qrcode', ['url' => $record->link, 'code' => $record->code]);
                })->icon(Heroicon::QrCode)
                ->modalSubmitAction(false)->modalCancelAction(false)->modalWidth(Width::ExtraLarge),
                Action::make('delete')
                    ->action(function ($record) {
                        dispatch(new deleteUrl($record));
                    })
                    ->requiresConfirmation()
                    ->icon('heroicon-o-trash')->color('danger')
                    ->visible(function ($record) {
                        if (auth()->user()->can('admin:urls:delete')) {
                            if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('super')) {
                                return true;
                            }
                            else {
                                return $record->user_id == auth()->user()->id;
                            }
                        }
                        else {
                            return false;
                        }
                    }),
            ], RecordActionsPosition::BeforeCells);
    }

    public function render()
    {
        return view('livewire.base-page');
    }
}
