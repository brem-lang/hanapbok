<?php

namespace App\Filament\Pages;

use App\Models\GuestReview as ModelsGuestReview;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class GuestReview extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.guest-review';

    protected static ?string $navigationGroup = 'Reports';

    protected static ?int $navigationSort = 6;

    public static function canAccess(): bool
    {
        return auth()->user()->isResortsAdmin();
    }

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                Action::make('print')
                    ->label('Print')
                    ->icon('heroicon-o-printer')
                    ->url(fn () => route('reports.print', ['resort_id' => auth()->user()?->AdminResort?->id]))
                    ->openUrlInNewTab(),
            ])
            ->query(ModelsGuestReview::query()->with('user')->where('resort_id', auth()->user()?->AdminResort?->id)->latest())
            ->columns([
                TextColumn::make('user.name')
                    ->label('Guest Name')
                    ->sortable()
                    // ->toggleable()
                    ->searchable(),
                TextColumn::make('review')
                    ->label('Review')
                    ->sortable()
                    ->limit(50)
                // ->toggleable()
                ,
                TextColumn::make('created_at')
                    ->label('Date')
                    ->date('F d, Y h:i A')
                    ->searchable()
                    // ->toggleable()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('user_id')
                    ->label('Guest')
                    ->options(User::where('role', 'guest')->pluck('name', 'id'))->preload()
                    ->searchable(),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('date')
                            ->label('Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                //
            ]);
    }
}
