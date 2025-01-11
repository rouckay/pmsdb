<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AthletResource\Pages;
use App\Filament\Resources\AthletResource\RelationManagers;
use App\Models\Athlet;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AthletResource extends Resource
{
    protected static ?string $model = Athlet::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('father_name')
                    ->maxLength(191),
                Forms\Components\TextInput::make('phone_number')
                    ->tel()
                    ->maxLength(191),
                Forms\Components\FileUpload::make('photo')
                ,
                Forms\Components\TextInput::make('fees')
                    ->required()
                    ->maxLength(191),
                Forms\Components\Select::make('admission_type')
                    ->options([
                        'gym' => 'GYM',
                        'fitness' => 'Fitness',
                    ])
                    ->default('gym')
                    ->required(),
                Forms\Components\DatePicker::make('admiission_expiry_date')
                    ->required(),
                Forms\Components\Select::make('box_id')
                    ->relationship('box', 'box_number')
                    ->searchable()
                    ->createOptionForm([
                        Card::make()->
                            schema([
                                Forms\Components\TextInput::make('box_number')
                                    ->required()
                                    ->prefix('A-')
                                    ->maxLength(191),
                                Forms\Components\DatePicker::make('expire_date')
                                    ->required(),
                            ])
                    ])
                ,
                Forms\Components\RichEditor::make('details')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('father_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fees')
                    ->searchable(),
                Tables\Columns\TextColumn::make('admission_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('admiission_expiry_date')
                    ->searchable(),
                Tables\Columns\TextColumn::make('box_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListAthlets::route('/'),
            'create' => Pages\CreateAthlet::route('/create'),
            'view' => Pages\ViewAthlet::route('/{record}'),
            'edit' => Pages\EditAthlet::route('/{record}/edit'),
        ];
    }
}
