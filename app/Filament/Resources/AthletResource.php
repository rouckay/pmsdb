<?php
namespace App\Filament\Resources;

use App\Filament\Resources\AthletResource\Pages;
use App\Filament\Resources\AthletResource\RelationManagers;
use App\Models\Athlet;
use App\Models\Fee;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Carbon\Carbon;

class AthletResource extends Resource
{
    protected static ?string $model = Athlet::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    protected static ?string $modelLabel = 'شاګرد ';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('نام')
                        ->required()
                        ->maxLength(191),
                    Forms\Components\TextInput::make('father_name')
                        ->label('د پلار نوم')
                        ->required()
                        ->maxLength(191),
                    Forms\Components\TextInput::make('phone_number')
                        ->label('شماره تلفن')
                        ->tel()
                        ->required()
                        ->maxLength(191),
                    Forms\Components\FileUpload::make('photo')
                        ->label('تصویر')
                        ->imageEditor(),
                    Forms\Components\Select::make('admission_type')
                        ->label('نوع ثبت نام')
                        ->required()
                        ->options([
                            'gym' => 'GYM',
                            'fitness' => 'Fitness',
                        ])
                        ->default('gym')

                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set, $get) {
                            $boxId = $get('box_id') ?? null;
                            $fees = $state === 'gym' ? 500 : 1000;
                            if ($boxId) {
                                $fees += 150;
                            }
                            $set('fees', $fees);
                        }),
                    Forms\Components\TextInput::make('fees')
                        ->required()
                        ->label('فیس')
                        ->numeric()
                        ->maxLength(10)
                        ->default(500)
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set, $get) {
                            $admissionType = $get('admission_type') ?? 'gym';
                            $boxId = $get('box_id') ?? null;
                            $fees = $admissionType === 'gym' ? 500 : 1000;
                            if ($boxId) {
                                $fees += 150;
                            }
                            $set('fees', $fees);
                        }),
                    Forms\Components\Select::make('box_id')
                        ->label('صندق')
                        ->relationship('box', 'box_number')
                        ->searchable()
                        ->nullable() // Make the box_id field optional
                        ->createOptionForm([
                            Forms\Components\TextInput::make('box_number')->required()->prefix('A-')->maxLength(191),
                            Forms\Components\DatePicker::make('expire_date')->required()->default(now()->addDays(30)),
                        ])->reactive()->afterStateUpdated(function ($state, callable $set, $get) {
                            $admissionType = $get('admission_type') ?? 'gym';
                            $fees = $admissionType === 'gym' ? 500 : 1000;
                            if ($state) {
                                $fees += 150;
                            }$set('fees', $fees);
                            $set('updated_at', now());
                            $set('admission_expiry_date', now()->addDays(30));
                        })->rule(function ($get) {
                            return function ($attribute, $value, $fail) use ($get) {
                                if ($value) {
                                    $existingAthlet = Athlet::where('box_id', $value)->first();
                                    if ($existingAthlet && $existingAthlet->id !== $get('id')) {
                                        $fail('This box is already assigned to another athlete.');
                                    }
                                }
                            };
                        }),
                    Forms\Components\DatePicker::make('admission_expiry_date')->label('تاریخ ختم فیس')->required()->default(now()->addDays(30)),
                    Forms\Components\RichEditor::make('details')->label('تفصیل')->columnSpanFull(),
                ])->columns(3)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo')
                    ->label('تصویر')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('نام')
                    ->searchable(),
                Tables\Columns\TextColumn::make('father_name')
                    ->label('د پلار نوم')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('phone_number')
                    ->label('شماره تلفن')
                    ->searchable(),
                Tables\Columns\TextColumn::make('admission_type')
                    ->label('بخش')
                    ->searchable(),
                Tables\Columns\TextColumn::make('admission_expiry_date')
                    ->searchable()
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        0 => 'danger',
                        -1 => 'danger',
                        default => 'warning',
                    })
                    ->label('روزهای ختم فیس')
                    ->getStateUsing(fn($record) => floor(Carbon::parse($record->admission_expiry_date)->diffInDays(now()))),
                Tables\Columns\TextColumn::make('days_since_created')
                    ->label('تمام روزها')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'امروز' => 'success',
                        default => 'warning',
                    })
                    ->getStateUsing(fn($record) => Carbon::parse($record->created_at)->isToday() ? 'امروز' : floor(Carbon::parse($record->created_at)->diffInDays(now()))),
                Tables\Columns\TextColumn::make('box_id')
                    ->numeric()
                    ->label('صندق')
                    ->sortable(),
                Tables\Columns\TextColumn::make('this_month_fees')
                    ->label(' فیس این ماه')
                    ->getStateUsing(fn($record) => Fee::where('athlet_id', $record->id)->whereMonth('created_at', now()->month)->sum('fees'))
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        '0' => 'danger',
                        default => 'warning',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('fees')
                    ->label('فیس')
                    ->getStateUsing(fn($record) => Fee::where('athlet_id', $record->id)->sum('fees'))
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
                Tables\Filters\SelectFilter::make('admission_type')
                    ->label('نوع ثبت نام')
                    ->options([
                        'gym' => 'GYM',
                        'fitness' => 'Fitness',
                    ])
                    ->multiple()
                ,
                Tables\Filters\Filter::make('athlet_who_fees_expire_tomorrow')->label('شاګردانی که فیس شان سبا ختم میشود.')->query(fn(Builder $query) => $query->where('admission_expiry_date', '<=', now()->addDays(1))),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('collectFee')
                    ->label('Collect Fee')
                    ->action(function ($record, $data) {
                        $admissionType = $record->admission_type ?? 'gym';
                        $boxId = $data['box_id'] ?? null;
                        $fees = $data['fees'] ?? ($admissionType === 'gym' ? 500 : 1000);
                        if ($boxId) {
                            $fees += 150;
                        }
                        Fee::create([
                            'athlet_id' => $record->id,
                            'fees' => $fees,
                        ]);
                        $record->admission_expiry_date = now()->addDays(30);
                        $record->box_id = $boxId;
                        $record->save();
                    })
                    ->form([
                        Forms\Components\TextInput::make('fees')
                            ->label('فیس')
                            ->required()
                            ->numeric()
                            ->default(500),
                        Forms\Components\Select::make('box_id')
                            ->label('صندق')
                            ->relationship('box', 'box_number')
                            ->searchable()
                            ->nullable() // Make the box_id field optional
                            ->createOptionForm([
                                Forms\Components\TextInput::make('box_number')
                                    ->required()
                                    ->prefix('A-')
                                    ->maxLength(191),
                                Forms\Components\DatePicker::make('expire_date')
                                    ->required()
                                    ->default(now()->addDays(30)),
                            ])
                            ->reactive()
                            ->rule(function ($get) {
                                return function ($attribute, $value, $fail) use ($get) {
                                    if ($value) {
                                        $existingAthlet = Athlet::where('box_id', $value)->first();
                                        if ($existingAthlet && $existingAthlet->id !== $get('id')) {
                                            $fail('This box is already assigned to another athlete.');
                                        }
                                    }
                                };
                            }),
                    ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([

                Tables\Actions\Action::make('totalfees')
                    ->label('Total Fees: ' . Fee::sum('fees')),
                Tables\Actions\Action::make('totalAthletes')
                    ->label('Total Athletes: ' . Athlet::count()),
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
