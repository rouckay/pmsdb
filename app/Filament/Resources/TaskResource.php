<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskResource\Pages;
use App\Filament\Resources\TaskResource\RelationManagers;
use App\Models\Task;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-fire';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\RichEditor::make('description')
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('due_date'),
                Forms\Components\Select::make('priority')
                    ->options(['Low', 'Medium', 'High', 'urgent'])
                    ->required(),
                Forms\Components\FileUpload::make('image_path')
                    ->image(),
                Forms\Components\Toggle::make('status')
                    ->required(),
                Forms\Components\Select::make('assigned_to')
                    ->label('Assigned To')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->required()
                    ->default(auth()->id())
                    ->label('Created By')
                    ->disabled()
                    ->relationship('user', 'name'),
                Forms\Components\Select::make('update_by')
                    ->required()
                    ->default(auth()->id())
                    ->label('Update By')
                    ->disabled()
                    ->relationship('user', 'name'),
                Forms\Components\Select::make('project_id')
                    ->required()
                    ->relationship('project', 'name')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required(),
                        Forms\Components\Textarea::make('description')
                            ->columnSpanFull(),
                        Forms\Components\DateTimePicker::make('due_date'),
                        Forms\Components\Toggle::make('status')
                            ->required(),
                        Forms\Components\FileUpload::make('image_path')
                            ->image(),
                        Forms\Components\Select::make('created_by')
                            ->required()
                            ->default(auth()->id())
                            ->disabled()
                            ->relationship('created_by', 'name'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('due_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('priority')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image_path'),
                Tables\Columns\IconColumn::make('status')
                    ->boolean(),
                Tables\Columns\TextColumn::make('assigned_to')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('update_by')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('project.name')
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
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'view' => Pages\ViewTask::route('/{record}'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}
