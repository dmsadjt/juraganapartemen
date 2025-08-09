<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UnitResource\Pages;
use App\Filament\Resources\UnitResource\RelationManagers;
use App\Models\Unit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Str;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;

class UnitResource extends Resource
{
    protected static ?string $model = Unit::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->label('nama')->maxlength(255)->required(),
            Forms\Components\Select::make('type')
                ->label('Jenis properti')
                ->options([
                    'apartemen' => 'Apartemen',
                    'rumah' => 'Rumah',
                ])
                ->required(),
            Forms\Components\TextInput::make('tower')->maxlength(255),
            Forms\Components\TextInput::make('floor')->label('Lantai')->integer(),
            Forms\Components\TextInput::make('size')->label('Luas')->integer()->required(),
            Forms\Components\TextInput::make('room_number')->label('Room Number')->integer(),
            Forms\Components\TextInput::make('electricity')->label('Listrik')->integer(),
            Forms\Components\TextInput::make('bedrooms')->label('Kamar tidur')->integer()->required(),
            Forms\Components\TextInput::make('bathrooms')->label('Kamar mandi')->integer()->required(),
            Forms\Components\Select::make('listing_type')
                ->label('Jenis listing')
                ->options([
                    'sell' => 'Jual',
                    'rent' => 'Sewa',
                ])
                ->required(),
            Forms\Components\TextInput::make('price_sell')->label('Harga Jual')->integer(),
            Forms\Components\TextInput::make('price_rent_month')->label('Harga Sewa per bulan')->integer(),
            Forms\Components\TextInput::make('price_rent_year')->label('Harga Sewa per tahun')->integer(),
            Forms\Components\TextInput::make('deposit')->integer(),
            Forms\Components\TextInput::make('service_charge')->label('Service Charge')->integer(),
            Forms\Components\Select::make('surat_type')
                ->label('Jenis Kepemilikan')
                ->options([
                    'PPJB' => 'PPJB',
                    'SHM' => 'SHM (Sertifikat Hak Milik)',
                    'HGB' => 'HGB (Hak Guna Bangunan)',
                    'Strata Title' => 'Strata Title',
                ]),
            Forms\Components\Select::make('status')->options([
                'available' => 'Tersedia',
                'booked' => 'Tersewa',
                'sold' => 'Terjual',
            ]),
            TextArea::make('description')->label('Deskripsi Unit'),
            Forms\Components\TextInput::make('map_link')->label('Location')->url(),
            Repeater::make('images') // matches `public function images()` in Unit model
                ->relationship('images')
                ->label('Unit Images')
                ->schema([FileUpload::make('image_path')->label('Image')->image()->directory('units')->preserveFilenames(false)->getUploadedFileNameForStorageUsing(fn($file) => 'unit-' . time() . '-' . Str::random(5) . '.' . $file->getClientOriginalExtension())])
                ->createItemButtonLabel('Add Image')
                ->reorderable()
                ->collapsible(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('type')
                    ->label('Jenis Unit')
                    ->formatStateUsing(function ($state) {
                    return match($state) {
                        'apartemen' => 'Apartemen',
                        'rumah' => 'Rumah',
                        default => 'Unknown'
                    };
                }),
                TextColumn::make('listing_type')
                    ->label('Jenis Listing')
                    ->formatStateUsing(function ($state) {
                        return match($state) {
                            'rent' => 'Disewakan',
                            'sell' => 'Dijual',
                            default => 'Unknown'
                        };
                    }),
                TextColumn::make('status')
                    ->badge()
                    ->icon(fn (string $state): string => match ($state) {
                        'available' => 'heroicon-m-check-circle',
                        'booked' => 'heroicon-m-key',
                        'sold' => 'heroicon-m-tag'
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'available' => 'success',
                        'booked' => 'warning',
                        'sold' => 'danger'
                    }),
                ImageColumn::make('images.image_path') // from relationship
                    ->label('Preview')
                    ->getStateUsing(fn ($record) =>
                        $record->images->first()?->image_path
                    )
                    ->disk('public') // or 'local', depending on where you store files
                    ->size(80), // thumbnail size
            ])
            ->filters([
                TernaryFilter::make('type')
                    ->label('Apartemen')
                    ->placeHolder('Jenis Unit')
                    ->trueLabel('Apartemen')
                    ->falseLabel('Rumah')
                    ->queries(
                        true: fn (Builder $query) => $query->where('type', 'apartemen'),
                        false: fn (Builder $query) => $query->where('type', 'rumah'),
                        blank: fn (Builder $query) => $query,
                    )
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
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
            'index' => Pages\ListUnits::route('/'),
            'create' => Pages\CreateUnit::route('/create'),
            'edit' => Pages\EditUnit::route('/{record}/edit'),
        ];
    }
}
