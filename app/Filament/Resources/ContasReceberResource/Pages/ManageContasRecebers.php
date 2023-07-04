<?php

namespace App\Filament\Resources\ContasReceberResource\Pages;

use App\Filament\Resources\ContasReceberResource;
use App\Filament\Widgets\ContasReceberStats;
use App\Models\ContasReceber;
use App\Models\FluxoCaixa;
use Carbon\Carbon;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Contracts\View\View;

class ManageContasRecebers extends ManageRecords
{
    protected static string $resource = ContasReceberResource::class;

    protected static ?string $title = 'Contas a Receber';

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Lançar Conta')
            ->after(function ($data, $record) {
              if($record->parcelas > 1)
                {
                    $valor_parcela = ($record->valor_total / $record->parcelas);
                    $vencimentos = Carbon::create($record->data_vencimento);
                    for($cont = 1; $cont < $data['parcelas']; $cont++)
                    {
                                        $dataVencimentos = $vencimentos->addDays(30);
                                        $parcelas = [
                                        'cliente_id' => $data['cliente_id'],
                                        'valor_total' => $data['valor_total'],
                                        'parcelas' => $data['parcelas'],
                                        'formaPgmto' => $data['formaPgmto'],
                                        'ordem_parcela' => $cont+1,
                                        'data_vencimento' => $dataVencimentos,
                                        'valor_recebido' => 0.00,
                                        'status' => 0,
                                        'obs' => $data['obs'],
                                        'valor_parcela' => $valor_parcela,
                                        ];
                            ContasReceber::create($parcelas);
                    }

                }
                else
                { 
                   if($data['formaPgmto'] == 1)
                   {
                    $addFluxoCaixa = [
                        'valor' => ($record->valor_total),
                        'tipo'  => 'CREDITO',
                        'obs'   => 'Recebimento de Conta',
                    ];

                    FluxoCaixa::create($addFluxoCaixa); 

                   } 
                    
                }


            }
        ), 
            
        ]; 
    } 

    protected function getHeaderWidgets(): array
    {
        return [
            ContasReceberStats::class,
         //   VendasMesChart::class,
        ];
    }

    protected function getFooter(): View
    {
        return view('filament/footer/contas-receber/footer');
    }
}