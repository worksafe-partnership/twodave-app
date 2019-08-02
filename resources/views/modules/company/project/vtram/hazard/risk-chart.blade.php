<table class="risk-table">
    <tr>
        <th rowspan="3" colspan="3" class="center padding-21 hide-report">RISK ASSESSMENT CALCULATION LEGEND</th>
        <th class="center" colspan="4">S = POTENTIAL SEVERITY</th>
    </tr>
    <tr>
        <th class="center">1</th>
        <th class="center">2</th>
        <th class="center">3</th>
        <th class="center">4</th>
    </tr>
    <tr>
        <th class="center">NEGLIGIBLE</th>
        <th class="center">MINOR</th>
        <th class="center">SEVERE</th>
        <th class="center">EXTERME</th>
    </tr>
    <tr>
        <th rowspan="4" class="center padding-21">P = PROBABILITY</th>
        <th class="center small-pad">1</th>
        <th class="small-pad">IMPROBABLE</th>
        <td class="green center risk-rating" data-value="0" data-type="{{ $hazardType }}" data-prob="1" data-severity="1">{{ $riskList[0] }}</td>
        <td class="green center risk-rating" data-value="0" data-type="{{ $hazardType }}" data-prob="1" data-severity="2">{{ $riskList[0] }}</td>
        <td class="green center risk-rating" data-value="0" data-type="{{ $hazardType }}" data-prob="1" data-severity="3">{{ $riskList[0] }}</td>
        <td class="yellow center risk-rating" data-value="1" data-type="{{ $hazardType }}" data-prob="1" data-severity="4">{{ $riskList[1] }}</td>
    </tr>
    <tr>
        <th class="center small-pad">2</th>
        <th class="small-pad">REMOTE</th>
        <td class="green center risk-rating" data-value="0" data-type="{{ $hazardType }}" data-prob="2" data-severity="1">{{ $riskList[0] }}</td>
        <td class="green center risk-rating" data-value="0" data-type="{{ $hazardType }}" data-prob="2" data-severity="2">{{ $riskList[0] }}</td>
        <td class="yellow center risk-rating" data-value="1" data-type="{{ $hazardType }}" data-prob="2" data-severity="3">{{ $riskList[1] }}</td>
        <td class="orange center risk-rating" data-value="2" data-type="{{ $hazardType }}" data-prob="2" data-severity="4">{{ $riskList[2] }}</td>
    </tr>
    <tr>
        <th class="center small-pad">3</th>
        <th class="small-pad">POSSIBLE</th>
        <td class="green center risk-rating" data-value="0" data-type="{{ $hazardType }}" data-prob="3" data-severity="1">{{ $riskList[0] }}</td>
        <td class="yellow center risk-rating" data-value="1" data-type="{{ $hazardType }}" data-prob="3" data-severity="2">{{ $riskList[1] }}</td>
        <td class="orange center risk-rating" data-value="2" data-type="{{ $hazardType }}" data-prob="3" data-severity="3">{{ $riskList[2] }}</td>
        <td class="red center risk-rating" data-value="3" data-type="{{ $hazardType }}" data-prob="3" data-severity="4">{{ $riskList[3] }}</td>
    </tr>
    <tr>
        <th class="center small-pad">4</th>
        <th class="small-pad">PROBABLE</th>
        <td class="green center risk-rating" data-value="0" data-type="{{ $hazardType }}" data-prob="4" data-severity="1">{{ $riskList[0] }}</td>
        <td class="orange center risk-rating" data-value="2" data-type="{{ $hazardType }}" data-prob="4" data-severity="2">{{ $riskList[2] }}</td>
        <td class="red center risk-rating" data-value="3" data-type="{{ $hazardType }}" data-prob="4" data-severity="3">{{ $riskList[3] }}</td>
        <td class="red center risk-rating" data-value="3" data-type="{{ $hazardType }}" data-prob="4" data-severity="4">{{ $riskList[3] }}</td>
    </tr>
</table>
