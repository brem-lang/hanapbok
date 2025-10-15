<?php

namespace App\Filament\Pages;

use App\Filament\Resources\ResortResource;
use App\Models\Resort;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class ResortsAdmin extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.resorts-admin';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?string $title = 'Assign Resorts Admin';

    public static function canAccess(): bool
    {
        return false;
    }

    public function mount()
    {
        if (auth()->user()->isGuest()) {
            abort(404);
        }
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Resort::query()->latest())
            ->paginated([10, 25, 50])
            ->columns([
                TextColumn::make('userAdmin.name')->searchable()
                    ->label('Assigned Admin')
                    ->sortable(),
                TextColumn::make('name')->searchable(),
                TextColumn::make('barangay')->searchable(),
                TextColumn::make('description')->searchable()->limit(20),
                TextColumn::make('is_active')
                    ->label('Active')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '1' => 'success',
                        '0' => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => $state ? 'Yes' : 'No')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Action::make('view')
                        ->label('View')
                        ->icon('heroicon-o-eye')
                        ->url(fn ($record) => ResortResource::getUrl('edit', ['record' => $record->id]))
                        ->openUrlInNewTab(),
                    Action::make('assign_admin')
                        ->label('Assign Admin')
                        ->icon('heroicon-o-user-group')
                        ->form([
                            Select::make('user_id')
                                ->label('Admin')
                                ->required()
                                ->options(User::where('role', 'resorts_admin')->whereDoesntHave('AdminResort')->pluck('name', 'id')->toArray())
                                ->formatStateUsing(fn ($record) => $record?->user_id ?? null),
                        ])
                        ->action(function ($record, $data) {
                            $record->user_id = $data['user_id'];
                            $record->save();

                            Notification::make()
                                ->title('Admin Assigned Successfully')
                                ->success()
                                ->send();
                        })
                        ->requiresConfirmation(),
                ]),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
