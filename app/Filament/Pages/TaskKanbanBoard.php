<?php

namespace App\Filament\Pages;

use App\Enum\TaskStatus;
use App\Models\Task;
use Mokhosh\FilamentKanban\Pages\KanbanBoard;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextArea;
use Filament\Actions\CreateAction;

class TaskKanbanBoard extends KanbanBoard
{
    protected static string $model = Task::class;
    protected static string $statusEnum = TaskStatus::class;

    protected static ?string $navigationIcon = 'heroicon-o-check';
    protected static string $recordTitleAttribute = 'title';

    protected function getEditModalFormSchema(int|null $recordId): array
    {
        return [
            TextInput::make('name')
                ->required(),
            RichEditor::make('description')
                ->columnSpanFull(),
            DateTimePicker::make('due_date'),
            Select::make('priority')
                ->options(['Low', 'Medium', 'High', 'urgent'])
                ->required(),

            Toggle::make('status')
                ->required(),
            Select::make('assigned_to')
                ->label('Assigned To')
                ->relationship('user', 'name')
                ->required(),
            Select::make('user_id')
                ->required()
                ->default(auth()->id())
                ->label('Created By')
                ->disabled()
                ->relationship('user', 'name'),
            Select::make('update_by')
                ->required()
                ->default(auth()->id())
                ->label('Update By')
                ->disabled()
                ->relationship('user', 'name'),
            Select::make('project_id')
                ->required()
                ->relationship('project', 'name')
                ->createOptionForm([
                    TextInput::make('name')
                        ->required(),
                    Textarea::make('description')
                        ->columnSpanFull(),
                    DateTimePicker::make('due_date'),
                    Toggle::make('status')
                        ->required(),
                    FileUpload::make('image_path')
                        ->image(),
                    Select::make('created_by')
                        ->required()
                        ->default(auth()->id())
                        ->disabled()
                        ->relationship('created_by', 'name'),
                ]),
        ];
    }
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->model(Task::class)
                ->form([
                    TextInput::make('name')
                        ->required(),
                    RichEditor::make('description')
                        ->columnSpanFull(),
                    DateTimePicker::make('due_date'),
                    Select::make('priority')
                        ->options(['Low', 'Medium', 'High', 'urgent'])
                        ->required(),

                    Toggle::make('status')
                        ->required(),
                    Select::make('assigned_to')
                        ->label('Assigned To')
                        ->relationship('user', 'name')
                        ->required(),
                    Select::make('user_id')
                        ->required()
                        ->default(auth()->id())
                        ->label('Created By')
                        ->disabled()
                        ->relationship('user', 'name'),
                    Select::make('update_by')
                        ->required()
                        ->default(auth()->id())
                        ->label('Update By')
                        ->disabled()
                        ->relationship('user', 'name'),
                    Select::make('project_id')
                        ->required()
                        ->relationship('project', 'name')
                        ->createOptionForm([
                            TextInput::make('name')
                                ->required(),
                            Textarea::make('description')
                                ->columnSpanFull(),
                            DateTimePicker::make('due_date'),
                            Toggle::make('status')
                                ->required(),
                            FileUpload::make('image_path')
                                ->image(),
                            Select::make('created_by')
                                ->required()
                                ->default(auth()->id())
                                ->disabled()
                                ->relationship('created_by', 'name'),
                        ]),


                ])->mutateFormDataUsing(function ($data) {
                    // $data['user_id'] = auth()->id();
                    return $data;
                })

        ];
    }
}
