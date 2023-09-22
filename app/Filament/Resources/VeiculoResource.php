<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VeiculoResource\Pages;
use App\Filament\Resources\VeiculoResource\RelationManagers;
use App\Models\Marca;
use App\Models\Veiculo;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VeiculoResource extends Resource
{
    protected static ?string $model = Veiculo::class;

    protected static ?string $navigationGroup = 'Cadastros';

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationLabel = 'Veículos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Veículo')
                    ->schema([
                        Forms\Components\TextInput::make('modelo')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('marca_id')
                            ->label('Marca')
                            ->required()
                            ->options(Marca::all()->pluck('nome', 'id')->toArray()),
                        Forms\Components\TextInput::make('ano')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('placa')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('cor')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('valor_diaria'),
                        Forms\Components\TextInput::make('km_atual')
                            ->label('Km Atual'),
                    ]),

                    Fieldset::make('Manutenção')
                    ->schema([
                        Forms\Components\TextInput::make('prox_troca_oleo')
                            ->label('Próxima Troca de Óleo - Km'),
                        Forms\Components\TextInput::make('aviso_troca_oleo')
                            ->label('Aviso Troca do Óleo - Km'),
                        Forms\Components\TextInput::make('prox_troca_filtro')
                            ->label('Próxima Troca do Filtro - Km'),
                        Forms\Components\TextInput::make('aviso_troca_filtro')
                            ->label('Aviso Troca do Filtro - Km'),
                        Forms\Components\TextInput::make('prox_troca_correia')
                            ->label('Próxima Troca da Correia - Km'),
                        Forms\Components\TextInput::make('aviso_troca_correia')
                            ->label('Aviso Troca do Correia - Km'),
                        Forms\Components\TextInput::make('prox_troca_pastilha')
                            ->label('Próxima Troca da Pastilha - Km'),
                        Forms\Components\TextInput::make('aviso_troca_pastilha')
                            ->label('Aviso Troca da Pastilha - Km'),
                        Forms\Components\TextInput::make('chassi')
                            ->label('Nº do Chassi'),
                        Forms\Components\DatePicker::make('data_compra')
                            ->label('Data da Compra do Veículo'),
                        Forms\Components\Toggle::make('status')
                            ->default(true)
                            ->label('Ativo'),
                    ]),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('modelo')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('marca.nome'),
                Tables\Columns\TextColumn::make('ano'),
                Tables\Columns\TextColumn::make('placa')
                     ->searchable(),
                Tables\Columns\TextColumn::make('cor'),
                Tables\Columns\TextColumn::make('km_atual')
                 ->label('Km Atual'),
              /*  ->getStateUsing(function (Veiculo $record): void {

                  //  dd($record->km_atual);

                    if($record->km_atual >= $record->aviso_troca_oleo){
                        Notification::make()
                        ->title('ATENÇÃO: Veículos com troca de óleo próxima')
                        ->body('Veiculo: '.$record->modelo.' Placa: '.$record->placa)
                        ->warning()
                        ->persistent()
                        ->send();
                        }

                        if($record->km_atual >= $record->aviso_troca_filtro){
                            Notification::make()
                            ->title('ATENÇÃO: Veículos com troca do filtro próxima')
                            ->body('Veiculo: '.$record->modelo.' Placa: '.$record->placa)
                            ->warning()
                            ->persistent()
                            ->send();
                        }

                        if($record->km_atual >= $record->aviso_troca_correia){
                            Notification::make()
                            ->title('ATENÇÃO: Veículos com troca da correia próxima')
                            ->body('Veiculo: '.$record->modelo.' Placa: '.$record->placa)
                            ->warning()
                            ->persistent()
                            ->send();
                        }

                        if($record->km_atual >= $record->aviso_troca_pastilha){
                            Notification::make()
                            ->title('ATENÇÃO: Veículos com troca da pastilha próxima')
                            ->body('Veiculo: '.$record->modelo.' Placa: '.$record->placa)
                            ->warning()
                            ->persistent()
                            ->send();
                        }
                    }

                ), */
                Tables\Columns\TextColumn::make('valor_diaria')
                   ->label('Valor Diária')
                  ->money('BRL'),
                Tables\Columns\IconColumn::make('status')
                    ->label('Ativo')
                    ->sortable()
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalHeading('Editar Veículo'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageVeiculos::route('/'),
        ];
    }


}
