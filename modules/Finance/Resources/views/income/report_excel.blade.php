<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Ingresos diversos</title>
    </head>
    <body>
        <div>
            <h3 align="center" class="title"><strong>Ingresos diversos</strong></h3>
        </div>
        <br>
        <div style="margin-top:20px; margin-bottom:15px;">
            <table>
                <tr>
                    <td colspan="2">
                        <p><strong>Establecimiento: </strong></p>
                    </td>
                    <td colspan="2" align="center">{{$establishment->address}} - {{$establishment->department->description}} - {{$establishment->district->description}}</td>
                </tr>
            </table>
        </div>
        <br>
        @if(!empty($records))
            <div class="">
                <div class=" ">
                    <table class="">
                        <thead>
                            <tr>
                                <th class="">#</th>
                                <th class="">Fecha de emisión</th>
                                <th class="">Cliente</th>
                                <th class="">Número</th>
                                <th class="">Motivo</th>
                                <th class="">Moneda</th>
                                <th class="">Total</th>
                              
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($records as $record)
                            @php
                                /* dd($record->state_type['description']); */
                            @endphp
                            <tr>
                                <td class="celda">
                                    {{$loop->iteration}}
                                </td>
                                <td class="celda">
                                    {{$record->date_of_issue}}
                                </td>
                                <td class="celda">
                                    {{$record->customer}}
                                </td>
                                <td class="celda">
                                    {{$record->number}}
                                </td>
                      
                                <td class="celda">
                                    {{$record->number}}
                                </td>
                                <td class="celda">
                                    {{$record->income_reason->description}}
                                </td>
                                <td class="celda">
                                    {{$record->currency_type_id}}
                                </td>
                                <td class="celda">
                                    {{$record->total}}
                                </td>
                              
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div>
                <p>No se encontraron registros.</p>
            </div>
        @endif
    </body>
</html>
