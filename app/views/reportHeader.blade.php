@section ("reportHeader")
    <table width="100%" style="font-size:12px;">
        <thead>
            <tr>
                <td>{{ HTML::image(Config::get('kblis.organization-logo'),  Config::get('kblis.country') . trans('messages.court-of-arms'), array('width' => '90px')) }}</td>
                <td>
                    @if(!empty($tests) && !empty($visit))
                        <?php
                            $tracking_number = $tests[0]->specimen->tracking_number;
                            $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
                            echo '<img align="right"  height="60" width="200" src="data:image/png;base64,'
                                    . base64_encode($generator->getBarcode($tracking_number, $generator::TYPE_CODE_128)) . '">';
                        ?>
                    @endif
                </td>
                <td colspan="3" style="text-align:right;padding-right: 15px;">
                    <strong><p> {{ strtoupper(Config::get('kblis.organization')) }}<br>
                    {{ strtoupper(Config::get('kblis.address-info')) }}</p>
                    <p><u>{{ trans('messages.laboratory-report')}}</u><br>
                </td>
            </tr>
        </thead>
    </table>
@show