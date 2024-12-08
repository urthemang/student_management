<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Resources\Pages\CreateRecord;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationLabel = 'Manage Student';
    protected static ?string $label = 'Student';
    protected static ?string $slug = 'students';

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    public static function form(Form $form): Form

    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                TextInput::make('email')->email()->required(),
                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->dehydrateStateUsing(fn($state) => filled($state) ? bcrypt($state) : null)
                    ->required(fn($livewire) => $livewire instanceof CreateRecord)
                    ->rules(fn($livewire) => $livewire instanceof CreateRecord
                        ? ['required', 'string', 'min:8', 'confirmed']
                        : ['nullable', 'string', 'min:8', 'confirmed'])
                    ->dehydrated(fn($state) => filled($state)),
                TextInput::make('password_confirmation')
                    ->label('Confirm Password')
                    ->password()
                    ->required(fn($livewire) => $livewire instanceof CreateRecord)
                    ->dehydrated(false),

                Repeater::make('userSubjects')
                    ->label('Subjects')
                    ->relationship()
                    ->schema([
                        Select::make('subject_id')
                            ->label('Subject')
                            ->options(\App\Models\Subject::all()->pluck('name', 'id'))
                            ->required(),
                        TextInput::make('grade')
                            ->label('Grade')
                            ->numeric()
                    ])
                    ->columns(2)
                    ->collapsible(),
                TextInput::make('average_grade')
                    ->label('Average Grade')
                    ->disabled()
                    ->afterStateHydrated(function (callable $set, ?\App\Models\User $record) {
                        if ($record && $record->userSubjects()->exists()) {
                            $average = $record->userSubjects()->avg('grade');
                            $set('average_grade', round($average, 2));
                        }
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('email')
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return static::getModel()::query()->where('is_admin', 0);
    }
}
